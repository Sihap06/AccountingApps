   <div class="w-full px-6 py-4 mx-auto flex flex-col h-screen max-h-screen">

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

       <div class="flex flex-wrap -mx-3 mt-8">
           <!-- card1 -->
           <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-4/12">
               <div
                   class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
                   <div class="flex-auto p-4">
                       <div class="flex flex-row -mx-3">
                           <div class="flex-none w-2/3 max-w-full px-3">
                               <div>
                                   <p
                                       class="mb-8 font-sans text-sm font-semibold leading-normal uppercase dark:text-white dark:opacity-60">
                                       Today's Transaction</p>
                                   <h5 class="mb-0 font-bold dark:text-white">
                                       {{ number_format($todayTransaction) }}
                                   </h5>
                               </div>
                           </div>
                           <div class="px-3 text-right basis-1/3">
                               <div
                                   class="inline-block w-12 h-12 text-center rounded-circle bg-gradient-to-tl from-blue-500 to-violet-500">
                                   <i class="ni leading-none ni-money-coins text-lg relative top-3.5 text-white"></i>
                               </div>
                           </div>
                       </div>
                   </div>
               </div>
           </div>

           <!-- card2 -->
           <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-4/12">
               <div
                   class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
                   <div class="flex-auto p-4">
                       <div class="flex flex-row -mx-3">
                           <div class="flex-none w-2/3 max-w-full px-3">
                               <div>
                                   <p
                                       class="mb-8 font-sans text-sm font-semibold leading-normal uppercase dark:text-white dark:opacity-60">
                                       Today's Omset</p>
                                   <h5 class="mb-0 font-bold dark:text-white">
                                       Rp {{ number_format($todayIncome) }}
                                   </h5>
                               </div>
                           </div>
                           <div class="px-3 text-right basis-1/3">
                               <div
                                   class="inline-block w-12 h-12 text-center rounded-circle bg-gradient-to-tl from-red-600 to-orange-600">
                                   <i class="ni leading-none ni-world text-lg relative top-3.5 text-white"></i>
                               </div>
                           </div>
                       </div>
                   </div>
               </div>
           </div>

           <!-- card3 -->
           <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-4/12">
               <div
                   class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
                   <div class="flex-auto p-4">
                       <div class="flex flex-row -mx-3">
                           <div class="flex-none w-2/3 max-w-full px-3">
                               <div>
                                   <p
                                       class="mb-8 font-sans text-sm font-semibold leading-normal uppercase dark:text-white dark:opacity-60">
                                       Today's Expenditure</p>
                                   <h5 class="mb-0 font-bold dark:text-white">
                                       Rp {{ number_format($todayExpenditure) }}
                                   </h5>
                               </div>
                           </div>
                           <div class="px-3 text-right basis-1/3">
                               <div
                                   class="inline-block w-12 h-12 text-center rounded-circle bg-gradient-to-tl from-emerald-500 to-teal-400">
                                   <i class="ni leading-none ni-paper-diploma text-lg relative top-3.5 text-white"></i>
                               </div>
                           </div>
                       </div>
                   </div>
               </div>
           </div>

       </div>

       <!-- cards row 2 -->
       <div class="flex flex-grow mt-8 -mx-3">
           <div class="w-full max-w-full px-3 mt-0 lg:w-7/12 lg:flex-none">
               <div
                   class="border-black/12.5 dark:bg-slate-850 dark:shadow-dark-xl shadow-xl relative z-20 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid bg-white bg-clip-border h-full">
                   <div class="border-black/12.5 mb-0 rounded-t-2xl border-b-0 border-solid p-6 pt-4 pb-0">
                       <h6 class="capitalize dark:text-white">Transactions Chart</h6>
                   </div>
                   <div class="flex-auto p-4">
                       <div>
                           <canvas id="transaction-chart" height="400"></canvas>
                       </div>
                   </div>
               </div>
           </div>

           <div class="w-full max-w-full px-3 lg:w-5/12 lg:flex-none ">
               <div
                   class="border-black/12.5 shadow-xl dark:bg-slate-850 dark:shadow-dark-xl relative flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid bg-white bg-clip-border h-full">
                   <div class="p-4 pb-0 rounded-t-4">
                       <h6 class="mb-0 dark:text-white">Latest Transactions</h6>
                   </div>
                   <div class="flex-auto p-4">
                       <ul class="flex flex-col pl-0 mb-0 rounded-lg">
                           @foreach ($dataTransaction as $item)
                               <li
                                   class="relative flex justify-between py-2 pr-4 mb-2 border-0 rounded-t-lg rounded-xl text-inherit">
                                   <div class="flex items-center">
                                       <div
                                           class="inline-block w-8 h-8 mr-4 text-center text-black bg-center shadow-sm fill-current stroke-none bg-gradient-to-tl from-zinc-800 to-zinc-700 dark:bg-gradient-to-tl dark:from-slate-750 dark:to-gray-850 rounded-xl">
                                           <i class="text-white fas fa-hashtag relative top-0.75 text-xxs"></i>
                                       </div>
                                       <div class="flex flex-col">
                                           <h6
                                               class="mb-1 text-sm leading-normal text-slate-700 dark:text-white capitalize">
                                               {{ $item->service }}
                                           </h6>
                                           <span class="text-xs leading-tight dark:text-white/80">Rp
                                               {{ number_format($item->biaya) }}</span>
                                       </div>
                                   </div>
                                   <div class="flex">
                                       <button
                                           class="group ease-in leading-pro text-xs rounded-3.5xl p-1.2 h-6.5 w-6.5 mx-0 my-auto inline-block cursor-pointer border-0 bg-transparent text-center align-middle font-bold text-slate-700 shadow-none transition-all dark:text-white"><i
                                               class="ni ease-bounce text-2xs group-hover:translate-x-1.25 ni-bold-right transition-all duration-200"
                                               aria-hidden="true"></i></button>
                                   </div>
                               </li>
                           @endforeach

                       </ul>
                   </div>
               </div>
           </div>
       </div>


   </div>

   @push('script')
       <script src="{{ asset('assets/js/plugins/chartjs.min.js') }}"></script>

       <script>
           var data = @json($dataChart);
           var label = @json($labelChart);

           var ctx1 = document.getElementById("transaction-chart").getContext("2d");

           var gradientStroke1 = ctx1.createLinearGradient(0, 230, 0, 50);

           gradientStroke1.addColorStop(1, 'rgba(94, 114, 228, 0.2)');
           gradientStroke1.addColorStop(0.2, 'rgba(94, 114, 228, 0.0)');
           gradientStroke1.addColorStop(0, 'rgba(94, 114, 228, 0)');
           new Chart(ctx1, {
               type: "line",
               data: {
                   labels: label,
                   datasets: [{
                       label: "Transactions",
                       tension: 0.4,
                       borderWidth: 0,
                       pointRadius: 0,
                       borderColor: "#5e72e4",
                       backgroundColor: gradientStroke1,
                       borderWidth: 3,
                       fill: true,
                       data: data,
                       maxBarThickness: 6

                   }],
               },
               options: {
                   responsive: true,
                   maintainAspectRatio: false,
                   plugins: {
                       legend: {
                           display: false,
                       }
                   },
                   interaction: {
                       intersect: false,
                       mode: 'index',
                   },
                   scales: {
                       y: {
                           grid: {
                               drawBorder: false,
                               display: true,
                               drawOnChartArea: true,
                               drawTicks: false,
                               borderDash: [5, 5]
                           },
                           ticks: {
                               display: true,
                               padding: 10,
                               color: '#fbfbfb',
                               font: {
                                   size: 11,
                                   family: "Open Sans",
                                   style: 'normal',
                                   lineHeight: 2
                               },
                           }
                       },
                       x: {
                           grid: {
                               drawBorder: false,
                               display: false,
                               drawOnChartArea: false,
                               drawTicks: false,
                               borderDash: [5, 5]
                           },
                           ticks: {
                               display: true,
                               color: '#ccc',
                               padding: 20,
                               font: {
                                   size: 11,
                                   family: "Open Sans",
                                   style: 'normal',
                                   lineHeight: 2
                               },
                           }
                       },
                   },
               },
           });
       </script>
   @endpush
