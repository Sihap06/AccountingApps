 <div
     class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border h-full w-full">
     <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
         <h6 class="dark:text-white">Transaction Table</h6>
     </div>
     <div class="flex-auto p-6">
         <div class="p-0 overflow-x-auto">
             <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
                 <thead class="align-bottom">
                     <tr>
                         <th
                             class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                             Tanggal
                         </th>
                         <th
                             class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                             Order ID
                         </th>
                         <th
                             class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                             Service
                         </th>
                         <th
                             class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                             Biaya
                         </th>
                         <th
                             class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                             Modal
                         </th>
                         <th
                             class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                             Metode Pembayaran
                         </th>
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
                                 <span
                                     class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                     {{ \Carbon\Carbon::parse($item->created_at)->format('d/m/y') }}
                                 </span>
                             </td>
                             <td
                                 class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                 <span
                                     class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                     {{ $item->order_transaction }}
                                 </span>
                             </td>
                             <td
                                 class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                 <span
                                     class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                     {{ $item->service }}
                                 </span>
                             </td>
                             <td
                                 class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                 <span
                                     class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                     Rp {{ number_format($item->biaya) }}
                                 </span>
                             </td>
                             <td
                                 class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                 <span
                                     class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                     Rp {{ number_format($item->modal) }}
                                 </span>
                             </td>
                             <td
                                 class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                 <span
                                     class="text-xs font-semibold uppercase leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                     {{ $item->payment_method }}
                                 </span>
                             </td>
                             <td
                                 class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                 <div>
                                     <a href="{{ route('dashboard.inventory.edit', $item->id) }}"
                                         class="inline-block px-3 py-2 text-xs mr-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-primary leading-normal  ease-in tracking-tight-rem shadow-md bg-150 bg-x-25 hover:-translate-y-px active:opacity-85 hover:shadow-md">
                                         <i class="fas fa-edit"></i>
                                     </a>
                                     <button type="button" wire:click="$emit('triggerDelete',{{ $item->id }})"
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

                 </tbody>
             </table>
             <div class="mt-4 px-4">
                 {{ $data->links() }}
             </div>
         </div>
     </div>
 </div>
