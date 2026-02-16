<?php

namespace App\Http\Controllers;

use App\Models\Accounts;
use App\Models\CashReceived;
use App\Models\Invoices;
use App\Models\ReturnInvoices;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class AccountsController extends Controller
{
    public function index(Request $request)
    {
        if (Gate::allows('is_admin')) {
            $query = Accounts::query();

            if ($request->name) {
                $query->where('customer_name', 'LIKE', '%' . $request->name . '%');
            }
            if ($request->phone) {
                $query->where('customer_phone', $request->phone);
            }
            if ($request->status) {
                if ($request->status == 'Clear') {
                    $query->where('prev_balance', 0);
                } elseif ($request->status == 'Remainings') {
                    $query->where('prev_balance', '>', 0);
                } elseif ($request->status == 'Credit') {
                    $query->where('prev_balance', '<', 0);
                }
            }
            if ($request->acc_id) {
                $query->where('id', $request->acc_id);
            }
            $remBalanceQuery = clone $query;
            $totalRem = $remBalanceQuery->where('prev_balance', '>', 0)->sum('prev_balance');
            $accounts = $query->orderBy('id', 'asc')->paginate(100);
            return view('accounts.index', compact('accounts', 'totalRem'));
        } else {
            return abort(401);
        }
    }

    public function viewpurchase(Request $request, $id)
    {
        if (Gate::allows('is_admin')) {
            $account = DB::table('accounts')->where('id', $id)->first();
            $query = DB::table('invoices')
                ->join('invoice_items', 'invoice_items.invoice_id', 'invoices.id')
                ->where('invoices.account_id', $account->id)
                ->select(
                    'invoice_items.name as name',
                    'invoice_items.stock_id as item_stock_id',
                    'invoice_items.qty as qty',
                    'invoice_items.id as item_id',
                    'invoice_items.final_price as price',
                    'invoice_items.total as total',
                    'invoice_items.created_at as date',
                    'invoice_items.status as status',
                    'invoice_items.partial_qty as partial_qty',
                    'invoices.id as invoice_no'
                )
                ->where('invoice_items.status', '!=', 'Returned');
            if ($request->filled('name')) {
                $query->where('invoice_items.name', 'like', '%' . $request->name . '%');
            }
            if ($request->filled('month')) {
                $query->whereRaw("DATE_FORMAT(invoice_items.created_at, '%Y-%m') = ?", [$request->month]);
            }
            if ($request->filled('date')) {
                $query->whereDate('invoice_items.created_at', $request->date);
            }
            $items = $query->orderBy('invoice_items.created_at', 'desc')
                ->paginate(500);

            return view('accounts.purchasehistory', compact('account', 'items'));
        } else {
            return abort(401);
        }
    }



    public function salehistory(Request $request)
    {
        if (!Gate::allows('is_admin')) {
            return abort(401);
        }

        $query = DB::table('invoice_items')
            ->join('invoices', 'invoice_items.invoice_id', 'invoices.id')
            ->select(
                'invoices.invoice_to as invoice_to',
                'invoices.id as invoice_no',
                'invoices.account_id as account_id',
                'invoice_items.name as item_name',
                'invoice_items.qty as item_qty',
                'invoice_items.id as item_id',
                'invoice_items.stock_id as item_stock_id',
                'invoice_items.final_price as item_price',
                'invoice_items.total as item_total',
                'invoice_items.created_at as sold_date',
                'invoice_items.status as status',
                'invoice_items.partial_qty as partial_qty'
            )
            ->where('invoice_items.status', '!=', 'Returned');
        if ($request->filled('month')) {
            $query->whereRaw("DATE_FORMAT(invoice_items.created_at, '%Y-%m') = ?", [$request->month]);
        }
        if ($request->filled('date')) {
            $query->whereDate('invoice_items.created_at', $request->date);
        }
        if ($request->name) {
            $query->where('invoice_items.name', 'LIKE', '%' . $request->name . '%');
        }
        if ($request->acc_id) {
            $query->where('invoices.account_id', $request->acc_id);
        }

        $items = $query->orderByDesc('invoice_items.created_at')->paginate(1000);
        $accounts = DB::table('accounts')->orderBy('created_at', 'asc')->get();

        return view('accounts.salehistory', compact('items', 'accounts'));
    }

    public function returnhistory(Request $request)
    {
        if (!Gate::allows('is_admin')) {
            return abort(401);
        }
        $query = DB::table('return_items');
        if ($request->has('date') && !empty($request->date)) {
            $query->whereDate('created_at', $request->date);
        }
        if ($request->name) {
            $query->where('item_name', 'LIKE', '%' . $request->name . '%');
        }
        if ($request->has('month') && !empty($request->month)) {
            $query->whereRaw("DATE_FORMAT(created_at, '%Y-%m') = ?", [$request->month]);
        }
        if ($request->has('acc_id') && !empty($request->acc_id)) {
            $query->where('acc_id', $request->acc_id);
        }
        $query->orderBy('created_at', 'desc');
        $items = $query->paginate(1000);
        $accounts = DB::table('accounts')
            ->select('customer_name', 'id')
            ->get();
        return view('accounts.returnhistory', compact('items', 'accounts'));
    }






    public function create()
    {
        if (Gate::allows('is_admin')) {
            $account = new Accounts();
            return view("accounts.create", compact('account'));
        } else {
            return abort(401);
        }
    }


    public function edit($id)
    {
        if (Gate::allows('is_admin')) {
            $account = Accounts::find($id);
            if (!$account)
                return redirect()->back();
            return view("accounts.create", compact('account'));
        } else {
            return abort(401);
        }
    }


    public function submit(Request $request)
    {
        if (Gate::allows('is_admin')) {

            $request->validate([
                'customer_name' => 'required|string|max:255',
                'customer_phone' => 'required|string|max:20|unique:accounts,customer_phone',
                'customer_address' => 'nullable|string|max:500',
            ]);

            $account = new Accounts();
            $account->customer_name = strtoupper($request->customer_name);
            $account->customer_phone = $request->customer_phone;
            $account->customer_address = strtoupper($request->customer_address);
            $account->save();

            if ($request->action === 'save_add_new') {
                return redirect()->back()->with('success', 'Account Created! You can add another one.');
            }
            return redirect()->route('index.account')->with('success', 'Account Created Successfully!');
        } else {
            return abort(401);
        }
    }




    public function update(Request $request, $id)
    {
        if (Gate::allows('is_admin')) {

            $request->validate([
                'customer_name' => 'required|string|max:255',
                'customer_phone' => 'required|string|max:20|unique:accounts,customer_phone,' . $id,
                'customer_address' => 'nullable|string|max:500',
            ]);

            $account = Accounts::findOrFail($id);
            $account->customer_name = strtoupper($request->customer_name);
            $account->customer_phone = $request->customer_phone;
            $account->customer_address = strtoupper($request->customer_address);
            $account->update();

            return redirect()->route('index.account')->with('success', 'Account Updated Successfully!');
        } else {
            return abort(401);
        }
    }



    public function downloadpdf(Request $request)
    {
        if (Gate::allows('is_admin')) {
            $accountId = $request->account_id;
            $status = $request->status;

            $query = DB::table('accounts')->orderBy('created_at', 'asc');

            if ($accountId !== 'All') {
                $query->where('id', $accountId);
            } else {
                if ($status === 'Have Some Remainings') {
                    $query->where('prev_balance', '>', 0);
                } elseif ($status === 'Dont Have Remainings') {
                    $query->where('prev_balance', '<=', 0);
                }
            }
            $accounts = $query->get();
            $pdf = Pdf::loadView('pdf.accounts', ['accounts' => $accounts]);
            return $pdf->download('accounts.pdf');
        } else {
            return abort(401);
        }
    }





    public function ledger($id)
    {

        if (Gate::allows('is_admin')) {
            $account = Accounts::findOrFail($id);
            if ($account->id == 1) {
                return redirect()->back()->with('error', 'Ledger For Counter Sale Not Available');
            }
            $invoices = Invoices::where('account_id', $id)->get();
            $returnInvoices = ReturnInvoices::where('account_id', $id)->get();
            $cashReceiveds = CashReceived::where('account_id', $id)->get();
            $ledgerEntries = collect();

            foreach ($invoices as $invoice) {
                $ledgerEntries->push([
                    'date' => $invoice->created_at,
                    'type' => 'Sale Invoice',
                    'id' => $invoice->id,
                    'debit' => $invoice->total_bill,
                    'credit' => 0,
                ]);
            }

            foreach ($returnInvoices as $return) {
                $ledgerEntries->push([
                    'date' => $return->created_at,
                    'type' => 'Return Invoice',
                    'id' => $return->id,
                    'debit' => 0,
                    'credit' => $return->total_return,
                ]);
            }

            foreach ($cashReceiveds as $cash) {
                $ledgerEntries->push([
                    'date' => $cash->created_at,
                    'type' => 'Cash Received',
                    'id' => $cash->id,
                    'debit' => 0,
                    'credit' => $cash->ammount,
                ]);
            }
            $ledgerEntries = $ledgerEntries->sortBy('date')->values();

            return view('accounts.ledger', compact('account', 'ledgerEntries'));
        } else {
            return abort(401);
        }
    }





    public function details($id)
    {
        if (!Gate::allows('is_admin')) {
            return abort(401);
        }

        $account = DB::table('accounts')->where('id', $id)->first();

        if (!$account) {
            return redirect()->back()->with('error', 'Account not found');
        }

        if ($account->id == 1) {
            return redirect()->back()->with('error', 'Details For Counter Sale Not Available');
        }

        $cash = DB::table('cash_receiveds')
            ->where('account_id', $id)
            ->select('id', 'narration', 'ammount', 'customer_name', 'created_at', 'final_rem')
            ->orderBy('created_at', 'desc')
            ->get();

        $saleinvoices = DB::table('invoices')
            ->where('account_id', $id)
            ->select('invoice_id', 'id', 'created_at', 'status', 'total_bill', 'total_items','profit')
            ->orderBy('created_at', 'desc')
            ->get();

        $saleitems = DB::table('invoice_items')
            ->join('invoices', 'invoice_items.invoice_id', 'invoices.id')
            ->select(
                'invoices.id as invoice_no',
                'invoice_items.name as item_name',
                'invoice_items.qty as item_qty',
                'invoice_items.final_price as item_price',
                'invoice_items.total as item_total',
                'invoice_items.created_at as sold_date',
                'invoice_items.stock_id as stock_id',
            )
            ->where('invoice_items.status', '!=', 'Returned')
            ->where('invoices.account_id', $id)
            ->orderBy('invoice_items.created_at', 'desc')
            ->get();

        $returninvoices = DB::table('return_invoices')
            ->where('account_id', $id)
            ->select('invoice_id', 'id', 'total_return', 'total_items', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get();

        $return_items = DB::table('return_items')
            ->where('acc_id', $id)
            ->select('item_name', 'item_price', 'qty', 'total', 'invoice_id', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get();

        $invoices = Invoices::where('account_id', $id)->get();
        $returnInvoices = ReturnInvoices::where('account_id', $id)->get();
        $cashReceiveds = CashReceived::where('account_id', $id)->get();

        $ledgerEntries = collect();

        foreach ($invoices as $invoice) {
            $ledgerEntries->push([
                'date' => $invoice->created_at,
                'type' => 'Sale Invoice',
                'id' => $invoice->id,
                'debit' => $invoice->total_bill,
                'credit' => 0,
            ]);
        }

        foreach ($returnInvoices as $return) {
            $ledgerEntries->push([
                'date' => $return->created_at,
                'type' => 'Return Invoice',
                'id' => $return->id,
                'debit' => 0,
                'credit' => $return->total_return,
            ]);
        }

        foreach ($cashReceiveds as $cashItem) {
            $ledgerEntries->push([
                'date' => $cashItem->created_at,
                'type' => 'Cash Received',
                'id' => $cashItem->id,
                'debit' => 0,
                'credit' => $cashItem->ammount,
            ]);
        }

        $ledgerEntries = $ledgerEntries->sortBy('date')->values();

        return view('accounts.details', compact(
            'account',
            'cash',
            'saleinvoices',
            'returninvoices',
            'saleitems',
            'return_items',
            'ledgerEntries'
        ));
    }
}
