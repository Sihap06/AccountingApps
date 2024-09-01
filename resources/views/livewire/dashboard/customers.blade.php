   <div class="w-full px-6 py-4 mx-auto flex flex-col h-screen">
       <div class="flex justify-between items-center">
           <div class="mb-0 border-b-0 border-solid">
               <h5 class="mb-1 font-serif">Customers</h5>
               <p class="mb-0 text-sm leading-normal dark:text-white dark:opacity-60 font-serif">
                   {{ \Carbon\Carbon::now()->format('l, d M Y') }}
               </p>
           </div>
           <div class="flex items-center md:ml-auto md:pr-4"></div>
       </div>

       <div class="flex flex-wrap -mx-3 mt-6 custom-height">
           <div class="flex-none w-full max-w-full px-3 h-full">
               <div
                   class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border h-full">
                   <div
                       class="block md:flex w-full justify-between items-center p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                       <button wire:click='create'
                           class="px-8 py-2 text-xs font-bold leading-normal text-center text-white capitalize transition-all ease-in rounded-lg shadow-md bg-slate-700 bg-150 hover:shadow-xs hover:-translate-y-px flex gap-x-2 items-center">
                           <div wire:loading wire:target='create'>
                               <div class="inline-block h-3 w-3 animate-spin rounded-full border-2 border-solid border-current border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]"
                                   role="status">
                                   <span
                                       class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]">Loading...</span>
                               </div>
                           </div>
                           New Customer
                       </button>
                       <div class="flex w-full md:w-4/12 items-center gap-x-3">
                           <input type="text" wire:model.debounce.500ms="searchTerm"
                               class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none"
                               placeholder="Masukkan nama atau no telefon " />
                       </div>
                   </div>

                   <div class="flex-auto px-0 pt-0 mt-4 pb-4 overflow-auto h-full">
                       <div class="p-0">
                           <table
                               class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
                               <thead class="align-bottom">
                                   <tr>
                                       <th
                                           class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                           No</th>
                                       <th
                                           class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                           Nama</th>
                                       <th
                                           class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                           No Telp</th>
                                       <th
                                           class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                           Alamat</th>
                                       <th
                                           class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                       </th>
                                   </tr>
                               </thead>
                               <tbody>
                                   @foreach ($data as $index => $item)
                                       <tr wire:key='{{ $index . time() }}' wire:loading.remove
                                           wire:target='gotoPage, previousPage, nextPage, searchTerm'>
                                           <td
                                               class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                               <span
                                                   class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                                   {{ $index + 1 }}
                                               </span>
                                           </td>
                                           <td
                                               class="p-2 text-left align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                               <span
                                                   class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                                   {{ $item->name }}
                                               </span>
                                           </td>
                                           <td
                                               class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                               <span
                                                   class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                                   {{ $item->no_telp }}
                                               </span>
                                           </td>
                                           <td
                                               class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                               <span
                                                   class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                                   {{ $item->alamat === null ? '-' : $item->alamat }}
                                               </span>
                                           </td>
                                           <td
                                               class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                               <div>
                                                   <button wire:click='edit({{ $item->id }})'
                                                       class="inline-block px-3 py-2 text-xs mr-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-warning-600 leading-normal ease-in tracking-tight-rem shadow-md bg-150 bg-x-25 hover:-translate-y-px active:opacity-85 hover:shadow-md">
                                                       <i class="fas fa-edit" wire:loading.remove
                                                           wire:target='edit({{ $item->id }})'></i>
                                                       <div wire:loading wire:target='edit({{ $item->id }})'>
                                                           <div class="inline-block h-3 w-3 animate-spin rounded-full border-2 border-solid border-current border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]"
                                                               role="status">
                                                               <span
                                                                   class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]">Loading...</span>
                                                           </div>
                                                       </div>
                                                   </button>
                                                   <button type="button"
                                                       wire:click="$emit('triggerDelete',{{ $item->id }})"
                                                       class="inline-block px-3 py-2 text-xs mr-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-red-600 leading-normal ease-in tracking-tight-rem shadow-md bg-150 bg-x-25 hover:-translate-y-px active:opacity-85 hover:shadow-md">
                                                       <i class="fas fa-trash-alt" wire:loading.remove
                                                           wire:target='delete({{ $item->id }})'></i>

                                                       <div wire:loading wire:target='delete({{ $item->id }})'>
                                                           <div class="inline-block h-3 w-3 animate-spin rounded-full border-2 border-solid border-current border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]"
                                                               role="status">
                                                               <span
                                                                   class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]">Loading...</span>
                                                           </div>
                                                       </div>
                                                   </button>
                                                   <a href="https://wa.me/{{ \Str::replaceFirst('0', '62', $item->no_telp) }}"
                                                       target="_blank"
                                                       class="inline-block px-3 py-2 text-xs mr-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-green-700 leading-normal ease-in tracking-tight-rem shadow-md bg-150 bg-x-25 hover:-translate-y-px active:opacity-85 hover:shadow-md">
                                                       <img src="{{ asset('assets/img/whatsapp.svg') }}"
                                                           alt="whatsapp-icon">
                                                   </a>
                                                   <button type="button"
                                                       wire:click="detailTransaction({{ $item->id }})"
                                                       class="inline-block px-3 py-2 text-xs mr-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-primary leading-normal ease-in tracking-tight-rem shadow-md bg-150 bg-x-25 hover:-translate-y-px active:opacity-85 hover:shadow-md">
                                                       <i class="fas fa-list" wire:loading.remove
                                                           wire:target='detailTransaction({{ $item->id }})'></i>

                                                       <div wire:loading
                                                           wire:target='detailTransaction({{ $item->id }})'>
                                                           <div class="inline-block h-3 w-3 animate-spin rounded-full border-2 border-solid border-current border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]"
                                                               role="status">
                                                               <span
                                                                   class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]">Loading...</span>
                                                           </div>
                                                       </div>
                                                   </button>
                                               </div>
                                           </td>
                                       </tr>
                                   @endforeach

                                   @for ($i = 0; $i <= 10; $i++)
                                       <tr wire:loading.class="table-row" class="hidden"
                                           wire:loading.class.remove="hidden"
                                           wire:target='gotoPage, previousPage, nextPage, searchTerm, loadMore'>
                                           <td
                                               class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                               <div
                                                   class="mb-2 h-5 w-full rounded overflow-hidden relative bg-gray-200">
                                               </div>
                                           </td>
                                           <td
                                               class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                               <div
                                                   class="mb-2 h-5 w-full rounded overflow-hidden relative bg-gray-200">
                                               </div>
                                           </td>
                                           <td
                                               class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                               <div
                                                   class="mb-2 h-5 w-full rounded overflow-hidden relative bg-gray-200">
                                               </div>
                                           </td>
                                           <td
                                               class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                               <div
                                                   class="mb-2 h-5 w-full rounded overflow-hidden relative bg-gray-200">
                                               </div>
                                           </td>
                                           <td
                                               class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                               <div
                                                   class="mb-2 h-5 w-full rounded overflow-hidden relative bg-gray-200">
                                               </div>
                                           </td>
                                       </tr>
                                   @endfor
                               </tbody>
                           </table>
                           <div class="p-4">
                               {{ $data->links() }}
                           </div>
                       </div>
                   </div>
               </div>
           </div>
       </div>

       @if ($isOpen)
           <div class="fixed z-10 inset-0 overflow-y-auto">
               <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                   <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                       <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                   </div>

                   <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                   <div
                       class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                       <div>
                           <div class="mt-3 sm:mt-5">
                               <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                   {{ $modalType === 'store' ? 'Add New Customer' : 'Edit Customer' }}
                               </h3>
                               <div class="mt-2">
                                   <div class="mt-4">
                                       <label for="name"
                                           class="block text-sm font-medium text-gray-700">Name</label>
                                       <div class="mt-1 relative rounded-md">
                                           <input type="text" wire:model.lazy="name" id="name"
                                               class="input input-md h-11 focus:ring-indigo-600 focus-within:ring-indigo-600 focus-within:border-indigo-600 focus:border-indigo-600">
                                       </div>
                                       @error('name')
                                           <span class="text-red-500 text-xs">{{ $message }}</span>
                                       @enderror
                                   </div>
                                   <div class="mt-4">
                                       <label for="no_telp" class="block text-sm font-medium text-gray-700">No
                                           Telefon</label>
                                       <div class="mt-1 relative rounded-md">
                                           <input type="text" wire:model.lazy="no_telp" id="no_telp"
                                               class="input input-md h-11 focus:ring-indigo-600 focus-within:ring-indigo-600 focus-within:border-indigo-600 focus:border-indigo-600">
                                       </div>
                                       @error('no_telp')
                                           <span class="text-red-500 text-xs">{{ $message }}</span>
                                       @enderror
                                   </div>
                                   <div class="mt-4">
                                       <label for="alamat" class="block text-sm font-medium text-gray-700">Address
                                           (optional)</label>
                                       <div class="mt-1 relative rounded-md">
                                           <input type="text" wire:model.lazy="alamat" id="alamat"
                                               class="input input-md h-11 focus:ring-indigo-600 focus-within:ring-indigo-600 focus-within:border-indigo-600 focus:border-indigo-600">
                                       </div>
                                       @error('alamat')
                                           <span class="text-red-500 text-xs">{{ $message }}</span>
                                       @enderror
                                   </div>
                               </div>
                           </div>
                       </div>
                       <div class="flex gap-x-2 mt-5 sm:mt-6">
                           <button wire:click="{{ $modalType }}" wire:loading.attr="disabled"
                               wire:target="{{ $modalType }}"
                               class="inline-flex gap-x-2 justify-center w-full rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-500 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm">
                               <div wire:loading wire:target='{{ $modalType }}'>
                                   <div class="inline-block h-3 w-3 animate-spin rounded-full border-2 border-solid border-current border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]"
                                       role="status">
                                       <span
                                           class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]">Loading...</span>
                                   </div>
                               </div>
                               Save
                           </button>
                           <button wire:click="closeModal" wire:loading.attr="disabled" wire:target="closeModal"
                               class="inline-flex gap-x-2 justify-center w-full rounded-md border border-transparent shadow-sm px-4 py-2 bg-gray-500 text-base font-medium text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:text-sm">
                               <div wire:loading wire:target='closeModal'>
                                   <div class="inline-block h-3 w-3 animate-spin rounded-full border-2 border-solid border-current border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]"
                                       role="status">
                                       <span
                                           class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]">Loading...</span>
                                   </div>
                               </div>
                               Cancel
                           </button>
                       </div>
                   </div>
               </div>
           </div>
       @endif

       @if ($isOpenDetailTransaction)
           <div class="fixed z-10 inset-0 overflow-y-auto">
               <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                   <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                       <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                   </div>

                   <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                   <div
                       class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-70/100 sm:w-full sm:p-6">
                       <div class="flex flex-row justify-between items-center">
                           <h3 class="text-lg leading-6 font-medium text-gray-900 mb-0" id="modal-title">
                               Detail Transaction
                           </h3>
                           <button wire:loading.remove='closeModalDetailTransaction' type="button"
                               wire:click='closeModalDetailTransaction'>
                               <svg width="20px" height="20px" viewBox="-0.5 0 25 25" fill="none"
                                   xmlns="http://www.w3.org/2000/svg">
                                   <path d="M3 21.32L21 3.32001" stroke="#000000" stroke-width="1.5"
                                       stroke-linecap="round" stroke-linejoin="round" />
                                   <path d="M3 3.32001L21 21.32" stroke="#000000" stroke-width="1.5"
                                       stroke-linecap="round" stroke-linejoin="round" />
                               </svg>
                           </button>

                           <div wire:loading wire:target='closeModalDetailTransaction'>
                               <div class="inline-block h-5 w-5 animate-spin rounded-full border-2 border-solid border-current border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]"
                                   role="status">
                                   <span
                                       class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]">Loading...</span>
                               </div>
                           </div>
                       </div>
                       <div class="mt-2">
                           <div class="flex-auto px-0 pt-0 mt-4 pb-4 overflow-auto h-full">
                               <div class="p-0">
                                   <table
                                       class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
                                       <thead class="align-bottom">
                                           <tr>
                                               <th
                                                   class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                                   Tanggal
                                               </th>
                                               <th
                                                   class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                                   Service
                                               </th>
                                               <th
                                                   class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                                   Biaya
                                               </th>
                                               <th
                                                   class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                                   Teknisi
                                               </th>
                                               <th
                                                   class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                                   Sparepart
                                               </th>
                                           </tr>
                                       </thead>
                                       <tbody>
                                           @foreach ($transactionItems as $item)
                                               <tr>
                                                   <td
                                                       class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                       <span
                                                           class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                                           {{ \Carbon\Carbon::parse($item['created_at'])->format('d/m/Y') }}
                                                       </span>
                                                   </td>
                                                   <td
                                                       class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                       <span
                                                           class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                                           {{ $item['service'] }}
                                                       </span>
                                                   </td>
                                                   <td
                                                       class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                       <span
                                                           class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                                           {{ number_format($item['biaya']) }}

                                                       </span>
                                                   </td>
                                                   <td
                                                       class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                       <span
                                                           class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                                           {{ \App\Models\Technician::getTechnicalName($item['technical_id']) ?? '-' }}
                                                       </span>
                                                   </td>
                                                   <td
                                                       class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                       <span
                                                           class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                                           {{ \App\Models\Product::getProductName($item['product_id']) ?? '-' }}
                                                       </span>
                                                   </td>
                                               </tr>

                                               @foreach ($item['items'] as $value)
                                                   <tr>
                                                       <td
                                                           class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                           <span
                                                               class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                                               {{ \Carbon\Carbon::parse($value['created_at'])->format('d/m/Y') }}
                                                           </span>
                                                       </td>
                                                       <td
                                                           class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                           <span
                                                               class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                                               {{ $value['service'] }}
                                                           </span>
                                                       </td>
                                                       <td
                                                           class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                           <span
                                                               class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                                               {{ number_format($value['biaya']) }}

                                                           </span>
                                                       </td>
                                                       <td
                                                           class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                           <span
                                                               class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                                               {{ \App\Models\Technician::getTechnicalName($value['technical_id']) ?? '-' }}
                                                           </span>
                                                       </td>
                                                       <td
                                                           class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                           <span
                                                               class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                                               {{ \App\Models\Product::getProductName($value['product_id']) ?? '-' }}
                                                           </span>
                                                       </td>
                                                   </tr>
                                               @endforeach
                                           @endforeach
                                       </tbody>
                                   </table>
                               </div>
                           </div>
                       </div>
                   </div>
               </div>
           </div>
       @endif
   </div>



   @push('style')
       <style>
           []::after {
               content: " ";
               box-shadow: 0 0 50px 9px rgba(254, 254, 254);
               position: absolute;
               top: 0;
               left: -100%;
               height: 100%;
               animation: load 1s infinite;
           }

           @keyframes load {
               0% {
                   left: -100%
               }

               100% {
                   left: 150%
               }
           }

           .custom-height {
               height: calc(100vh - 110px);
           }
       </style>
   @endpush

   @push('script')
       <script>
           document.addEventListener('DOMContentLoaded', function() {

               @this.on('triggerDelete', id => {
                   Swal.fire({
                       title: 'Are You Sure?',
                       html: "You won't be able to revert this!",
                       icon: 'warning',
                       showCancelButton: true,
                   }).then((result) => {
                       if (result.value) {
                           @this.call('delete', id)
                       }
                   });
               });
           })
       </script>
   @endpush
