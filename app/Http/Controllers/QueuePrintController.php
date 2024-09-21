<?php

namespace App\Http\Controllers;

use App\Models\QueuePrint;
use Illuminate\Http\Request;

class QueuePrintController extends Controller
{
    public function getQueue()
    {
        $data = QueuePrint::where('status', 'proses')->take(1)->get();
        return response()->json($data);
    }

    public function updateStatuQueue($id)
    {
        $data = QueuePrint::findOrFail($id);
        $data->status = 'done';
        $data->save;
        return response()->json(['message' => 'success']);
    }
}
