<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $income = Transaction::sum('grand_total');
        $product = Product::count();
        
        return view('dashboard', [
            'income' => $income,
            'product' => $product,
        ]);
    }

    public function getIncomeByDay()
    {
        // get income by day
        $incomeByDay = Transaction::selectRaw('DATE(created_at) as date, sum(grand_total) as total')
            ->groupBy('date')
            ->get();
            // dd($incomeByDay);
        $data = [];

        foreach ($incomeByDay as $row) {
            $data['labels'][] = $row->date;
            $data['income'][] = (int) $row->total;
        }

        $data['chart_income_by_day'] = json_encode($data);

        return $data;
    }
}
