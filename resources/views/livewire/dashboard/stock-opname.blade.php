<div class="w-full px-6 py-4 mx-auto flex flex-col h-screen">
    <div class="flex justify-between items-center">
        <div class="mb-0 border-b-0 border-solid">
            <h5 class="mb-1 font-serif">Stock Opname</h5>
            <p class="mb-0 text-sm leading-normal dark:text-white dark:opacity-60 font-serif">
                {{ \Carbon\Carbon::now()->format('l, d M Y') }}
            </p>
        </div>
        <div class="flex items-center gap-x-3">
            @if(auth()->user()->isOwner())
                <button wire:click="openTriggerModal"
                    class="px-6 py-2 text-xs font-bold leading-normal text-center text-white capitalize transition-all ease-in rounded-lg shadow-md bg-blue-600 hover:shadow-xs hover:-translate-y-px flex gap-x-2 items-center">
                    <div wire:loading wire:target="openTriggerModal">
                        <div class="inline-block h-3 w-3 animate-spin rounded-full border-2 border-solid border-current border-r-transparent" role="status"></div>
                    </div>
                    <i class="fas fa-clipboard-check"></i>
                    Create Stock Opname
                </button>
            @endif
        </div>
    </div>

    {{-- Active Stock Opname Section --}}
    @if($activeOpname)
        <div class="mt-6">
            <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-xl rounded-2xl bg-clip-border">
                {{-- Header --}}
                <div class="p-6 pb-3 border-b border-gray-100">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-3 mb-2">
                                <h6 class="mb-0 text-base font-semibold">Active Stock Opname</h6>
                                @if($activeOpname->status === 'pending')
                                    <span class="px-2.5 py-1 text-xs rounded-full bg-yellow-100 text-yellow-700 font-semibold">Pending</span>
                                @else
                                    <span class="px-2.5 py-1 text-xs rounded-full bg-blue-100 text-blue-700 font-semibold">In Progress</span>
                                @endif
                            </div>
                            <p class="text-xs text-gray-500">
                                Created by <span class="font-semibold">{{ $activeOpname->triggeredBy->name }}</span>
                                on {{ $activeOpname->created_at->format('d M Y H:i') }}
                                @if($activeOpname->assignedTo)
                                    &mdash; Assigned to <span class="font-semibold">{{ $activeOpname->assignedTo->name }}</span>
                                @endif
                            </p>
                            @if($activeOpname->notes)
                                <p class="text-xs text-gray-500 mt-1"><i class="fas fa-sticky-note mr-1"></i> {{ $activeOpname->notes }}</p>
                            @endif
                        </div>
                        <div class="flex items-center gap-3">
                            {{-- Progress bar --}}
                            <div class="flex items-center gap-2 min-w-[200px]">
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <div class="bg-blue-600 h-2.5 rounded-full transition-all duration-300" style="width: {{ $activeOpname->progress }}%"></div>
                                </div>
                                <span class="text-xs font-semibold text-gray-600 whitespace-nowrap">{{ $activeOpname->progress }}%</span>
                            </div>

                            @if($activeOpname->status === 'pending' && !auth()->user()->isOwner())
                                <button wire:click="startOpname" wire:loading.attr="disabled"
                                    class="px-4 py-2 text-xs font-bold text-white bg-green-600 rounded-lg hover:bg-green-700 transition-all whitespace-nowrap disabled:opacity-50 flex items-center gap-1.5">
                                    <div wire:loading wire:target="startOpname">
                                        <div class="inline-block h-3 w-3 animate-spin rounded-full border-2 border-solid border-current border-r-transparent" role="status"></div>
                                    </div>
                                    <span wire:loading.remove wire:target="startOpname"><i class="fas fa-play mr-1"></i></span>
                                    Start Opname
                                </button>
                            @endif

                            @if($activeOpname->status === 'in_progress')
                                <button wire:click="completeOpname" wire:loading.attr="disabled"
                                    class="px-4 py-2 text-xs font-bold text-white bg-green-600 rounded-lg hover:bg-green-700 transition-all whitespace-nowrap disabled:opacity-50 flex items-center gap-1.5">
                                    <div wire:loading wire:target="completeOpname">
                                        <div class="inline-block h-3 w-3 animate-spin rounded-full border-2 border-solid border-current border-r-transparent" role="status"></div>
                                    </div>
                                    <span wire:loading.remove wire:target="completeOpname"><i class="fas fa-check mr-1"></i></span>
                                    Complete
                                </button>
                            @endif

                            @if(auth()->user()->isOwner())
                                <button type="button" onclick="confirmCancelOpname()" wire:loading.attr="disabled"
                                    class="px-4 py-2 text-xs font-bold text-white bg-red-500 rounded-lg hover:bg-red-600 transition-all whitespace-nowrap disabled:opacity-50 flex items-center gap-1.5">
                                    <div wire:loading wire:target="cancelOpname">
                                        <div class="inline-block h-3 w-3 animate-spin rounded-full border-2 border-solid border-current border-r-transparent" role="status"></div>
                                    </div>
                                    <span wire:loading.remove wire:target="cancelOpname"><i class="fas fa-times mr-1"></i></span>
                                    Cancel
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Search & Filter --}}
                @if($activeOpname->status === 'in_progress')
                <div class="px-6 pt-4 flex flex-col md:flex-row gap-3">
                    <input type="text" wire:model.debounce.300ms="searchProduct"
                        class="text-sm leading-5.6 ease block w-full md:w-1/3 appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none"
                        placeholder="Search product..." />
                    <select wire:model="filterStatus"
                        class="text-sm leading-5.6 ease block w-full md:w-auto appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all focus:border-blue-500 focus:outline-none">
                        <option value="all">All</option>
                        <option value="unchecked">Unchecked</option>
                        <option value="checked">Checked</option>
                    </select>
                </div>
                @endif

                {{-- Items Table --}}
                <div class="flex-auto px-0 pt-4 pb-4 overflow-auto">
                    <table class="items-center w-full mb-0 align-top border-collapse text-slate-500">
                        <thead class="align-bottom">
                            <tr>
                                <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b text-xxs tracking-none whitespace-nowrap text-slate-400 opacity-70">No</th>
                                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b text-xxs tracking-none whitespace-nowrap text-slate-400 opacity-70">Product</th>
                                <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b text-xxs tracking-none whitespace-nowrap text-slate-400 opacity-70">System Stock</th>
                                <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b text-xxs tracking-none whitespace-nowrap text-slate-400 opacity-70">Actual Stock</th>
                                <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b text-xxs tracking-none whitespace-nowrap text-slate-400 opacity-70">Difference</th>
                                <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b text-xxs tracking-none whitespace-nowrap text-slate-400 opacity-70">Notes</th>
                                <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b text-xxs tracking-none whitespace-nowrap text-slate-400 opacity-70">Status</th>
                                @if($activeOpname->status === 'in_progress')
                                <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b text-xxs tracking-none whitespace-nowrap text-slate-400 opacity-70"></th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($activeItems as $index => $item)
                                <tr wire:key="item-{{ $item->id }}">
                                    <td class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap">
                                        <span class="text-xs font-semibold text-slate-400">{{ $index + 1 }}</span>
                                    </td>
                                    <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap">
                                        <span class="text-sm font-semibold text-slate-700">{{ $item->product_name }}</span>
                                    </td>
                                    <td class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap">
                                        <span class="text-xs font-semibold text-slate-600">{{ $item->system_stock }}</span>
                                    </td>

                                    @if($editingItemId === $item->id)
                                        {{-- Editing mode --}}
                                        <td class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap">
                                            <input type="number" wire:model.defer="actualStock" min="0"
                                                class="w-20 text-center text-sm rounded-lg border border-gray-300 px-2 py-1 focus:border-blue-500 focus:outline-none"
                                                autofocus />
                                            @error('actualStock')
                                                <p class="text-red-500 text-xxs mt-0.5">{{ $message }}</p>
                                            @enderror
                                        </td>
                                        <td class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap">
                                            <span class="text-xs text-gray-400">-</span>
                                        </td>
                                        <td class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap">
                                            <input type="text" wire:model.defer="itemNotes"
                                                class="w-32 text-sm rounded-lg border border-gray-300 px-2 py-1 focus:border-blue-500 focus:outline-none"
                                                placeholder="Optional" />
                                        </td>
                                        <td class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap">
                                            <span class="text-xs text-gray-400">-</span>
                                        </td>
                                        <td class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap">
                                            <div class="flex items-center justify-center gap-2">
                                                <button wire:click="saveItem" wire:loading.attr="disabled"
                                                    class="px-3 py-1 text-xs font-bold text-white bg-green-600 rounded-lg hover:bg-green-700 disabled:opacity-50">
                                                    <div wire:loading wire:target="saveItem" class="inline-block h-3 w-3 animate-spin rounded-full border-2 border-solid border-current border-r-transparent" role="status"></div>
                                                    <i wire:loading.remove wire:target="saveItem" class="fas fa-check"></i>
                                                </button>
                                                <button wire:click="cancelEdit" wire:loading.attr="disabled"
                                                    class="px-3 py-1 text-xs font-bold text-white bg-gray-400 rounded-lg hover:bg-gray-500 disabled:opacity-50">
                                                    <div wire:loading wire:target="cancelEdit" class="inline-block h-3 w-3 animate-spin rounded-full border-2 border-solid border-current border-r-transparent" role="status"></div>
                                                    <i wire:loading.remove wire:target="cancelEdit" class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </td>
                                    @else
                                        {{-- Display mode --}}
                                        <td class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap">
                                            <span class="text-xs font-semibold {{ $item->checked ? 'text-slate-600' : 'text-gray-400' }}">
                                                {{ $item->checked ? $item->actual_stock : '-' }}
                                            </span>
                                        </td>
                                        <td class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap">
                                            @if($item->checked)
                                                @if($item->difference == 0)
                                                    <span class="px-2 py-0.5 text-xs rounded-full bg-green-100 text-green-700 font-semibold">0</span>
                                                @elseif($item->difference > 0)
                                                    <span class="px-2 py-0.5 text-xs rounded-full bg-blue-100 text-blue-700 font-semibold">+{{ $item->difference }}</span>
                                                @else
                                                    <span class="px-2 py-0.5 text-xs rounded-full bg-red-100 text-red-700 font-semibold">{{ $item->difference }}</span>
                                                @endif
                                            @else
                                                <span class="text-xs text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap">
                                            <span class="text-xs text-gray-500">{{ $item->notes ?? '-' }}</span>
                                        </td>
                                        <td class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap">
                                            @if($item->checked)
                                                <span class="px-2 py-0.5 text-xs rounded-full bg-green-100 text-green-700 font-semibold">
                                                    <i class="fas fa-check text-xxs mr-0.5"></i> Done
                                                </span>
                                            @else
                                                <span class="px-2 py-0.5 text-xs rounded-full bg-gray-100 text-gray-500 font-semibold">Pending</span>
                                            @endif
                                        </td>
                                        @if($activeOpname->status === 'in_progress')
                                        <td class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap">
                                            <button wire:click="editItem({{ $item->id }})" wire:loading.attr="disabled"
                                                class="text-xs font-semibold text-blue-600 hover:text-blue-800 disabled:opacity-50">
                                                <div wire:loading wire:target="editItem({{ $item->id }})" class="inline-block h-3 w-3 animate-spin rounded-full border-2 border-solid border-blue-600 border-r-transparent" role="status"></div>
                                                <span wire:loading.remove wire:target="editItem({{ $item->id }})">{{ $item->checked ? 'Edit' : 'Check' }}</span>
                                            </button>
                                        </td>
                                        @endif
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="p-4 text-center text-gray-500 text-sm">
                                        @if($searchProduct)
                                            Product not found.
                                        @else
                                            No item.
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    {{-- History Section --}}
    <div class="mt-6 flex-1">
        <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-xl rounded-2xl bg-clip-border h-full">
            <div class="p-6 pb-3 border-b border-gray-100">
                <h6 class="mb-0 text-base font-semibold">Stock Opname History</h6>
            </div>
            <div class="flex-auto px-0 pt-0 pb-4 overflow-auto">
                <table class="items-center w-full mb-0 align-top border-collapse text-slate-500">
                    <thead class="align-bottom">
                        <tr>
                            <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b text-xxs tracking-none whitespace-nowrap text-slate-400 opacity-70">No</th>
                            <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b text-xxs tracking-none whitespace-nowrap text-slate-400 opacity-70">Date</th>
                            <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b text-xxs tracking-none whitespace-nowrap text-slate-400 opacity-70">Created By</th>
                            <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b text-xxs tracking-none whitespace-nowrap text-slate-400 opacity-70">Completed By</th>
                            <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b text-xxs tracking-none whitespace-nowrap text-slate-400 opacity-70">Total Items</th>
                            <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b text-xxs tracking-none whitespace-nowrap text-slate-400 opacity-70">Difference</th>
                            <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b text-xxs tracking-none whitespace-nowrap text-slate-400 opacity-70">Status</th>
                            <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b text-xxs tracking-none whitespace-nowrap text-slate-400 opacity-70"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($history as $index => $opname)
                            <tr>
                                <td class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap">
                                    <span class="text-xs font-semibold text-slate-400">{{ ($history->currentPage() - 1) * $history->perPage() + $loop->iteration }}</span>
                                </td>
                                <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap">
                                    <span class="text-xs font-semibold text-slate-600">{{ $opname->created_at->format('d M Y H:i') }}</span>
                                </td>
                                <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap">
                                    <span class="text-xs font-semibold text-slate-600">{{ $opname->triggeredBy->name ?? '-' }}</span>
                                </td>
                                <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap">
                                    <span class="text-xs font-semibold text-slate-600">{{ $opname->completedBy->name ?? '-' }}</span>
                                </td>
                                <td class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap">
                                    <span class="text-xs font-semibold text-slate-600">{{ $opname->items->count() }}</span>
                                </td>
                                <td class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap">
                                    @php
                                        $totalDiff = $opname->items->sum('difference');
                                    @endphp
                                    @if($opname->status === 'completed')
                                        @if($totalDiff == 0)
                                            <span class="px-2 py-0.5 text-xs rounded-full bg-green-100 text-green-700 font-semibold">Match</span>
                                        @else
                                            <span class="px-2 py-0.5 text-xs rounded-full bg-red-100 text-red-700 font-semibold">{{ $totalDiff > 0 ? '+' : '' }}{{ $totalDiff }}</span>
                                        @endif
                                    @else
                                        <span class="text-xs text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap">
                                    @if($opname->status === 'completed')
                                        <span class="px-2 py-0.5 text-xs rounded-full bg-green-100 text-green-700 font-semibold">Completed</span>
                                    @else
                                        <span class="px-2 py-0.5 text-xs rounded-full bg-gray-100 text-gray-500 font-semibold">Cancelled</span>
                                    @endif
                                </td>
                                <td class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap">
                                    <button wire:click="showDetail({{ $opname->id }})" wire:loading.attr="disabled"
                                        class="text-xs font-semibold text-blue-600 hover:text-blue-800 disabled:opacity-50">
                                        <div wire:loading wire:target="showDetail({{ $opname->id }})" class="inline-block h-3 w-3 animate-spin rounded-full border-2 border-solid border-blue-600 border-r-transparent" role="status"></div>
                                        <span wire:loading.remove wire:target="showDetail({{ $opname->id }})">Detail</span>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="p-4 text-center text-gray-500 text-sm">No stock opname history.</td>
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

    {{-- Trigger Modal (Owner) --}}
    @if($showTriggerModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="closeTriggerModal"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                                <i class="fas fa-clipboard-check text-blue-600"></i>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Create New Stock Opname</h3>
                                <form class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Assign to Cashier (Optional)</label>
                                        <select wire:model="selectedKasir"
                                            class="text-sm ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all focus:border-blue-500 focus:outline-none">
                                            <option value="">All Cashiers</option>
                                            @foreach($kasirList as $kasir)
                                                <option value="{{ $kasir->id }}">{{ $kasir->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Notes (Optional)</label>
                                        <textarea wire:model="triggerNotes" rows="3"
                                            class="text-sm ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all focus:border-blue-500 focus:outline-none"
                                            placeholder="Example: Check all stock for phone spare parts"></textarea>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="triggerStockOpname"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                            <div wire:loading wire:target="triggerStockOpname" class="mr-2">
                                <div class="inline-block h-3 w-3 animate-spin rounded-full border-2 border-solid border-current border-r-transparent" role="status"></div>
                            </div>
                            Create & Send Notification
                        </button>
                        <button wire:click="closeTriggerModal"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Detail Modal --}}
    @if($showDetailModal && $detailOpname)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="closeDetailModal"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-2">Stock Opname Detail</h3>
                        <div class="text-xs text-gray-500 mb-4 space-y-1">
                            <p>Date: {{ $detailOpname->created_at->format('d M Y H:i') }}</p>
                            <p>Created by: {{ $detailOpname->triggeredBy->name ?? '-' }}</p>
                            @if($detailOpname->completedBy)
                                <p>Completed by: {{ $detailOpname->completedBy->name }} on {{ $detailOpname->completed_at?->format('d M Y H:i') }}</p>
                            @endif
                        </div>

                        <div class="overflow-auto max-h-96">
                            <table class="items-center w-full mb-0 align-top border-collapse text-slate-500">
                                <thead class="align-bottom sticky top-0 bg-white">
                                    <tr>
                                        <th class="px-4 py-2 font-bold text-left text-xxs uppercase text-slate-400 border-b">Product</th>
                                        <th class="px-4 py-2 font-bold text-center text-xxs uppercase text-slate-400 border-b">System Stock</th>
                                        <th class="px-4 py-2 font-bold text-center text-xxs uppercase text-slate-400 border-b">Actual Stock</th>
                                        <th class="px-4 py-2 font-bold text-center text-xxs uppercase text-slate-400 border-b">Difference</th>
                                        <th class="px-4 py-2 font-bold text-left text-xxs uppercase text-slate-400 border-b">Notes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($detailItems as $item)
                                        <tr class="{{ ($item['difference'] ?? 0) != 0 ? 'bg-red-50' : '' }}">
                                            <td class="px-4 py-2 text-xs font-semibold text-slate-700 border-b">{{ $item['product_name'] }}</td>
                                            <td class="px-4 py-2 text-xs text-center text-slate-600 border-b">{{ $item['system_stock'] }}</td>
                                            <td class="px-4 py-2 text-xs text-center text-slate-600 border-b">{{ $item['actual_stock'] ?? '-' }}</td>
                                            <td class="px-4 py-2 text-xs text-center border-b">
                                                @if(isset($item['difference']))
                                                    @if($item['difference'] == 0)
                                                        <span class="text-green-600 font-semibold">0</span>
                                                    @elseif($item['difference'] > 0)
                                                        <span class="text-blue-600 font-semibold">+{{ $item['difference'] }}</span>
                                                    @else
                                                        <span class="text-red-600 font-semibold">{{ $item['difference'] }}</span>
                                                    @endif
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="px-4 py-2 text-xs text-slate-500 border-b">{{ $item['notes'] ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="closeDetailModal"
                            class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:w-auto sm:text-sm">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@push('script')
<script>
    function confirmCancelOpname() {
        Swal.fire({
            title: 'Cancel Opname?',
            text: "The current stock checking progress will be completely cancelled. Continue?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, Cancel!',
            cancelButtonText: 'No'
        }).then((result) => {
            if (result.isConfirmed) {
                @this.cancelOpname();
            }
        });
    }
</script>
@endpush
