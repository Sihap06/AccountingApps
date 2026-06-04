# Plan: Replace Sparepart pada Transaksi Complaint

Dokumen perencanaan implementasi flow **ganti sparepart** untuk transaksi berstatus `complaint`. Dokumen ini berupa rancangan (belum diimplementasikan) — tujuannya untuk disepakati sebelum coding.

## Daftar Isi

- [Konteks & Motivasi](#konteks--motivasi)
- [Tujuan](#tujuan)
- [Skenario User](#skenario-user)
- [Alur yang Diusulkan](#alur-yang-diusulkan)
- [Perubahan UI](#perubahan-ui)
- [Perubahan Backend](#perubahan-backend)
- [Perubahan Schema / Data](#perubahan-schema--data)
- [Logging & Audit](#logging--audit)
- [Validasi & Edge Cases](#validasi--edge-cases)
- [Permission](#permission)
- [Test Plan](#test-plan)
- [Open Questions](#open-questions)
- [Estimasi & Sequencing](#estimasi--sequencing)

---

## Konteks & Motivasi

Saat ini di tab *Transaction Complaint* ([TransactionComplaint.php](../app/Http/Livewire/Dashboard/TransactionComplaint.php)) tersedia hanya 3 aksi: **Detail**, **Transaction Completed** (status balik ke `done`, sparepart tidak disentuh), dan **Cancel Transaction** (status → `cancel`, semua sparepart masuk `product_returns`).

Skenario realistis "service ulang dengan sparepart pengganti" belum tertangani:

> Customer komplain layar HP yang baru dipasang rusak → teknisi cabut layar lama, pasang layar baru. Layar lama (rusak) harus tercatat di return stock; layar baru harus mengurangi stok normal; transaksi tetap valid sebagai revenue (tidak boleh `cancel`).

Workaround manual (`cancel` lama → buat transaksi baru) merusak laporan revenue dan memutus jejak order asli.

Sudah ada pola swap sparepart di [UpdateTransaction.php:423-455](../app/Http/Livewire/Dashboard/UpdateTransaction.php#L423-L455) — tapi di sana sparepart lama dikembalikan ke `products.stok` normal. Untuk komplain, sparepart lama wajib masuk `product_returns` (bukan stok normal) karena kondisinya belum tentu layak jual.

## Tujuan

1. Memungkinkan ganti **satu atau lebih** sparepart pada transaksi `complaint` tanpa mengubah status menjadi `cancel`.
2. Sparepart lama otomatis masuk `product_returns` dengan reason baru `complaint_replacement`.
3. Sparepart baru otomatis mengurangi `products.stok` (dengan guard insufficient stock).
4. Setelah replacement selesai, user dapat memilih:
   - **Replace & Complete** → status transaksi langsung kembali ke `done`.
   - **Replace Only** → status tetap `complaint` (kalau perlu replacement berlapis atau menunggu QC).
5. Audit lengkap: siapa, kapan, sparepart lama → baru, di transaksi/item mana.

## Skenario User

### Skenario 1: Ganti sparepart utama saja
- Transaksi punya `product_id = LCD-A` (rusak).
- Teknisi ganti dengan `LCD-B`.
- Hasil: `transactions.product_id = LCD-B`, stok `LCD-B` berkurang 1, record `product_returns` untuk `LCD-A` (return_type `transaction`).

### Skenario 2: Ganti sparepart di salah satu transaction item
- Transaksi punya 1 item layanan "Ganti Baterai" dengan `product_id = BAT-A`.
- Ganti dengan `BAT-B`.
- Hasil: `transaction_items.product_id = BAT-B`, stok `BAT-B` -1, record `product_returns` untuk `BAT-A` (return_type `transaction_item`, `transaction_item_id` terisi).

### Skenario 3: Ganti dengan sparepart yang sama (refurbish ulang)
- Sparepart `LCD-A` → `LCD-A` lagi (unit lain di stok yang sama).
- Hasil tetap valid: stok `LCD-A` net 0 (return +1 di `product_returns`, stok normal -1), record `product_returns` tetap dibuat (audit unit yang dicabut).

### Skenario 4: Sparepart pengganti = `null` (tidak diganti, hanya dicabut)
- Misal komplain "minta cabut tidak usah dipasang ulang".
- Hasil: sparepart lama masuk `product_returns`, `transactions.product_id` jadi `null`, stok tidak dikurangi.

### Skenario 5: Modal/biaya perlu disesuaikan
- Sparepart baru harga modal beda dari lama.
- Hasil: `transactions.modal` dan `untung` direcalculate (mengikuti pola [UpdateTransaction.php:458-464](../app/Http/Livewire/Dashboard/UpdateTransaction.php#L458-L464)).
- *Open question:* apakah `biaya` (harga jual ke customer) juga boleh diubah, atau lock?

## Alur yang Diusulkan

```
                                ┌─────────────────────────┐
                                │ status: complaint       │
                                └──────────┬──────────────┘
                                           │
                ┌──────────────────────────┼────────────────────────────┐
                │                          │                            │
        Transaction              Replace Sparepart                  Cancel
        Completed                          │                            │
                │                          ▼                            │
                │             ┌──────────────────────────┐              │
                │             │ Pilih item (main/item-N) │              │
                │             │ Pilih sparepart pengganti│              │
                │             │ (boleh null)             │              │
                │             └────────────┬─────────────┘              │
                │                          │                            │
                │                          ▼                            │
                │             ┌──────────────────────────┐              │
                │             │ 1. product_returns INSERT│              │
                │             │ 2. stok lama: tidak ada  │              │
                │             │ 3. stok baru: -1         │              │
                │             │ 4. transaction(item)     │              │
                │             │    .product_id update    │              │
                │             │ 5. recalc modal/untung   │              │
                │             │ 6. LogActivityTransaction│              │
                │             └────────────┬─────────────┘              │
                │                          │                            │
                │            ┌─────────────┴──────────────┐             │
                │            │                            │             │
                │      Mark as Complete            Keep as Complaint    │
                │            │                            │             │
                ▼            ▼                            ▼             ▼
        status: done   status: done              status: complaint  status: cancel
```

## Perubahan UI

### 1. Tab Transaction Complaint — tombol baru

Tambah tombol **"Replace Sparepart"** (warna kuning, ikon `fas fa-exchange-alt`) di [transaction-complaint.blade.php:120-154](../resources/views/livewire/dashboard/transaction-complaint.blade.php#L120-L154), di antara "Detail" dan "Transaction Completed".

### 2. Modal Replace Sparepart (baru)

Komponen modal terpisah di [transaction-complaint.blade.php](../resources/views/livewire/dashboard/transaction-complaint.blade.php) (atau partial baru):

| Bagian              | Isi                                                                 |
|---------------------|---------------------------------------------------------------------|
| Header              | "Replace Sparepart - Order #{order_transaction}"                    |
| Section *Transaction Utama* | Service, sparepart lama (read-only), dropdown sparepart baru, checkbox "Don't replace (just remove)" |
| Section *Items* (per row) | Service, sparepart lama, dropdown sparepart baru, checkbox "Don't replace" |
| Section *Reason*    | Textarea wajib (akan masuk ke `notes` semua record return)          |
| Section *Action*    | Radio: (a) Mark as Done setelah replace, (b) Keep as Complaint     |
| Footer              | Tombol "Replace" (disabled jika tidak ada perubahan) + "Cancel"     |

Dropdown sparepart memakai komponen yang sama dengan UpdateTransaction (Select2 + stok > 0 filter).

### 3. SweetAlert konfirmasi

Sebelum eksekusi:
- Title: "Confirm sparepart replacement"
- Body: list ringkas "LCD-A → LCD-B", "BAT-A → (removed)", dst.
- Tombol: "Yes, Replace" / "Cancel"

## Perubahan Backend

### Method baru di `TransactionComplaint.php`

```php
public function openReplaceModal($id);            // load form dengan data transaksi
public function replaceSparepart();               // eksekusi (dipanggil dari modal)
public function closeReplaceModal();
```

#### Signature `replaceSparepart()`

Input (properti Livewire):
- `replaceTransactionId: int`
- `replaceReason: string` (required)
- `replaceMarkAsDone: bool`
- `replaceMainProductId: int|null` (sparepart baru untuk transaksi utama; `null` = remove)
- `replaceMainSkip: bool` (true = jangan sentuh main sparepart)
- `replaceItems: array<int, [skip: bool, new_product_id: int|null]>` (keyed by `transaction_item_id`)

Logika (semua dalam `DB::transaction(...)`):

1. Load `Transaction` + items.
2. Guard: `transaction.status === 'complaint'`, kalau bukan → return error.
3. Untuk main transaction (jika `!replaceMainSkip`):
   - Jika `transaction.product_id !== null` → buat `ProductReturn` (`return_type=transaction`, `return_reason='complaint_replacement'`, `notes` berisi reason + sparepart baru).
   - Jika `replaceMainProductId !== null`:
     - Load product baru, cek `stok >= 1` → kalau tidak, throw + rollback.
     - `product->bypassVerification = true; product->stok -= 1; save();`
     - Update `transaction.modal` = harga product baru (ikuti pola UpdateTransaction.php:451).
   - `transaction.product_id = replaceMainProductId`.
4. Untuk setiap item di `replaceItems` yang tidak skip → ulangi logika #3 di scope `TransactionItem`.
5. Recalculate `transaction.untung` dan `fee_teknisi` via `getPerhitungan()` (perlu di-extract sebagai trait/service atau di-copy — lihat [UpdateTransaction.php:458-464](../app/Http/Livewire/Dashboard/UpdateTransaction.php#L458-L464)).
6. Catat `LogActivityTransaction` (lihat [Logging & Audit](#logging--audit)).
7. Jika `replaceMarkAsDone`, set `transaction.status = 'done'`.
8. Commit, dispatch `swal` success, refresh listing.

### Helper yang sebaiknya di-extract

Pola "swap sparepart + recalc" muncul minimal 2 kali (UpdateTransaction + plan ini). Kandidat refactor:

- `app/Services/SparepartSwapService.php` dengan method `swap(int|null $oldProductId, int|null $newProductId, bool $returnOldToStock)` — mengembalikan info `['modal' => ..., 'product' => ...]`.
- Pemakai existing (UpdateTransaction) bisa di-refactor di PR terpisah; untuk plan ini cukup di-copy dulu untuk meminimalkan blast radius.

## Perubahan Schema / Data

### Tabel `product_returns`

Tidak ada perubahan schema. Cukup tambah nilai baru pada kolom `return_reason`:

| Nilai existing       | Nilai baru                |
|----------------------|---------------------------|
| `complaint` (default)| `complaint_replacement`   |
| `complaint_cancel`   |                           |

Karena `return_reason` adalah `string` (bukan enum) — lihat [migration product_returns:21](../database/migrations/2025_07_28_175839_create_product_returns_table.php#L21) — **tidak perlu migration**.

### Tabel `transactions`

Tidak ada perubahan kolom. Status `complaint` tetap sah pasca-replace (bila user pilih "Keep as Complaint").

### Tabel `log_activity_transactions`

Cek apakah kolom `old_sparepart` / `new_sparepart` sudah cukup. Jika perlu mencatat **multiple swap dalam satu aksi** (main + N items), kandidat opsi:
- **Opsi A (sederhana):** insert N row LogActivityTransaction (satu per swap). Sederhana, konsisten dengan pola existing.
- **Opsi B:** tambah kolom `notes` di log untuk menyimpan ringkasan JSON. Butuh migration.

Rekomendasi: **Opsi A** untuk MVP.

## Logging & Audit

Per swap, buat 1 record `LogActivityTransaction` dengan:
- `user = auth()->user()->name`
- `order_transaction = transaction.order_transaction`
- `activity = 'replace_sparepart'` (nilai baru — pastikan kolom `activity` tidak ada constraint enum di DB)
- `old_sparepart`, `new_sparepart`
- *(jika ditambah)* `notes = "Reason: ... | Scope: transaction|transaction_item#id"`

Setiap `ProductReturn` juga punya `notes` yang berisi reason + nama sparepart pengganti, agar bisa ditelusuri dari sisi inventory.

## Validasi & Edge Cases

| Case                                                         | Behavior yang diharapkan                                       |
|--------------------------------------------------------------|-----------------------------------------------------------------|
| Transaksi status bukan `complaint`                           | Reject + swal error                                            |
| Replace tanpa ada perubahan sama sekali (semua skip)         | Disable tombol di UI; backend guard sebagai safety net          |
| Reason kosong                                                | Validasi Livewire `required`                                    |
| Sparepart baru stok 0                                        | Reject transaction (rollback), swal error sebut nama sparepart  |
| `replaceMainProductId == transaction.product_id` (sama)      | Tetap proses (kasus refurbish dengan unit lain di SKU sama)    |
| Sparepart lama sudah di-soft-delete (`Product::withTrashed`) | Tetap bisa dibuat record return (pola sama dengan UpdateTransaction)|
| User klik Replace 2x berturut-turut                          | `DB::transaction` + cek status komplain di awal mencegah dobel  |
| Item yang `product_id` lama = `null`, lalu di-set sparepart baru | Tidak buat ProductReturn (tidak ada barang lama), hanya kurangi stok baru |
| `replaceMarkAsDone=true` tapi semua skip                     | Cuma update status ke `done` (mirip Transaction Completed); pertimbangkan reject untuk hindari confusion |

## Permission

Saat ini [TransactionComplaint.php](../app/Http/Livewire/Dashboard/TransactionComplaint.php) tidak punya gating permission. Untuk fitur baru:

- Tambah permission `complaint_replace_sparepart` (mengikuti pola `stock_opname_create` dari commit `6df0bc5`).
- Gate tombol "Replace Sparepart" dan method backend dengan permission ini.
- Default: hanya owner/admin.

*Catatan:* perlu konfirmasi user — apakah cancel & complete existing juga ingin di-gate sekalian (saat ini terbuka untuk semua role).

## Test Plan

### Unit / Feature test (Laravel)

1. `replaceSparepart` pada transaksi `complaint` dengan main saja → assert: `product_returns` ada 1 row, `transaction.product_id` updated, stok berkurang.
2. Replace dengan item-only → assert scope `transaction_item`.
3. Replace dengan stok baru = 0 → assert rollback (tidak ada side effect).
4. Replace pada transaksi non-complaint → assert reject.
5. `replaceMarkAsDone=true` → assert status `done`.
6. Replace + recalc modal → assert nilai `modal` dan `untung` cocok dengan `getPerhitungan`.
7. Replace dengan sparepart lama = `null` (kasus item tanpa sparepart sebelumnya) → assert tidak buat ProductReturn.

### Manual / UI test

1. Tampilan tab Transaction Complaint: tombol baru muncul, modal terbuka, dropdown sparepart ter-filter stok.
2. Swal konfirmasi menampilkan ringkasan benar.
3. Setelah submit: listing refresh, jika "Mark as Done" transaksi hilang dari tab.
4. Cek halaman Inventory: kolom "Return Stock" produk lama bertambah, klik buka modal — muncul record dengan `return_reason = complaint_replacement` dan notes berisi sparepart pengganti.
5. Cek halaman Log Activity Transaction: ada entry `replace_sparepart` per swap.
6. Cek halaman Reporting Transaction: transaksi tetap muncul sebagai revenue (tidak `cancel`).

## Open Questions

Pertanyaan yang perlu dijawab user sebelum implementasi:

1. **Biaya jasa**: apakah saat replace boleh juga ubah `transactions.biaya` / `transaction_items.biaya`? Atau lock — karena replacement adalah "garansi", customer tidak ditagih lagi.
2. **Auto-complete**: apakah default action setelah replace adalah `done` atau tetap `complaint`?
3. **Permission scope**: hanya owner, atau termasuk teknisi tertentu?
4. **Multiple replace berurutan**: apakah perlu cegah jika sebelumnya pernah ada `complaint_replacement` untuk transaksi yang sama (mungkin tidak — replacement berlapis valid)?
5. **Notifikasi**: apakah perlu trigger notifikasi (email/dashboard) ke supervisor saat replacement dilakukan?
6. **Tracking serial/IMEI sparepart**: saat ini sparepart hanya dikenali via `product_id` (SKU). Apakah perlu kolom `serial_number` di `product_returns` untuk identifikasi unit fisik?
7. **Refactor `SparepartSwapService`**: dilakukan sekarang (PR lebih besar tapi rapi) atau nanti (PR fokus tapi duplikasi)?

## Estimasi & Sequencing

Sequencing PR yang disarankan:

1. **PR-1 (backend foundation)** — ~½ hari
   - Method `replaceSparepart` + listener di `TransactionComplaint.php`
   - Validasi + `DB::transaction`
   - Permission baru `complaint_replace_sparepart` + seeder
   - Unit/feature test

2. **PR-2 (UI)** — ~1 hari
   - Modal Replace Sparepart + form Livewire
   - SweetAlert konfirmasi
   - Integrasi dengan PR-1
   - Manual test pass

3. **PR-3 (optional refactor)** — ~½ hari
   - Extract `SparepartSwapService`
   - Migrate `UpdateTransaction.php` untuk pakai service
   - Smoke test regression edit transaction biasa

Total estimasi: **~2 hari kerja** untuk PR-1 + PR-2 (PR-3 opsional).

## Lampiran: File yang Akan Disentuh

| File                                                                            | Perubahan                                              |
|---------------------------------------------------------------------------------|--------------------------------------------------------|
| [app/Http/Livewire/Dashboard/TransactionComplaint.php](../app/Http/Livewire/Dashboard/TransactionComplaint.php) | Method `openReplaceModal`, `replaceSparepart`, `closeReplaceModal`; properti form |
| [resources/views/livewire/dashboard/transaction-complaint.blade.php](../resources/views/livewire/dashboard/transaction-complaint.blade.php) | Tombol baru + modal Replace + SweetAlert listener |
| [app/Exports/TransactionComplaintExport.php](../app/Exports/TransactionComplaintExport.php) | (opsional) Tambah kolom "Sparepart Replaced" di export |
| `database/seeders/PermissionSeeder.php` (cek nama aktual)                       | Tambah permission `complaint_replace_sparepart`       |
| [docs/complaint-transaction.md](complaint-transaction.md)                       | Update — tambah section "Replace Sparepart Flow"      |
| *(opsional)* `app/Services/SparepartSwapService.php`                            | Service baru hasil refactor                            |
| *(opsional)* `tests/Feature/TransactionComplaintReplaceTest.php`                | Feature test                                           |
