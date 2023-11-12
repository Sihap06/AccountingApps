   <div class="w-full px-6 py-4 mx-auto flex flex-col h-screen">

       <div class="flex justify-between items-center ">
           <div class=" mb-0 border-b-0 border-solid ">
               <h5 class="mb-1 font-serif">Inventory</h5>
               <p class="mb-0 text-sm leading-normal dark:text-white dark:opacity-60 font-serif">
                   {{ \Carbon\Carbon::now()->format('l, d M Y') }}
               </p>
           </div>
           <div class="flex items-center md:ml-auto md:pr-4">

           </div>
       </div>


       <div class="flex flex-wrap -mx-3 mt-6">
           <div class="flex-none w-full max-w-full px-3">
               <div
                   class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
                   <div
                       class="block md:flex w-full justify-between items-center p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                       <div class="pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                           <h6 class="dark:text-white">Inventory Table</h6>
                       </div>
                       <a href="{{ route('dashboard.inventory.create') }}"
                           class="px-8 py-2 mb-4 text-xs font-bold leading-normal text-center text-white capitalize transition-all ease-in rounded-lg shadow-md bg-slate-700 bg-150 hover:shadow-xs hover:-translate-y-px">Tambah</a>
                   </div>

                   <div class="flex justify-end px-6 my-4">
                       <div class="flex w-full md:w-4/12 items-center gap-x-3">
                           <input type="text" wire:model.debounce.500ms="searchTerm"
                               class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none"
                               placeholder="Masukkan nama atau kode" />
                       </div>
                   </div>

                   <div class="flex-auto px-0 pt-0 pb-4">
                       <div class="p-2 overflow-x-auto">
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
                                           Kode</th>
                                       <th
                                           class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                           Harga</th>
                                       <th
                                           class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                           Stok</th>
                                       <th
                                           class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                       </th>
                                   </tr>
                               </thead>
                               <tbody>
                                   @foreach ($data as $index => $item)
                                       <tr wire:key='{{ $index }}' wire:loading.remove
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
                                                   {{ $item->kode }}
                                               </span>
                                           </td>
                                           <td
                                               class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                               <span
                                                   class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                                   Rp {{ number_format($item->harga) }}
                                               </span>
                                           </td>
                                           <td
                                               class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                               <span
                                                   class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">{{ $item->stok }}</span>
                                           </td>
                                           <td
                                               class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                               <div>
                                                   <a href="{{ route('dashboard.inventory.edit', $item->id) }}"
                                                       class="inline-block px-3 py-2 text-xs mr-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-primary leading-normal  ease-in tracking-tight-rem shadow-md bg-150 bg-x-25 hover:-translate-y-px active:opacity-85 hover:shadow-md">
                                                       <i class="fas fa-edit"></i>
                                                   </a>
                                                   <button type="button"
                                                       wire:click="$emit('triggerDelete',{{ $item->id }})"
                                                       class="inline-block px-3 py-2 text-xs mr-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-red-600 leading-normal  ease-in tracking-tight-rem shadow-md bg-150 bg-x-25 hover:-translate-y-px active:opacity-85 hover:shadow-md">
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

                                               </div>
                                           </td>
                                       </tr>
                                   @endforeach

                                   @for ($i = 0; $i <= 10; $i++)
                                       <tr wire:loading.class="table-row" class="hidden"
                                           wire:loading.class.remove="hidden"
                                           wire:target='gotoPage, previousPage, nextPage, searchTerm'>
                                           <td
                                               class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                               <div
                                                   class="mb-2 h-5 w-full rounded overflow-hidden relative bg-gray-200" />
                                           </td>
                                           <td
                                               class="p-2 text-left align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                               <div
                                                   class="mb-2 h-5 w-full rounded overflow-hidden relative bg-gray-200" />
                                           </td>
                                           <td
                                               class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                               <div
                                                   class="mb-2 h-5 w-full rounded overflow-hidden relative bg-gray-200" />
                                           </td>
                                           <td
                                               class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                               <div
                                                   class="mb-2 h-5 w-full rounded overflow-hidden relative bg-gray-200" />
                                           </td>
                                           <td
                                               class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                               <div
                                                   class="mb-2 h-5 w-full rounded overflow-hidden relative bg-gray-200" />
                                           </td>
                                           <td
                                               class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                               <div
                                                   class="mb-2 h-5 w-full rounded overflow-hidden relative bg-gray-200" />
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
