   <div class="w-full px-6 mx-auto">

       <div class="flex justify-between items-center ">
           <div class=" mb-0 border-b-0 border-solid ">
               <h5 class="mb-1 font-serif">Dashboard Statistik</h5>
               <p class="mb-0 text-sm leading-normal dark:text-white dark:opacity-60 font-serif">
                   {{ \Carbon\Carbon::now()->format('l, d M Y') }}
               </p>
           </div>
           <div class="flex items-center md:ml-auto md:pr-4">

           </div>
       </div>

       <!-- cards row 2 -->
       <div class="flex flex-wrap mt-8 -mx-3">
           <div class="w-full max-w-full px-3 mt-0 lg:w-7/12 lg:flex-none">
               <div
                   class="border-black/12.5 dark:bg-slate-850 dark:shadow-dark-xl shadow-xl relative z-20 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid bg-white bg-clip-border">
                   <div class="border-black/12.5 mb-0 rounded-t-2xl border-b-0 border-solid p-6 pt-4 pb-0">
                       <h6 class="capitalize dark:text-white">Sales overview</h6>
                       <p class="mb-0 text-sm leading-normal dark:text-white dark:opacity-60">
                           <i class="fa fa-arrow-up text-emerald-500"></i>
                           <span class="font-semibold">4% more</span> in 2021
                       </p>
                   </div>
                   <div class="flex-auto p-4">
                       <div>
                           <canvas id="chart-line" height="300"></canvas>
                       </div>
                   </div>
               </div>
           </div>

           <div class="w-full max-w-full px-3 lg:w-5/12 lg:flex-none">
               <div
                   class="border-black/12.5 shadow-xl dark:bg-slate-850 dark:shadow-dark-xl relative flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid bg-white bg-clip-border">
                   <div class="p-4 pb-0 rounded-t-4">
                       <h6 class="mb-0 dark:text-white">Categories</h6>
                   </div>
                   <div class="flex-auto p-4">
                       <ul class="flex flex-col pl-0 mb-0 rounded-lg">
                           <li
                               class="relative flex justify-between py-2 pr-4 mb-2 border-0 rounded-t-lg rounded-xl text-inherit">
                               <div class="flex items-center">
                                   <div
                                       class="inline-block w-8 h-8 mr-4 text-center text-black bg-center shadow-sm fill-current stroke-none bg-gradient-to-tl from-zinc-800 to-zinc-700 dark:bg-gradient-to-tl dark:from-slate-750 dark:to-gray-850 rounded-xl">
                                       <i class="text-white ni ni-mobile-button relative top-0.75 text-xxs"></i>
                                   </div>
                                   <div class="flex flex-col">
                                       <h6 class="mb-1 text-sm leading-normal text-slate-700 dark:text-white">Devices
                                       </h6>
                                       <span class="text-xs leading-tight dark:text-white/80">250 in stock, <span
                                               class="font-semibold">346+ sold</span></span>
                                   </div>
                               </div>
                               <div class="flex">
                                   <button
                                       class="group ease-in leading-pro text-xs rounded-3.5xl p-1.2 h-6.5 w-6.5 mx-0 my-auto inline-block cursor-pointer border-0 bg-transparent text-center align-middle font-bold text-slate-700 shadow-none transition-all dark:text-white"><i
                                           class="ni ease-bounce text-2xs group-hover:translate-x-1.25 ni-bold-right transition-all duration-200"
                                           aria-hidden="true"></i></button>
                               </div>
                           </li>
                           <li class="relative flex justify-between py-2 pr-4 mb-2 border-0 rounded-xl text-inherit">
                               <div class="flex items-center">
                                   <div
                                       class="inline-block w-8 h-8 mr-4 text-center text-black bg-center shadow-sm fill-current stroke-none bg-gradient-to-tl from-zinc-800 to-zinc-700 dark:bg-gradient-to-tl dark:from-slate-750 dark:to-gray-850 rounded-xl">
                                       <i class="text-white ni ni-tag relative top-0.75 text-xxs"></i>
                                   </div>
                                   <div class="flex flex-col">
                                       <h6 class="mb-1 text-sm leading-normal text-slate-700 dark:text-white">Tickets
                                       </h6>
                                       <span class="text-xs leading-tight dark:text-white/80">123 closed, <span
                                               class="font-semibold">15 open</span></span>
                                   </div>
                               </div>
                               <div class="flex">
                                   <button
                                       class="group ease-in leading-pro text-xs rounded-3.5xl p-1.2 h-6.5 w-6.5 mx-0 my-auto inline-block cursor-pointer border-0 bg-transparent text-center align-middle font-bold text-slate-700 shadow-none transition-all dark:text-white"><i
                                           class="ni ease-bounce text-2xs group-hover:translate-x-1.25 ni-bold-right transition-all duration-200"
                                           aria-hidden="true"></i></button>
                               </div>
                           </li>
                           <li
                               class="relative flex justify-between py-2 pr-4 mb-2 border-0 rounded-b-lg rounded-xl text-inherit">
                               <div class="flex items-center">
                                   <div
                                       class="inline-block w-8 h-8 mr-4 text-center text-black bg-center shadow-sm fill-current stroke-none bg-gradient-to-tl from-zinc-800 to-zinc-700 dark:bg-gradient-to-tl dark:from-slate-750 dark:to-gray-850 rounded-xl">
                                       <i class="text-white ni ni-box-2 relative top-0.75 text-xxs"></i>
                                   </div>
                                   <div class="flex flex-col">
                                       <h6 class="mb-1 text-sm leading-normal text-slate-700 dark:text-white">Error logs
                                       </h6>
                                       <span class="text-xs leading-tight dark:text-white/80">1 is active, <span
                                               class="font-semibold">40 closed</span></span>
                                   </div>
                               </div>
                               <div class="flex">
                                   <button
                                       class="group ease-in leading-pro text-xs rounded-3.5xl p-1.2 h-6.5 w-6.5 mx-0 my-auto inline-block cursor-pointer border-0 bg-transparent text-center align-middle font-bold text-slate-700 shadow-none transition-all dark:text-white"><i
                                           class="ni ease-bounce text-2xs group-hover:translate-x-1.25 ni-bold-right transition-all duration-200"
                                           aria-hidden="true"></i></button>
                               </div>
                           </li>
                           <li
                               class="relative flex justify-between py-2 pr-4 border-0 rounded-b-lg rounded-xl text-inherit">
                               <div class="flex items-center">
                                   <div
                                       class="inline-block w-8 h-8 mr-4 text-center text-black bg-center shadow-sm fill-current stroke-none bg-gradient-to-tl from-zinc-800 to-zinc-700 dark:bg-gradient-to-tl dark:from-slate-750 dark:to-gray-850 rounded-xl">
                                       <i class="text-white ni ni-satisfied relative top-0.75 text-xxs"></i>
                                   </div>
                                   <div class="flex flex-col">
                                       <h6 class="mb-1 text-sm leading-normal text-slate-700 dark:text-white">Happy
                                           users</h6>
                                       <span class="text-xs leading-tight dark:text-white/80"><span
                                               class="font-semibold">+ 430 </span></span>
                                   </div>
                               </div>
                               <div class="flex">
                                   <button
                                       class="group ease-in leading-pro text-xs rounded-3.5xl p-1.2 h-6.5 w-6.5 mx-0 my-auto inline-block cursor-pointer border-0 bg-transparent text-center align-middle font-bold text-slate-700 shadow-none transition-all dark:text-white"><i
                                           class="ni ease-bounce text-2xs group-hover:translate-x-1.25 ni-bold-right transition-all duration-200"
                                           aria-hidden="true"></i></button>
                               </div>
                           </li>
                           <li
                               class="relative flex justify-between py-2 pr-4 border-0 rounded-b-lg rounded-xl text-inherit">
                               <div class="flex items-center">
                                   <div
                                       class="inline-block w-8 h-8 mr-4 text-center text-black bg-center shadow-sm fill-current stroke-none bg-gradient-to-tl from-zinc-800 to-zinc-700 dark:bg-gradient-to-tl dark:from-slate-750 dark:to-gray-850 rounded-xl">
                                       <i class="text-white ni ni-satisfied relative top-0.75 text-xxs"></i>
                                   </div>
                                   <div class="flex flex-col">
                                       <h6 class="mb-1 text-sm leading-normal text-slate-700 dark:text-white">Happy
                                           users</h6>
                                       <span class="text-xs leading-tight dark:text-white/80"><span
                                               class="font-semibold">+ 430 </span></span>
                                   </div>
                               </div>
                               <div class="flex">
                                   <button
                                       class="group ease-in leading-pro text-xs rounded-3.5xl p-1.2 h-6.5 w-6.5 mx-0 my-auto inline-block cursor-pointer border-0 bg-transparent text-center align-middle font-bold text-slate-700 shadow-none transition-all dark:text-white"><i
                                           class="ni ease-bounce text-2xs group-hover:translate-x-1.25 ni-bold-right transition-all duration-200"
                                           aria-hidden="true"></i></button>
                               </div>
                           </li>
                       </ul>
                   </div>
               </div>
           </div>
       </div>


   </div>
