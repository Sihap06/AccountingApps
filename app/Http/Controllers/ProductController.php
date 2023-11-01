<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function postProduct(Request $request){
        $rules = [
            'name' => 'required|max:255',
            'harga' => 'required|numeric|between:1,99999999999999',
            'stok' => 'required|numeric',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status'=> 'error',
                'message' => 'bad request!',
                'data' => $validator->errors()
            ],400);
        }
        $data = new Product();
        $data->name = $request->get('name');
        $data->harga = $request->get('harga');
        $data->stok = $request->get('stok');
        $data->kode = time();
        $data->save();
        return response()->json([
            'status'=> 'success',
            'message' => 'successfully store product',
            'data' => $data
        ],201);
    }
    public function detailProductlistProduct(){
        $data = Product::paginate(10);
        if (count($data)!= 0) {
            return response()->json([
                'status'=> 'success',
                'message' => 'successfully get list product',
                'data' => $data
            ],200);
        }else{
            return response()->json([
                'status'=> 'error',
                'message' => 'no data found on our record',
            ],404);
        }
    }
    public function deleteProduct($id){
        $data = Product::fid($id)->delete();
        if ($data) {
            return response()->json([
                'status'=> 'success',
                'message' => 'successfully delete product',
                'data' => $data
            ],200);
        }else{
            return response()->json([
                'status'=> 'error',
                'message' => 'no data found on our record',
            ],404);
        }
    }
    public function detailProduct($id){
        $data = Product::find($id);

        if($data){
            return response()->json([
                'status'=> 'success',
                'message' => 'successfully get detail product',
                'data' => $data
            ],200);
        }else{
            return response()->json([
                'status'=> 'error',
                'message' => 'no data found on our record',
            ],404);
        }
    }
}
