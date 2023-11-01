   <div class="w-full px-6 py-4 mx-auto flex flex-col h-screen">

       <div class="flex justify-between items-center ">
           <div class=" mb-0 border-b-0 border-solid ">
               <h5 class="mb-1 font-serif">Reporting</h5>
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
                   <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                       <h6 class="dark:text-white">Reporting Table</h6>
                   </div>
                   <div class="flex-auto px-0 pt-0 pb-2">
                       <div class="p-0 overflow-x-auto">
                           <table
                               class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
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
                                           class="px-6 py-3 font-semibold capitalize align-middle bg-transparent border-b border-collapse border-solid shadow-none dark:border-white/40 dark:text-white tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                       </th>
                                   </tr>
                               </thead>
                               <tbody>
                                   <tr>
                                       <td
                                           class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                           <span
                                               class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">23/04/18</span>
                                       </td>
                                       <td
                                           class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                           <span
                                               class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">#00001</span>
                                       </td>
                                       <td
                                           class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                           <span
                                               class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">LCD
                                               Iphone 14</span>
                                       </td>
                                       <td
                                           class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                           <span
                                               class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">Rp
                                               1,000,000</span>
                                       </td>
                                       <td
                                           class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                           <span
                                               class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">Rp
                                               500,000</span>
                                       </td>
                                       <td
                                           class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                           <span
                                               class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">BCA</span>
                                       </td>
                                       <td
                                           class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                           <a href="javascript:;"
                                               class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                               Edit </a>
                                       </td>
                                   </tr>

                               </tbody>
                           </table>
                       </div>
                   </div>
               </div>
           </div>
       </div>


   </div>
