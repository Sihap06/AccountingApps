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
                           class="px-8 py-2 mb-4 text-xs font-bold leading-normal text-center text-white capitalize transition-all ease-in rounded-lg shadow-md bg-slate-700 bg-150 hover:shadow-xs hover:-translate-y-px"
                           data-te-ripple-color="light">Tambah</a>
                   </div>

                   <div class="flex-auto px-0 pt-0 pb-4">
                       <div class="p-2 overflow-x-auto">
                           <table
                               class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
                               <thead class="align-bottom">
                                   <tr>
                                       <th
                                           class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
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
                                   @foreach ($data as $item)
                                       <tr>
                                           <td
                                               class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                               <div class="flex px-2 py-1">
                                                   <span
                                                       class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                                       {{ $item->name }}
                                                   </span>
                                               </div>
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
                                               <a href="{{ route('dashboard.inventory.edit', $item->id) }}"
                                                   class="inline-block px-3 py-2 text-xs mr-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-primary leading-normal  ease-in tracking-tight-rem shadow-md bg-150 bg-x-25 hover:-translate-y-px active:opacity-85 hover:shadow-md">
                                                   <i class="fas fa-edit"></i>
                                               </a>
                                               <button type="button" onclick="deleteConfirm()"
                                                   class="inline-block px-3 py-2 text-xs mr-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-red-600 leading-normal  ease-in tracking-tight-rem shadow-md bg-150 bg-x-25 hover:-translate-y-px active:opacity-85 hover:shadow-md">
                                                   <i class="fas fa-trash-alt"></i>
                                               </button>
                                           </td>
                                       </tr>
                                   @endforeach

                               </tbody>
                           </table>
                           <div class="mt-4 px-4">
                               {{ $data->links() }}
                           </div>
                       </div>
                   </div>
               </div>
           </div>
       </div>

   </div>
