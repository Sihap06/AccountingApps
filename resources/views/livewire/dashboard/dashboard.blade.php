   <div class="w-full px-6 py-4 mx-auto flex flex-col">

       @if (auth()->user()->hasPermission('verification') && $showPendingModal)
           <!-- Pending Verification Modal -->
           <div class="fixed inset-0 z-50 overflow-hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
               <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                   <!-- Background overlay -->
                   <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

                   <!-- Modal panel -->
                   <div
                       class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                       <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                           <div class="sm:flex sm:items-start">
                               <div
                                   class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 sm:mx-0 sm:h-10 sm:w-10">
                                   <svg class="h-6 w-6 text-yellow-600" xmlns="http://www.w3.org/2000/svg"
                                       fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                           d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                   </svg>
                               </div>
                               <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                   <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                       Pending Verification
                                   </h3>
                                   <div class="mt-2">
                                       <p class="text-sm text-gray-500">
                                           There are <span class="font-bold text-yellow-600">{{ $pendingCount }}</span>
                                           changes awaiting your verification.
                                       </p>
                                       <p class="text-sm text-gray-500 mt-2">
                                           Please verify the changes to continue system operations.
                                       </p>
                                   </div>
                               </div>
                           </div>
                       </div>
                       <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                           <button wire:click="goToVerification" type="button"
                               class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-yellow-600 text-base font-medium text-white hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 sm:ml-3 sm:w-auto sm:text-sm">
                               Go to Verification Page
                           </button>
                       </div>
                   </div>
               </div>
           </div>
       @endif

       {{-- Verification Result Notification (for the requester) --}}
       @if (count($verificationResults) > 0)
           <div class="mb-6 space-y-3">
               @foreach ($verificationResults as $result)
                   @php
                       $isApproved = $result['status'] === 'approved';
                       $borderColor = $isApproved ? 'border-emerald-500' : 'border-red-500';
                       $iconBg = $isApproved
                           ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-500'
                           : 'bg-red-50 dark:bg-red-900/30 text-red-500';
                       $iconClass = $isApproved ? 'fa-circle-check' : 'fa-circle-xmark';
                       $title = $isApproved ? 'Request Approved' : 'Request Rejected';
                       $statusBadge = $isApproved ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700';
                   @endphp
                   <div class="bg-white dark:bg-slate-850 border-l-4 {{ $borderColor }} rounded-2xl shadow-xl p-5">
                       <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                           <div class="flex items-start gap-4 flex-1">
                               <div
                                   class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full {{ $iconBg }}">
                                   <i class="fas {{ $iconClass }} text-xl"></i>
                               </div>
                               <div class="flex-1">
                                   <div class="flex items-center gap-2 mb-1 flex-wrap">
                                       <h6 class="text-base font-bold text-gray-800 dark:text-white">{{ $title }}
                                       </h6>
                                       <span
                                           class="text-xs px-2 py-0.5 rounded-full font-semibold uppercase {{ $statusBadge }}">
                                           {{ $result['action'] }} {{ $result['type'] }}
                                       </span>
                                   </div>
                                   <p class="text-sm text-gray-600 dark:text-gray-400">
                                       Verified by <span
                                           class="font-semibold text-gray-800 dark:text-gray-200">{{ $result['verified_by'] }}</span>
                                       on {{ $result['verified_at'] }}
                                   </p>
                                   @if ($result['reason'])
                                       <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                                           <i class="fas fa-comment-dots mr-1 text-gray-400"></i>
                                           <span class="font-semibold">Your reason:</span> {{ $result['reason'] }}
                                       </p>
                                   @endif
                                   @if ($result['verification_notes'])
                                       <div
                                           class="mt-2 p-3 rounded-lg {{ $isApproved ? 'bg-emerald-50 dark:bg-emerald-900/20' : 'bg-red-50 dark:bg-red-900/20' }}">
                                           <p
                                               class="text-sm {{ $isApproved ? 'text-emerald-700 dark:text-emerald-300' : 'text-red-700 dark:text-red-300' }}">
                                               <i
                                                   class="fas {{ $isApproved ? 'fa-thumbs-up' : 'fa-comment-exclamation' }} mr-1"></i>
                                               <span
                                                   class="font-semibold">{{ $isApproved ? 'Approver note' : 'Rejection reason' }}:</span>
                                               {{ $result['verification_notes'] }}
                                           </p>
                                       </div>
                                   @elseif(!$isApproved)
                                       <p class="text-xs text-gray-400 italic mt-2">No rejection reason provided.</p>
                                   @endif
                               </div>
                           </div>
                           <div class="flex-shrink-0">
                               <button wire:click="acknowledgeVerificationResult({{ $result['id'] }})"
                                   class="px-3 py-2.5 text-sm font-bold text-gray-500 hover:text-gray-700 dark:text-white dark:hover:text-gray-200 bg-gray-100 hover:bg-gray-200 dark:bg-slate-800 dark:hover:bg-slate-700 rounded-lg transition-all whitespace-nowrap"
                                   title="Dismiss">
                                   <i class="fas fa-times"></i>
                               </button>
                           </div>
                       </div>
                   </div>
               @endforeach

               @if (count($verificationResults) > 1)
                   <div class="text-right">
                       <button wire:click="acknowledgeAllVerificationResults"
                           class="text-xs font-semibold text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white underline">
                           Dismiss all ({{ count($verificationResults) }})
                       </button>
                   </div>
               @endif
           </div>
       @endif

       {{-- Stock Opname Notification (Kasir/Manajer) --}}
       @if ($showStockOpnameNotif && $stockOpnameData)
           <div class="mb-6 bg-white dark:bg-slate-850 border-l-4 border-blue-500 rounded-2xl shadow-xl p-5">
               <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                   <div class="flex items-start gap-4">
                       <div
                           class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-50 dark:bg-blue-900/30 text-blue-500">
                           <i class="fas fa-clipboard-check text-xl"></i>
                       </div>
                       <div>
                           <h6 class="text-base font-bold text-gray-800 dark:text-white mb-1">Stock Opname Required!
                           </h6>
                           <p class="text-sm text-gray-600 dark:text-gray-400">
                               <span
                                   class="font-semibold text-gray-800 dark:text-gray-200">{{ $stockOpnameData['triggered_by'] }}</span>
                               requested you to perform a stock opname on
                               {{ \Carbon\Carbon::parse($stockOpnameData['created_at'])->format('d M Y H:i') }}.
                           </p>
                           @if ($stockOpnameData['notes'])
                               <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                                   <i class="fas fa-sticky-note mr-1 text-gray-400 dark:text-gray-500"></i>
                                   {{ $stockOpnameData['notes'] }}
                               </p>
                           @endif
                           <p class="text-xs text-blue-600 dark:text-blue-400 font-semibold mt-2">
                               Status:
                               {{ $stockOpnameData['status'] === 'pending' ? 'Waiting to start' : 'In progress' }}
                           </p>
                       </div>
                   </div>
                   <div class="flex items-center gap-3">
                       <button wire:click="goToStockOpname"
                           class="px-5 py-2.5 text-sm font-bold text-white bg-gradient-to-tl from-blue-500 to-violet-500 rounded-lg hover:shadow-md hover:-translate-y-px transition-all whitespace-nowrap">
                           <i class="fas fa-arrow-right mr-1"></i> Start Stock Opname
                       </button>
                       <button wire:click="dismissStockOpnameNotif"
                           class="px-3 py-2.5 text-sm font-bold text-gray-500 hover:text-gray-700 dark:text-white dark:hover:text-gray-200 bg-gray-100 hover:bg-gray-200 dark:bg-slate-800 dark:hover:bg-slate-700 rounded-lg transition-all whitespace-nowrap">
                           <i class="fas fa-times"></i>
                       </button>
                   </div>
               </div>
           </div>
       @endif

       <div class="flex justify-between items-center ">
           <div class=" mb-0 border-b-0 border-solid ">
               <h5 class="mb-1 font-serif">Statistics Dashboard</h5>
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
                                       Today's Transactions</p>
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
                                       Today's Revenue</p>
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
           @if (auth()->user()->hasPermission('verification'))
               <div class="w-full max-w-full px-3 mt-0 lg:w-7/12 lg:flex-none">
                   <div
                       class="border-black/12.5 dark:bg-slate-850 dark:shadow-dark-xl shadow-xl relative z-20 break-words rounded-2xl border-0 border-solid bg-white bg-clip-border h-full">
                       <div
                           class="flex items-center justify-between border-black/12.5 mb-0 rounded-t-2xl border-b-0 border-solid p-6 pt-4 pb-0">
                           <h6 class="capitalize dark:text-white">Transaction Chart</h6>
                           <div class="flex gap-x-3 w-full md:w-5/12 items-center">
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

                               <button wire:click='exportExcel()' wire:loading.attr="disabled"
                                   class="inline-block px-3 py-2 text-xs font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-green-500 leading-normal ease-in tracking-tight-rem shadow-md bg-150 bg-x-25 hover:-translate-y-px active:opacity-85 hover:shadow-md disabled:opacity-50 disabled:cursor-not-allowed">
                                   <span wire:loading.remove wire:target="exportExcel">
                                       <i class="fas fa-file-excel mr-1"></i>
                                       Export
                                   </span>
                                   <span wire:loading wire:target="exportExcel">
                                       <i class="fas fa-spinner fa-spin mr-1"></i>
                                       Exporting...
                                   </span>
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
           @endif

           <div
               class="w-full max-w-full px-3 {{ auth()->user()->hasPermission('verification') ? 'lg:w-5/12' : 'lg:w-full' }} lg:flex-none ">
               <div
                   class="border-black/12.5 shadow-xl dark:bg-slate-850 dark:shadow-dark-xl relative flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid bg-white bg-clip-border h-full">
                   <div class="p-4 pb-0 rounded-t-4">
                       <h6 class="mb-0 dark:text-white">Recent Transactions</h6>
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
                                       <button wire:click="showTransactionDetail({{ $item->id }})"
                                           wire:loading.attr="disabled"
                                           class="group ease-in leading-pro text-xs rounded-3.5xl p-1.2 h-6.5 w-6.5 mx-0 my-auto inline-block cursor-pointer border-0 bg-transparent text-center align-middle font-bold text-slate-700 shadow-none transition-all dark:text-white disabled:opacity-50">
                                           <i wire:loading.remove
                                               wire:target="showTransactionDetail({{ $item->id }})"
                                               class="ni ease-bounce text-2xs group-hover:translate-x-1.25 ni-bold-right transition-all duration-200"
                                               aria-hidden="true"></i>
                                           <div wire:loading wire:target="showTransactionDetail({{ $item->id }})"
                                               class="inline-block h-3 w-3 animate-spin rounded-full border-2 border-solid border-current border-r-transparent align-middle">
                                           </div>
                                       </button>
                                   </div>
                               </li>
                           @endforeach

                       </ul>
                   </div>
               </div>
           </div>
       </div>

       <div class="mt-8"></div>

       <div class="flex flex-wrap -mx-3">
           <!-- card1: Completed Transactions (with month/year filter) -->
           <div class="w-full max-w-full px-3 mb-6 sm:w-full sm:flex-none xl:mb-0 xl:w-4/12">
               <div
                   class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border h-full">
                   <div class="flex-auto p-4">
                       <div class="flex flex-row -mx-3">
                           <div class="flex-none w-2/3 max-w-full px-3">
                               <div>
                                   <p
                                       class="mb-2 font-sans text-sm font-semibold leading-normal uppercase dark:text-white dark:opacity-60">
                                       Completed Transactions</p>
                                   <h4 class="mb-2 font-bold text-neutral-700 dark:text-white">
                                       <span wire:loading.remove
                                           wire:target="selectedMonthFilter,selectedYearFilter,updateTransactionStats">
                                           {{ number_format($completedCount) }}
                                       </span>
                                       <span wire:loading
                                           wire:target="selectedMonthFilter,selectedYearFilter,updateTransactionStats"
                                           class="inline-block h-5 w-5 animate-spin rounded-full border-2 border-solid border-emerald-500 border-r-transparent align-middle"></span>
                                   </h4>
                                   <p class="text-xs text-gray-500 dark:text-gray-400">
                                       {{ \Carbon\Carbon::createFromFormat('m', $selectedMonthFilter)->locale('en')->translatedFormat('F') }}
                                       {{ $selectedYearFilter }}
                                   </p>
                               </div>
                           </div>
                           <div class="px-3 text-right basis-1/3">
                               <div
                                   class="inline-block w-12 h-12 text-center rounded-circle bg-gradient-to-tl from-emerald-500 to-teal-400">
                                   <i class="ni leading-none ni-check-bold text-lg relative top-3.5 text-white"></i>
                               </div>
                           </div>
                       </div>

                       <!-- Inline period filter (only affects Completed card) -->
                       <div class="mt-3 pt-3 border-t border-gray-100 dark:border-slate-700">
                           <div class="flex items-center gap-2">
                               <i class="fas fa-filter text-xs text-gray-400"></i>
                               <select wire:model="selectedMonthFilter" wire:change="updateTransactionStats"
                                   class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-xs leading-tight ease flex-1 appearance-none rounded-md border border-solid border-gray-300 bg-white bg-clip-padding px-2 py-1 font-normal text-gray-700 outline-none transition-all focus:border-blue-500 focus:outline-none">
                                   <option value="01">January</option>
                                   <option value="02">February</option>
                                   <option value="03">March</option>
                                   <option value="04">April</option>
                                   <option value="05">May</option>
                                   <option value="06">June</option>
                                   <option value="07">July</option>
                                   <option value="08">August</option>
                                   <option value="09">September</option>
                                   <option value="10">October</option>
                                   <option value="11">November</option>
                                   <option value="12">December</option>
                               </select>
                               <select wire:model="selectedYearFilter" wire:change="updateTransactionStats"
                                   class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-xs leading-tight ease w-20 appearance-none rounded-md border border-solid border-gray-300 bg-white bg-clip-padding px-2 py-1 font-normal text-gray-700 outline-none transition-all focus:border-blue-500 focus:outline-none">
                                   @foreach ($years as $year)
                                       <option value="{{ $year }}">{{ $year }}</option>
                                   @endforeach
                               </select>
                           </div>
                       </div>
                   </div>
               </div>
           </div>

           <!-- card2: Cancelled Transactions (shares filter with Completed) -->
           <div class="w-full max-w-full px-3 mb-6 sm:w-full sm:flex-none xl:mb-0 xl:w-4/12 cursor-pointer"
               wire:click="showCancelledTransactions">
               <div
                   class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border h-full hover:shadow-2xl transition-shadow">
                   <div class="flex-auto p-4">
                       <div class="flex flex-row -mx-3">
                           <div class="flex-none w-2/3 max-w-full px-3">
                               <div>
                                   <p
                                       class="mb-2 font-sans text-sm font-semibold leading-normal uppercase dark:text-white dark:opacity-60">
                                       Cancelled Transactions</p>
                                   <h4 class="mb-2 font-bold text-neutral-700 dark:text-white">
                                       <span wire:loading.remove
                                           wire:target="selectedMonthFilter,selectedYearFilter,updateTransactionStats,showCancelledTransactions">
                                           {{ number_format($cancelledCount) }}
                                       </span>
                                       <span wire:loading
                                           wire:target="selectedMonthFilter,selectedYearFilter,updateTransactionStats,showCancelledTransactions"
                                           class="inline-block h-5 w-5 animate-spin rounded-full border-2 border-solid border-red-500 border-r-transparent align-middle"></span>
                                   </h4>
                                   <p class="text-xs text-gray-500 dark:text-gray-400">
                                       {{ \Carbon\Carbon::createFromFormat('m', $selectedMonthFilter)->locale('en')->translatedFormat('F') }}
                                       {{ $selectedYearFilter }}
                                   </p>
                               </div>
                           </div>
                           <div class="px-3 text-right basis-1/3">
                               <div
                                   class="inline-block w-12 h-12 text-center rounded-circle bg-gradient-to-tl from-red-600 to-orange-600">
                                   <i class="ni leading-none ni-fat-remove text-lg relative top-3.5 text-white"></i>
                               </div>
                           </div>
                       </div>

                       <!-- Click hint -->
                       <div class="mt-3 pt-3 border-t border-gray-100 dark:border-slate-700">
                           <p class="text-xs text-blue-500 font-semibold flex items-center gap-2 hover:underline">
                               <i class="fas fa-eye text-xs"></i>
                               Click to view list
                           </p>
                       </div>
                   </div>
               </div>
           </div>

           <!-- card3: Transactions In Process (all time) -->
           <div class="w-full max-w-full px-3 mb-6 sm:w-full sm:flex-none xl:mb-0 xl:w-4/12">
               <div
                   class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border h-full">
                   <div class="flex-auto p-4">
                       <div class="flex flex-row -mx-3">
                           <div class="flex-none w-2/3 max-w-full px-3">
                               <div>
                                   <p
                                       class="mb-2 font-sans text-sm font-semibold leading-normal uppercase dark:text-white dark:opacity-60">
                                       Transactions In Process</p>
                                   <h4 class="mb-2 font-bold text-neutral-700 dark:text-white">
                                       {{ number_format($inProcessCount) }}
                                   </h4>
                                   <p class="text-xs text-gray-500 dark:text-gray-400">
                                       All time
                                   </p>
                               </div>
                           </div>
                           <div class="px-3 text-right basis-1/3">
                               <div
                                   class="inline-block w-12 h-12 text-center rounded-circle bg-gradient-to-tl from-cyan-600 to-sky-600">
                                   <i class="ni leading-none ni-folder-17 text-lg relative top-3.5 text-white"></i>
                               </div>
                           </div>
                       </div>

                       <!-- Spacer to align card height with Completed card -->
                       <div class="mt-3 pt-3 border-t border-gray-100 dark:border-slate-700">
                           <p class="text-xs text-gray-400 italic flex items-center gap-2">
                               <i class="fas fa-infinity text-xs"></i>
                               Showing all-time data
                           </p>
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

       {{-- Cancelled Transactions Modal --}}
       @if ($showCancelledModal)
           <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
               aria-modal="true">
               <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                   <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"
                       wire:click="closeCancelledModal"></div>
                   <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                   <div
                       class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-5xl sm:w-full">
                       <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                           <div class="flex justify-between items-center mb-4">
                               <h3 class="text-lg leading-6 font-medium text-gray-900">Cancelled Transactions</h3>
                               <button wire:click="closeCancelledModal" class="text-gray-400 hover:text-gray-600">
                                   <i class="fas fa-times text-xl"></i>
                               </button>
                           </div>

                           <p class="text-sm text-gray-500 mb-4">
                               Showing cancelled transactions for
                               {{ \Carbon\Carbon::createFromFormat('m', $selectedMonthFilter)->locale('en')->translatedFormat('F') }}
                               {{ $selectedYearFilter }}
                               <span
                                   class="ml-2 px-2 py-0.5 bg-red-100 text-red-700 rounded-full text-xs font-semibold">{{ count($cancelledTransactions) }}
                                   items</span>
                           </p>

                           @if (count($cancelledTransactions) > 0)
                               <div class="overflow-auto max-h-96">
                                   <table class="items-center w-full mb-0 align-top border-collapse text-slate-500">
                                       <thead class="align-bottom sticky top-0 bg-gray-50">
                                           <tr>
                                               <th
                                                   class="px-4 py-3 font-bold text-left text-xs uppercase text-slate-400 border-b">
                                                   Order ID</th>
                                               <th
                                                   class="px-4 py-3 font-bold text-left text-xs uppercase text-slate-400 border-b">
                                                   Service</th>
                                               <th
                                                   class="px-4 py-3 font-bold text-left text-xs uppercase text-slate-400 border-b">
                                                   Customer</th>
                                               <th
                                                   class="px-4 py-3 font-bold text-right text-xs uppercase text-slate-400 border-b">
                                                   Total</th>
                                               <th
                                                   class="px-4 py-3 font-bold text-center text-xs uppercase text-slate-400 border-b">
                                                   Created At</th>
                                               <th
                                                   class="px-4 py-3 font-bold text-center text-xs uppercase text-slate-400 border-b">
                                                   Cancelled At</th>
                                               <th
                                                   class="px-4 py-3 font-bold text-center text-xs uppercase text-slate-400 border-b">
                                                   Action</th>
                                           </tr>
                                       </thead>
                                       <tbody>
                                           @foreach ($cancelledTransactions as $item)
                                               <tr class="hover:bg-gray-50">
                                                   <td class="px-4 py-3 text-xs font-semibold text-slate-700 border-b">
                                                       {{ $item['order_transaction'] }}</td>
                                                   <td class="px-4 py-3 text-xs text-slate-600 border-b">
                                                       {{ $item['service'] }}</td>
                                                   <td class="px-4 py-3 text-xs text-slate-600 border-b">
                                                       <div>{{ $item['customer_name'] }}</div>
                                                       <div class="text-gray-400 text-xxs">
                                                           {{ $item['customer_phone'] }}</div>
                                                   </td>
                                                   <td
                                                       class="px-4 py-3 text-xs text-right font-semibold text-slate-700 border-b">
                                                       Rp {{ number_format($item['total']) }}</td>
                                                   <td class="px-4 py-3 text-xs text-center text-slate-500 border-b">
                                                       {{ $item['created_at'] }}</td>
                                                   <td class="px-4 py-3 text-xs text-center text-slate-500 border-b">
                                                       {{ $item['cancelled_at'] }}</td>
                                                   <td class="px-4 py-3 text-xs text-center border-b">
                                                       <button wire:click="showTransactionDetail({{ $item['id'] }})"
                                                           wire:loading.attr="disabled"
                                                           class="text-blue-600 hover:text-blue-800 font-semibold disabled:opacity-50">
                                                           <div wire:loading
                                                               wire:target="showTransactionDetail({{ $item['id'] }})"
                                                               class="inline-block h-3 w-3 animate-spin rounded-full border-2 border-solid border-current border-r-transparent mr-1">
                                                           </div>
                                                           <span wire:loading.remove
                                                               wire:target="showTransactionDetail({{ $item['id'] }})">
                                                               <i class="fas fa-eye mr-1"></i>View
                                                           </span>
                                                       </button>
                                                   </td>
                                               </tr>
                                           @endforeach
                                       </tbody>
                                   </table>
                               </div>
                           @else
                               <div class="text-center py-12">
                                   <i class="fas fa-inbox text-4xl text-gray-300 mb-4"></i>
                                   <p class="text-gray-500">No cancelled transactions for this period.</p>
                               </div>
                           @endif
                       </div>
                       <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                           <button wire:click="closeCancelledModal" wire:loading.attr="disabled"
                               class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:w-auto sm:text-sm disabled:opacity-50">
                               <div wire:loading wire:target="closeCancelledModal" class="mr-2">
                                   <div class="inline-block h-3 w-3 animate-spin rounded-full border-2 border-solid border-current border-r-transparent"
                                       role="status"></div>
                               </div>
                               Close
                           </button>
                       </div>
                   </div>
               </div>
           </div>
       @endif

       {{-- Transaction Detail Modal --}}
       @if ($showTransactionDetailModal && $transactionDetail)
           <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
               aria-modal="true">
               <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                   <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"
                       wire:click="closeTransactionDetailModal"></div>
                   <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                   <div
                       class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                       <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                           {{-- Header --}}
                           <div class="flex justify-between items-start mb-6 pb-4 border-b border-gray-200">
                               <div>
                                   <h3 class="text-lg leading-6 font-medium text-gray-900">Transaction Detail</h3>
                                   <p class="text-sm text-gray-500 mt-1">Order ID: <span
                                           class="font-semibold">{{ $transactionDetail['order_transaction'] }}</span>
                                   </p>
                               </div>
                               <div class="flex items-center gap-3">
                                   @if ($transactionDetail['status'] === 'done')
                                       <span
                                           class="px-3 py-1 text-xs rounded-full bg-emerald-100 text-emerald-700 font-semibold">Completed</span>
                                   @elseif($transactionDetail['status'] === 'cancel')
                                       <span
                                           class="px-3 py-1 text-xs rounded-full bg-red-100 text-red-700 font-semibold">Cancelled</span>
                                   @elseif($transactionDetail['status'] === 'proses')
                                       <span
                                           class="px-3 py-1 text-xs rounded-full bg-blue-100 text-blue-700 font-semibold">In
                                           Process</span>
                                   @endif
                                   <button wire:click="closeTransactionDetailModal"
                                       class="text-gray-400 hover:text-gray-600">
                                       <i class="fas fa-times text-xl"></i>
                                   </button>
                               </div>
                           </div>

                           {{-- Customer Info --}}
                           <div class="bg-gray-50 rounded-lg p-4 mb-6">
                               <h4 class="text-sm font-semibold text-gray-700 mb-3">Customer Information</h4>
                               <div class="grid grid-cols-2 gap-4">
                                   <div>
                                       <p class="text-xs text-gray-500">Name</p>
                                       <p class="text-sm font-medium text-gray-900">
                                           {{ $transactionDetail['customer_name'] }}
                                       </p>
                                   </div>
                                   <div>
                                       <p class="text-xs text-gray-500">Phone</p>
                                       <p class="text-sm font-medium text-gray-900">
                                           {{ $transactionDetail['customer_phone'] }}
                                       </p>
                                   </div>
                               </div>
                           </div>

                           {{-- Transaction Info --}}
                           <div class="grid grid-cols-3 gap-4 mb-6">
                               <div class="bg-gray-50 rounded-lg p-3">
                                   <p class="text-xs text-gray-500">Payment Method</p>
                                   <p class="text-sm font-semibold text-gray-900 uppercase">
                                       {{ $transactionDetail['payment_method'] }}</p>
                               </div>
                               <div class="bg-gray-50 rounded-lg p-3">
                                   <p class="text-xs text-gray-500">Created At</p>
                                   <p class="text-sm font-semibold text-gray-900">
                                       {{ $transactionDetail['created_at'] }}</p>
                               </div>
                               <div class="bg-gray-50 rounded-lg p-3">
                                   <p class="text-xs text-gray-500">Created By</p>
                                   <p class="text-sm font-semibold text-gray-900">
                                       {{ $transactionDetail['created_by'] }}</p>
                               </div>
                           </div>

                           {{-- Main Service --}}
                           <div class="mb-6">
                               <h4 class="text-sm font-semibold text-gray-700 mb-3">Main Service</h4>
                               <div class="border rounded-lg overflow-hidden">
                                   <table class="w-full text-sm">
                                       <thead class="bg-gray-50">
                                           <tr>
                                               <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">
                                                   Service</th>
                                               <th class="px-4 py-2 text-right text-xs font-medium text-gray-500">Cost
                                               </th>
                                               @if ($transactionDetail['main_potongan'] > 0)
                                                   <th class="px-4 py-2 text-right text-xs font-medium text-gray-500">
                                                       Discount
                                                   </th>
                                               @endif
                                               <th class="px-4 py-2 text-right text-xs font-medium text-gray-500">Total
                                               </th>
                                           </tr>
                                       </thead>
                                       <tbody>
                                           <tr class="border-t">
                                               <td class="px-4 py-3">{{ $transactionDetail['service'] }}</td>
                                               <td class="px-4 py-3 text-right">Rp
                                                   {{ number_format($transactionDetail['main_biaya']) }}</td>
                                               @if ($transactionDetail['main_potongan'] > 0)
                                                   <td class="px-4 py-3 text-right text-red-600">-Rp
                                                       {{ number_format($transactionDetail['main_potongan']) }}</td>
                                               @endif
                                               <td class="px-4 py-3 text-right font-semibold">Rp
                                                   {{ number_format($transactionDetail['main_total']) }}</td>
                                           </tr>
                                       </tbody>
                                   </table>
                               </div>
                           </div>

                           {{-- Additional Items --}}
                           @if (count($transactionDetailItems) > 0)
                               <div class="mb-6">
                                   <h4 class="text-sm font-semibold text-gray-700 mb-3">Additional Services</h4>
                                   <div class="border rounded-lg overflow-hidden">
                                       <table class="w-full text-sm">
                                           <thead class="bg-gray-50">
                                               <tr>
                                                   <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">
                                                       Service
                                                   </th>
                                                   <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">
                                                       Technician</th>
                                                   <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">
                                                       Sparepart
                                                   </th>
                                                   <th class="px-4 py-2 text-center text-xs font-medium text-gray-500">
                                                       Warranty
                                                   </th>
                                                   <th class="px-4 py-2 text-right text-xs font-medium text-gray-500">
                                                       Total</th>
                                               </tr>
                                           </thead>
                                           <tbody>
                                               @foreach ($transactionDetailItems as $item)
                                                   <tr class="border-t">
                                                       <td class="px-4 py-3">{{ $item['service'] }}</td>
                                                       <td class="px-4 py-3 text-xs">{{ $item['technician'] }}</td>
                                                       <td class="px-4 py-3 text-xs">{{ $item['product'] }}</td>
                                                       <td class="px-4 py-3 text-center text-xs">
                                                           {{ $item['warranty'] }}</td>
                                                       <td class="px-4 py-3 text-right font-semibold">Rp
                                                           {{ number_format($item['total']) }}</td>
                                                   </tr>
                                               @endforeach
                                           </tbody>
                                       </table>
                                   </div>
                               </div>
                           @endif

                           {{-- Total --}}
                           <div class="bg-gray-900 rounded-lg p-4 text-white">
                               <div class="flex justify-between items-center">
                                   <span class="text-sm">Grand Total</span>
                                   <span class="text-xl font-bold">Rp
                                       {{ number_format($transactionDetail['grand_total']) }}</span>
                               </div>
                           </div>
                       </div>

                       {{-- Footer --}}
                       <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                           <a href="{{ url('dashboard/detail-transaction', $transactionDetail['id']) }}"
                               class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 sm:w-auto sm:text-sm">
                               <i class="fas fa-edit mr-2 mt-1"></i>Edit Transaction
                           </a>
                           <button wire:click="closeTransactionDetailModal" wire:loading.attr="disabled"
                               class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:w-auto sm:text-sm disabled:opacity-50">
                               <div wire:loading wire:target="closeTransactionDetailModal" class="mr-2">
                                   <div class="inline-block h-3 w-3 animate-spin rounded-full border-2 border-solid border-current border-r-transparent"
                                       role="status"></div>
                               </div>
                               Close
                           </button>
                       </div>
                   </div>
               </div>
           </div>
       @endif

   </div>

   @push('script')
       <script src="{{ asset('assets/js/plugins/chartjs.min.js') }}"></script>

       <script>
           let transactionChart; // Declare chart instance globally

           document.addEventListener('DOMContentLoaded', () => {
               @if (auth()->user()->hasPermission('verification'))
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
               @endif
           });
       </script>
   @endpush
