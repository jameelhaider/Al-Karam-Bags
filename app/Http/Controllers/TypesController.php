<?php

namespace App\Http\Controllers;

use App\Models\Types;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Gate;

class TypesController extends Controller
{
    public function index(Request $request,$type)
    {
        if (Gate::allows('is_admin')) {
            $query = Types::query()
                ->select('types.name as name', 'types.id as id');
            if ($request->name) {
                $query->where('types.name','LIKE','%'. $request->name .'%');
            }
            $types = $query
                ->orderBy('created_at', 'desc')
                ->where('type',$type)
                ->paginate(100);

            return view('type.index', compact('types'));
        } else {
            return abort(401);
        }
    }





    public function create()
    {
        if (Gate::allows('is_admin')) {
            $type = new Types();
            return view("type.create", compact('type'));
        } else {
            return abort(401);
        }
    }


    public function edit($type,$id)
    {
        if (Gate::allows('is_admin')) {
            $type = Types::find($id);
            if (!$type)
                return redirect()->back();
            return view("type.create", compact('type'));
        } else {
            return abort(401);
        }
    }


    public function submit(Request $request)
    {
        if (Gate::allows('is_admin')) {
            $request->validate([
                'name' => 'required|unique:types,name',
            ], [
                'name.unique' => 'The type name already exists.',
            ]);
            $type = new Types();
            $type->name = $request->name;
            $type->type = $request->type;
            $type->save();

            if ($request->action === 'save_add_new') {
                return redirect()->back()->with('success', 'Type Added! You can add another one.');
            }
            return redirect()->route('index.type',['type'=>$request->type])->with('success', 'Type Added Successfully!');
        } else {
            return abort(401);
        }
    }




    public function update(Request $request, $id)
    {
        if (Gate::allows('is_admin')) {
            $type = Types::findOrFail($id);
            $request->validate([
                'name' => [
                    'required',
                    Rule::unique('types')->ignore($id),
                ],
            ], [
                'name.unique' => 'The type name already exists.',
            ]);
            $type->name = $request->name;
            $type->type = $request->type;
            $type->save();
            return redirect()->route('index.type',['type'=>$request->type])->with('success', 'Type Updated Successfully!');
        } else {
            return abort(401);
        }
    }





    public function delete($id)
    {
        if (Gate::allows('is_admin')) {
            $type = Types::find($id);
            if (!is_null($type)) {
                $type->delete();
            }
            return redirect()->back()->with('success', 'Type Deleted Successfully');
        } else {
            return abort(401);
        }
    }
}
