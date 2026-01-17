<?php

namespace App\Http\Controllers;

use App\Models\CloseMonth;
use App\Models\CloseMonths;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;

class CloseMonthController extends Controller
{
    public function index(Request $request)
    {
        if (Gate::allows('is_admin')) {
            $query = CloseMonths::query()
            ->leftJoin('expenses', 'close_months.id', '=', 'expenses.month_id')
            ->select('close_months.id', 'close_months.month', 'close_months.created_at',
                DB::raw('COUNT(expenses.id) as total_expenses'),
                DB::raw('SUM(expenses.ammount) as total_amount')
            )
            ->groupBy('close_months.id', 'close_months.month', 'close_months.created_at');
            // Filter by month if requested
            if ($request->month) {
                $query->where('close_months.month', $request->month);
            }
            // Order and paginate the results
            $closemonths = $query
                ->orderBy('close_months.month', 'desc')
                ->paginate(100);
    // return $closemonths;
            return view('closemonth.index', compact('closemonths'));
        } else {
            return abort(401);
        }
    }




    public function create()
    {
        if (Gate::allows('is_admin')) {
            $closemonth = new CloseMonths();
            return view("closemonth.create", compact('closemonth'));
        } else {
            return abort(401);
        }

    }



    public function submit(Request $request)
    {
        if (Gate::allows('is_admin')) {
            $exist=DB::table('close_months')->where('month',$request->month)->first();
            if($exist){
                return redirect()->back()->with('error', 'This Month Already Start!');
            }else{
                $closemonth = new CloseMonths();
                $closemonth->month = $request->month;
                $closemonth->save();
                return redirect()->route('index.closemonth')->with('success', 'Month Start Successfully!');
            }
        } else {
            return abort(401);
        }

    }



    public function update(Request $request, $id)
    {
        if (Gate::allows('is_admin')) {
            $closemonth = CloseMonths::find($id);
            $closemonth->month = $request->month;
            $closemonth->update();
            return redirect()->route('index.closemonth')->with('success', 'Month Updated SUccessfully!');
        } else {
            return abort(401);
        }

    }
}
