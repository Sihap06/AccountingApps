<?php

namespace App\Http\Controllers;

use App\Models\Technician;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TechnicianController extends Controller
{
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|max:255',
            'kode' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'bad request!',
                'data' => $validator->errors()
            ], 400);
        }
        $data = new Technician();
        $data->name = $request->get('name');
        $data->harga = $request->get('kode');
        $data->save();
        return response()->json([
            'status' => 'success',
            'message' => 'successfully store product',
            'data' => $data
        ], 201);
    }
}
