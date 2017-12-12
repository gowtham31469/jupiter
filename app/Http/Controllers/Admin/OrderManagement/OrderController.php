<?php

namespace App\Http\Controllers\Admin\OrderManagement;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classes\Modules;
use Response;
use View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use DB;

class OrderController extends Controller
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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('Admin/login');
    }

    public function dashboard()
    {
        $module_var = new Modules();
        $is_allowed = $module_var->permittedModules();
        if($is_allowed)
        {            
            $get_main_module = $module_var->getMainModules();
            $get_sub_module = $module_var->getSubModules(9);
            View::composer('layouts.Admin.navbar', function($view) use ($get_main_module,$get_sub_module) {
                return $view->with(['modules' => $get_main_module,'sub_modules' => $get_sub_module]);
            });
            
            $buyers = DB::table('buyers')->select('*')->get();
            return view('Admin/Order Management/dashboard',['buyers' => $buyers]);
        }
        else
        {
             return redirect('/admin/dashboard');
        }

    }
    public function orders()
    {
        $module_var = new Modules();
        $is_allowed = $module_var->permittedModules();
        if($is_allowed)
        {
            $get_main_module = $module_var->getMainModules();
            $get_sub_module = $module_var->getSubModules(9);
            View::composer('layouts.Admin.navbar', function($view) use ($get_main_module,$get_sub_module) {
                return $view->with(['modules' => $get_main_module,'sub_modules' => $get_sub_module]);
            });
            
            $buyers = DB::table('buyers')->select('*')->get();
            return view('Admin/Order Management/orders',['buyers' => $buyers]);
        }
        else
        {
             return redirect('/admin/dashboard');
        }

    }
}
