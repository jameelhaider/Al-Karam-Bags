<?php

namespace App\Http\Controllers;

use App\Models\Stocks;
use App\Models\Demands;
use App\Models\Accounts;
use App\Models\Invoices;
use App\Models\InvoiceItems;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class StocksController extends Controller
{
    public function index(Request $request, $type)
    {
        if (Gate::allows('is_admin')) {
            $query = Stocks::query()
                ->select('stocks.qty as qty', 'stocks.status as status', 'stocks.id as id', 'stocks.purchase_price as purchase_price', 'stocks.sale_price as sale_price', 'stocks.name as name', 'stocks.alert_qty as alert_qty');
            if ($request->name) {
                $query->where('stocks.name', 'LIKE', '%' . $request->name . '%');
            }
            if ($request->status == "AVAILABLE") {
                $query->where('stocks.qty', '>', 0);
            } elseif ($request->status == "AVAILABLE, LOW STOCK") {
                $query->where('stocks.qty', '>', 0)
                    ->where('stocks.qty', '<=', DB::raw('stocks.alert_qty'));
            } elseif ($request->status == "OUT OF STOCK") {
                $query->where('stocks.qty', '<=', 0);
            }
            $stocks = $query
                ->where('stocks.status', 'Available')
                ->where('stocks.type', $type)
                ->orderBy('stocks.created_at', 'desc')
                ->paginate(1000);
            return view('stocks.index', compact('stocks'));
        } else {
            return abort(401);
        }
    }





    public function view($id)
    {
        if (Gate::allows('is_admin')) {
            $stock = DB::table('stocks')
                ->select('stocks.name as name', 'stocks.id as id')
                ->where('stocks.id', $id)->first();
            return view('stocks.view', compact('stock'));
        } else {
            return abort(401);
        }
    }




    public function create($type)
    {
        if (Gate::allows('is_admin')) {
            $stock = new Stocks();
            $companies = DB::table('companies')->where('type', $type)->get();
            $types = DB::table('types')->where('type', $type)->get();
            $dealers = DB::table('dealers')->get();
            return view("stocks.create", compact('stock', 'companies', 'dealers', 'types'));
        } else {
            return abort(401);
        }
    }


    public function edit($type, $id)
    {
        if (Gate::allows('is_admin')) {
            $stock = Stocks::find($id);
            $companies = DB::table('companies')->where('type', $type)->get();
            $types = DB::table('types')->where('type', $type)->get();
            $dealers = DB::table('dealers')->get();
            if (!$stock)
                return redirect()->back();
            return view("stocks.create", compact('stock', 'companies', 'dealers', 'types'));
        } else {
            return abort(401);
        }
    }


    public function submit(Request $request)
    {
        if (Gate::allows('is_admin')) {
            $request->validate([
                'dealer_id'       => 'required|integer|exists:dealers,id',
                'alert_qty'       => 'nullable|integer|min:1',
                'type'            => 'required|in:parts,tools',
                'type_id'         => 'nullable|integer|exists:types,id',
                'company_id'      => 'required|integer|exists:companies,id',
                'model_id'        => 'required|integer|exists:models,id',
                'name2'           => 'nullable|string|max:255',
                'other_models'    => 'nullable|string|max:255',
                'color'           => 'nullable|string|max:100',
                'quality_status'  => 'nullable|string|max:100',
                'purchase_price'  => 'required|integer|min:0',
                'sale_price'      => 'required|integer|min:0',
                'qty'             => 'required|integer|min:0',
                'action'          => 'nullable|in:save,save_add_new',
            ]);



            $stock = new Stocks();
            $stock->dealer_id = $request->dealer_id;
            $stock->alert_qty = $request->alert_qty;
            //type
            $stock->type = $request->type;
            $stock->type_id = $request->type_id;
            $type_name = DB::table('types')->where('id', $request->type_id)->first()->name;
            $stock->type_name = $type_name;
            //company_id or model_id
            $stock->company_id = $request->company_id;
            $stock->model_id = $request->model_id;
            //company_name or model_name
            $model_name = DB::table('models')->where('id', $request->model_id)->first()->model;
            $company_name = DB::table('companies')->where('id', $request->company_id)->first()->name;
            $stock->model_name = $model_name;
            $stock->company_name = $company_name;
            //name
            if ($request->type == 'parts') {
                $nameComponents = [];

                if ($company_name !== 'No Company') {
                    $nameComponents[] = $company_name;
                }

                if ($model_name !== 'No Model') {
                    $nameComponents[] = $model_name;
                }

                if ($request->other_models !== null) {
                    $nameComponents[] = '( ' . $request->other_models . ' )';
                }

                $nameComponents[] = $type_name;

                if ($request->color !== 'No Color') {
                    $nameComponents[] = $request->color;
                }
                if ($request->quality_status !== 'No Quality') {
                    $nameComponents[] = $request->quality_status;
                }

                $stock->name = implode(' - ', $nameComponents);
                $stock->name2 = $request->other_models;
            } else {
               $nameComponents = [];
                if ($company_name !== 'No Company') {
                    $nameComponents[] = $company_name;
                }
                $nameComponents[] = $type_name;
                if ($model_name !== 'No Model') {
                    $nameComponents[] = $model_name;
                }
                if ($request->name2 !== null) {
                    $nameComponents[] = '( ' . $request->name2 . ' )';
                }
                $stock->name = implode(' - ', $nameComponents);
                $stock->name2 = $request->name2;
            }

            //price
            $stock->purchase_price = $request->purchase_price;
            $stock->l_purchase_price = $request->purchase_price;
            $stock->sale_price = $request->sale_price;
            //qty
            $stock->qty = $request->qty;
            //color or quality_status
            $stock->color = $request->color;
            $stock->quality_status = $request->quality_status;
            //save
            $stock->save();

            if ($request->action === 'save_add_new') {
                return redirect()->back()->with('success', 'Stock Added! You can add another one.');
            }
            return redirect()->route('index.stock', ['type' => $request->type])->with('success', 'Stock Added Successfully!');
        } else {
            return abort(401);
        }
    }


    public function update(Request $request, $id)
    {
        if (Gate::allows('is_admin')) {
            $request->validate([
                'dealer_id'       => 'required|integer|exists:dealers,id',
                'alert_qty'       => 'nullable|integer|min:1',
                'type'            => 'required|in:parts,tools',
                'type_id'         => 'nullable|integer|exists:types,id',
                'company_id'      => 'required|integer|exists:companies,id',
                'model_id'        => 'required|integer|exists:models,id',
                'name2'           => 'nullable|string|max:255',
                'other_models'    => 'nullable|string|max:255',
                'color'           => 'nullable|string|max:100',
                'quality_status'  => 'nullable|string|max:100',
                'purchase_price'  => 'required|integer|min:0',
                'sale_price'      => 'required|integer|min:0',
                'qty'             => 'required|integer|min:0',
                'new_qty'         => 'nullable|integer|min:1',
                'new_purchase_price' => 'nullable|integer|min:0',
            ]);
            $stock = Stocks::find($id);
            $stock->dealer_id = $request->dealer_id;
            $stock->alert_qty = $request->alert_qty;
            //type
            $stock->type = $request->type;
            $stock->type_id = $request->type_id;
            $type_name = DB::table('types')->where('id', $request->type_id)->first()->name;
            $stock->type_name = $type_name;
            //company_id or model_id
            $stock->company_id = $request->company_id;
            $stock->model_id = $request->model_id;
            //company_name or model_name
            $model_name = DB::table('models')->where('id', $request->model_id)->first()->model;
            $company_name = DB::table('companies')->where('id', $request->company_id)->first()->name;
            $stock->model_name = $model_name;
            $stock->company_name = $company_name;
            //name
            if ($request->type == 'parts') {
                $nameComponents = [];
                if ($company_name !== 'No Company') {
                    $nameComponents[] = $company_name;
                }
                if ($model_name !== 'No Model') {
                    $nameComponents[] = $model_name;
                }
                if ($request->other_models !== null) {
                    $nameComponents[] = '( ' . $request->other_models . ' )';
                }
                $nameComponents[] = $type_name;
                if ($request->color !== 'No Color') {
                    $nameComponents[] = $request->color;
                }
                if ($request->quality_status !== 'No Quality') {
                    $nameComponents[] = $request->quality_status;
                }
                $stock->name = implode(' - ', $nameComponents);
                $stock->name2 = $request->other_models;
            } else {
                $nameComponents = [];
                if ($company_name !== 'No Company') {
                    $nameComponents[] = $company_name;
                }
                $nameComponents[] = $type_name;
                if ($model_name !== 'No Model') {
                    $nameComponents[] = $model_name;
                }
                if ($request->name2 !== null) {
                    $nameComponents[] = '( ' . $request->name2 . ' )';
                }
                $stock->name = implode(' - ', $nameComponents);
                $stock->name2 = $request->name2;
            }


            if ($request->new_qty && $request->new_purchase_price) {
                $prevAmount = $request->qty * $request->purchase_price;
                $newAmount = $request->new_qty * $request->new_purchase_price;
                $totalAmount = $prevAmount + $newAmount;
                $totalQty = $request->qty + $request->new_qty;
                $avgPurchasePrice = round($totalAmount / $totalQty);
                $stock->purchase_price = $avgPurchasePrice;
                $stock->qty = $totalQty;

                $stock->l_purchase_price = $request->new_purchase_price;
            } else {
                $stock->purchase_price = round($request->purchase_price);
                $stock->qty = $request->qty;
                $stock->l_purchase_price = $request->purchase_price;
            }
            $stock->sale_price = round($request->sale_price);
            //color or quality_status
            $stock->color = $request->color;
            $stock->quality_status = $request->quality_status;
            $stock->save();

            return redirect()->route('index.stock', ['type' => $request->type])->with('success', 'Stock updated successfully!');
        } else {
            return abort(401);
        }
    }



    public function addtodemand(Request $request)
    {
        // return $request;
        if (Gate::allows('is_admin')) {
            $request->validate([
                'stock_id' => 'required|exists:stocks,id',
                'qty' => 'nullable|numeric|min:1',
            ]);
            $stock = Stocks::find($request->stock_id);
            $demand = new Demands();
            if ($stock->type == 'parts') {
                if ($stock->company_name != 'No Company' && $stock->model_name != 'No Model') {
                    $demand->name = $stock->company_name . ' ' . $stock->model_name;
                } else {
                    $demand->name = $stock->name;
                }
                $demand->item_type = $stock->type_name;
                $demand->item_type_id = $stock->type_id;
                $demand->type = 'parts';
            } else {
                $demand->name = $stock->name;
                $demand->item_type = null;
                $demand->item_type_id = null;
                $demand->type = 'tools';
            }

            $demand->qty = $request->qty ?? null;
            $demand->save();
            return redirect()->back()->with('success', 'Added to demand successfully');
        } else {
            return abort(401);
        }
    }

    public function addtoinvoice(Request $request)
    {
        if (Gate::allows('is_admin')) {
            $stock = Stocks::find($request->stock_id);
            $invoice = Invoices::find($request->invoice_no);
            if (!$invoice) {
                return redirect()->back()->with('error', 'Invoice Not Found');
            }
            if (!$stock) {
                return redirect()->back()->with('error', 'Stock Not Found');
            }
            if ($request->quantity > $stock->qty) {
                return redirect()->back()->with('error', 'Insufficient stock quantity');
            }
            $invoice_item = new InvoiceItems();
            $invoice_item->invoice_id = $invoice->id;
            $invoice_item->name = $stock->name;
            $invoice_item->qty = $request->quantity;
            $invoice_item->price = $stock->sale_price;
            $invoice_item->final_price = $request->final_price;
            $invoice_item->total = $request->final_price * $request->quantity;
            $invoice_item->stock_id = $stock->id;
            $invoice_item->save();

            $account = Accounts::find($invoice->account_id);
            if ($account && $account->id > 1) {
                $account->prev_balance += ($request->final_price * $request->quantity);
                $account->save();
            }
            $stock->qty -= $request->quantity;
            $stock->save();
            $profit_per_item = $request->final_price - $stock->purchase_price;
            $total_profit = $profit_per_item * $request->quantity;
            $invoice->total_bill += $request->final_price * $request->quantity;
            $invoice->total_items += 1;
            $invoice->profit += $total_profit;
            $invoice->save();
            return redirect()->back()->with('success', 'Added to invoice successfully');
        } else {
            return abort(401);
        }
    }






    public function delete($id)
    {
        if (Gate::allows('is_admin')) {
            $stock = Stocks::find($id);
            if (!is_null($stock)) {
                $stock->delete();
            }
            return redirect()->back()->with('success', 'Stock Deleted Successfully');
        } else {
            return abort(401);
        }
    }


    public function downloadpartspdf(Request $request)
    {
        if (!Gate::allows('is_admin')) {
            return abort(401);
        }

        // Handle type selection (if All selected, load all types)
        $selectedTypes = (array) $request->type_id;
        if (in_array('All', $selectedTypes)) {
            $types = DB::table('types')->orderBy('name')->get();
        } else {
            $types = DB::table('types')
                ->whereIn('id', $selectedTypes)
                ->orderByRaw("FIELD(id, " . implode(',', $selectedTypes) . ")") // keep same order as selected
                ->get();
        }

        $groupedStocks = [];

        foreach ($types as $type) {
            $query = DB::table('stocks')
                ->where('type', 'parts')
                ->where('type_id', $type->id)
                ->when($request->company_id && $request->company_id !== 'All', function ($query) use ($request) {
                    return $query->where('company_id', $request->company_id);
                })
                ->when($request->status && $request->status !== 'All', function ($query) use ($request) {
                    if ($request->status === 'Available') {
                        return $query->where('qty', '>', 0);
                    } elseif ($request->status === 'Available, Low Stock') {
                        return $query->where('qty', '>', 0)
                            ->where('qty', '<=', DB::raw('alert_qty'));
                    } elseif ($request->status === 'Out Of Stock') {
                        return $query->where('qty', '<=', 0);
                    }
                })
                ->orderBy('created_at', 'desc');

            $stocks = $query->get();

            $groupedStocks[] = [
                'type_name' => $type->name,
                'stocks'    => $stocks,
            ];
        }

        $data = [
            'groups'     => $groupedStocks,
            'title'      => 'Parts Stock Inventory',
            'show_price' => $request->has('show_price'),
            'show_qty'   => $request->has('show_qty'),
        ];

        $pdf = Pdf::loadView('pdf.stockspdf', $data);
        return $pdf->download('parts.pdf');
    }





    public function downloadtoolspdf(Request $request)
    {
        if (Gate::allows('is_admin')) {
            $query = DB::table('stocks')
                ->when($request->company_id && $request->company_id !== 'All', function ($query) use ($request) {
                    return $query->where('company_id', $request->company_id);
                })
                ->when($request->status && $request->status !== 'All', function ($query) use ($request) {
                    if ($request->status === 'Available') {
                        return $query->where('qty', '>', 0);
                    } elseif ($request->status === 'Available, Low Stock') {
                        return $query->where('qty', '>', 0)
                            ->where('qty', '<=', DB::raw('alert_qty'));
                    } elseif ($request->status === 'Out Of Stock') {
                        return $query->where('qty', '<=', 0);
                    }
                })
                ->orderBy('created_at', 'desc');

            $stocks = $query->where('type', 'tools')->get();
            $data['stocks'] = $stocks;
            $data['title'] = 'Tools Stock Inventory';
            $data['show_price'] = $request->has('show_price');
            $data['show_qty'] = $request->has('show_qty');

            $pdf = Pdf::loadView('pdf.stockspdf', $data);
            return $pdf->download('tools.pdf');
        } else {
            return abort(401);
        }
    }







    public function stockstats(Request $request, $type)
    {
        if (!Gate::allows('is_admin')) {
            return abort(401);
        }

        if (!in_array($type, ['parts', 'tools'])) {
            return redirect()->back()->with('error', 'Invalid type');
        }

        $limit = $request->input('limit', 10);

        $query = DB::table('invoice_items')
            ->join('stocks', 'invoice_items.stock_id', '=', 'stocks.id')
            ->select(
                'stocks.id as stock_id',
                'stocks.company_name',
                'stocks.model_name',
                'stocks.type_name',
                'stocks.type_id',
                'stocks.name',
                DB::raw('SUM(invoice_items.qty) as total_sold')
            )
            ->where('stocks.type', $type);
        if ($request->filled('type_id')) {
            $query->where('stocks.type_id', $request->type_id);
        }
        if ($request->filled('company_id')) {
            $query->where('stocks.company_id', $request->company_id);
        }
        $topSelling = $query
            ->groupBy(
                'stocks.id',
                'stocks.type_id',
                'stocks.name',
                'stocks.company_name',
                'stocks.model_name',
                'stocks.type_name',
            )
            ->orderByDesc('total_sold')
            ->limit($limit)
            ->get();

        $types = DB::table('types')
            ->orderBy('created_at', 'asc')
            ->get();
        $companies = DB::table('companies')
            ->where('type', $type)
            ->orderBy('created_at', 'asc')
            ->get();
        if ($type === 'parts' && $request->filled('type_id')) {
            $selectedType = DB::table('types')->where('id', $request->type_id)->value('name');
            $title2 = "Top {$limit} Selling {$selectedType}";
        } else {
            $title2 = "Top {$limit} Selling " . ucfirst($type);
        }
        return view('stocks.sellingstats', compact('topSelling', 'types', 'limit', 'title2', 'companies'));
    }
}
