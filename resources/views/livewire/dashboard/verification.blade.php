<div class="w-full px-6 py-4 mx-auto flex flex-col h-screen">

    <div class="flex justify-between items-center ">
        <div class=" mb-0 border-b-0 border-solid ">
            <h5 class="mb-1 font-serif">Verifikasi Perubahan</h5>
            <p class="mb-0 text-sm leading-normal dark:text-white dark:opacity-60 font-serif">
                {{ \Carbon\Carbon::now()->format('l, d M Y') }}
            </p>
        </div>
        <div class="flex items-center md:ml-auto md:pr-4">
            {{-- Empty for now --}}
        </div>
    </div>

    <div class="flex flex-wrap -mx-3 mt-6 justify-center">
        <div class="flex-none w-full max-w-full px-3">
            <div
                class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border h-full">
                <div
                    class="block md:flex w-full justify-between items-center p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                    <div class="pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                        <h6 class="dark:text-white">Daftar Perubahan</h6>
                        @if ($pendingCount > 0)
                            <span
                                class="inline-block px-3 py-1 text-xs font-bold text-white bg-yellow-600 rounded-full">
                                {{ $pendingCount }} Pending
                            </span>
                        @endif
                    </div>
                    <div class="flex items-center gap-x-3">
                        {{-- Bulk Approve Button --}}
                        @if (count($selectedChanges) > 0)
                            <button type="button" onclick="confirmBulkApprove()"
                                class="px-8 py-2 mb-4 text-xs font-bold leading-normal text-center text-white capitalize transition-all ease-in rounded-lg shadow-md bg-slate-700 bg-150 hover:shadow-xs hover:-translate-y-px"
                                data-te-ripple-color="light">
                                <div wire:loading wire:target='bulkApprove'>
                                    <div class="inline-block h-3 w-3 animate-spin rounded-full border-2 border-solid border-current border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]"
                                        role="status">
                                        <span
                                            class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]">Loading...</span>
                                    </div>
                                </div>
                                Setujui {{ count($selectedChanges) }} Item
                            </button>
                        @endif

                        {{-- Filter Status --}}
                        <select wire:model="statusFilter"
                            class="mb-4 focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none">
                            <option value="">Semua Status</option>
                            <option value="pending">Pending</option>
                            <option value="approved">Disetujui</option>
                            <option value="rejected">Ditolak</option>
                        </select>

                        {{-- Filter Type --}}
                        <select wire:model="typeFilter"
                            class="mb-4 focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none">
                            <option value="">Semua Tipe</option>
                            <option value="App\Models\Product">Produk</option>
                            <option value="App\Models\Transaction">Transaksi</option>
                            <option value="App\Models\TransactionItem">Item Transaksi</option>
                            <option value="App\Models\Expenditure">Pengeluaran</option>
                        </select>
                    </div>
                </div>

                {{-- Alerts --}}
                @if (session()->has('success'))
                    <div class="mx-6 mt-4 relative px-4 py-3 text-white bg-green-500 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session()->has('error'))
                    <div class="mx-6 mt-4 relative px-4 py-3 text-white bg-red-500 rounded-lg">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="flex-auto px-0 pt-0 pb-4">
                    <div class="p-2 overflow-x-auto">
                        <table
                            class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
                            <thead class="align-bottom">
                                <tr>
                                    <th
                                        class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                        <input type="checkbox" wire:model="selectAll" class="cursor-pointer">
                                    </th>
                                    <th
                                        class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                        No
                                    </th>
                                    <th
                                        class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                        Tipe
                                    </th>
                                    <th
                                        class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                        Aksi
                                    </th>
                                    <th
                                        class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                        Diminta Oleh
                                    </th>
                                    <th
                                        class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                        Tanggal
                                    </th>
                                    <th
                                        class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                        Status
                                    </th>
                                    <th
                                        class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                        Verifikator
                                    </th>
                                    <th
                                        class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pendingChanges as $index => $change)
                                    <tr>
                                        <td
                                            class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            @if ($change->isPending())
                                                <input type="checkbox" wire:model="selectedChanges"
                                                    value="{{ $change->id }}" class="cursor-pointer">
                                            @endif
                                        </td>
                                        <td
                                            class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            <span
                                                class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                                {{ ($pendingChanges->currentPage() - 1) * $pendingChanges->perPage() + $index + 1 }}
                                            </span>
                                        </td>
                                        <td
                                            class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            @php
                                                $typeMap = [
                                                    'App\Models\Product' => 'Produk',
                                                    'App\Models\Transaction' => 'Transaksi',
                                                    'App\Models\TransactionItem' => 'Transaksi Item',
                                                    'App\Models\Expenditure' => 'Pengeluaran',
                                                ];
                                            @endphp
                                            <span
                                                class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                                {{ $typeMap[$change->changeable_type] ?? $change->changeable_type }}
                                            </span>
                                        </td>
                                        <td
                                            class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            <span
                                                class="text-xs font-semibold leading-tight px-2 py-1 rounded-md text-white
                                                {{ $change->action == 'create' ? 'bg-green-600' : ($change->action == 'update' ? 'bg-yellow-600' : 'bg-red-600') }}">
                                                {{ ucfirst($change->action) }}
                                            </span>
                                        </td>
                                        <td
                                            class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            <span
                                                class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                                {{ $change->requestedBy->name ?? '-' }}
                                            </span>
                                        </td>
                                        <td
                                            class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            <span
                                                class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                                {{ $change->requested_at->format('d/m/Y H:i') }}
                                            </span>
                                        </td>
                                        <td
                                            class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            <span
                                                class="text-xs font-semibold leading-tight px-2 py-1 rounded-md text-white
                                                {{ $change->status == 'pending' ? 'bg-yellow-600' : ($change->status == 'approved' ? 'bg-green-600' : 'bg-red-600') }}">
                                                {{ $change->status == 'pending' ? 'Pending' : ($change->status == 'approved' ? 'Disetujui' : 'Ditolak') }}
                                            </span>
                                        </td>
                                        <td
                                            class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            <span
                                                class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                                {{ $change->verifiedBy->name ?? '-' }}
                                                @if ($change->verified_at)
                                                    <br><small>{{ $change->verified_at->format('d/m/Y') }}</small>
                                                @endif
                                            </span>
                                        </td>
                                        <td
                                            class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            <button type="button" wire:click="showDetail({{ $change->id }})"
                                                class="inline-block px-3 py-2 text-xs mr-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-primary leading-normal ease-in tracking-tight-rem shadow-md bg-150 bg-x-25 hover:-translate-y-px active:opacity-85 hover:shadow-md">
                                                <i class="fas fa-eye" wire:loading.remove
                                                    wire:target='showDetail({{ $change->id }})'></i>
                                                <div wire:loading wire:target='showDetail({{ $change->id }})'>
                                                    <div class="inline-block h-3 w-3 animate-spin rounded-full border-2 border-solid border-current border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]"
                                                        role="status">
                                                        <span
                                                            class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]">Loading...</span>
                                                    </div>
                                                </div>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9"
                                            class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            <span
                                                class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                                Tidak ada data perubahan
                                            </span>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="px-6 py-3">
                        {{ $pendingChanges->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Detail Modal --}}
    @if ($showDetailModal && $selectedChange)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div
                    class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                            Detail Perubahan #{{ $selectedChange->id }}
                        </h3>

                        {{-- Info Section --}}
                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div>
                                <p class="text-sm text-gray-500">Tipe Data:</p>
                                <p class="text-sm font-semibold">
                                    @php
                                        $typeMap = [
                                            'App\Models\Product' => 'Produk',
                                            'App\Models\Transaction' => 'Transaksi',
                                            'App\Models\TransactionItem' => 'Item Transaksi',
                                            'App\Models\Expenditure' => 'Pengeluaran',
                                        ];
                                    @endphp
                                    {{ $typeMap[$selectedChange->changeable_type] ?? $selectedChange->changeable_type }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Aksi:</p>
                                <p class="text-sm font-semibold">
                                    <span
                                        class="px-2 py-1 rounded-md text-white text-xs
                                        {{ $selectedChange->action == 'create' ? 'bg-green-600' : ($selectedChange->action == 'update' ? 'bg-yellow-600' : 'bg-red-600') }}">
                                        {{ ucfirst($selectedChange->action) }}
                                    </span>
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Diminta Oleh:</p>
                                <p class="text-sm font-semibold">{{ $selectedChange->requestedBy->name ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Tanggal Permintaan:</p>
                                <p class="text-sm font-semibold">
                                    {{ $selectedChange->requested_at->format('d/m/Y H:i:s') }}</p>
                            </div>
                        </div>

                        {{-- Reason Section --}}
                        @if ($selectedChange->reason)
                            <div class="mb-6">
                                <h4 class="text-md font-semibold mb-2">Alasan Perubahan</h4>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-sm text-gray-700">{{ $selectedChange->reason }}</p>
                                </div>
                            </div>
                        @endif

                        {{-- Data Comparison --}}
                        <div class="mb-6">
                            <h4 class="text-md font-semibold mb-3">Perbandingan Data</h4>

                            @if ($selectedChange->action == 'create')
                                <div class="bg-green-50 p-4 rounded-lg">
                                    <h5 class="text-sm font-semibold text-green-800 mb-2">Data Baru</h5>
                                    <div class="grid grid-cols-2 gap-2">
                                        @foreach ($selectedChange->new_data as $key => $value)
                                            <div>
                                                <span
                                                    class="text-xs text-gray-600">{{ ucwords(str_replace('_', ' ', $key)) }}:</span>
                                                <span
                                                    class="text-xs font-semibold">{{ is_array($value) ? json_encode($value) : $value }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @elseif($selectedChange->action == 'update')
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-yellow-50 p-4 rounded-lg">
                                        <h5 class="text-sm font-semibold text-yellow-800 mb-2">Data Lama</h5>
                                        <div class="space-y-1">
                                            @foreach ($selectedChange->old_data as $key => $value)
                                                <div
                                                    class="{{ isset($selectedChange->new_data[$key]) && $selectedChange->new_data[$key] != $value ? 'text-red-600' : '' }}">
                                                    <span
                                                        class="text-xs text-gray-600">{{ ucwords(str_replace('_', ' ', $key)) }}:</span>
                                                    <span
                                                        class="text-xs font-semibold">{{ is_array($value) ? json_encode($value) : $value }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="bg-green-50 p-4 rounded-lg">
                                        <h5 class="text-sm font-semibold text-green-800 mb-2">Data Baru</h5>
                                        <div class="space-y-1">
                                            @foreach ($selectedChange->new_data as $key => $value)
                                                <div
                                                    class="{{ isset($selectedChange->old_data[$key]) && $selectedChange->old_data[$key] != $value ? 'text-green-600' : '' }}">
                                                    <span
                                                        class="text-xs text-gray-600">{{ ucwords(str_replace('_', ' ', $key)) }}:</span>
                                                    <span
                                                        class="text-xs font-semibold">{{ is_array($value) ? json_encode($value) : $value }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @elseif($selectedChange->action == 'delete')
                                <div class="bg-red-50 p-4 rounded-lg">
                                    <h5 class="text-sm font-semibold text-red-800 mb-2">Data yang Akan Dihapus</h5>
                                    <div class="grid grid-cols-2 gap-2">
                                        @foreach ($selectedChange->old_data as $key => $value)
                                            <div>
                                                <span
                                                    class="text-xs text-gray-600">{{ ucwords(str_replace('_', ' ', $key)) }}:</span>
                                                <span
                                                    class="text-xs font-semibold">{{ is_array($value) ? json_encode($value) : $value }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- Verification Actions --}}
                        @if ($selectedChange->isPending())
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-700">Catatan Persetujuan
                                        (Opsional)</label>
                                    <textarea wire:model="verificationNotes" rows="3"
                                        class="mt-1 focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none"
                                        placeholder="Masukkan catatan jika diperlukan..."></textarea>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-700">Alasan Penolakan <span
                                            class="text-red-500">*</span></label>
                                    <textarea wire:model="rejectNotes" rows="3"
                                        class="mt-1 focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none"
                                        placeholder="Masukkan alasan penolakan..."></textarea>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        @if ($selectedChange->isPending())
                            <button type="button" onclick="confirmApprove()"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                                <div wire:loading wire:target='approve' class="mr-2">
                                    <div class="inline-block h-3 w-3 animate-spin rounded-full border-2 border-solid border-current border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]"
                                        role="status">
                                        <span
                                            class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]">Loading...</span>
                                    </div>
                                </div>
                                <span wire:loading.remove wire:target='approve'>Setujui</span>
                            </button>
                            <button type="button" onclick="confirmReject()"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                <div wire:loading wire:target='reject' class="mr-2">
                                    <div class="inline-block h-3 w-3 animate-spin rounded-full border-2 border-solid border-current border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]"
                                        role="status">
                                        <span
                                            class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]">Loading...</span>
                                    </div>
                                </div>
                                <span wire:loading.remove wire:target='reject'>Tolak</span>
                            </button>
                        @endif
                        <button wire:click="closeDetail"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
    function confirmApprove() {
        Swal.fire({
            title: 'Konfirmasi Persetujuan',
            text: "Apakah Anda yakin ingin menyetujui perubahan ini?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Setujui!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                @this.approve();
            }
        });
    }

    function confirmReject() {
        const rejectNotes = document.querySelector('[wire\\:model="rejectNotes"]').value;

        if (!rejectNotes.trim()) {
            Swal.fire({
                title: 'Peringatan!',
                text: 'Alasan penolakan harus diisi!',
                icon: 'warning',
                confirmButtonColor: '#ef4444'
            });
            return;
        }

        Swal.fire({
            title: 'Konfirmasi Penolakan',
            text: "Apakah Anda yakin ingin menolak perubahan ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Tolak!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                @this.reject();
            }
        });
    }

    function confirmBulkApprove() {
        const count = {{ count($selectedChanges) }};
        Swal.fire({
            title: 'Konfirmasi Persetujuan Massal',
            text: `Apakah Anda yakin ingin menyetujui ${count} perubahan yang dipilih?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#6b7280',
            confirmButtonText: `Ya, Setujui ${count} Item!`,
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                @this.bulkApprove();
            }
        });
    }
</script>
