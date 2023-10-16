 <div class="w-full px-6 mx-auto">


     <div class="flex flex-wrap -mx-3">
         <div class="w-full max-w-full px-3 mt-0 lg:w-8/12 lg:flex-none">
             <div class="relative z-20 flex min-w-0 flex-col break-words h-full p-4">
                 <div class="relative flex justify-between items-center grow">
                     <div class=" mb-0 border-b-0 border-solid ">
                         <h5 class="mb-1 font-serif">i service jember </h5>
                         <p class="mb-0 text-sm leading-normal dark:text-white dark:opacity-60 font-serif">
                             {{ \Carbon\Carbon::now()->format('l, d M Y') }}
                         </p>
                     </div>
                     <div class="flex items-center md:ml-auto md:pr-4">
                         <div class="relative flex flex-wrap items-stretch w-full transition-all rounded-lg ease">
                             <span
                                 class="text-sm ease leading-5.6 absolute z-50 -ml-px flex h-full items-center whitespace-nowrap rounded-lg rounded-tr-none rounded-br-none border border-r-0 border-transparent bg-transparent py-2 px-2.5 text-center font-normal text-slate-500 transition-all">
                                 <i class="fas fa-search"></i>
                             </span>
                             <input type="text"
                                 class="pl-9 text-sm focus:shadow-primary-outline ease w-1/100 leading-5.6 relative -ml-px block min-w-0 flex-auto rounded-lg border border-solid border-gray-300 dark:bg-slate-850 dark:text-white bg-white bg-clip-padding py-2 pr-3 text-gray-700 transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none focus:transition-shadow"
                                 placeholder="Type here..." />
                         </div>
                     </div>
                 </div>

                 <div class="w-full max-w-full mt-8  sm:mr-0 md:flex-none">
                     <ul class="relative flex flex-wrap p-1 list-none bg-gray-50 rounded-xl" nav-pills role="tablist">
                         <li class="z-30  text-center px-8">
                             <a class="z-30 flex items-center justify-center w-full px-0 py-1 mb-0 transition-all ease-in-out border-0 rounded-lg bg-inherit text-slate-700"
                                 nav-link active href="javascript:;" role="tab" aria-selected="true">
                                 <i class="ni ni-app"></i>
                                 <span class="ml-2">App</span>
                             </a>
                         </li>
                         <li class="z-30  text-center px-8">
                             <a class="z-30 flex items-center justify-center w-full px-0 py-1 mb-0 transition-all ease-in-out border-0 rounded-lg bg-inherit text-slate-700"
                                 nav-link href="javascript:;" role="tab" aria-selected="false">
                                 <i class="ni ni-email-83"></i>
                                 <span class="ml-2">Messages</span>
                             </a>
                         </li>
                         <li class="z-30  text-center px-8">
                             <a class="z-30 flex items-center justify-center w-full px-0 py-1 mb-0 transition-colors ease-in-out border-0 rounded-lg bg-inherit text-slate-700"
                                 nav-link href="javascript:;" role="tab" aria-selected="false">
                                 <i class="ni ni-settings-gear-65"></i>
                                 <span class="ml-2">Settings</span>
                             </a>
                         </li>
                     </ul>

                     <div class="mt-6 grid grid-cols-1 md:grid-cols-3">
                         <div
                             class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border ">
                             <div class="flex-auto p-6 pt-0">

                                 <div class="mt-6 text-left">
                                     <h4 class="dark:text-white ">
                                         Iphone 14
                                     </h4>
                                     <div class="mb-2 leading-relaxed text-base dark:text-white/80 text-slate-700">
                                         Screen Repair
                                     </div>

                                 </div>
                             </div>
                             <div class="flex flex-wrap justify-center -mx-3">
                                 <div class="w-10/12 max-w-full px-3 flex-0 ">
                                     <div class="mb-6 lg:mb-0">
                                         <a href="javascript:;">
                                             <img class="h-auto max-w-full w-full border-2 border-white border-solid rounded-circle"
                                                 src="../assets/img/team-3.jpg" alt="product image">
                                         </a>
                                     </div>
                                 </div>
                             </div>

                             <div class="flex-auto p-6 pt-0">

                                 <div class="mt-8 text-center">
                                     <button
                                         class="inline-block px-5 py-2.5 font-bold leading-normal text-center text-white align-middle transition-all bg-transparent rounded-lg cursor-pointer text-sm ease-in shadow-md bg-150 bg-gradient-to-tl from-zinc-800 to-zinc-700 dark:bg-gradient-to-tl dark:from-slate-750 dark:to-gray-850 hover:shadow-xs active:opacity-85 hover:-translate-y-px tracking-tight-rem bg-x-25">
                                         Rp {{ number_format(100000) }}
                                         &nbsp;&nbsp;
                                         <i class="fas fa-plus"> </i>
                                     </button>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
         <div class="w-full max-w-full px-3 mt-0 lg:w-4/12 lg:flex-none">
             <div
                 class="border-black/12.5 dark:bg-slate-850 dark:shadow-dark-xl shadow-xl relative z-20 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid bg-white bg-clip-border h-full">
                 <div class="border-black/12.5 mb-0 rounded-t-2xl border-b-0 border-solid p-6 pt-4 pb-0">
                     <h6 class="capitalize dark:text-white">Sales overview</h6>
                     <p class="mb-0 text-sm leading-normal dark:text-white dark:opacity-60">
                         <i class="fa fa-arrow-up text-emerald-500"></i>
                         <span class="font-semibold">4% more</span> in 2021
                     </p>
                 </div>
             </div>
         </div>


     </div>

 </div>
