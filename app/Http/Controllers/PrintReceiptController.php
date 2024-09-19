<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class PrintReceiptController extends Controller
{
    public function printReceipt()
    {
        $printData = [
            'content' => 'This is the content to print'
        ];

        // Send the print data to the local Express server
        $response = Http::post('http://0.0.0.0:3000', $printData);

        if ($response->successful()) {
            return response()->json(['message' => 'Print data sent successfully']);
        }

        return response()->json(['message' => 'Failed to send print data'], 500);
    }
}
