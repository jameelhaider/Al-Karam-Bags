<?php

namespace App\Http\Controllers;

use App\Models\Models;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ModelsController extends Controller
{
    public function index(Request $request, $type)
    {

        if (Gate::allows('is_admin')) {
            $query = Models::query()
                ->select('models.model as model', 'models.company_name as company_name', 'models.id as id');
            if ($request->company_id) {
                $query->whereIn('models.company_id', (array) $request->company_id);
            }
            if ($request->model) {
                $query->where('models.model', 'LIKE', '%' . $request->model . '%');
            }
            $models = $query
                ->orderBy('models.created_at', 'desc')
                ->where('type', $type)
                ->paginate(500);

            $companies = DB::table('companies')->where('type', $type)->get();
            return view('models.index', compact('companies', 'models'));
        } else {
            return abort(401);
        }
    }




    public function create($type)
    {
        if (Gate::allows('is_admin')) {
            $model = new Models();
            $companies = DB::table('companies')->where('type', $type)->get();
            return view("models.create", compact('model', 'companies'));
        } else {
            return abort(401);
        }
    }


    public function edit($type, $id)
    {
        if (Gate::allows('is_admin')) {
            $model = Models::find($id);
            $companies = DB::table('companies')->where('type', $type)->get();
            if (!$model)
                return redirect()->back();
            return view("models.create", compact('model', 'companies'));
        } else {
            return abort(401);
        }
    }


    public function submit(Request $request)
    {
        if (Gate::allows('is_admin')) {
            $request->validate([
                'company_id' => 'required|exists:companies,id',
                'model' => [
                    'required',
                    Rule::unique('models')->where(function ($query) use ($request) {
                        return $query->where('company_id', $request->company_id);
                    }),
                ],
            ], [
                'model.unique' => 'The model name already exists for this company.'
            ]);
            $model = new Models();
            $model->company_id = $request->company_id;
            $company_name = DB::table('companies')
                ->where('id', $request->company_id)
                ->first()->name;
            $model->company_name = $company_name;
            $model->model = $request->model;
            $model->type = $request->type;
            $model->save();

            if ($request->action === 'save_add_new') {
                return redirect()->back()->with('success', 'Model Added! You can add another one.');
            }

            return redirect()->route('index.model', ['type' => $request->type])->with('success', 'Model Added Successfully!');
        } else {
            return abort(401);
        }
    }




    public function update(Request $request, $id)
    {
        if (Gate::allows('is_admin')) {
            $model = Models::findOrFail($id);
            $request->validate([
                'company_id' => 'required|exists:companies,id',
                'model' => [
                    'required',
                    Rule::unique('models')->where(function ($query) use ($request, $id) {
                        return $query->where('company_id', $request->company_id)
                            ->where('id', '!=', $id);
                    }),
                ],
            ], [
                'model.unique' => 'The model name already exists for this company.'
            ]);
            $model->company_id = $request->company_id;
            $company_name = DB::table('companies')
                ->where('id', $request->company_id)
                ->first()->name;
            $model->company_name = $company_name;
            $model->model = $request->model;
            $model->type = $request->type;
            $model->save();
            return redirect()->route('index.model', ['type' => $request->type])->with('success', 'Model Updated Successfully!');
        } else {
            return abort(401);
        }
    }





    public function delete($id)
    {
        if (Gate::allows('is_admin')) {
            $model = Models::find($id);
            if (!is_null($model)) {
                $model->delete();
            }
            return redirect()->back()->with('success', 'Model Deleted Successfully');
        } else {
            return abort(401);
        }
    }
}
