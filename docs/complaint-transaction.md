# Complaint Transaction

Dokumentasi fitur **Complaint Transaction** pada aplikasi AccountingApps. Fitur ini digunakan untuk menandai transaksi yang sudah selesai (`done`) sebagai komplain dari customer, mengelola tindak lanjutnya (selesai ulang atau cancel), dan secara otomatis memindahkan sparepart yang sudah terpakai ke **return stock** ketika transaksi komplain di-cancel.

## Daftar Isi

- [Ringkasan Alur](#ringkasan-alur)
- [Status Transaksi](#status-transaksi)
- [Komponen Terkait](#komponen-terkait)
- [Skema Database](#skema-database)
- [Alur Detail](#alur-detail)
  - [1. Mengajukan Komplain](#1-mengajukan-komplain-flag-status--complaint)
  - [2. Tab Transaction Complaint](#2-tab-transaction-complaint)
  - [3. Mark as Done (Transaction Completed)](#3-mark-as-done-transaction-completed)
  - [4. Cancel Complaint Transaction](#4-cancel-complaint-transaction)
  - [5. Pengelolaan Return Stock](#5-pengelolaan-return-stock)
  - [6. Export Excel](#6-export-excel)
- [Lokasi UI](#lokasi-ui)
- [Catatan Implementasi](#catatan-implementasi)

---

## Ringkasan Alur

```
┌──────────────┐  flag complaint   ┌──────────────────┐
│ status: done │ ───────────────► │ status: complaint │
└──────────────┘                  └─────────┬────────┘
   (Reporting > Transaction)                │
                                            │
                          ┌─────────────────┴─────────────────┐
                          │                                   │
                  Transaction Completed              Cancel Transaction
                          │                                   │
                          ▼                                   ▼
                ┌──────────────┐              ┌──────────────────────────┐
                │ status: done │              │ status: cancel           │
                └──────────────┘              │ + sparepart → return     │
                                              │   stock (product_returns)│
                                              └──────────────────────────┘
```

## Status Transaksi

Kolom `transactions.status` menggunakan enum dengan nilai:

| Status      | Deskripsi                                                                 |
|-------------|---------------------------------------------------------------------------|
| `proses`    | Transaksi sedang dikerjakan.                                              |
| `done`      | Transaksi selesai (default).                                              |
| `cancel`    | Transaksi dibatalkan.                                                     |
| `complaint` | Customer mengajukan komplain atas transaksi yang sudah `done`.            |

Migrasi: [database/migrations/2024_07_31_111355_add_status_to_transaction_table.php](../database/migrations/2024_07_31_111355_add_status_to_transaction_table.php)

## Komponen Terkait

| File                                                                                                          | Peran                                                                          |
|---------------------------------------------------------------------------------------------------------------|--------------------------------------------------------------------------------|
| [app/Http/Livewire/Dashboard/Reporting/Transaction.php](../app/Http/Livewire/Dashboard/Reporting/Transaction.php) | Method `complaint($id)` — flag transaksi `done` menjadi `complaint`.            |
| [app/Http/Livewire/Dashboard/TransactionComplaint.php](../app/Http/Livewire/Dashboard/TransactionComplaint.php)   | Komponen utama listing & action transaksi komplain.                             |
| [resources/views/livewire/dashboard/transaction-complaint.blade.php](../resources/views/livewire/dashboard/transaction-complaint.blade.php) | View list, modal detail, dan konfirmasi SweetAlert.                             |
| [app/Exports/TransactionComplaintExport.php](../app/Exports/TransactionComplaintExport.php)                       | Export Excel beserta ringkasan & catatan.                                       |
| [app/Models/ProductReturn.php](../app/Models/ProductReturn.php)                                                   | Model `product_returns` (tujuan sparepart hasil cancel komplain).               |
| [app/Http/Livewire/Dashboard/Inventory.php](../app/Http/Livewire/Dashboard/Inventory.php)                         | Method `showReturns`, `restoreReturnToStock`, `deleteReturn` di halaman Inventory. |
| [resources/views/livewire/dashboard/tab-on-pos.blade.php](../resources/views/livewire/dashboard/tab-on-pos.blade.php) | Tab "Transaction Complaint" di POS.                                             |
| [resources/views/livewire/dashboard/dashboard.blade.php](../resources/views/livewire/dashboard/dashboard.blade.php) | Embed komponen di dashboard (`is_dashboard = true`).                            |

## Skema Database

### Tabel `transactions` (kolom relevan)

| Kolom               | Tipe                                                  | Keterangan                       |
|---------------------|-------------------------------------------------------|----------------------------------|
| `status`            | `enum('proses','cancel','done','complaint')`         | Default `done`.                  |
| `product_id`        | `bigint nullable`                                     | Sparepart utama transaksi.       |
| `order_transaction` | `string`                                              | ID order yang ditampilkan ke UI. |

### Tabel `product_returns`

Migrasi: [database/migrations/2025_07_28_175839_create_product_returns_table.php](../database/migrations/2025_07_28_175839_create_product_returns_table.php)

| Kolom                  | Tipe                                       | Keterangan                                                |
|------------------------|--------------------------------------------|-----------------------------------------------------------|
| `id`                   | `bigint, PK`                               |                                                           |
| `transaction_id`       | `bigint, FK → transactions.id`             | Transaksi sumber.                                         |
| `product_id`           | `bigint, FK → products.id`                 | Produk/sparepart yang dikembalikan.                       |
| `quantity`             | `integer, default 1`                       | Jumlah unit yang dikembalikan.                            |
| `return_reason`        | `string, default 'complaint'`              | Diisi `complaint_cancel` saat dipicu cancel komplain.     |
| `return_type`          | `enum('transaction','transaction_item')`   | Sumber sparepart (transaksi induk vs. item layanan).      |
| `transaction_item_id`  | `bigint nullable, FK → transaction_items.id` | Diisi jika `return_type = transaction_item`.            |
| `returned_by`          | `bigint, FK → users.id`                    | User yang melakukan cancel.                               |
| `notes`                | `text nullable`                            | Catatan otomatis berisi order/service.                    |
| `timestamps`           |                                            |                                                           |

> Sparepart yang masuk `product_returns` **tidak** otomatis dikembalikan ke `products.stok` — admin harus melakukan **Restore to Stock** dari halaman Inventory.

## Alur Detail

### 1. Mengajukan Komplain (flag status → `complaint`)

- **Lokasi:** *Reporting > Transaction* — tombol komplain pada baris transaksi.
- **Method:** `complaint($id)` di [app/Http/Livewire/Dashboard/Reporting/Transaction.php:330](../app/Http/Livewire/Dashboard/Reporting/Transaction.php#L330).
- **Aksi:** mengubah `transactions.status` menjadi `complaint`.
- **Hasil:** transaksi muncul di tab *Transaction Complaint*.

### 2. Tab Transaction Complaint

- **Lokasi:** Tab "Transaction Complaint" di halaman POS (`tabActive === 'transaction-complaint'`) atau di dashboard (mode `is_dashboard = true`).
- **Komponen:** `App\Http\Livewire\Dashboard\TransactionComplaint`.
- **Query data:** seluruh `transactions` dengan `status = 'complaint'` dan `deleted_at IS NULL`, di-join dengan `transaction_items` & `customers`. Mendukung pencarian (`searchTerm`) berdasarkan `order_transaction` atau nama customer.
- **Kolom tabel:** No, Date, Customer, Order ID, Service, Status, Action.
- **Aksi yang tersedia per baris:**
  - **Detail** — menampilkan modal berisi info customer, total biaya, detail HP (brand/type/IMEI/internal/color), serta tabel layanan + sparepart per item. Lihat method `detail($id)` di [app/Http/Livewire/Dashboard/TransactionComplaint.php:43](../app/Http/Livewire/Dashboard/TransactionComplaint.php#L43).
  - **Transaction Completed** — meminta konfirmasi SweetAlert, lalu memanggil `TransactionComplete($id)`.
  - **Cancel Transaction** — meminta konfirmasi SweetAlert dengan peringatan bahwa sparepart akan dipindahkan ke return stock, lalu memanggil `cancelComplaintTransaction($id)`.

### 3. Mark as Done (Transaction Completed)

- **Method:** `TransactionComplete($id)` di [app/Http/Livewire/Dashboard/TransactionComplaint.php:199](../app/Http/Livewire/Dashboard/TransactionComplaint.php#L199).
- **Aksi:** mengubah `transactions.status` kembali menjadi `done`.
- **Tidak** memengaruhi stok atau membuat record `product_returns`.
- **Use case:** komplain berhasil diselesaikan (mis. service ulang berhasil) tanpa pengembalian barang.

### 4. Cancel Complaint Transaction

- **Method:** `cancelComplaintTransaction($id)` di [app/Http/Livewire/Dashboard/TransactionComplaint.php:212](../app/Http/Livewire/Dashboard/TransactionComplaint.php#L212).
- **Aksi:**
  1. Mengambil `Transaction` berdasarkan `id`.
  2. Jika `transaction.product_id` tidak null **dan** product masih ada → membuat record `ProductReturn` dengan:
     - `return_type = 'transaction'`
     - `return_reason = 'complaint_cancel'`
     - `quantity = 1`
     - `returned_by = Auth::id()`
     - `notes = "Returned due to complaint cancellation - Order: {order_transaction}"`
  3. Mengambil seluruh `TransactionItem` milik transaksi yang `product_id` tidak null.
  4. Untuk setiap item dengan product yang valid → membuat `ProductReturn` dengan:
     - `return_type = 'transaction_item'`
     - `transaction_item_id = item.id`
     - `return_reason = 'complaint_cancel'`
     - `notes = "Returned due to complaint cancellation - Service: {service}"`
  5. Mengubah `transactions.status` menjadi `cancel`.
- **Catatan:** stok produk **tidak** otomatis dikembalikan; sparepart "menunggu" di `product_returns` sampai admin menjalankan *Restore to Stock*.

### 5. Pengelolaan Return Stock

- **Lokasi:** halaman *Inventory*, tombol "Returns" pada produk yang punya record return.
- **Method utama** ([app/Http/Livewire/Dashboard/Inventory.php](../app/Http/Livewire/Dashboard/Inventory.php)):
  - `showReturns($productId)` — load list `ProductReturn` untuk produk tertentu (dengan eager-load `transaction` & `returnedBy`), tampilkan modal.
  - `restoreReturnToStock($returnId)` — menambahkan `quantity` return ke `products.stok`, mencatat `LogActivityProduct`, lalu menghapus record return.
  - `deleteReturn($returnId)` — menghapus record return tanpa menambah stok (mis. barang rusak / tidak layak jual).
- Saat restore, `Product::bypassVerification = true` dipakai agar guard stok tidak menghalangi penambahan dari proses internal.

### 6. Export Excel

- **Method:** `exportExcel()` di [app/Http/Livewire/Dashboard/TransactionComplaint.php:264](../app/Http/Livewire/Dashboard/TransactionComplaint.php#L264).
- **Export class:** [app/Exports/TransactionComplaintExport.php](../app/Exports/TransactionComplaintExport.php).
- **Library:** Maatwebsite Excel.
- **Nama file:** `transaction-complaint-{YYYY-MM-DD}.xlsx`.
- **Kolom:** No, Tanggal, Order ID, Customer, No. Telp, Service, Item Service, Biaya, Modal, Total Biaya, Payment Method, Teknisi, Warranty, Status.
- **Tambahan:** styling header, ringkasan (`RINGKASAN COMPLAINT` — total transaksi & nilai), dan blok catatan.
- **Filter:** semua transaksi dengan `status = 'complaint'` (export tidak mengikuti `searchTerm` — ekspor seluruh data komplain).

## Lokasi UI

- **POS / Dashboard tab:** [resources/views/livewire/dashboard/tab-on-pos.blade.php:36](../resources/views/livewire/dashboard/tab-on-pos.blade.php#L36) — tab "Transaction Complaint".
- **Dashboard utama:** [resources/views/livewire/dashboard/dashboard.blade.php:526](../resources/views/livewire/dashboard/dashboard.blade.php#L526) — embed dengan `is_dashboard = true` (tinggi tabel berbeda).
- **Reporting > Transaction:** tombol "Complaint" pada list transaksi `done`, view di [resources/views/livewire/dashboard/reporting/transaction.blade.php:204](../resources/views/livewire/dashboard/reporting/transaction.blade.php#L204).

## Catatan Implementasi

- **Properti `is_dashboard`** mengubah class CSS tinggi tabel agar pas saat ditampilkan dalam grid dashboard vs. fullscreen tab.
- **Listener `refreshComponent`** memungkinkan komponen lain mendispatch `$emit('refreshComponent')` untuk memuat ulang list (mis. setelah ada flag komplain baru).
- **Confirmations** menggunakan SweetAlert via event `triggerCompleteTransaction` & `triggerCancelTransaction` — Livewire melakukan `$emit` lalu listener JS memanggil method server.
- **Soft-delete:** query selalu memfilter `transactions.deleted_at IS NULL` dan `transaction_items.deleted_at IS NULL`. Cancel komplain **tidak** soft-delete transaksi, hanya mengubah status.
- **Idempotensi cancel:** method tidak mencegah cancel ulang; jika dipanggil dua kali, dapat menghasilkan duplikat record `product_returns`. Pertimbangkan menambah guard `if ($transaction->status === 'cancel') return;` jika dibutuhkan.
- **Stok otomatis:** sengaja tidak menambah stok saat cancel — kebijakan bisnis menempatkan keputusan layak/tidaknya barang kembali ke stok pada admin Inventory.
