<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Models;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Sabberworm\CSS\Settings;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return redirect('/admin');
        // return view('home');
    }

    public function changepassword()
    {
        if (Gate::allows('is_admin')) {
            return view('changepassword');
        } else {
            return abort(401);
        }
    }


    public function updatepassword(Request $request)
    {
        if (Gate::allows('is_admin')) {
            $validator = Validator::make($request->all(), [
                'current_password' => 'required',
                'new_password' => 'required|min:8|confirmed',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $user = Auth::user();
            if (!Hash::check($request->current_password, $user->password)) {
                return redirect()->back()->withErrors(['current_password' => 'The current password is incorrect'])->withInput();
            }
            $user->password = Hash::make($request->new_password);
            $user->save();

            return redirect()->back()->with('success', 'Password updated successfully');
        } else {
            return abort(401);
        }
    }


    public function getModels($id)
    {
        if (Gate::allows('is_admin')) {
            $models = Models::where('company_id', $id)->orderby('created_at','desc')->get();
            return response()->json($models);
        } else {
            return abort(401);
        }
    }

     public function updateshow(Request $request)
    {
        if (Gate::allows('is_admin')) {
            // return $request;
        $setting = Setting::first();
        $validatedData = $request->validate([
            'is_show_qty' => 'required|boolean',
            'is_show_purchase' => 'required|boolean',
            'is_show_sale' => 'required|boolean',
            'is_show_status' => 'required|boolean',
            'is_show_action' => 'required|boolean',
        ]);
        $setting->is_show_qty = $validatedData['is_show_qty'];
        $setting->is_show_purchase = $validatedData['is_show_purchase'];
        $setting->is_show_sale = $validatedData['is_show_sale'];
        $setting->is_show_status = $validatedData['is_show_status'];
        $setting->is_show_action = $validatedData['is_show_action'];
        $setting->save();
        return redirect()->back();
        } else {
            return abort(401);
        }
    }
}
