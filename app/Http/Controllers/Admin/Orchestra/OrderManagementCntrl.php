<?php

namespace App\Http\Controllers\Admin\Orchestra;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;   
use App\Classes\Modules;
use Response;
use View;
use DB;

class OrderManagementCntrl extends Controller
{
    private $modules_permission;
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
        $module_var = new Modules();
        $is_allowed = $module_var->permittedModules();
        if($is_allowed)
        {
            $top_mod = "Order Management";
            $get_main_module = $module_var->getMainModules();
            View::composer('layouts.Admin.navbar', function($view) use ($get_main_module) {
                return $view->with(['modules' => $get_main_module]);
            });
            $get_sub_module = $module_var->getSubModules(9);
            View::composer('layouts.Admin.sidebar', function($view) use ($get_sub_module,$top_mod) {
                return $view->with(['modules' => $get_sub_module,'top_mod' => $top_mod]);
            });
 	    $orders = DB::table('orchestra_parcel_details')->select('*')->get();
            return view('Admin/Orchestra/order-management',['orders' => $orders]);
        }
        else
        {
             return redirect('/admin/dashboard');
        }
        
    }
}

