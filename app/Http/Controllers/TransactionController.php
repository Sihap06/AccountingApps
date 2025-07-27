<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Technician;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\LogActivityProduct;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function postTransaction(Request $request, $customer_id)
    {
        $transaction_id = null;

        foreach ($request->all() as $key => $value) {
            $currencyString = preg_replace("/[^0-9]/", "", $value['biaya']);

            $value['biaya'] = $currencyString;
            $value['modal'] = 0;

            if ($value['product_id'] === '') {
                $value['product_id'] = null;
            }

            if ($value['technical_id'] === '') {
                $value['technical_id'] = null;
            }

            if ($value['product_id'] !== null) {
                $product = app(ProductController::class)->detailProduct((int)$value['product_id'])->getData(true)['data'];
                $value['modal'] = $product['harga'];
                $value['product_id'] = (int)$value['product_id'];
                $value['technical_id'] = null;
            } elseif ($value['technical_id'] !== null) {
                $value['modal'] = 0;
                $value['product_id'] = null;
            }

            // Calculate effective price after discount
            $effectiveBiaya = $value['biaya'] - (isset($value['potongan']) ? (int)$value['potongan'] : 0);
            $perhitungan = $this->getPerhitungan($value['technical_id'], $effectiveBiaya, $value['modal']);

            if ($value['product_id'] != null) {
                $product = Product::find($value['product_id']);
                if ($product->stok == 0) {
                    return response()->json([
                        'status' => 'failed',
                        'message' => 'out of stock!',
                    ], 400);
                }

                // Store old stock value for logging
                $oldStock = $product->stok;

                // Set bypass flag to skip verification for transaction stock updates
                $product->bypassVerification = true;

                // Update stock
                $product->stok = $product->stok - 1;
                $product->save();

                // Create activity log for stock change during transaction
                $log = new LogActivityProduct();
                $log->user = Auth::user()->name;
                $log->activity = 'update';
                $log->product = $product->name;
                $log->old_stok = $oldStock;
                $log->new_stok = $product->stok;
                $log->save();
            }

            if ($key === 0) {
                $data = new Transaction();
                
                // Set bypass flag to skip verification for transaction creation
                $data->bypassVerification = true;
                
                $data->product_id = $value['product_id'];
                $data->technical_id = $value['technical_id'];
                $data->service = $value['service'];
                $data->biaya = $value['biaya'];
                $data->potongan = isset($value['potongan']) ? (int)$value['potongan'] : 0;
                $data->modal = $perhitungan['modal'];
                $data->fee_teknisi  = $perhitungan['fee_teknisi'];
                $data->untung = $perhitungan['untung'];
                $data->created_by = Auth::user()->id;
                $data->order_transaction = $value['order_transaction'];
                $data->customer_id = $customer_id;
                $data->status = 'proses';
                $data->warranty = (int)$value['warranty'];
                $data->warranty_type = $value['warranty_type'];
                
                // Add phone fields if they exist
                if (isset($value['phone_brand'])) {
                    $data->phone_brand = $value['phone_brand'];
                }
                if (isset($value['phone_type'])) {
                    $data->phone_type = $value['phone_type'];
                }
                if (isset($value['phone_color'])) {
                    $data->phone_color = $value['phone_color'];
                }
                if (isset($value['phone_imei'])) {
                    $data->phone_imei = $value['phone_imei'];
                }
                if (isset($value['phone_internal'])) {
                    $data->phone_internal = $value['phone_internal'];
                }
                
                $data->save();

                $transaction_id = $data->id;
            } else {
                $transactionItems = new TransactionItem();
                $transactionItems->transaction_id = $transaction_id;
                $transactionItems->product_id = $value['product_id'];
                $transactionItems->technical_id = $value['technical_id'];
                $transactionItems->service = $value['service'];
                $transactionItems->biaya = $value['biaya'];
                $transactionItems->potongan = isset($value['potongan']) ? (int)$value['potongan'] : 0;
                $transactionItems->modal = $perhitungan['modal'];
                $transactionItems->fee_teknisi  = $perhitungan['fee_teknisi'];
                $transactionItems->untung = $perhitungan['untung'];
                $transactionItems->warranty = (int)$value['warranty'];
                $transactionItems->warranty_type = $value['warranty_type'];
                
                // Add phone fields if they exist
                if (isset($value['phone_brand'])) {
                    $transactionItems->phone_brand = $value['phone_brand'];
                }
                if (isset($value['phone_type'])) {
                    $transactionItems->phone_type = $value['phone_type'];
                }
                if (isset($value['phone_color'])) {
                    $transactionItems->phone_color = $value['phone_color'];
                }
                if (isset($value['phone_imei'])) {
                    $transactionItems->phone_imei = $value['phone_imei'];
                }
                if (isset($value['phone_internal'])) {
                    $transactionItems->phone_internal = $value['phone_internal'];
                }
                
                $transactionItems->save();
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'successfully store transaction'
        ], 201);
    }
    public function listTransaction()
    {
        $data = Transaction::orderby('id', 'ASC')->get();
        if (count($data) != 0) {
            return response()->json([
                'status' => 'success',
                'message' => 'successfully get list transaction',
                'data' => $data
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'no data found on our record',
            ], 404);
        }
    }
    public function latesTransaction()
    {
        $data = Transaction::orderby('id', 'DESC')->first();
        if ($data) {
            return response()->json([
                'status' => 'success',
                'message' => 'successfully get list transaction',
                'data' => $data
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'no data found on our record',
            ], 404);
        }
    }
    public function deleteTransaction($id)
    {
        $data = Transaction::fid($id)->delete();
        if ($data) {
            return response()->json([
                'status' => 'success',
                'message' => 'successfully delete transaction',
                'data' => $data
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'no data found on our record',
            ], 404);
        }
    }

    public function reportingTransaction(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $limit = $request->input('limit');

        $query = Transaction::query();
        if ($startDate) {
            $query->where('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->where('created_at', '<=', $endDate);
        }
        if ($limit) {
            $query->limit($limit);
        }
        $data = $query->get();

        return response()->json([
            'status' => 'success',
            'message' => 'successfully get list transaction',
            'data' => $data
        ], 200);
    }



    private function getPerhitungan($technical_id, $biaya, $modal)
    {
        if ($technical_id != null) {
            $tecnician = Technician::findOrFail($technical_id);
            $percentModal = $tecnician->percent_fee;
            $percentUntung = 100 - $percentModal;
            $countModal = $biaya * $percentModal / 100;
            $countUntung = $biaya * $percentUntung / 100;
            return [
                'fee_teknisi' => $countModal,
                'modal' => $countModal,
                'untung' => $countUntung
            ];
        } else {
            return [
                'fee_teknisi' => 0,
                'modal' => ($modal != null) ? $modal : 0,
                'untung' => $biaya - $modal
            ];
        }
    }

    public function receipt()
    {
        $pdf = Pdf::loadView('pdf.receipt', ['date' => Carbon::now()->format('d M Y')])->setPaper([595.28, 420.945], 'landscape');
        return $pdf->stream('receipt.pdf');
    }
}
