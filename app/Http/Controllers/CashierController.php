<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\TempTransaction;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CashierController extends Controller
{
    public function index()
    {
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

        // for unique transaction invoice
        $unique = strtotime(date('Y-m-d H:i:s'));
        $random = rand(10000, 99999);
        $invoice = $unique . $random;

        $total = 0;
        $tempTransaction = TempTransaction::where('user_id', $user)->get();
        foreach ($tempTransaction as $key => $value) {
            $total += $value->total_price;
        }
        $tax = ($total) * 11/100;
        $payment = $request->total_paid;
        $change = $payment - ($total + $tax);
        $grandTotal = $total + $tax;

        Transaction::create([
            'user_id' => $user,
            'transaction_date' => date('Y-m-d'),
            'inovice_number' => $invoice,
            'total_amount' => $total,
            'tax' => $tax,
            'grand_total' => $grandTotal,
            'total_paid' => $payment,
            'total_return' => $change
        ]);

        $transaction = Transaction::where('inovice_number', $invoice)->first();
        $tempTransactions = TempTransaction::where('user_id', $user)->get();

        // insert into table transaction detail
        foreach ($tempTransactions as $key => $value) {
            TransactionDetail::create([
                'user_id' => $user,
                'transaction_id' => $transaction->id,
                'product_id' => $value->product_id,
                'quantity' => $value->quantity,
                'total_price' => $value->total_price
            ]);
        }

        

        // foreach ($tempTransactions as $key => $value) {
        //     $product = Product::where('id', $value->product_id)->first();
        //     $transaction->detailTransactions()->create([
        //         'user_id' => $user,
        //         'transaction_id' => $transaction->id,
        //         'product_id' => $value->product_id,
        //         'quantity' => $value->quantity,
        //         'total_price' => $value->total_price
        //     ]);
        // }
        

        // delete all data from temp transaction
        TempTransaction::where('user_id', $user)->delete();

        return response()->json('Success');
    }
}
