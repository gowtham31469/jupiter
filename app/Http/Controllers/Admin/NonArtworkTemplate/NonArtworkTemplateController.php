<?php

namespace App\Http\Controllers\Admin\NonArtworkTemplate;

use DB;
use View;
use App\Buyer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classes\Modules;
// 
class NonArtworkTemplateController extends Controller
{

    public function nonArtworkOrders()
    {   
            $module_var = new Modules();
            $get_main_module = $module_var->getMainModules();
            $get_sub_module = $module_var->getSubModules(9);
            
            View::composer('layouts.Admin.navbar', function($view) use ($get_main_module,$get_sub_module) {
                return $view->with(['modules' => $get_main_module,'sub_modules' => $get_sub_module]);
            });
            
            $buyers = Buyer::where('artwork_flag','=',1)->where('is_active','=',1)->pluck('buyer_name','id');
            
            $orders = DB::table('non_artwork_orders')->select('*')->get();
            
            $buyer_count = count($buyers);
            
            return view('Admin/Non Artwork Template/order_management',['buyers' => $buyers,'buyer_count' => $buyer_count,'orders' => $orders]);
    }



    public function saveNonArtworkOrders(Request $request)
    {
        dd($request->all());
    }


    public function getNonArtworkOrders(Request $request)
    {
        dd($request->all());
    }
}
