<?php

namespace App\Http\Controllers;

use App\Models\CashReceived;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class CashReceivedController extends Controller
{
    public function index(Request $request)
    {
        if (Gate::allows('is_admin')) {
            $query = CashReceived::query();
            if ($request->account_id) {
                $query->where('account_id', $request->account_id);
            }
            if ($request->date) {
                $query->whereDate('created_at', $request->date);
            }
            if ($request->month) {
                $query->whereRaw("DATE_FORMAT(created_at, '%Y-%m') = ?", [$request->month]);
            }

            $cash = $query
                ->orderBy('created_at', 'desc')
                ->paginate(100);
            $accounts = DB::table('accounts')->orderBy('id', 'asc')->get();

            return view('cashreceived.index', compact('cash', 'accounts'));
        } else {
            return abort(401);
        }
    }



    public function create()
    {
        if (Gate::allows('is_admin')) {
            $cash = new CashReceived();
            $accounts = DB::table('accounts')->orderBy('id', 'asc')->get();
            $cash_receiveds = DB::table('cash_receiveds')
                ->orderBy('id', 'desc')
                ->limit(20)
                ->get();
            return view("cashreceived.create", compact('cash', 'accounts', 'cash_receiveds'));
        } else {
            return abort(401);
        }
    }


    public function edit($id)
    {
        if (!Gate::allows('is_admin')) {
            abort(401, 'Unauthorized access.');
        }
        try {
            $cash = CashReceived::findOrFail($id);
            $accounts = DB::table('accounts')->orderBy('id', 'asc')->get();
            if (now()->diffInHours($cash->created_at) >= 24) {
                return redirect()->route('index.cash')->with('error', 'This record can no longer be edited after 24 hours.');
            }
            return view('cashreceived.create', compact('cash', 'accounts'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Cash record not found.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong while loading the record.');
        }
    }



    public function submit(Request $request)
    {
        if (Gate::allows('is_admin')) {
            $cash = new CashReceived();
            $account = DB::table('accounts')->where('id', $request->account_id)->first();

            if (!$account) {
                return redirect()->back()->with('error', 'Account not found!');
            }

            $cash->customer_name = $account->customer_name;
            $cash->account_id = $request->account_id;
            $cash->narration = $request->narration;
            $cash->ammount = $request->ammount;

            $cash->initial_rem = $account->prev_balance;
            $cash->final_rem = $account->prev_balance - $request->ammount;

            $cash->save();
            DB::table('accounts')
                ->where('id', $request->account_id)
                ->update([
                    'prev_balance' => $account->prev_balance - $request->ammount
                ]);
            if ($request->action === 'save_add_new') {
                return redirect()->back()->with('success', 'Cash Received! You can receive another one.');
            }
            return redirect()->route('index.cash')->with('success', 'Cash Received Successfully!');
        } else {
            return abort(401);
        }
    }




    public function update(Request $request, $id)
    {
        if (Gate::allows('is_admin')) {
            $cash = CashReceived::find($id);
            if ($cash->account_id != $request->account_id) {
                return redirect()->back()->with('error', 'While Edit Account Cant Be Changed, Try Delete Instead!');
            }
            if (!$cash) {
                return redirect()->back()->with('error', 'Cash record not found!');
            }
            $account = DB::table('accounts')->where('id', $request->account_id)->first();
            if (!$account) {
                return redirect()->back()->with('error', 'Account not found!');
            }
            DB::table('accounts')
                ->where('id', $cash->account_id)
                ->update([
                    'prev_balance' => DB::raw("prev_balance + {$cash->ammount}")
                ]);

            $cash->customer_name = $account->customer_name;
            $cash->account_id = $request->account_id;
            $cash->narration = $request->narration;
            $cash->ammount = $request->ammount;


            $cash->initial_rem = $cash->initial_rem;
            $cash->final_rem = $cash->initial_rem - $request->ammount;

            $cash->update();
            DB::table('accounts')
                ->where('id', $request->account_id)
                ->update([
                    'prev_balance' => DB::raw("prev_balance - {$request->ammount}")
                ]);

            return redirect()->route('index.cash')->with('success', 'Cash Received Updated Successfully!');
        } else {
            return abort(401);
        }
    }


    public function delete($id)
    {
        if (!Gate::allows('is_admin')) {
            abort(401, 'Unauthorized access.');
        }

        try {
            $cash = CashReceived::findOrFail($id);
            if (now()->diffInHours($cash->created_at) >= 24) {
                return redirect()->route('index.cash')->with('error', 'This record can no longer be deleted after 24 hours.');
            }
            DB::table('accounts')
                ->where('id', $cash->account_id)
                ->increment('prev_balance', $cash->ammount);
            $cash->delete();
            return redirect()
                ->route('index.cash')
                ->with('success', 'Cash Received Entry Deleted Successfully!');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Cash record not found.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong while deleting the record.');
        }
    }



    public function viewreceipt($id)
    {
        if (Gate::allows('is_admin')) {
            $cash = CashReceived::find($id);
            if (!$cash)
                return redirect()->back();
            return view("cashreceived.receipt", compact('cash'));
        } else {
            return abort(401);
        }
    }





}
