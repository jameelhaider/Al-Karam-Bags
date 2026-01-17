<?php

namespace App\Http\Controllers;

use App\Models\Companies;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class CompaniesController extends Controller
{
    public function index(Request $request,$type)
    {
        if (Gate::allows('is_admin')) {
            $query = Companies::query()
                ->select('companies.name as name', 'companies.id as id');
            if ($request->name) {
                $query->where('companies.name','LIKE','%'. $request->name .'%');
            }
            $companies = $query
                ->orderBy('created_at', 'desc')
                ->where('type',$type)
                ->paginate(100);

            return view('companies.index', compact('companies'));
        } else {
            return abort(401);
        }
    }





    public function create()
    {
        if (Gate::allows('is_admin')) {
            $company = new Companies();
            return view("companies.create", compact('company'));
        } else {
            return abort(401);
        }
    }


    public function edit($type,$id)
    {
        if (Gate::allows('is_admin')) {
            $company = Companies::find($id);
            if (!$company)
                return redirect()->back();
            return view("companies.create", compact('company'));
        } else {
            return abort(401);
        }
    }


    public function submit(Request $request)
    {
        if (Gate::allows('is_admin')) {
            $request->validate([
                'name' => [
                    'required',
                    Rule::unique('companies')->where(function ($query) use ($request) {
                        return $query->where('type', $request->type);
                    }),
                ],
            ], [
                'name.unique' => 'The company name already exists for ' . $request->type . '.',
            ]);

            $company = new Companies();
            $company->name = $request->name;
            $company->type = $request->type;
            $company->save();

            if ($request->action === 'save_add_new') {
                return redirect()->back()->with('success', 'Company Added! You can add another one.');
            }

            return redirect()->route('index.company', ['type' => $request->type])
                ->with('success', 'Company Added Successfully!');
        } else {
            return abort(401);
        }
    }





    public function update(Request $request, $id)
{
    if (Gate::allows('is_admin')) {
        $company = Companies::findOrFail($id);

        $request->validate([
            'name' => [
                'required',
                Rule::unique('companies')->where(function ($query) use ($request) {
                    return $query->where('type', $request->type);
                })->ignore($id),
            ],
        ], [
            'name.unique' => 'The company name already exists for this type.',
        ]);

        $company->name = $request->name;
        $company->type = $request->type;
        $company->save();

        return redirect()->route('index.company', ['type' => $request->type])
            ->with('success', 'Company Updated Successfully!');
    } else {
        return abort(401);
    }
}






    public function delete($id)
    {
        if (Gate::allows('is_admin')) {
            $company = Companies::find($id);
            if (!is_null($company)) {
                $company->delete();
            }
            return redirect()->back()->with('success', 'Company Deleted Successfully');
        } else {
            return abort(401);
        }
    }

}
