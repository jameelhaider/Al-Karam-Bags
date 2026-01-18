<?php

namespace App\Http\Controllers;

use App\Models\Demands;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class DemandsController extends Controller
{
    public function index(Request $request, $type)
    {
        if (Gate::allows('is_admin')) {
            $query = Demands::query()
                ->select('demands.name', 'demands.id', 'demands.qty', 'demands.type')
                ->where('demands.type', $type);
            if ($request->name) {
                $query->where('demands.name', 'LIKE', '%' . $request->name . '%');
            }
            $demands = $query
                ->orderBy('created_at', 'desc')
                ->paginate(100);
            return view('demands.index', compact('demands'));
        } else {
            return abort(401);
        }
    }





    public function create()
    {
        if (Gate::allows('is_admin')) {
            $demand = new Demands();
            return view("demands.create", compact('demand'));
        } else {
            return abort(401);
        }
    }


    public function edit($type, $id)
    {
        if (Gate::allows('is_admin')) {
            $demand = Demands::find($id);
            if (!$demand)
                return redirect()->back();
            return view("demands.create", compact('demand'));
        } else {
            return abort(401);
        }
    }


    public function submit(Request $request)
    {
        if (Gate::allows('is_admin')) {
            $demand = new Demands();
            $demand->type = $request->type;
            $demand->name = strtoupper($request->name);
            $demand->qty = $request->qty ? $request->qty : null;
            $demand->save();
            if ($request->action === 'save_add_new') {
                return redirect()->back()->with('success', 'Demand Added! You can add another one.');
            }
            return redirect()->route('index.demand', ['type' => $request->type])->with('success', 'Demand Added Successfully!');
        } else {
            return abort(401);
        }
    }




    public function update(Request $request, $id)
    {
        if (Gate::allows('is_admin')) {
            $demand = Demands::findOrFail($id);
            $demand->qty = $request->qty ? $request->qty : null;
            $demand->type = $request->type;
            $demand->name = strtoupper($request->name);
            $demand->save();
            return redirect()->route('index.demand', ['type' => $request->type])->with('success', 'Demand Updated Successfully!');
        } else {
            return abort(401);
        }
    }






    public function delete($id)
    {
        if (Gate::allows('is_admin')) {
            $demand = Demands::find($id);
            if (!is_null($demand)) {
                $demand->delete();
            }
            return redirect()->back()->with('success', 'Demand Deleted Successfully');
        } else {
            return abort(401);
        }
    }

    public function deleteSelected(Request $request)
    {
        $ids = $request->input('selected_ids', []);
        if (count($ids) > 0) {
            Demands::whereIn('id', $ids)->delete();
            return redirect()->back()->with('success', 'Selected demands deleted successfully.');
        }
        return redirect()->back()->with('error', 'No demands selected.');
    }


    public function deleteall($type)
    {
        if (Gate::allows('is_admin')) {
            Demands::where('type', $type)->delete();
            return redirect()->back()->with('success', 'All Demands of type "' . $type . '" deleted successfully.');
        } else {
            return abort(401);
        }
    }





  public function downloadpdf(Request $request)
{
    if (Gate::allows('is_admin')) {

        $query = DB::table('demands')
            ->where('type', $request->type);

        if ($request->type === 'schoolbags') {
            $title = 'School Bag Demands';
        } elseif ($request->type === 'handcarries') {
            $title = 'Hand Carry Demands';
        } elseif ($request->type === 'travelbags') {
            $title = 'Travel Bag Demands';
        } else {
            $title = 'Hand Bag Demands';
        }
        $demands = $query->orderBy('created_at', 'desc')->get();
        $pdf = Pdf::loadView('pdf.demands', [
            'demands' => $demands,
            'title'   => $title,
        ]);
        $fileName = str_replace(' ', '_', strtolower($title)) . '.pdf';
        return $pdf->download($fileName);
    }

    return abort(401);
}

}
