<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // $user = Auth::user()->role->name == 'petugas loket';
        // dd($user);
        if (auth()->user()->role->name == 'admin') {
            $income = Transaction::sum('grand_total');
            $product = Product::count();
            $ticketSold = TransactionDetail::sum('quantity');
            $transaction = Transaction::count();
            return view('dashboard', [
                'income' => $income,
                'product' => $product,
                'ticketSold' => $ticketSold,
                'transaction' => $transaction,
            ]);
        } else if (auth()->user()->role->name == 'petugas loket') {
            $income = Transaction::where('user_id', auth()->user()->id)->sum('grand_total');
            $product = Product::count();
            $ticketSold = TransactionDetail::where('user_id', auth()->user()->id)->sum('quantity');
            $transaction = Transaction::where('user_id', auth()->user()->id)->count();
            return view('dashboard', [
                'income' => $income,
                'product' => $product,
                'ticketSold' => $ticketSold,
                'transaction' => $transaction,
            ]);
        } else {
            return view('dashboard');
        }
        
    }

    public function getIncomeByDay()
    {
        if (auth()->user()->role->name == 'admin') {
            $incomeByDay = Transaction::selectRaw('DATE(created_at) as date, sum(grand_total) as total')
                ->groupBy('date')
                ->get();

                $data = [];

                foreach ($incomeByDay as $row) {
                    $data['labels'][] = $row->date;
                    $data['income'][] = (int) $row->total;
                }

                $data['chart_income_by_day'] = json_encode($data);

                return $data;
            // return response()->json($income);
        } else if (auth()->user()->role->name == 'petugas loket') {
            $incomeByDay = Transaction::where('user_id', auth()->user()->id)
                ->selectRaw('DATE(created_at) as date, sum(grand_total) as total')
                ->groupBy('date')
                ->get();

                $data = [];

                foreach ($incomeByDay as $row) {
                    $data['labels'][] = $row->date;
                    $data['income'][] = (int) $row->total;
                }

                $data['chart_income_by_day'] = json_encode($data);

                return $data;
            // return response()->json($income);
        }
        // get income by day
        // $incomeByDay = Transaction::selectRaw('DATE(created_at) as date, sum(grand_total) as total')
        //     ->groupBy('date')
        //     ->get();
        //     // dd($incomeByDay);
        // $data = [];

        // foreach ($incomeByDay as $row) {
        //     $data['labels'][] = $row->date;
        //     $data['income'][] = (int) $row->total;
        // }

        // $data['chart_income_by_day'] = json_encode($data);

        // return $data;
    }
}
