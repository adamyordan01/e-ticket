<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\TempTransaction;
use Illuminate\Support\Facades\Auth;

class TempTransactionController extends Controller
{
    public function index()
    {
        $tempTransactions = TempTransaction::where('user_id', Auth::id())->get();
        
        // return view('cashier.index', [
        //     'tempTransactions' => $tempTransactions
        // ]);
    }

    public function create()
    {
        $user = Auth::id();
        $tempTransactions = TempTransaction::where('user_id', $user)->get();
        // dd($tempTransactions);
        return view('cashier.dataTotal', compact('tempTransactions'));
    }

    public function store(Request $request)
    {
        $user = Auth::id();
        $product = Product::where('id', $request->product_id)->first();

        $cek = TempTransaction::where('product_id', $request->product_id)->first();
        // dd($cek);
        if ($cek) {
            $cek->update([
                'quantity' => $cek->quantity + 1,
                'total_price' => ($cek->quantity + 1) * $product->price,
            ]);
        } else {
            $tempTransaction = TempTransaction::firstOrCreate([
                'user_id' => $user,
                'product_id' => $request->product_id,
                'quantity' => 1,
                'total_price' => $product->price
            ]);
        }

        return response()->json('Success');
    }

    public function update(Request $request, $id)
    {
        $data = TempTransaction::where('product_id', $id)->first();
        $product = Product::where('id', $id)->first();
        if ($request->status == 'plus'){
            $data->update([
                'quantity' => $data->quantity + 1,
                'total_price' => ($data->quantity + 1) * $product->price,
            ]);
        } else if ($request->status == 'input') {
            $data->update([
                'quantity' => $request->qty,
                'total_price' => $request->qty * $product->price,
            ]);
        } else {
            $data->update([
                'quantity' => $data->quantity - 1,
                'total_price' => ($data->quantity - 1) * $product->price,
            ]);
        }
    }

    public function destroy(Request $request)
    {
        $user = Auth::id();
        $tempTransaction = TempTransaction::where('product_id', $request->product_id)->first();
        $tempTransaction->delete();
    }
}
