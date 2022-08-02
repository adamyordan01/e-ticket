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
        $role = Auth::user()->role->name;

        if ($role == 'admin') {
            $transactions = DB::select("
                SELECT
                    transactions.id,
                    transactions.total_amount,
                    transactions.grand_total,
                    transactions.tax,
                    transactions.total_paid,
                    transactions.total_return,
                    transactions.transaction_date,
                    transactions.invoice_number 
                FROM
                    transactions
                    INNER JOIN users ON transactions.user_id = users.id
                ORDER BY
                    transactions.invoice_number DESC
            ");
        } else {
            $transactions = DB::select("
                SELECT
                    transactions.id,
                    transactions.total_amount,
                    transactions.grand_total,
                    transactions.tax,
                    transactions.total_paid,
                    transactions.total_return,
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
        }



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
            ->editColumn('total_amount', function ($transaction) {
                return 'Rp. ' . number_format($transaction->total_amount, 0, ',', '.');
            })
            ->editColumn('grand_total', function ($transaction) {
                return 'Rp. ' . number_format($transaction->grand_total, 0, ',', '.');
            })
            ->editColumn('tax', function ($transaction) {
                return 'Rp. ' . number_format($transaction->tax, 0, ',', '.');
            })
            ->editColumn('total_paid', function ($transaction) {
                return 'Rp. ' . number_format($transaction->total_paid, 0, ',', '.');
            })
            ->editColumn('total_return', function ($transaction) {
                return 'Rp. ' . number_format($transaction->total_return, 0, ',', '.');
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
