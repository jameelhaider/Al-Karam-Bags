<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Demands;
use App\Models\Stocks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class StocksController extends Controller
{
    public function index(Request $request, $type)
    {
        if (Gate::allows('is_admin')) {
            $query = Stocks::query()
                ->select('qty', 'id', 'purchase_price', 'sale_price', 'name', 'alert_qty')
                ->where('type', $type);
            if ($request->name) {
                $query->where('name', 'LIKE', '%' . $request->name . '%');
            }
            if ($request->status == "AVAILABLE") {
                $query->where('qty', '>', 0);
            } elseif ($request->status == "OUT OF STOCK") {
                $query->where('qty', '<=', 0);
            }

            $stocks = $query
                ->orderBy('created_at', 'desc')
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
            return view("stocks.create", compact('stock'));
        } else {
            return abort(401);
        }
    }


    public function edit($type, $id)
    {
        if (Gate::allows('is_admin')) {
            $stock = Stocks::find($id);
            if (!$stock)
                return redirect()->back();
            return view("stocks.create", compact('stock'));
        } else {
            return abort(401);
        }
    }


    public function submit(Request $request)
    {
        if (Gate::allows('is_admin')) {
            $request->validate([
                'alert_qty'       => 'nullable|integer|min:1',
                'type'            => 'required|in:schoolbags,travelbags,handbags,handcarries',
                'purchase_price'  => 'required|integer|min:1',
                'sale_price'      => 'required|integer|min:2',
                'qty'             => 'required|integer|min:0',
                'action'          => 'nullable|in:save,save_add_new',
            ]);
            $stock = new Stocks();
            $stock->alert_qty = $request->alert_qty;
            $stock->purchase_price = $request->purchase_price;
            $stock->name = strtoupper($request->name);
            $stock->sale_price = $request->sale_price;
            $stock->qty = $request->qty;
            $stock->type = $request->type;
            $stock->save();
            if ($request->action === 'save_add_new') {
                return redirect()->back()->with('success', 'Stock Added! You can add another one.');
            }
            return redirect()->route('stock.index', ['type' => $request->type])->with('success', 'Stock Added Successfully!');
        } else {
            return abort(401);
        }
    }


    public function update(Request $request, $id)
    {
        if (Gate::allows('is_admin')) {
            $request->validate([
                'alert_qty'       => 'nullable|integer|min:1',
                'type'            => 'required|in:schoolbags,travelbags,handbags,handcarries',
                'purchase_price'  => 'required|integer|min:0',
                'sale_price'      => 'required|integer|min:0',
                'qty'             => 'required|integer|min:0',
            ]);
            $stock = Stocks::find($id);
            $stock->alert_qty = $request->alert_qty;
            $stock->purchase_price = $request->purchase_price;
            $stock->name = strtoupper($request->name);
            $stock->sale_price = $request->sale_price;
            $stock->qty = $request->qty;
            $stock->save();
            return redirect()->route('stock.index', ['type' => $request->type])->with('success', 'Stock updated successfully!');
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


      public function addtodemand(Request $request)
    {
        if (Gate::allows('is_admin')) {
            $request->validate([
                'stock_id' => 'required|exists:stocks,id',
                'qty' => 'nullable|numeric|min:1',
            ]);
            $stock = Stocks::find($request->stock_id);
            $demand = new Demands();
            $demand->qty = $request->qty ?? null;
            $demand->type = $request->type;
            $demand->name = strtoupper($stock->name);
            $demand->save();
            return redirect()->back()->with('success', 'Added to demand successfully');
        } else {
            return abort(401);
        }
    }



    public function stockstats(Request $request, $type)
    {
        if (!Gate::allows('is_admin')) {
            return abort(401);
        }

        if (!in_array($type, ['schoolbags', 'handcarries', 'handbags', 'travelbags'])) {
            return redirect()->back()->with('error', 'Invalid type');
        }

        $limit = $request->input('limit', 10);

        $query = DB::table('invoice_items')
            ->join('stocks', 'invoice_items.stock_id', '=', 'stocks.id')
            ->select(
                'stocks.id as stock_id',
                'stocks.name',
                DB::raw('SUM(invoice_items.qty) as total_sold')
            )
            ->where('stocks.type', $type);
        $topSelling = $query
            ->groupBy(
                'stocks.id',
                'stocks.name',
            )
            ->orderByDesc('total_sold')
            ->limit($limit)
            ->get();
           if($type=='schoolbags'){
 $tit='School Bags';
            }elseif($type=='handcarries'){
 $tit='Hand Carries';
            }
            elseif($type=='travelbags'){
 $tit='Tarvel Bags';
            }
             elseif($type=='handbags'){
 $tit='Hand Bags';
            }
            $title2 = "Top {$limit} Selling {$tit}";
        return view('stocks.sellingstats', compact('topSelling', 'limit', 'title2'));
    }
}
