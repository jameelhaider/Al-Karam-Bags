<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Expenses;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
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
            $month = DB::table('close_months')->where('id', $month_id)
                ->select('month as month')
                ->first();
            $expenses = DB::table('expenses')->where('month_id', $month_id)->get();
            $totalAmount = $expenses->sum('ammount');
            return view('expenses.view', compact('expenses', 'totalAmount', 'month'));
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
        if (!Gate::allows('is_admin')) {
            return abort(401);
        }

        $closed = $request->month ? DB::table('close_months')->where('month', $request->month)->first() : null;

        if ($request->month && !$closed) {
            return redirect()->route('stats.profit')->with('error', 'This Month Is Not Closed Yet.');
        }

        if ($request->month && $closed) {
            $date = Carbon::createFromFormat('Y-m', $request->month);
            $year = $date->year;
            $monthNumber = $date->month;

            // --- Monthly Sales ---
            $sales = DB::table('invoices')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $monthNumber)
                ->select('invoices.id as id', 'invoices.invoice_id as invoice_id', 'invoices.profit as profit', 'invoices.total_bill as total_bill', 'invoices.status as status')
                ->get();

            // Only count invoices not returned for total_bill
            $totalSalesAmount = $sales->where('status', '!=', 'returned')->sum('total_bill');

            $salesData = $sales->groupBy('invoice_id')->map(fn($group) => $group->sum('profit'));
            $countsales = $sales->count();

            // --- Monthly Expenses ---
            $expenses = DB::table('expenses')
                ->join('close_months', 'expenses.month_id', 'close_months.id')
                ->where('close_months.month', $request->month)
                ->get();

            $countexpenses = $expenses->count();
            $totalExpenses = $expenses->sum('ammount');

            $totalSalesRevenue = $salesData->sum();
            $totalProfit = $totalSalesRevenue - $totalExpenses;
            $totalSalesTransactions = $countsales;
            $averageSaleValue = $countsales > 0 ? $totalSalesRevenue / $countsales : 0;

            // --- Day-wise Report ---
            $startDate = Carbon::create($year, $monthNumber, 1);
            $endDate = ($year == now()->year && $monthNumber == now()->month)
                ? now()
                : $startDate->copy()->endOfMonth();

            $period = CarbonPeriod::create($startDate, $endDate);
            $dayWiseReport = [];

            foreach ($period as $day) {
                $dailySales = DB::table('invoices')
                    ->whereDate('created_at', $day->toDateString())
                    ->get();
                $dailyRevenue = $dailySales->sum('profit');

                // Total bill excluding returned
                $dailyTotalBill = $dailySales->where('status', '!=', 'returned')->sum('total_bill');

                $dailyExpense = DB::table('expenses')
                    ->join('close_months', 'expenses.month_id', 'close_months.id')
                    ->where('close_months.month', $request->month)
                    ->whereDate('expenses.created_at', $day->toDateString())
                    ->sum('expenses.ammount');

                $profit = $dailyRevenue - $dailyExpense;

                $status = $profit > 0 ? 'In Profit' : ($profit < 0 ? 'In Loss' : 'No Profit No Loss');

                $dayWiseReport[] = [
                    'date'        => $day->format('d M Y'),
                    'day'         => $day->format('l'),
                    'revenue'     => $dailyRevenue,
                    'total_bill'  => $dailyTotalBill, // <-- new field
                    'expense'     => $dailyExpense,
                    'profit'      => $profit,
                    'status'      => $status
                ];
            }
        } else {
            // Default if no month selected
            $sales = null;
            $expenses = null;
            $countsales = null;
            $countexpenses = null;
            $totalSalesRevenue = null;
            $totalExpenses = null;
            $totalProfit = null;
            $totalSalesTransactions = null;
            $averageSaleValue = null;
            $dayWiseReport = [];
            $totalSalesAmount = 0;
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
            'averageSaleValue',
            'dayWiseReport',
            'totalSalesAmount' // <-- pass to view
        ));
    }
}
