<?php

namespace App\Http\Livewire\Dashboard;

use App\Exports\TransactionChartExport;
use App\Models\Expenditure;
use App\Models\Transaction;
use App\Models\PendingChange;
use App\Models\StockOpname;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class Dashboard extends Component
{
    public $selectedYear;
    public $showPendingModal = false;
    public $pendingCount = 0;
    public $showStockOpnameNotif = false;
    public $stockOpnameData = null;
    public $verificationResults = []; // approved/rejected requests not yet acknowledged
    public $selectedMonthFilter;
    public $selectedYearFilter;
    public $chartData = [];
    public $chartLabels = [];

    // Cancelled transactions modal
    public $showCancelledModal = false;
    public $cancelledTransactions = [];

    // Transaction detail modal
    public $showTransactionDetailModal = false;
    public $transactionDetail = null;
    public $transactionDetailItems = [];

    public function mount()
    {
        $this->selectedYear = Carbon::now()->format('Y');
        $this->selectedMonthFilter = Carbon::now()->format('m');
        $this->selectedYearFilter = Carbon::now()->format('Y');
        
        // Initialize chart data
        if (auth()->user()->hasPermission('verification')) {
            $chartResult = $this->transactionChart();
            $this->chartData = $chartResult[0];
            $this->chartLabels = $chartResult[1];
            
            // Check for pending verifications
            $this->pendingCount = PendingChange::pending()->count();
            $this->showPendingModal = $this->pendingCount > 0;
        }

        // Check for active stock opname notification (for non-owner)
        if (!auth()->user()->isOwner()) {
            $activeOpname = StockOpname::active()
                ->where(function ($q) {
                    $q->where('assigned_to', auth()->id())
                      ->orWhereNull('assigned_to');
                })
                ->with('triggeredBy')
                ->first();

            if ($activeOpname) {
                $this->showStockOpnameNotif = true;
                $this->stockOpnameData = [
                    'id' => $activeOpname->id,
                    'triggered_by' => $activeOpname->triggeredBy->name ?? '-',
                    'notes' => $activeOpname->notes,
                    'created_at' => $activeOpname->created_at->format('d M Y H:i'),
                    'status' => $activeOpname->status,
                ];
            }
        }

        $this->loadVerificationResults();
    }

    /**
     * Load any approved/rejected pending changes belonging to the current user
     * that have not been acknowledged yet, so the dashboard can surface them.
     */
    public function loadVerificationResults()
    {
        $changes = PendingChange::with('verifiedBy')
            ->where('requested_by', auth()->id())
            ->whereIn('status', ['approved', 'rejected'])
            ->whereNotNull('verified_at')
            ->whereNull('acknowledged_at')
            ->orderBy('verified_at', 'desc')
            ->get();

        $this->verificationResults = $changes->map(function ($change) {
            return [
                'id' => $change->id,
                'status' => $change->status,
                'action' => $change->action,
                'type' => class_basename($change->changeable_type),
                'reason' => $change->reason,
                'verification_notes' => $change->verification_notes,
                'verified_by' => $change->verifiedBy->name ?? '-',
                'verified_at' => optional($change->verified_at)->format('d M Y H:i'),
            ];
        })->toArray();
    }

    public function acknowledgeVerificationResult($id)
    {
        $change = PendingChange::where('id', $id)
            ->where('requested_by', auth()->id())
            ->first();

        if ($change) {
            $change->acknowledged_at = now();
            $change->save();
        }

        $this->verificationResults = array_values(array_filter(
            $this->verificationResults,
            fn($item) => $item['id'] !== $id
        ));
    }

    public function acknowledgeAllVerificationResults()
    {
        PendingChange::where('requested_by', auth()->id())
            ->whereIn('status', ['approved', 'rejected'])
            ->whereNull('acknowledged_at')
            ->update(['acknowledged_at' => now()]);

        $this->verificationResults = [];
    }

    public function transactionChart()
    {
        $transactionChart = DB::table('t1')
            ->selectRaw("
            GROUP_CONCAT(total_fee SEPARATOR ', ') as monthly_fees,
            GROUP_CONCAT(month SEPARATOR ', ') as month
        ")
            ->fromSub(function ($query) {
                $query->from('transactions as t')
                    ->leftJoinSub(
                        DB::table('transaction_items')
                            ->selectRaw('transaction_id, SUM(biaya - IFNULL(potongan, 0)) as total_item_fee')
                            ->whereNull('deleted_at')
                            ->groupBy('transaction_id'),
                        'ti',
                        'ti.transaction_id',
                        't.id'
                    )
                    ->selectRaw("
                    MONTHNAME(t.created_at) as month,
                    MONTH(t.created_at) as number_of_month,
                    (SUM(t.biaya - IFNULL(t.potongan, 0)) + IFNULL(SUM(ti.total_item_fee), 0)) as total_fee
                ")
                    ->whereYear('t.created_at', $this->selectedYear)
                    ->whereNull('t.deleted_at')
                    ->where('t.status', 'done')
                    ->groupBy(
                        DB::raw('MONTH(t.created_at)'),
                        DB::raw('MONTHNAME(t.created_at)')
                    )
                    ->orderBy(DB::raw('MONTH(t.created_at)'));
            }, 't1')
            ->get();

        $dataChart = [];
        $labelChart = [];

        if (!$transactionChart->isEmpty()) {
            $dataChart = array_map('floatval', explode(',', $transactionChart[0]->monthly_fees));
            $labelChart = explode(',', $transactionChart[0]->month);
        }

        return [$dataChart, $labelChart];
    }

    public function render()
    {
        $startYear = 2023;
        $currentYear = Carbon::now()->year;
        $years = range($startYear, $currentYear);
        $now = Carbon::now()->format('Y-m-d');
        $transaction = Transaction::select(DB::raw("SUM(untung) as biaya"), DB::raw("COUNT(id) as transaction"))
            ->whereDate('created_at', $now)->get();

        $data = Transaction::leftJoin('transaction_items', function ($join) {
            $join->on('transactions.id', '=', 'transaction_items.transaction_id')
                ->whereNull('transaction_items.deleted_at');
        })
            ->select(
                DB::raw('(transactions.biaya - IFNULL(transactions.potongan, 0)) + IFNULL(SUM(transaction_items.biaya - IFNULL(transaction_items.potongan, 0)), 0) as total_biaya'),
            )
            ->where('transactions.status', 'done')
            ->whereNull('transactions.deleted_at')
            ->whereDate('transactions.created_at', $now)
            ->groupBy(
                'transactions.created_at',
                'transactions.id',
                'transactions.order_transaction',
                'transactions.biaya',
                'transactions.potongan',
                'transactions.modal',
                'transactions.payment_method'
            );

        // Mengambil hasil query
        $data = $data->get();

        $todayTransaction = $data->count();
        $todayIncome = $data->sum('total_biaya');

        $expenditur = Expenditure::select(DB::raw("SUM(total) as total"))
            ->whereDate('tanggal', $now)
            ->get();

        $todayExpenditure = $expenditur[0]['total'];

        // Use cached chart data instead of calling transactionChart() every render
        $dataChart = $this->chartData;
        $labelChart = $this->chartLabels;

        $dataTransaction = Transaction::take(6)->latest()->get();

        $completedCount = Transaction::where('status', 'done')
            ->whereYear('created_at', $this->selectedYearFilter)
            ->whereMonth('created_at', $this->selectedMonthFilter)
            ->count();

        $cancelledCount = Transaction::where('status', 'cancel')
            ->whereYear('created_at', $this->selectedYearFilter)
            ->whereMonth('created_at', $this->selectedMonthFilter)
            ->count();

        $inProcessCount = Transaction::where('status', 'proses')->count();

        return view('livewire.dashboard.dashboard', compact('dataChart', 'labelChart', 'todayTransaction', 'todayIncome', 'todayExpenditure', 'dataTransaction', 'completedCount', 'cancelledCount', 'inProcessCount', 'years'))
            ->layout('components.layouts.dashboard');
    }

    public function updateChart()
    {
        $transactionChart = $this->transactionChart();

        $this->chartData = $transactionChart[0];
        $this->chartLabels = $transactionChart[1];

        $this->emit('chartUpdate', $this->chartData, $this->chartLabels);
    }

    public function exportExcel()
    {
        // Use cached data if available, otherwise fetch fresh data
        if (empty($this->chartData) || empty($this->chartLabels)) {
            $transactionChart = $this->transactionChart();
            $dataChart = $transactionChart[0];
            $labelChart = $transactionChart[1];
        } else {
            $dataChart = $this->chartData;
            $labelChart = $this->chartLabels;
        }

        $transactionData = DB::table('transactions as t')
            ->leftJoinSub(
                DB::table('transaction_items')
                    ->selectRaw('transaction_id, SUM(biaya - IFNULL(potongan, 0)) as total_item_fee')
                    ->whereNull('deleted_at')
                    ->groupBy('transaction_id'),
                'ti',
                'ti.transaction_id',
                't.id'
            )
            ->selectRaw("
                t.id,
                t.order_transaction,
                t.service,
                t.customer_id,
                (t.biaya - IFNULL(t.potongan, 0)) as biaya,
                t.status,
                t.payment_method,
                t.created_at,
                MONTHNAME(t.created_at) as month,
                MONTH(t.created_at) as month_number,
                ((t.biaya - IFNULL(t.potongan, 0)) + IFNULL(ti.total_item_fee, 0)) as total_fee
            ")
            ->whereYear('t.created_at', $this->selectedYear)
            ->whereNull('t.deleted_at')
            ->where('t.status', 'done')
            ->orderBy('t.created_at')
            ->get();

        $export = new TransactionChartExport($transactionData, $dataChart, $labelChart, $this->selectedYear);

        $this->updateChart();

        return Excel::download($export, 'transaction-chart-' . $this->selectedYear . '.xlsx');
    }

    public function goToVerification()
    {
        return redirect()->route('dashboard.verification.index');
    }

    public function goToStockOpname()
    {
        return redirect()->route('dashboard.stock-opname.index');
    }

    public function dismissStockOpnameNotif()
    {
        $this->showStockOpnameNotif = false;
    }

    public function updateTransactionStats()
    {
        // This method is called when statistics filters change
        // We don't need to do anything here as Livewire will automatically re-render
        // the component when the properties change
    }

    // === Cancelled Transactions Modal ===

    public function showCancelledTransactions()
    {
        $this->loadCancelledTransactions();
        $this->showCancelledModal = true;
    }

    public function closeCancelledModal()
    {
        $this->showCancelledModal = false;
        $this->cancelledTransactions = [];
    }

    public function loadCancelledTransactions()
    {
        $this->cancelledTransactions = Transaction::where('status', 'cancel')
            ->whereYear('created_at', $this->selectedYearFilter)
            ->whereMonth('created_at', $this->selectedMonthFilter)
            ->with(['customer', 'items'])
            ->latest()
            ->get()
            ->map(function ($transaction) {
                $total = $transaction->biaya - ($transaction->potongan ?? 0);
                $total += $transaction->items->sum(function ($item) {
                    return $item->biaya - ($item->potongan ?? 0);
                });

                return [
                    'id' => $transaction->id,
                    'order_transaction' => $transaction->order_transaction,
                    'service' => $transaction->service,
                    'customer_name' => $transaction->customer->name ?? '-',
                    'customer_phone' => $transaction->customer->no_telp ?? '-',
                    'total' => $total,
                    'created_at' => $transaction->created_at->format('d M Y H:i'),
                    'cancelled_at' => $transaction->updated_at->format('d M Y H:i'),
                ];
            })
            ->toArray();
    }

    // === Transaction Detail Modal ===

    public function showTransactionDetail($transactionId)
    {
        $transaction = Transaction::with(['customer', 'items.product', 'user'])->find($transactionId);

        if (!$transaction) {
            $this->dispatchBrowserEvent('swal', [
                'title' => 'Error',
                'text' => 'Transaction not found.',
                'icon' => 'error'
            ]);
            return;
        }

        $mainTotal = $transaction->biaya - ($transaction->potongan ?? 0);
        $itemsTotal = $transaction->items->sum(function ($item) {
            return $item->biaya - ($item->potongan ?? 0);
        });
        $grandTotal = $mainTotal + $itemsTotal;

        $this->transactionDetail = [
            'id' => $transaction->id,
            'order_transaction' => $transaction->order_transaction,
            'service' => $transaction->service,
            'status' => $transaction->status,
            'payment_method' => $transaction->payment_method,
            'created_at' => $transaction->created_at->format('d M Y H:i'),
            'created_by' => $transaction->user->name ?? '-',
            'customer_name' => $transaction->customer->name ?? '-',
            'customer_phone' => $transaction->customer->no_telp ?? '-',
            'main_biaya' => $transaction->biaya,
            'main_potongan' => $transaction->potongan ?? 0,
            'main_total' => $mainTotal,
            'items_total' => $itemsTotal,
            'grand_total' => $grandTotal,
        ];

        $this->transactionDetailItems = $transaction->items->map(function ($item) {
            return [
                'service' => $item->service,
                'biaya' => $item->biaya,
                'potongan' => $item->potongan ?? 0,
                'total' => $item->biaya - ($item->potongan ?? 0),
                'technician' => $item->technical_id ? \App\Models\Technician::find($item->technical_id)?->name : '-',
                'product' => $item->product?->name ?? '-',
                'warranty' => $item->warranty ? $item->warranty . ' ' . ($item->warranty_type == 'daily' ? 'Days' : ($item->warranty_type == 'weekly' ? 'Weeks' : 'Months')) : '-',
            ];
        })->toArray();

        $this->showTransactionDetailModal = true;
    }

    public function closeTransactionDetailModal()
    {
        $this->showTransactionDetailModal = false;
        $this->transactionDetail = null;
        $this->transactionDetailItems = [];
    }
}
