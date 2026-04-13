<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request) {
        $query = Transaction::query();
        
        if($request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        $transactions = $query->latest()->get();
        $total_revenue = $transactions->sum('total_paid');

        return view('owner.reports.index', compact('transactions', 'total_revenue'));
    }
}