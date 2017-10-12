<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classes\Modules;
use Response;
use View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use DB;

class AdminController extends Controller
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
    
    public function orderManagement()
    {
        $module_var = new Modules();
        $is_allowed = $module_var->permittedModules();
        if($is_allowed)
        {
            $top_mod = "User Management";
            $get_main_module = $module_var->getMainModules();
            View::composer('layouts.Admin.navbar', function($view) use ($get_main_module) {
                return $view->with(['modules' => $get_main_module]);
            });
            $get_sub_module = $module_var->getSubModules(9);
            $buyers = DB::table('buyers')->select('*')->get();
            return view('Admin/order-management',['buyers' => $buyers]);
        }
        else
        {
             return redirect('/admin/dashboard');
        }
        
    }
    
    public function buyerManagement()
    {
        $module_var = new Modules();
        $is_allowed = $module_var->permittedModules();
        if($is_allowed)
        {
            $top_mod = "User Management";
            $get_main_module = $module_var->getMainModules();
            View::composer('layouts.Admin.navbar', function($view) use ($get_main_module) {
                return $view->with(['modules' => $get_main_module]);
            });
            $buyers = DB::table('buyers')->select('*')->get();
            return view('Admin/buyer-management',['buyers' => $buyers]);
        }
        else
        {
             return redirect('/admin/dashboard');
        }
    }
    
    public function supplierManagement()
    {
        $module_var = new Modules();
        $is_allowed = $module_var->permittedModules();
        if($is_allowed)
        {
            $top_mod = "User Management";
            $get_main_module = $module_var->getMainModules();
            View::composer('layouts.Admin.navbar', function($view) use ($get_main_module) {
                return $view->with(['modules' => $get_main_module]);
            });
            $buyers = DB::table('buyers')->select('*')->get();
            return view('Admin/supplier-management',['buyers' => $buyers]);
        }
        else
        {
             return redirect('/admin/dashboard');
        }
    }
    
    public function templateManagement()
    {
        $module_var = new Modules();
        $is_allowed = $module_var->permittedModules();
        if($is_allowed)
        {
            $top_mod = "User Management";
            $get_main_module = $module_var->getMainModules();
            View::composer('layouts.Admin.navbar', function($view) use ($get_main_module) {
                return $view->with(['modules' => $get_main_module]);
            });
            $buyers = DB::table('buyers')->select('*')->get();
            return view('Admin/template-management',['buyers' => $buyers]);
        }
        else
        {
             return redirect('/admin/dashboard');
        }
    }  
    public function orderSubModules(Request $request)
    {
            $buyer_id=$request->buyer;
            $parent_module = DB::table('module')->select('*')->where('parent_id','6')->where('module_name',$buyer_id)->get();
            $parent_id = $parent_module{0}->id;
            $module_var = new Modules();
            $get_sub_module = $module_var->getSubModules($parent_id);
            echo json_encode($get_sub_module);
    } 
    public function templateSubModules(Request $request)
    {
            $buyer_id=$request->buyer;
            $parent_module = DB::table('module')->select('*')->where('parent_id','5')->where('module_name',$buyer_id)->get();
            $parent_id = $parent_module{0}->id;
            $module_var = new Modules();
            $get_sub_module = $module_var->getSubModules($parent_id);
            echo json_encode($get_sub_module);
    }
}