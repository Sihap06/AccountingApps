<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class PrintReceiptController extends Controller
{
    public function printReceipt()
    {
        $printData = [
            'order_transaction' => 'INV0001'
        ];

        // Send the print data to the local Express server
        $response = Http::post('http://192.168.1.1:3000/generate-word', $printData);

        dd($response);

        if ($response->successful()) {
            return response()->json(['message' => 'Print data sent successfully']);
        }

        return response()->json(['message' => 'Failed to send print data'], 500);
    }
    public function test()
    {
        return view('pdf.test-print');
    }
}
