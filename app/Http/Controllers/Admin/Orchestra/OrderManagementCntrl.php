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
 	    $orders = DB::table('orchestra_parcel_details')->select('*')->get();
            return view('Admin/Orchestra/order-management',['orders' => $orders,'sub_modules' => $get_sub_module]);
        }
        else
        {
             return redirect('/admin/dashboard');
        }
        
    } 
    public function labelOrders()
    {
            $orders = DB::table('orchestra_item_details')->select('id','UVC','Collection as Season','SupplierName','Description','Theme','Style_Name','Disney','Quantity')->get();
            echo json_encode($orders);
    }
    public function stickerOrders()
    {
            $orders = DB::table('orchestra_parcel_details')->select('id','UVC','BuyerPONo','SupplierName','Theme','Quantity','IsSTD as Type')->get();
            echo json_encode($orders);
    }
}

