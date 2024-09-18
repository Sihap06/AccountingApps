<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;

class PrintReceiptController extends Controller
{
    public function printReceipt()
    {
        try {
            // For Windows printer setup
            // $connector = new WindowsPrintConnector("EPSON_LX_310"); // Name of the printer

            // For network printer setup
            // $connector = new NetworkPrintConnector("192.168.0.123", 9100);

            // For USB or parallel port (Linux)
            $connector = new FilePrintConnector("USB001");

            dd($connector);

            $printer = new Printer($connector);

            // Printing a basic text
            $printer->text("Hello World!\n");
            $printer->feed(2); // Move down 2 lines
            $printer->cut();   // Cut the paper

            $printer->close();
        } catch (Exception $e) {
            // Handle the exception
            echo "Couldn't print to this printer: " . $e->getMessage();
        }
    }
}
