<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class TransactionController extends Controller
{
    public function getTransactions()
    {
        $user = Auth::id();

        $transactions = DB::select("
            SELECT
                transactions.id,
                transactions.grand_total,
                transactions.transaction_date,
                transactions.invoice_number 
            FROM
                transactions
                INNER JOIN users ON transactions.user_id = users.id 
            WHERE
                transactions.user_id = '$user'
            ORDER BY
                transactions.invoice_number DESC
        ");

        return DataTables::of($transactions)
            ->addIndexColumn()
            ->addColumn('action', function ($transaction) {
                return '
                    <a href="print/' . $transaction->id . '" data-id="' . $transaction->id . '" class="btn btn-primary btn-print" target="_blank"><i class="fas fa-print"></i> Cetak</a>
                ';
            })
            ->editColumn('transaction_date', function ($transaction) {
                return Carbon::parse($transaction->transaction_date)->format('d-m-Y');
            })
            ->editColumn('grand_total', function ($transaction) {
                return 'Rp. ' . number_format($transaction->grand_total, 0, ',', '.');
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
