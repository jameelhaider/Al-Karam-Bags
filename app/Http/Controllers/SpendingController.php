<?php

namespace App\Http\Controllers;

use App\Models\Accounts;
use App\Models\Spending;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;

class SpendingController extends Controller
{
    public function index(Request $request)
    {
        if (!Gate::allows('is_admin')) {
            abort(401);
        }
        $query = Spending::query();
        if ($request->date) {
            $query->whereDate('date', $request->date);
        }
        if ($request->month) {
            $query->where('month', $request->month);
        }
        if ($request->day) {
            $query->where('day', $request->day);
        }
        $spendings = $query->orderBy('date', 'desc')->get();
        $grouped = $spendings->groupBy('date');
        $totalSpend = $spendings->sum('amount');
        $monthlyTotals = $spendings->groupBy('month')->map(function ($items) {
            return $items->sum('amount');
        });
        return view('spendings.index', compact(
            'grouped',
            'totalSpend',
            'monthlyTotals'
        ));
    }







     public function delete($id)
    {
        if (!Gate::allows('is_admin')) {
            abort(401, 'Unauthorized access.');
        }

        try {
            $spending = Spending::findOrFail($id);
            $spending->delete();
            return redirect()
                ->route('index.spendings')
                ->with('success', 'Spend Record Deleted Successfully!');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Spend record not found.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong while deleting the record.');
        }
    }




    public function create()
    {
        if (Gate::allows('is_admin')) {
            $spending = new Spending();
            return view("spendings.create", compact('spending'));
        } else {
            return abort(401);
        }
    }


    public function edit($id)
    {
        if (Gate::allows('is_admin')) {
            $spending = Spending::find($id);
            if (!$spending)
                return redirect()->back();
            return view("spendings.create", compact('spending'));
        } else {
            return abort(401);
        }
    }


    public function submit(Request $request)
    {
        if (Gate::allows('is_admin')) {
            $request->validate([
                'title' => ['required', 'string', 'max:255'],
                'date' => ['required', 'date'],
                'amount' => ['nullable', 'numeric', 'min:1'],
                'description' => ['nullable', 'string', 'max:500'],
            ]);

            $spending = new Spending();
            $date = Carbon::parse($request->date);
            $spending->title = strtoupper($request->title);
            $spending->date = $date;
            $spending->amount = $request->amount;
            $spending->day = $date->format('l');
            $spending->month = $date->format('F');
            $spending->description = strtoupper($request->description);
            $spending->save();

            if ($request->action === 'save_add_new') {
                return redirect()->back()->with('success', 'Record Created! You can add another one.');
            }
            return redirect()->route('index.spendings')->with('success', 'Record Created Successfully!');
        } else {
            return abort(401);
        }
    }




    public function update(Request $request, $id)
    {
        if (Gate::allows('is_admin')) {

          $request->validate([
                'title' => ['required', 'string', 'max:255'],
                'date' => ['required', 'date'],
                'amount' => ['nullable', 'numeric', 'min:1'],
                'description' => ['nullable', 'string', 'max:500'],
            ]);

            $spending = Spending::findOrFail($id);
             $date = Carbon::parse($request->date);
            $spending->title = strtoupper($request->title);
            $spending->date = $date;
            $spending->amount = $request->amount;
            $spending->day = $date->format('l');
            $spending->month = $date->format('F');
            $spending->description = strtoupper($request->description);
            $spending->update();

            return redirect()->route('index.spendings')->with('success', 'Record Updated Successfully!');
        } else {
            return abort(401);
        }
    }
}
