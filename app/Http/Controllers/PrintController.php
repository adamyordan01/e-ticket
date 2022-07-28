<?php

namespace App\Http\Controllers;

use Mike42\Escpos\Printer;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Mike42\Escpos\EscposImage;
use App\Models\TransactionDetail;
use DateTime;
use Mike42\Escpos\CapabilityProfile;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class PrintController extends Controller
{
    public function print($id)
    {
        // dd($id);
        // $data = Transaction::find($id);
        $data = TransactionDetail::where('transaction_id', $id)->get();
        // dd($data);
        foreach ($data as $transaction) {
            // try {
                // $profile = CapabilityProfile::load("EPSON TM-T81");
                $connector = null;
                $connector = new WindowsPrintConnector("EPSON TM-T81 Receipt");
                $printer = new Printer($connector);
                
                $printer -> setJustification(Printer::JUSTIFY_CENTER);
                $printer -> setTextSize(2, 2);
                $printer -> text('Indothrift Fest');
                // $printer->setUnderline(1);
                $printer -> feed();
                
                // $printer -> setJustification(Printer::JUSTIFY_CENTER);
                // $printer->setUnderline(1);
                // $printer -> feed();
                
                $printer -> setJustification(Printer::JUSTIFY_CENTER);
                $printer -> setTextSize(1, 1);
                $printer -> text(date('l, d-m-Y H:i:s') . "\n");
                $printer -> feed();

                $printer -> setJustification(Printer::JUSTIFY_CENTER);
                $printer -> barcode($transaction->barcode,Printer::BARCODE_CODE39);
                $printer -> feed();
                // $printer -> setBarcodeHeight(80);
                // $printer -> setBarcodeWidth(3);
                // $printer -> setBarcodeTextPosition(Printer::BARCODE_TEXT_BELOW);
                // $printer -> text("Test");

                $printer -> cut();
                $printer -> close();
                
                // return response()->json('Success');
            // } catch (Exception $e) {
            //     //throw $th;
            //     return response()->json($e);
            // }

            // $printer -> close();
        }
        

        return response()->json('Success');
    }

    public function index()
    {

    }
}
