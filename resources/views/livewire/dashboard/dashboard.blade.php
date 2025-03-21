   <div class="w-full px-6 py-4 mx-auto flex flex-col">

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
           <div class="w-full max-w-full px-3 mb-6 sm:w-full sm:flex-none xl:mb-0 xl:w-4/12">
               <div
                   class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
                   <div class="flex-auto p-4">
                       <div class="flex flex-row -mx-3">
                           <div class="flex-none w-2/3 max-w-full px-3">
                               <a href="{{ url('dashboard/reporting/transaction') }}">
                                   <p
                                       class="mb-8 font-sans text-sm font-semibold leading-normal uppercase dark:text-white dark:opacity-60">
                                       Today's Transaction</p>
                                   <h5 class="mb-0 font-bold dark:text-white">
                                       {{ number_format($todayTransaction) }}
                                   </h5>
                               </a>
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
           <div class="w-full max-w-full px-3 mb-6 sm:w-full sm:flex-none xl:mb-0 xl:w-4/12">
               <div
                   class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
                   <div class="flex-auto p-4">
                       <div class="flex flex-row -mx-3">
                           <div class="flex-none w-2/3 max-w-full px-3">
                               <a href="{{ url('dashboard/reporting/income') }}">
                                   <p
                                       class="mb-8 font-sans text-sm font-semibold leading-normal uppercase dark:text-white dark:opacity-60">
                                       Today's Omset</p>
                                   <h5 class="mb-0 font-bold dark:text-white">
                                       Rp {{ number_format($todayIncome) }}
                                   </h5>
                               </a>
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
           <div class="w-full max-w-full px-3 mb-6 sm:w-full sm:flex-none xl:mb-0 xl:w-4/12">
               <div
                   class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
                   <div class="flex-auto p-4">
                       <div class="flex flex-row -mx-3">
                           <div class="flex-none w-2/3 max-w-full px-3">
                               <a href="{{ url('dashboard/reporting/expenditure') }}">
                                   <p
                                       class="mb-8 font-sans text-sm font-semibold leading-normal uppercase dark:text-white dark:opacity-60">
                                       Today's Expenditure</p>
                                   <h5 class="mb-0 font-bold dark:text-white">
                                       Rp {{ number_format($todayExpenditure) }}
                                   </h5>
                               </a>
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
                   class="border-black/12.5 dark:bg-slate-850 dark:shadow-dark-xl shadow-xl relative z-20 break-words rounded-2xl border-0 border-solid bg-white bg-clip-border h-full">
                   <div
                       class="flex items-center justify-between border-black/12.5 mb-0 rounded-t-2xl border-b-0 border-solid p-6 pt-4 pb-0">
                       <h6 class="capitalize dark:text-white">Transactions Chart</h6>
                       <div class="flex gap-x-3 w-full md:w-3/12 items-center">
                           <select wire:model="selectedYear"
                               class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none">
                               @foreach ($years as $year)
                                   <option value="{{ $year }}">{{ $year }}</option>
                               @endforeach
                           </select>

                           <button wire:click='updateChart()'
                               class="inline-block px-3 py-2 text-xs mr-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-primary leading-normal  ease-in tracking-tight-rem shadow-md bg-150 bg-x-25 hover:-translate-y-px active:opacity-85 hover:shadow-md">
                               Update
                           </button>
                       </div>
                   </div>
                   <div class="flex text-center items-center justify-center">
                       <div wire:loading wire:target='updateChart'>
                           <div class="inline-block h-6 w-6 animate-spin rounded-full border-2 border-solid border-current border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]"
                               role="status">
                               <span
                                   class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]">Loading...</span>
                           </div>
                       </div>
                   </div>
                   <div wire:loading.remove wire:target='updateChart'>
                       <div>
                           <canvas id="transaction-chart" height="400" width="400"></canvas>
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
                                       <a href="{{ url('dashboard/detail-transaction', $item->id) }}"
                                           class="group ease-in leading-pro text-xs rounded-3.5xl p-1.2 h-6.5 w-6.5 mx-0 my-auto inline-block cursor-pointer border-0 bg-transparent text-center align-middle font-bold text-slate-700 shadow-none transition-all dark:text-white"><i
                                               class="ni ease-bounce text-2xs group-hover:translate-x-1.25 ni-bold-right transition-all duration-200"
                                               aria-hidden="true"></i>
                                       </a>
                                   </div>
                               </li>
                           @endforeach

                       </ul>
                   </div>
               </div>
           </div>
       </div>

       <div class="flex flex-wrap -mx-3 mt-8">
           <!-- card1 -->
           <div class="w-full max-w-full px-3 mb-6 sm:w-full sm:flex-none xl:mb-0 xl:w-4/12">
               <div
                   class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
                   <div class="flex-auto p-4">
                       <div class="flex flex-row -mx-3">
                           <div class="flex-none w-2/3 max-w-full px-3">
                               <div>
                                   <p
                                       class="mb-8 font-sans text-sm font-semibold leading-normal uppercase dark:text-white dark:opacity-60">
                                       Transaction Done</p>
                                   <h4 class="mb-4 font-bold text-neutral-700 dark:text-white">
                                       {{ number_format($transactionsMonthly[0]->total_transaksi_done) }}
                                   </h4>
                               </div>
                           </div>
                           <div class="px-3 text-right basis-1/3">
                               <div
                                   class="inline-block w-12 h-12 text-center rounded-circle bg-gradient-to-tl from-emerald-500 to-teal-400">
                                   <i class="ni leading-none ni-check-bold text-lg relative top-3.5 text-white"></i>
                               </div>
                           </div>
                       </div>
                   </div>
               </div>
           </div>

           <!-- card2 -->
           <div class="w-full max-w-full px-3 mb-6 sm:w-full sm:flex-none xl:mb-0 xl:w-4/12">
               <div
                   class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
                   <div class="flex-auto p-4">
                       <div class="flex flex-row -mx-3">
                           <div class="flex-none w-2/3 max-w-full px-3">
                               <div>
                                   <p
                                       class="mb-8 font-sans text-sm font-semibold leading-normal uppercase dark:text-white dark:opacity-60">
                                       Transaction Cancel</p>
                                   <h4 class="mb-4 font-bold text-neutral-700 dark:text-white">
                                       {{ number_format($transactionsMonthly[0]->total_transaksi_cancel) }}
                                   </h4>
                               </div>
                           </div>
                           <div class="px-3 text-right basis-1/3">
                               <div
                                   class="inline-block w-12 h-12 text-center rounded-circle bg-gradient-to-tl from-red-600 to-orange-600">
                                   <i class="ni leading-none ni-fat-remove text-lg relative top-3.5 text-white"></i>
                               </div>
                           </div>
                       </div>
                   </div>
               </div>
           </div>
           <div class="w-full max-w-full px-3 mb-6 sm:w-full sm:flex-none xl:mb-0 xl:w-4/12">
               <div
                   class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
                   <div class="flex-auto p-4">
                       <div class="flex flex-row -mx-3">
                           <div class="flex-none w-2/3 max-w-full px-3">
                               <div>
                                   <p
                                       class="mb-8 font-sans text-sm font-semibold leading-normal uppercase dark:text-white dark:opacity-60">
                                       Transaction Process</p>
                                   <h4 class="mb-4 font-bold text-neutral-700 dark:text-white">
                                       {{ number_format($transactionsMonthly[0]->total_transaksi_proses) }}
                                   </h4>
                               </div>
                           </div>
                           <div class="px-3 text-right basis-1/3">
                               <div
                                   class="inline-block w-12 h-12 text-center rounded-circle bg-gradient-to-tl from-cyan-600 to-sky-600">
                                   <i class="ni leading-none ni-folder-17 text-lg relative top-3.5 text-white"></i>
                               </div>
                           </div>
                       </div>
                   </div>
               </div>
           </div>


       </div>
       <div class="mt-8">
           <div class="flex flex-col gap-4">
               @livewire('dashboard.transaction-process', ['is_dashboard' => true])
               @livewire('dashboard.transaction-complaint', ['is_dashboard' => true])

           </div>
       </div>


   </div>

   @push('script')
       <script src="{{ asset('assets/js/plugins/chartjs.min.js') }}"></script>

       <script>
           let transactionChart; // Declare chart instance globally

           document.addEventListener('DOMContentLoaded', () => {
               const data = @json($dataChart);
               const label = @json($labelChart);

               const createChart = (labels, dataset) => {
                   const context = document.getElementById("transaction-chart").getContext("2d");

                   const gradient = context.createLinearGradient(0, 230, 0, 50);
                   gradient.addColorStop(1, 'rgba(94, 114, 228, 0.2)');
                   gradient.addColorStop(0.2, 'rgba(94, 114, 228, 0.0)');
                   gradient.addColorStop(0, 'rgba(94, 114, 228, 0)');

                   return new Chart(context, {
                       type: "line",
                       data: {
                           labels: labels,
                           datasets: [{
                               label: "Transactions",
                               tension: 0.4,
                               borderWidth: 0,
                               pointRadius: 0,
                               borderColor: "#5e72e4",
                               backgroundColor: gradient,
                               borderWidth: 3,
                               fill: true,
                               data: dataset,
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
               };

               // Initialize the chart
               transactionChart = createChart(label, data);

               // Listen for Livewire updates
               window.livewire.on('chartUpdate', (dataChart, labelChart) => {
                   if (transactionChart) {
                       // Destroy the old chart instance
                       transactionChart.destroy();
                   }

                   // Recreate the chart with updated data
                   transactionChart = createChart(labelChart, dataChart);
               });
           });
       </script>
   @endpush
