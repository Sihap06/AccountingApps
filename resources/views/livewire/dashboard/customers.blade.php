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
                       @if ($isOpen)
                           <div class="fixed z-10 inset-0 overflow-y-auto">
                               <div
                                   class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                   <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                                       <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                                   </div>

                                   <span class="hidden sm:inline-block sm:align-middle sm:h-screen"
                                       aria-hidden="true">&#8203;</span>

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
                                                           <input type="text" wire:model="name" id="name"
                                                               class="input input-md h-11 focus:ring-indigo-600 focus-within:ring-indigo-600 focus-within:border-indigo-600 focus:border-indigo-600">
                                                       </div>
                                                       @error('name')
                                                           <span class="text-red-500 text-xs">{{ $message }}</span>
                                                       @enderror
                                                   </div>
                                                   <div class="mt-4">
                                                       <label for="no_telp"
                                                           class="block text-sm font-medium text-gray-700">No
                                                           Telefon</label>
                                                       <div class="mt-1 relative rounded-md">
                                                           <input type="text" wire:model="no_telp" id="no_telp"
                                                               class="input input-md h-11 focus:ring-indigo-600 focus-within:ring-indigo-600 focus-within:border-indigo-600 focus:border-indigo-600">
                                                       </div>
                                                       @error('no_telp')
                                                           <span class="text-red-500 text-xs">{{ $message }}</span>
                                                       @enderror
                                                   </div>
                                                   <div class="mt-4">
                                                       <label for="alamat"
                                                           class="block text-sm font-medium text-gray-700">Address
                                                           (optional)</label>
                                                       <div class="mt-1 relative rounded-md">
                                                           <input type="text" wire:model="alamat" id="alamat"
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
                                           <button wire:click="closeModal" wire:loading.attr="disabled"
                                               wire:target="closeModal"
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
                               <div
                                   class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                   <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                                       <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                                   </div>

                                   <span class="hidden sm:inline-block sm:align-middle sm:h-screen"
                                       aria-hidden="true">&#8203;</span>

                                   <div
                                       class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-70/100 sm:w-full sm:p-6">
                                       <div class="flex flex-row justify-between items-center">
                                           <h3 class="text-lg leading-6 font-medium text-gray-900 mb-0"
                                               id="modal-title">
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
                                                       <svg fill="#FFFFFF" height="15px" width="15px"
                                                           version="1.1" id="Layer_1"
                                                           xmlns="http://www.w3.org/2000/svg"
                                                           xmlns:xlink="http://www.w3.org/1999/xlink"
                                                           viewBox="0 0 308 308" xml:space="preserve">
                                                           <g id="XMLID_468_">
                                                               <path id="XMLID_469_"
                                                                   d="M227.904,176.981c-0.6-0.288-23.054-11.345-27.044-12.781c-1.629-0.585-3.374-1.156-5.23-1.156 c-3.032,0-5.579,1.511-7.563,4.479c-2.243,3.334-9.033,11.271-11.131,13.642c-0.274,0.313-0.648,0.687-0.872,0.687 c-0.201,0-3.676-1.431-4.728-1.888c-24.087-10.463-42.37-35.624-44.877-39.867c-0.358-0.61-0.373-0.887-0.376-0.887 c0.088-0.323,0.898-1.135,1.316-1.554c1.223-1.21,2.548-2.805,3.83-4.348c0.607-0.731,1.215-1.463,1.812-2.153 c1.86-2.164,2.688-3.844,3.648-5.79l0.503-1.011c2.344-4.657,0.342-8.587-0.305-9.856c-0.531-1.062-10.012-23.944-11.02-26.348 c-2.424-5.801-5.627-8.502-10.078-8.502c-0.413,0,0,0-1.732,0.073c-2.109,0.089-13.594,1.601-18.672,4.802 c-5.385,3.395-14.495,14.217-14.495,33.249c0,17.129,10.87,33.302,15.537,39.453c0.116,0.155,0.329,0.47,0.638,0.922 c17.873,26.102,40.154,45.446,62.741,54.469c21.745,8.686,32.042,9.69,37.896,9.69c0.001,0,0.001,0,0.001,0 c2.46,0,4.429-0.193,6.166-0.364l1.102-0.105c7.512-0.666,24.02-9.22,27.775-19.655c2.958-8.219,3.738-17.199,1.77-20.458 C233.168,179.508,230.845,178.393,227.904,176.981z" />
                                                               <path id="XMLID_470_"
                                                                   d="M156.734,0C73.318,0,5.454,67.354,5.454,150.143c0,26.777,7.166,52.988,20.741,75.928L0.212,302.716 c-0.484,1.429-0.124,3.009,0.933,4.085C1.908,307.58,2.943,308,4,308c0.405,0,0.813-0.061,1.211-0.188l79.92-25.396 c21.87,11.685,46.588,17.853,71.604,17.853C240.143,300.27,308,232.923,308,150.143C308,67.354,240.143,0,156.734,0z M156.734,268.994c-23.539,0-46.338-6.797-65.936-19.657c-0.659-0.433-1.424-0.655-2.194-0.655c-0.407,0-0.815,0.062-1.212,0.188 l-40.035,12.726l12.924-38.129c0.418-1.234,0.209-2.595-0.561-3.647c-14.924-20.392-22.813-44.485-22.813-69.677 c0-65.543,53.754-118.867,119.826-118.867c66.064,0,119.812,53.324,119.812,118.867 C276.546,215.678,222.799,268.994,156.734,268.994z" />
                                                           </g>
                                                       </svg>
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
                       </div>
                   </div>
               </div>
           </div>
       </div>
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
