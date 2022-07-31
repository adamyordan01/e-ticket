<?php

namespace App\Http\Controllers;

use App\Models\Check;
use App\Models\TransactionDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CheckController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Check::class);
        
        return view('check.index');
    }

    public function show($id)
    {
        $detail = TransactionDetail::with('product')->where('barcode', $id)->first();
        // dd($detail);
        // $today = date('Y-m-d');
        // $date_event = Carbon::parse($detail->product->date_event)->format('Y-m-d');
        

        if ($detail) {
            $today = date('Y-m-d');
            $date_event = Carbon::parse($detail->product->date_event)->format('Y-m-d');
            if ($today == $date_event) {
                // check if date_event is today
                // dd('date_event is today');
                $status = 1;
                return view('check.show', compact('detail', 'status'));
            } else if ($today < $date_event) {
                // check if date_event is in the future
                // dd('date_event is in the future');
                $status = 2;
                return view('check.show', compact('detail', 'status'));
            } else if ($today > $date_event) {
                // check if date_event is in the past
                // dd('date_event is in the past');
                $status = 3;
                return view('check.show', compact('detail', 'status'));
            }

        } else {
            // dd('barcode not found');
            $status = 4;
            return view('check.show', compact('status'));
        }
    }
}
