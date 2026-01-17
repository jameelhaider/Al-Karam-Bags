<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Jobs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class JobsController extends Controller
{
    public function index(Request $request)
    {
        if (! Gate::allows('is_admin')) {
            return abort(401);
        }
        $jobs = DB::table('jobs')
            ->when($request->customer, function ($query, $customer) {
                $query->where(function ($q) use ($customer) {
                    $q->where('customer_name', 'LIKE', "%{$customer}%")
                        ->orWhere('customer_phone', 'LIKE', "%{$customer}%");
                });
            })
            ->when($request->mobile, function ($query, $mobile) {
                $query->where(function ($q) use ($mobile) {
                    $q->where('company_name', 'LIKE', "%{$mobile}%")
                        ->orWhere('model_name', 'LIKE', "%{$mobile}%");
                });
            })
            ->when($request->job_id, function ($query, $jobId) {
                $query->where('id', $jobId);
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->orderByRaw("
    CASE
        WHEN status = 'Inn' THEN 0
        WHEN status = 'Decision' THEN 1
        WHEN status = 'Out' THEN 2
        ELSE 3
    END
")

            ->orderBy('id', 'asc')
            ->paginate(50);
        return view('jobs.index', compact('jobs'));
    }




    public function create()
    {
        if (Gate::allows('is_admin')) {
            $job = new Jobs();
            $companies = DB::table('companies')->where('type', 'parts')->get();
            $board_issues = DB::table('issues')->where('issue_type', 'Board Issue')->get();
            $replacement_issues = DB::table('issues')->where('issue_type', 'Replacement')->get();
            $software_issues = DB::table('issues')->where('issue_type', 'Software Issue')->get();
            return view("jobs.create", compact('job', 'companies', 'board_issues', 'replacement_issues', 'software_issues'));
        } else {
            return abort(401);
        }
    }


    public function edit($id)
    {
        if (Gate::allows('is_admin')) {
            $job = Jobs::find($id);
            $companies = DB::table('companies')->where('type', 'parts')->get();
            $board_issues = DB::table('issues')->where('issue_type', 'Board Issue')->get();
            $replacement_issues = DB::table('issues')->where('issue_type', 'Replacement')->get();
            $software_issues = DB::table('issues')->where('issue_type', 'Software Issue')->get();
            if (!$job)
                return redirect()->back();
            return view("jobs.create", compact('job', 'companies', 'companies', 'board_issues', 'replacement_issues', 'software_issues'));
        } else {
            return abort(401);
        }
    }


    public function submit(Request $request)
    {
        if (!Gate::allows('is_admin')) {
            return abort(401);
        }

        $job = new Jobs();
        $job->customer_name  = $request->customer_name;
        $job->customer_phone = $request->customer_phone;
        $job->inn_date = $request->inn_date;
        $job->advance = $request->advance;
        $job->status = $request->status;
        $job->note = $request->note;
        $job->dead_approval = $request->dead_approval;

        $company = DB::table('companies')->where('id', $request->company_id)->first();
        if ($company) {
            $job->company_id   = $company->id;
            $job->company_name = $company->name;
        }

        $model = DB::table('models')->where('id', $request->model_id)->first();
        if ($model) {
            $job->model_id   = $model->id;
            $job->model_name = $model->model;
        }

        // Save issues as array of objects
        $job->issues = array_values($request->input('issues', []));
        $job->parts = array_values($request->input('parts', []));

        $job->save();

        if ($request->action === 'save_add_new') {
            return redirect()->back()->with('success', 'Job Added! You can add another one.');
        }

        return redirect()->route('index.job')->with('success', 'Job Added Successfully!');
    }







    public function update(Request $request, $id)
    {
        if (!Gate::allows('is_admin')) {
            return abort(401);
        }

        $job = Jobs::findOrFail($id);

        $job->customer_name  = $request->customer_name;
        $job->customer_phone = $request->customer_phone;
        $job->inn_date = $request->inn_date;
        $job->advance = $request->advance;
        $job->status = $request->status;
        $job->reason = $request->reason;
        $job->design_date = $request->decision_date;
        $job->repair_status = $request->repair_status;
        $job->out_date = $request->out_date;
        $job->note = $request->note;
        $job->dead_approval = $request->dead_approval;

        $company = DB::table('companies')->where('id', $request->company_id)->first();
        if ($company) {
            $job->company_id   = $company->id;
            $job->company_name = $company->name;
        }

        $model = DB::table('models')->where('id', $request->model_id)->first();
        if ($model) {
            $job->model_id   = $model->id;
            $job->model_name = $model->model;
        }

        // Update issues and parts (same as submit)
        $job->issues = array_values($request->input('issues', []));
        $job->parts  = array_values($request->input('parts', []));

        $job->save();

        return redirect()->route('index.job')->with('success', 'Job Updated Successfully!');
    }



    public function view($job_id)
    {
        if (Gate::allows('is_admin')) {
            $job = Jobs::find($job_id);
            return view("jobs.view", compact('job'));
        } else {
            return abort(401);
        }
    }
}
