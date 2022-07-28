<?php

namespace App\Http\Controllers;

use App\Models\TransactionDetail;
use Illuminate\Http\Request;

class CheckController extends Controller
{
    public function index()
    {
        return view('check.index');
    }

    public function show($id)
    {
        $detail = TransactionDetail::where('barcode', $id)->first();

        if ($detail) {
            $status = $detail->status;
            $detail->update([
                'status' => 1
            ]);
        } else {
            $status = 2;
        }

        return view('check.show', compact('status'));
    }
}
