<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Expenses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ExpensesController extends Controller
{
    public function submit(Request $request)
    {
        if (Gate::allows('is_admin')) {
            $expense = new Expenses();
            $expense->name = $request->name;
            $expense->expense_type = $request->expense_type;
            $expense->ammount = $request->ammount;
            $expense->month_id = $request->closemonth_id;
            $expense->save();
            return redirect()->back()->with('success', 'Expense Added Successfully!');
        } else {
            return abort(401);
        }

    }


    public function view($month_id)
    {
        if (Gate::allows('is_admin')) {
            $month=DB::table('close_months')->where('id',$month_id)
            ->select('month as month')
            ->first();
            $expenses = DB::table('expenses')->where('month_id', $month_id)->get();
            $totalAmount = $expenses->sum('ammount');
            return view('expenses.view', compact('expenses','totalAmount','month'));

        } else {
            return abort(401);
        }
    }

    public function delete($id)
    {
        if (Gate::allows('is_admin')) {
            $expense = Expenses::find($id);
            if (!is_null($expense)) {
                $expense->delete();
            }
            return redirect()->back()->with('success', 'Expense Deleted Successfully');
        } else {
            return abort(401);
        }

    }


    public function stats(Request $request)
    {
        if (Gate::allows('is_admin')) {
            $closed = DB::table('close_months')->where('month', $request->month)->first();

            if ($request->month) {
                if ($closed) {
                    $date = Carbon::createFromFormat('Y-m', $request->month);
                    $year = $date->year;
                    $monthNumber = $date->month;
                    $sales = DB::table('invoices')
                        ->whereYear('created_at', $year)
                        ->whereMonth('created_at', $monthNumber)
                        ->select('invoices.id as id','invoices.invoice_id as invoice_id','invoices.profit as profit')
                        ->get();
                    $salesData = $sales->groupBy('invoice_id')->map(function ($group) {
                        return $group->sum('profit');
                    });
                    $countsales = $sales->count();
                    $expenses = DB::table('expenses')
                        ->join('close_months', 'expenses.month_id', 'close_months.id')
                        ->where('close_months.month', $request->month)
                        ->get();
                    $countexpenses = DB::table('expenses')
                        ->join('close_months', 'expenses.month_id', 'close_months.id')
                        ->where('close_months.month', $request->month)
                        ->count();
                    $totalExpenses = DB::table('expenses')
                        ->join('close_months', 'expenses.month_id', 'close_months.id')
                        ->where('close_months.month', $request->month)
                        ->sum('expenses.ammount');
                    $totalSalesRevenue = $salesData->sum();
                    $totalProfit = $totalSalesRevenue - $totalExpenses;
                    $totalSalesTransactions = $countsales;
                    $averageSaleValue = $countsales > 0 ? $totalSalesRevenue / $countsales : 0;
                } else {
                    return redirect()->route('stats.profit')->with('error', 'This Month Is Not Closed Yet.');
                }
            } else {
                // Default to null if no month is selected
                $sales = null;
                $expenses = null;
                $countsales = null;
                $countexpenses = null;
                $totalSalesRevenue = null;
                $totalExpenses = null;
                $totalProfit = null;
                $averageSaleValue = null;
                $totalSalesTransactions = null;
            }

            return view('expenses.stats', compact(
                'sales',
                'expenses',
                'countsales',
                'countexpenses',
                'totalSalesRevenue',
                'totalExpenses',
                'totalProfit',
                'totalSalesTransactions',
                'averageSaleValue'
            ));
        } else {
            return abort(401);
        }
    }



}
