<?php

namespace App\Http\Controllers;

use App\Models\Issues;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class IssuesController extends Controller
{
    public function index(Request $request)
    {
        if (Gate::allows('is_admin')) {
            $query = Issues::query();

            if ($request->issue_name) {
                $query->where('issue_name', 'LIKE', '%' . $request->issue_name . '%');
            }
            if ($request->issue_type) {
                $query->where('issue_type', $request->issue_type);
            }
            $issues = $query->orderBy('id', 'desc')->paginate(100);
            return view('issues.index', compact('issues'));
        } else {
            return abort(401);
        }
    }







    public function create()
    {
        if (Gate::allows('is_admin')) {
            $issue = new Issues();
            return view("issues.create", compact('issue'));
        } else {
            return abort(401);
        }
    }


    public function edit($id)
    {
        if (Gate::allows('is_admin')) {
            $issue = Issues::find($id);
            if (!$issue)
                return redirect()->back();
            return view("issues.create", compact('issue'));
        } else {
            return abort(401);
        }
    }


    public function submit(Request $request)
    {
        if (Gate::allows('is_admin')) {
            $issue = new Issues();
            $issue->issue_name = $request->issue_name;
            $issue->issue_type = $request->issue_type;
            $issue->default_price = $request->default_price;
            $issue->save();
            if ($request->action === 'save_add_new') {
                return redirect()->back()->with('success', 'Issue Added! You can add another one.');
            }
            return redirect()->route('index.issue')->with('success', 'Issue Added Successfully!');
        } else {
            return abort(401);
        }
    }



    public function update(Request $request, $id)
    {
        if (Gate::allows('is_admin')) {
            $issue = Issues::find($id);
            $issue->issue_name = $request->issue_name;
            $issue->issue_type = $request->issue_type;
            $issue->default_price = $request->default_price;
            $issue->update();
            return redirect()->route('index.issue')->with('success', 'Issue Updated Successfully!');
        } else {
            return abort(401);
        }
    }
}
