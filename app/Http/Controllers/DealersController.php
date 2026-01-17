<?php

namespace App\Http\Controllers;

use App\Models\Dealers;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class DealersController extends Controller
{
    public function index(Request $request)
    {
        if (Gate::allows('is_admin')) {
            $query = Dealers::query();

            if ($request->name) {
                $query->where('name', 'LIKE', '%' . $request->name . '%');
            }
             if ($request->bussiness_name) {
                $query->where('bussiness_name', 'LIKE', '%' . $request->bussiness_name . '%');
            }
            if ($request->phone) {
                $query->where('phone', 'LIKE', '%' . $request->phone . '%');
            }
            $dealers = $query
                ->orderBy('created_at', 'desc')
                ->paginate(100);
            return view('dealers.index', compact('dealers'));
        } else {
            return abort(401);
        }
    }


    public function create()
    {
        if (Gate::allows('is_admin')) {
            $dealer = new Dealers();
            return view("dealers.create", compact('dealer'));
        } else {
            return abort(401);
        }

    }


    public function edit($id)
    {
        if (Gate::allows('is_admin')) {
            $dealer = Dealers::find($id);
            if (!$dealer)
                return redirect()->back();
            return view("dealers.create", compact('dealer'));
        } else {
            return abort(401);
        }

    }


    public function submit(Request $request)
    {
        if (Gate::allows('is_admin')) {
            $dealer = new Dealers();
            $dealer->name = $request->name;
            $dealer->phone = $request->phone;
            $dealer->bussiness_name = $request->bussiness_name;
            $dealer->address = $request->address;
            $dealer->save();
            if ($request->action === 'save_add_new') {
                return redirect()->back()->with('success', 'Dealer Added! You can add another one.');
            }
            return redirect()->route('index.dealer')->with('success', 'Dealer Added Successfully!');
        } else {
            return abort(401);
        }

    }



    public function update(Request $request, $id)
    {
        if (Gate::allows('is_admin')) {
            $dealer = Dealers::find($id);
            $dealer->name = $request->name;
            $dealer->phone = $request->phone;
            $dealer->bussiness_name = $request->bussiness_name;
            $dealer->address = $request->address;
            $dealer->update();
            return redirect()->route('index.dealer')->with('success', 'Dealer Updated SUccessfully!');
        } else {
            return abort(401);
        }

    }




    public function delete($id)
    {
        if (Gate::allows('is_admin')) {
            $dealer = Dealers::find($id);
            if (!is_null($dealer)) {
                $dealer->delete();
            }
            return redirect()->back()->with('success', 'Dealer Deleted Successfully');
        } else {
            return abort(401);
        }

    }


    public function downloadpdf()
    {
        if (Gate::allows('is_admin')) {
            $dealers = DB::table('dealers')
                ->orderBy('created_at', 'desc')
                ->get();
            $pdf = Pdf::loadView('pdf.dealers', ['dealers' => $dealers]);
            return $pdf->download('dealers.pdf');
        } else {
            return abort(401);
        }
    }

}
