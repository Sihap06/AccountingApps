<?php

namespace App\Exports;

use App\Models\Transaction;
use App\Models\TransactionItem;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class IncomeReport implements FromView, ShouldAutoSize, WithColumnFormatting, WithTitle
{
    private $month;
    private $year;

    public function __construct($month, $year)
    {
        $this->month = $month;
        $this->year = $year;
    }

    public function view(): View
    {
        $queryTransaction = Transaction::select('id', 'service', 'untung', 'created_at', 'order_transaction')
            ->whereMonth('created_at', $this->month)
            ->whereYear('created_at', $this->year)
            ->where('status', 'done');

        $dataIncomeTransaction = $queryTransaction->get()->toArray();
        $dataIncome = [...$dataIncomeTransaction];

        foreach ($dataIncomeTransaction as $key => $value) {
            $queryTransactionItem = TransactionItem::select('service', 'untung', 'created_at')
                ->addSelect(DB::raw("'" . $value['order_transaction'] . "' as order_transaction"))
                ->addSelect(DB::raw("'" . $value['id'] . "' as id"))
                ->where('transaction_id', $value['id'])
                ->whereMonth('created_at', $this->month)
                ->whereYear('created_at', $this->year);

            $dataIncomeTransactionItem = $queryTransactionItem->get()->toArray();
            $dataIncome = [...$dataIncome, ...$dataIncomeTransactionItem];
        }

        $collection = collect($dataIncome);
        $data = $collection->groupBy(function ($item) {
            return Carbon::parse($item['created_at'])->toDateString(); // Group by date part only
        })->map(function ($group) {
            return [
                'tanggal' => $group->first()['created_at'],
                'total' => $group->sum('untung')
            ];
        });

        return view('export.income', [
            'data' => $data
        ]);
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }

    public function title(): string
    {
        return 'Income';
    }
}
