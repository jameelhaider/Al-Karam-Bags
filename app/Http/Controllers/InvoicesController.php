<?php

namespace App\Http\Controllers;

use App\Models\Stocks;
use App\Models\Invoices;
use App\Models\InvoiceItems;
use App\Models\ReturnInvoices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class InvoicesController extends Controller
{
    public function index(Request $request)
    {
        if (Gate::allows('is_admin')) {
            $query = Invoices::query()
                ->select('invoices.total_bill as total_bill','invoices.customer_name as customer_name', 'invoices.total_items as total_items', 'invoices.id as id', 'invoices.invoice_id as invoice_id', 'invoices.created_at as created_at', 'invoices.profit as profit', 'invoices.invoice_to as invoice_to', 'invoices.account_id as acc_id', 'invoices.status as status');

            if ($request->invoice_id) {
                $query->where('invoices.invoice_id', $request->invoice_id);
            }
            if ($request->invoice_no) {
                $query->where('invoices.id', $request->invoice_no);
            }
            if ($request->account_id) {
                $query->where('invoices.account_id', $request->account_id);
            }
            if ($request->date) {
                $query->whereDate('invoices.created_at', $request->date);
            }

            $invoices = $query
                ->orderBy('invoices.created_at', 'desc')
                ->paginate(500);
            $accounts = DB::table('accounts')
                ->select('customer_name', 'id')
                ->orderBy('id', 'asc')
                ->get();
            // return $accounts;
            return view('invoices.index', compact('invoices', 'accounts'));
        } else {
            return abort(401);
        }
    }



    public function returnindex(Request $request)
    {
        if (Gate::allows('is_admin')) {
            $query = ReturnInvoices::query()
                ->select('return_invoices.total_return as total_return', 'return_invoices.total_items as total_items', 'return_invoices.id as id', 'return_invoices.invoice_id as invoice_id', 'return_invoices.created_at as created_at', 'return_invoices.return_from as return_from', 'return_invoices.account_id as acc_id');

            if ($request->invoice_id) {
                $query->where('return_invoices.invoice_id', $request->invoice_id);
            }
            if ($request->invoice_no) {
                $query->where('return_invoices.id', $request->invoice_no);
            }
            if ($request->account_id) {
                $query->where('return_invoices.account_id', $request->account_id);
            }
            if ($request->date) {
                $query->whereDate('return_invoices.created_at', $request->date);
            }

            $invoices = $query
                ->orderBy('return_invoices.created_at', 'desc')
                ->paginate(500);
            $accounts = DB::table('accounts')
                ->select('customer_name', 'id')
                ->orderBy('id', 'asc')
                ->get();
            return view('invoices.return.index', compact('invoices', 'accounts'));
        } else {
            return abort(401);
        }
    }


    public function view($id)
    {
        if (Gate::allows('is_admin')) {
            $invoice = DB::table('invoices')
                ->where('id', $id)
                ->first();
            if (!$invoice) {
                return redirect()->route('index.invoice');
            }
            $invoice_items = DB::table('invoice_items')
                ->where('invoice_id', $invoice->id)
                ->select(
                    'invoice_items.name as name',
                    'invoice_items.final_price as final_price',
                    'invoice_items.qty as qty',
                    'invoice_items.total as total',
                    'invoice_items.id as item_id',
                    'invoice_items.status as status',
                    'invoice_items.partial_qty as partial_qty'
                )
                ->get();

            // return $invoice_items;
            return view('invoices.view', compact('invoice', 'invoice_items'));
        } else {
            return abort(401);
        }
    }



    public function returnview($id)
    {
        if (Gate::allows('is_admin')) {
            $invoice = DB::table('return_invoices')
                ->where('id', $id)
                ->first();
            if (!$invoice) {
                return redirect()->route('index.return.invoice');
            }
            $return_items = DB::table('return_items')
                ->where('invoice_id', $invoice->id)
                ->select('return_items.item_name as name', 'return_items.return_price as return_price', 'return_items.item_price as sale_price', 'return_items.qty as return_qty', 'return_items.total as total')
                ->get();
            return view('invoices.return.view', compact('invoice', 'return_items'));
        } else {
            return abort(401);
        }
    }





    public function make(Request $request)
    {
        if (Gate::allows('is_admin')) {
            $schoolbagsQuery = DB::table('stocks')
                ->where('type', 'schoolbags')
                ->where('qty','>',0)
                ->select('id', 'name', 'sale_price', 'purchase_price', 'qty');
            $handcarriesQuery = DB::table('stocks')
                ->where('type', 'handcarries')
                ->where('qty','>',0)
                ->select('id', 'name', 'sale_price', 'purchase_price', 'qty');

            $travelbagsQuery = DB::table('stocks')
                ->where('type', 'travelbags')
                ->where('qty','>',0)
                ->select('id', 'name', 'sale_price', 'purchase_price', 'qty');
            $handbagsQuery = DB::table('stocks')
                ->where('type', 'handbags')
                ->where('qty','>',0)
                ->select('id', 'name', 'sale_price', 'purchase_price', 'qty');

            if ($request->has('name')) {
                $searchName = $request->input('name');
                $schoolbagsQuery->where('name', 'LIKE', '%' . $searchName . '%');
                $handcarriesQuery->where('name', 'LIKE', '%' . $searchName . '%');
                $travelbagsQuery->where('name', 'LIKE', '%' . $searchName . '%');
                $handbagsQuery->where('name', 'LIKE', '%' . $searchName . '%');
            }
            $schoolbags = $schoolbagsQuery->orderByDesc('created_at')->get();
            $handcarries = $handcarriesQuery->orderByDesc('created_at')->get();
            $travelbags = $travelbagsQuery->orderByDesc('created_at')->get();
            $handbags = $handbagsQuery->orderByDesc('created_at')->get();

            $accounts = DB::table('accounts')
                ->select('id', 'customer_name', 'prev_balance')
                ->orderBy('id', 'asc')
                ->get();
            return view('invoices.make', compact('schoolbags', 'handcarries', 'travelbags', 'handbags', 'accounts'));
        } else {
            return abort(401);
        }
    }






    public function returninvoice($id, $invoice_to, $acc_id)
    {
        if (!Gate::allows('is_admin')) {
            return abort(401);
        }

        $invoiceItems = InvoiceItems::where('invoice_id', $id)->get();
        $invoice = Invoices::find($id);
        $account = DB::table('accounts')->where('id', $acc_id)->first();

        if (!$invoice) {
            return redirect()->back()->with('error', 'Invoice not found.');
        }
        if ($invoiceItems->isEmpty()) {
            return redirect()->back()->with('error', 'No invoice items found.');
        }
        if (!$account) {
            return redirect()->back()->with('error', 'Account not found.');
        }

        $itemsToReturn = [];
        $totalReturn = 0;
        $totalItemsCount = 0;

        foreach ($invoiceItems as $item) {
            $alreadyReturned = 0;
            if ($item->status === 'Returned') {
                $alreadyReturned = (int)$item->qty;
            } elseif ($item->status === 'Partial Returned') {
                $alreadyReturned = (int)($item->partial_qty ?? 0);
            }
            $returnableQty = (int)$item->qty - $alreadyReturned;
            if ($returnableQty <= 0) {
                continue;
            }
            $itemsToReturn[] = [
                'item' => $item,
                'return_qty' => $returnableQty,
            ];
            $totalReturn += $item->final_price * $returnableQty;
            $totalItemsCount++;
        }

        if (empty($itemsToReturn)) {
            return redirect()->back()->with('error', 'No items available to return for this invoice.');
        }

        DB::beginTransaction();
        try {
            $returnInvoiceId = DB::table('return_invoices')->insertGetId([
                'invoice_id'      => 'CC-' . rand(100000000, 999999999),
                'total_return'    => $totalReturn,
                'total_items'     => $totalItemsCount,
                'return_from'     => $account->customer_name,
                'account_id'      => $account->id,
                'prev_balance'    => $account->prev_balance,
                'current_balance' => $account->prev_balance - $totalReturn,
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);

            foreach ($itemsToReturn as $row) {
                /** @var InvoiceItems $item */
                $item = $row['item'];
                $returnQty = (int)$row['return_qty'];

                $stock = Stocks::find($item->stock_id);
                if (!$stock) {
                    DB::rollBack();
                    return redirect()->back()->with('error', 'Stock not found for stock ID ' . $item->stock_id . '.');
                }
                $stock->qty = ($stock->qty ?? 0) + $returnQty;
                $stock->save();
                DB::table('return_items')->insert([
                    'item_name'    => $item->name,
                    'item_price'   => $item->final_price,
                    'return_price' => $item->final_price,
                    'invoice_id'   => $returnInvoiceId,
                    'qty'          => $returnQty,
                    'total'        => $item->final_price * $returnQty,
                    'item_id'      => $item->stock_id,
                    'invoice_to'   => $invoice_to,
                    'acc_id'       => $acc_id,
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]);
                $currentPartial = (int)($item->partial_qty ?? 0);
                $newPartial = $currentPartial + $returnQty;
                $newStatus = ($newPartial >= (int)$item->qty) ? 'Returned' : 'Partial Returned';

                InvoiceItems::where('id', $item->id)->update([
                    'partial_qty' => $newPartial,
                    'status'      => $newStatus,
                    'updated_at'  => now(),
                ]);
            }
            if ($account->id > 0 && $account->id != '1') {
                DB::table('accounts')
                    ->where('id', $invoice->account_id)
                    ->update([
                        'prev_balance' => $account->prev_balance - $totalReturn,
                    ]);
            }
            $remaining = InvoiceItems::where('invoice_id', $invoice->id)
                ->where('status', '!=', 'Returned')
                ->count();
            $invoice->update([
                'status' => ($remaining === 0) ? 'Returned' : 'Partial Returned',
                'profit' => 0,
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Invoice returned successfully, stock quantities updated, and profit reset to 0.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'An error occurred while returning invoice: ' . $e->getMessage());
        }
    }




    public function returninvoiceitem(Request $request)
    {
        if (!Gate::allows('is_admin')) {
            return abort(401);
        }
        $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'item_id' => 'required|exists:invoice_items,id',
            'action' => 'required|in:Return Complete Item,Return Some Qty',
            'return_qty' => 'required|integer|min:1',
            'return_price' => 'required',
        ]);
        $item = DB::table('invoice_items')->where('id', $request->item_id)->first();
        $invoice = DB::table('invoices')->where('id', $item->invoice_id)->first();
        $stock = DB::table('stocks')->where('id', $item->stock_id)->first();
        $account = DB::table('accounts')->where('id', $invoice->account_id)->first();

        if (!$stock || !$account) {
            return redirect()->back()->with('error', 'Related stock or account record not found.');
        }
        if ($request->return_qty > $item->qty) {
            return redirect()->back()->with('error', 'Return quantity exceeds the sold quantity.');
        }
        if ($item->status == 'Returned') {
            return redirect()->back()->with('error', 'Item Already Returned.');
        }
        DB::table('stocks')
            ->where('id', $item->stock_id)
            ->increment('qty', $request->return_qty);
        if ($request->action === 'Return Complete Item') {

            $returnInvoiceId = DB::table('return_invoices')->insertGetId([
                'invoice_id'  => 'CC-' . rand(100000000, 999999999),
                'total_return' => $request->return_price * $request->return_qty,
                'total_items'        => 1,
                'return_from'      => $account->customer_name,
                'account_id'    => $account->id,
                'prev_balance' => $account->prev_balance,
                'current_balance'     => $account->prev_balance - ($request->return_qty * $request->return_price),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('return_items')->insert([
                'item_name'  => $item->name,
                'item_price' => $item->final_price,
                'return_price' => $request->return_price,
                'invoice_id' => $returnInvoiceId,
                'qty'        => $request->return_qty,
                'total'      => $request->return_price * $request->return_qty,
                'item_id'    => $item->stock_id,
                'invoice_to' => $account->customer_name,
                'acc_id'     => $account->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            DB::table('invoice_items')->where('id', $request->item_id)->update([
                'status' => 'Returned',
            ]);
            $invoice_item =  DB::table('invoice_items')->where('id', $request->item_id)->first();

            if ($invoice->total_items == 1 && $invoice_item->status == 'Returned') {
                DB::table('invoices')->where('id', $request->invoice_id)->update([
                    'status' => 'Returned',
                ]);
            }


            if ($invoice->total_items > 1) {
                $allReturned = DB::table('invoice_items')
                    ->where('invoice_id', $invoice->id)
                    ->where('status', '!=', 'Returned')
                    ->count();

                if ($allReturned == 0) {
                    DB::table('invoices')->where('id', $request->invoice_id)->update([
                        'status' => 'Returned',
                    ]);
                }
            }
            if ($account->id != 1 && $account->id > 1) {
                DB::table('accounts')->where('id', $invoice->account_id)->update([
                    'prev_balance' => $account->prev_balance - ($request->return_price * $request->return_qty),
                ]);
            }
            $profitdeccomp = ($stock->purchase_price - $item->final_price) * $item->qty;
            DB::table('invoices')->where('id', $request->invoice_id)->increment('profit', $profitdeccomp);
            return redirect()->back()->with('success', 'Complete item returned successfully.');
        } else {
            $returnableqty = (int)$item->qty - (int)$item->partial_qty;
            $returnInvoiceId = DB::table('return_invoices')->insertGetId([
                'invoice_id'  => 'CC-' . rand(100000000, 999999999),
                'total_return' => $request->return_price * $request->return_qty,
                'total_items'        => 1,
                'return_from'      => $account->customer_name,
                'account_id'    => $account->id,
                'prev_balance' => $account->prev_balance,
                'current_balance'     => $account->prev_balance - ($request->return_qty * $request->return_price),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('return_items')->insert([
                'item_name'  => $item->name,
                'item_price' => $item->final_price,
                'return_price' => $request->return_price,
                'invoice_id' => $returnInvoiceId,
                'qty'        => $request->return_qty,
                'total'      => $request->return_price * $request->return_qty,
                'item_id'    => $item->stock_id,
                'invoice_to' => $account->customer_name,
                'acc_id'     => $account->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            DB::table('invoice_items')->where('id', $request->item_id)->update([
                'status' => 'Partial Returned',
            ]);
            DB::table('invoice_items')
                ->where('id', $request->item_id)
                ->update([
                    'partial_qty' => DB::raw('COALESCE(partial_qty, 0) + ' . (int) $request->return_qty)
                ]);
            if ($returnableqty == '0') {
                DB::table('invoice_items')->where('id', $request->item_id)->update([
                    'status' => 'Returned',
                ]);
            }
            if ($account->id != 1 && $account->id > 1) {
                DB::table('accounts')->where('id', $invoice->account_id)->update([
                    'prev_balance' => $account->prev_balance - ($request->return_price * $request->return_qty),
                ]);
            }
            $profitdecsome = ($stock->purchase_price - $request->return_price) * $request->return_qty;
            DB::table('invoices')->where('id', $request->invoice_id)->increment('profit', $profitdecsome);
            return redirect()->back()->with('success', 'Partial quantity returned successfully.');
        }
    }







    public function removeitem($id, $invoice_id)
    {
        if (!Gate::allows('is_admin')) {
            return abort(401);
        }
        DB::beginTransaction();
        try {
            $invoice = DB::table('invoices')->find($invoice_id);
            if (!$invoice) {
                return redirect()->back()->with('error', 'Invoice not found');
            }
            $account = DB::table('accounts')->find($invoice->account_id);
            if (!$account) {
                return redirect()->back()->with('error', 'Account not found');
            }
            $invoice_item = DB::table('invoice_items')->find($id);
            if (!$invoice_item) {
                return redirect()->back()->with('error', 'Invoice item not found');
            }
            $stock_item = DB::table('stocks')->find($invoice_item->stock_id);
            if (!$stock_item) {
                return redirect()->back()->with('error', 'Stock item not found');
            }
            if ($invoice->total_items == 1) {
                DB::table('stocks')->where('id', $invoice_item->stock_id)->update([
                    'qty' => DB::raw("qty + {$invoice_item->qty}")
                ]);
                if ($account->id != 1) {
                    DB::table('accounts')->where('id', $invoice->account_id)->update([
                        'prev_balance' => DB::raw("prev_balance - {$invoice_item->total}")
                    ]);
                }
                DB::table('invoice_items')->where('id', $id)->delete();
                DB::table('invoices')->where('id', $invoice_id)->delete();
                DB::commit();
                return redirect()->back()->with('success', 'Last item removed. Invoice deleted successfully.');
            }
            $item_total  = $invoice_item->total;
            $item_profit = ($invoice_item->final_price - $stock_item->purchase_price) * $invoice_item->qty;
            DB::table('invoices')->where('id', $invoice_id)->update([
                'total_bill'  => DB::raw("total_bill - {$item_total}"),
                'total_items' => DB::raw("total_items - 1"),
                'profit'      => DB::raw("profit - {$item_profit}")
            ]);
            if ($account->id != 1) {
                DB::table('accounts')->where('id', $invoice->account_id)->update([
                    'prev_balance' => DB::raw("prev_balance - {$item_total}")
                ]);
            }
            DB::table('stocks')->where('id', $invoice_item->stock_id)->update([
                'qty' => DB::raw("qty + {$invoice_item->qty}")
            ]);
            DB::table('invoice_items')->where('id', $id)->delete();
            DB::commit();
            return redirect()->back()->with('success', 'Item removed and invoice updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error while removing item: ' . $e->getMessage());
        }
    }




    public function save(Request $request)
    {
        if (Gate::allows('is_admin')) {
            $request->validate([
                'account_id'       => 'required|integer|exists:accounts,id',
                'status'           => 'required|in:Paid,Un Paid,Blank',
                'items'            => 'required|array|min:1',
                'customer_name'            => 'nullable',
            ]);

            $account = DB::table('accounts')->where('id', $request->account_id)->first();

            if ($account) {
                $items = collect($request->input('items'));
                $selectedItems = $items->filter(fn($item) => isset($item['selected']));
                if ($selectedItems->isEmpty()) {
                    return redirect()->back()->with('error', 'You must select at least one item to create an invoice.');
                }

                $totalBill = 0;
                $totalProfit = 0;
                $totalItems = 0;

                $invoice = Invoices::create([
                    'total_bill' => 0,
                    'total_items' => 0,
                    'profit' => 0,
                    'customer_name' => strtoupper($request->customer_name),
                    'invoice_to' => $account->customer_name,
                    'account_id' => $account->id,
                    'prev_balance' => $account->prev_balance,
                    'status' => $request->status,
                    'invoice_id' => 'CC-' . rand(100000000, 999999999),
                ]);

                foreach ($selectedItems as $id => $item) {
                    $price = $item['price'];
                    $qty = $item['qty'];
                    $final_price = $item['final_price'];

                    $stock = Stocks::find($id);
                    if (!$stock || $stock->qty < $qty) {
                        return redirect()->back()->with('error', "Not enough stock available for item ID {$id}.");
                    }

                    $purchasePrice = $stock->purchase_price;
                    $total = $final_price * $qty;
                    $profit = ($final_price - $purchasePrice) * $qty;

                    InvoiceItems::create([
                        'invoice_id' => $invoice->id,
                        'name' => $stock->name,
                        'stock_id' => $stock->id,
                        'price' => $price,
                        'qty' => $qty,
                        'final_price' => $final_price,
                        'total' => $total,
                    ]);

                    $stock->update([
                        'qty' => $stock->qty - $qty,
                    ]);

                    $totalBill += $total;
                    $totalProfit += $profit;
                    $totalItems++;
                }

                $invoice->update([
                    'total_bill' => $totalBill,
                    'total_items' => $totalItems,
                    'profit' => $totalProfit,
                ]);

                if ($account->id != 1) {
                    DB::table('accounts')
                        ->where('id', $account->id)
                        ->update([
                            'prev_balance' => $account->prev_balance + $totalBill
                        ]);
                }

                return redirect()->route('invoice.view', ['id' => $invoice->id])
                    ->with('success', 'Invoice Created Successfully!');
            } else {
                return redirect()->back()->with('error', $request->account_id . ' Account ID Does Not Exist');
            }
        } else {
            return abort(401);
        }
    }














}
