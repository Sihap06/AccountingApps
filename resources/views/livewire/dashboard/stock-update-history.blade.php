<div class="flex flex-wrap -mx-3 custom-height">
    <div class="flex-none w-full max-w-full px-3 h-full">
        <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-xl rounded-2xl bg-clip-border h-full">
            {{-- Filter --}}
            <div class="block md:flex w-full justify-between items-center p-6 pb-0 mb-0">
                <div class="flex items-center gap-3 mb-2 md:mb-0">
                    <select wire:model="filterMonth"
                        class="text-sm rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-700 outline-none focus:border-blue-500 focus:outline-none">
                        <option value="01">Januari</option>
                        <option value="02">Februari</option>
                        <option value="03">Maret</option>
                        <option value="04">April</option>
                        <option value="05">Mei</option>
                        <option value="06">Juni</option>
                        <option value="07">Juli</option>
                        <option value="08">Agustus</option>
                        <option value="09">September</option>
                        <option value="10">Oktober</option>
                        <option value="11">November</option>
                        <option value="12">Desember</option>
                    </select>
                    <select wire:model="filterYear"
                        class="text-sm rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-700 outline-none focus:border-blue-500 focus:outline-none">
                        @foreach($years as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex w-full md:w-5/12 items-center gap-3">
                    <input type="text" wire:model.debounce.500ms="searchTerm"
                        class="text-sm ease block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-700 outline-none placeholder:text-gray-500 focus:border-blue-500 focus:outline-none"
                        placeholder="Cari user atau produk..." />
                    <button wire:click="exportExcel" wire:loading.attr="disabled"
                        class="px-4 py-2 text-xs font-bold text-center text-white uppercase rounded-lg cursor-pointer bg-green-500 leading-normal transition-all hover:-translate-y-px hover:shadow-md disabled:opacity-50 whitespace-nowrap flex items-center gap-1.5">
                        <div wire:loading wire:target="exportExcel">
                            <div class="inline-block h-3 w-3 animate-spin rounded-full border-2 border-solid border-current border-r-transparent" role="status"></div>
                        </div>
                        <span wire:loading.remove wire:target="exportExcel"><i class="fas fa-file-excel mr-1"></i></span>
                        Export
                    </button>
                </div>
            </div>

            {{-- Table --}}
            <div class="flex-auto px-0 pt-4 pb-4 overflow-auto h-full">
                <table class="items-center w-full mb-0 align-top border-collapse text-slate-500">
                    <thead class="align-bottom">
                        <tr>
                            <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b text-xxs tracking-none whitespace-nowrap text-slate-400 opacity-70">No</th>
                            <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b text-xxs tracking-none whitespace-nowrap text-slate-400 opacity-70">Tanggal</th>
                            <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b text-xxs tracking-none whitespace-nowrap text-slate-400 opacity-70">User</th>
                            <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b text-xxs tracking-none whitespace-nowrap text-slate-400 opacity-70">Jumlah Produk</th>
                            <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b text-xxs tracking-none whitespace-nowrap text-slate-400 opacity-70">Total Qty</th>
                            <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b text-xxs tracking-none whitespace-nowrap text-slate-400 opacity-70">Catatan</th>
                            <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b text-xxs tracking-none whitespace-nowrap text-slate-400 opacity-70">Nota</th>
                            <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b text-xxs tracking-none whitespace-nowrap text-slate-400 opacity-70"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($history as $index => $update)
                            <tr>
                                <td class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap">
                                    <span class="text-xs font-semibold text-slate-400">{{ ($history->currentPage() - 1) * $history->perPage() + $loop->iteration }}</span>
                                </td>
                                <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap">
                                    <span class="text-xs font-semibold text-slate-600">{{ $update->created_at->format('d M Y H:i') }}</span>
                                </td>
                                <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap">
                                    <span class="text-xs font-semibold text-slate-600">{{ $update->user->name ?? '-' }}</span>
                                </td>
                                <td class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap">
                                    <span class="text-xs font-semibold text-slate-600">{{ $update->items->count() }}</span>
                                </td>
                                <td class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap">
                                    <span class="px-2 py-0.5 text-xs rounded-full bg-green-100 text-green-700 font-semibold">+{{ $update->items->sum('qty_added') }}</span>
                                </td>
                                <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap">
                                    <span class="text-xs text-slate-500">{{ \Illuminate\Support\Str::limit($update->notes ?? '-', 30) }}</span>
                                </td>
                                <td class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap">
                                    @if($update->nota_image)
                                        <button wire:click="showNota({{ $update->id }})" wire:loading.attr="disabled"
                                            class="text-xs font-semibold text-blue-600 hover:text-blue-800 disabled:opacity-50">
                                            <div wire:loading wire:target="showNota({{ $update->id }})" class="inline-block h-3 w-3 animate-spin rounded-full border-2 border-solid border-blue-600 border-r-transparent" role="status"></div>
                                            <span wire:loading.remove wire:target="showNota({{ $update->id }})"><i class="fas fa-image mr-1"></i>Lihat</span>
                                        </button>
                                    @else
                                        <span class="text-xs text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap">
                                    <button wire:click="showDetail({{ $update->id }})" wire:loading.attr="disabled"
                                        class="text-xs font-semibold text-blue-600 hover:text-blue-800 disabled:opacity-50">
                                        <div wire:loading wire:target="showDetail({{ $update->id }})" class="inline-block h-3 w-3 animate-spin rounded-full border-2 border-solid border-blue-600 border-r-transparent" role="status"></div>
                                        <span wire:loading.remove wire:target="showDetail({{ $update->id }})">Detail</span>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="p-4 text-center text-gray-500 text-sm">Belum ada riwayat update stok.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="px-6 mt-4">
                    {{ $history->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- Detail Modal --}}
    @if($showDetailModal && $detailUpdate)
        <div class="fixed z-50 inset-0 overflow-y-auto">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeDetailModal"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-5xl sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">Detail Update Stok</h3>
                                <div class="text-xs text-gray-500 mt-1 space-y-0.5">
                                    <p>Tanggal: {{ $detailUpdate->created_at->format('d M Y H:i') }}</p>
                                    <p>User: {{ $detailUpdate->user->name ?? '-' }}</p>
                                    @if($detailUpdate->notes)
                                        <p>Catatan: {{ $detailUpdate->notes }}</p>
                                    @endif
                                </div>
                            </div>
                            @if($detailUpdate->nota_image)
                                <a href="{{ asset('storage/' . $detailUpdate->nota_image) }}" target="_blank"
                                    class="px-3 py-1.5 text-xs font-bold text-blue-600 border border-blue-600 rounded-lg hover:bg-blue-50 transition-all">
                                    <i class="fas fa-image mr-1"></i> Lihat Nota
                                </a>
                            @endif
                        </div>

                        <div class="overflow-auto max-h-96">
                            <table class="w-full text-sm border-collapse">
                                <thead class="sticky top-0 bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-bold uppercase text-gray-500 border-b">Produk</th>
                                        <th class="px-4 py-2 text-center text-xs font-bold uppercase text-gray-500 border-b">Qty Tambah</th>
                                        <th class="px-4 py-2 text-center text-xs font-bold uppercase text-gray-500 border-b">Harga Beli</th>
                                        <th class="px-4 py-2 text-center text-xs font-bold uppercase text-gray-500 border-b">Stok Sebelum</th>
                                        <th class="px-4 py-2 text-center text-xs font-bold uppercase text-gray-500 border-b">Stok Sesudah</th>
                                        <th class="px-4 py-2 text-center text-xs font-bold uppercase text-gray-500 border-b">Harga Sebelum</th>
                                        <th class="px-4 py-2 text-center text-xs font-bold uppercase text-gray-500 border-b">Harga Sesudah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($detailItems as $item)
                                        <tr class="{{ ($item['price_before'] ?? 0) != ($item['price_after'] ?? 0) ? 'bg-blue-50' : '' }}">
                                            <td class="px-4 py-2 text-xs font-semibold text-gray-700 border-b">{{ $item['product_name'] }}</td>
                                            <td class="px-4 py-2 text-xs text-center border-b">
                                                <span class="px-2 py-0.5 rounded-full bg-green-100 text-green-700 font-semibold">+{{ $item['qty_added'] }}</span>
                                            </td>
                                            <td class="px-4 py-2 text-xs text-center text-gray-600 border-b">Rp {{ number_format($item['purchase_price'] ?? 0) }}</td>
                                            <td class="px-4 py-2 text-xs text-center text-gray-600 border-b">{{ $item['stock_before'] }}</td>
                                            <td class="px-4 py-2 text-xs text-center font-semibold text-green-600 border-b">{{ $item['stock_after'] }}</td>
                                            <td class="px-4 py-2 text-xs text-center text-gray-600 border-b">Rp {{ number_format($item['price_before'] ?? 0) }}</td>
                                            <td class="px-4 py-2 text-xs text-center font-semibold text-blue-600 border-b">Rp {{ number_format($item['price_after'] ?? 0) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="closeDetailModal"
                            class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:w-auto sm:text-sm">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Nota Modal --}}
    @if($showNotaModal)
        <div class="fixed z-50 inset-0 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeNotaModal"></div>

                <div class="inline-block bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-2xl sm:w-full">
                    <div class="bg-white p-4">
                        <div class="flex justify-between items-center mb-3">
                            <h3 class="text-lg font-medium text-gray-900">Nota Pembelian</h3>
                            <button wire:click="closeNotaModal" class="text-gray-400 hover:text-gray-600">
                                <i class="fas fa-times text-lg"></i>
                            </button>
                        </div>
                        <div class="flex justify-center">
                            <img src="{{ $notaImageUrl }}" class="max-h-[70vh] rounded-lg object-contain" alt="Nota" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
