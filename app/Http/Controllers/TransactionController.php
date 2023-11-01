<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    public function postTransaction(Request $request){
        $rules = [
            'service' => 'required|max:255',
            'payment_method' => 'required|max:255',
            'biaya' => 'required|numeric|between:1,99999999999999',
            'modal' => 'numeric|nullable',
            'modal' => 'numeric|nullable',
            'order_transaction' => 'required|numeric',
            'technical_id' => 'numeric|nullable',
            'created_by' => 'required|numeric'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status'=> 'error',
                'message' => 'bad request!',
                'data' => $validator->errors()
            ],400);
        }
        $perhitungan = $this->getPerhitungan($request->get('technical_id'), $request->get('biaya'), $request->get('modal'));
        $data = new Transaction();
        $data->product_id = $request->get('product_id');
        $data->technical_id = $request->get('technical_id');
        $data->service = $request->get('service');
        $data->biaya = $request->get('biaya');
        $data->modal = $perhitungan['modal'];
        $data->fee_teknisi  = $perhitungan['fee_teknisi'];
        $data->untung = $perhitungan['untung'];
        $data->created_by = $request->get('created_by');
        $data->payment_method = $request->get('payment_method');
        $data->save();
        return response()->json([
            'status'=> 'success',
            'message' => 'successfully store transaction',
            'data' => $data
        ],201);
    }
    public function listTransaction(){
        $data = Transaction::orderby('order_transaction', 'DESC')->get();
        if (count($data)!= 0) {
            return response()->json([
                'status'=> 'success',
                'message' => 'successfully get list transaction',
                'data' => $data
            ],200);
        }else{
            return response()->json([
                'status'=> 'error',
                'message' => 'no data found on our record',
            ],404);
        }
    }
    public function latesTransaction(){
        $data = Transaction::orderby('order_transaction', 'DESC')->first();
        if ($data) {
            return response()->json([
                'status'=> 'success',
                'message' => 'successfully get list transaction',
                'data' => $data
            ],200);
        }else{
            return response()->json([
                'status'=> 'error',
                'message' => 'no data found on our record',
            ],404);
        }
    }
    public function deleteTransaction($id){
        $data = Transaction::fid($id)->delete();
        if ($data) {
            return response()->json([
                'status'=> 'success',
                'message' => 'successfully delete transaction',
                'data' => $data
            ],200);
        }else{
            return response()->json([
                'status'=> 'error',
                'message' => 'no data found on our record',
            ],404);
        }
    }

    public function reportingTransaction(Request $request){
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
            'status'=> 'success',
            'message' => 'successfully get list transaction',
            'data' => $data
        ],200);
        
    }



    private function getPerhitungan($technical_id, $biaya, $modal){
        if ($technical_id != null) {
            $countModal = $biaya * 40/100;
            $countUntung = $biaya * 60/100;
            return [
                'fee_teknisi' => $countModal,
                'modal' => $countModal,
                'untung' => $countUntung
            ];
        }else{
            return [
                'fee_teknisi' => 0,
                'modal' => ($modal != null) ? $modal : 0,
                'untung' => $biaya - $modal
            ];
        }
    }
}
