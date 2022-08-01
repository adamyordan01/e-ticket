<?php

namespace App\Http\Controllers;

use App\Models\Cashier;
use DateTime;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\TempTransaction;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\Auth;

class CashierController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Cashier::class);

        $products = Product::where('status', 1)->get();
        $tempTransactions = TempTransaction::where('user_id', Auth::id())->get();
        
        return view('cashier.index', [
            'products' => $products,
            'tempTransactions' => $tempTransactions
        ]);
    }

    public function create(Request $request)
    {
        $user = Auth::id();
    }

    public function store(Request $request)
    {
        $user = Auth::id();

        // make invoice ordered
        $lastInvoice = Transaction::select('invoice_number')->orderBy('id', 'desc')->first();

        if (!$lastInvoice) {
            $newInvoice = 'INV-000001';
        } else {
            $lastIncrement = substr($lastInvoice->invoice_number, -6, 6);
            $newIncrement = 'INV-' . str_pad($lastIncrement + 1, 6, 0, STR_PAD_LEFT);
            $newInvoice = $newIncrement;
        }

        $total = 0;
        $tempTransaction = TempTransaction::where('user_id', $user)->get();
        foreach ($tempTransaction as $key => $value) {
            $total += $value->total_price;
        }
        $tax = $request->tax;
        // $tax = ($total) * 11/100;
        $payment = $request->total_paid;
        $change = $payment - ($total + $tax);
        $grandTotal = $total + $tax;

        $transaction = Transaction::create([
            'user_id' => $user,
            'transaction_date' => date('Y-m-d'),
            'invoice_number' => $newInvoice,
            'total_amount' => $total,
            'tax' => $tax,
            'grand_total' => $grandTotal,
            'total_paid' => $payment,
            'total_return' => $change
        ]);

        $transaction = Transaction::where('invoice_number', $newInvoice)->first();
        $tempTransactions = TempTransaction::where('user_id', $user)->get();

        // insert into table transaction detail
        foreach ($tempTransactions as $key => $value) {
            if ($value->quantity >= 2){
                for ($i=0; $i < $value->quantity; $i++) {
                    $now = DateTime::createFromFormat('U.u', microtime(true));
                    // $barcode = $now->format("u");
                    $barcode = time();
                    // $unique = strtotime(date('Y-m-d H:i:s'));
                    // $random = rand(10, 99);
                    // $barcode = $unique . $random;
                    $cariProduct = Product::findOrFail($value->product_id);
                    TransactionDetail::create([
                        'user_id' => $user,
                        'transaction_id' => $transaction->id,
                        'product_id' => $value->product_id,
                        'quantity' => 1,
                        'total_price' => $cariProduct->price,
                        'barcode' => $barcode,
                        'status' => 0
                    ]);
                }
            } else {
                $cariProduct = Product::findOrFail($value->product_id);
                // $unique = strtotime(date('Y-m-d H:i:s'));
                // $random = rand(10, 99);
                // $barcode = $unique . $random;
                $now = DateTime::createFromFormat('U.u', microtime(true));
                // $barcode = $now->format("u");
                $barcode = time();

                TransactionDetail::create([
                    'user_id' => $user,
                    'transaction_id' => $transaction->id,
                    'product_id' => $value->product_id,
                    'quantity' => $value->quantity,
                    'total_price' => $cariProduct->price,
                    'barcode' => $barcode,
                    'status' => 0
                ]);
            }
        }
        
        // delete all data from temp transaction
        TempTransaction::where('user_id', $user)->delete();

        return response()->json($transaction->id);
    }
}
