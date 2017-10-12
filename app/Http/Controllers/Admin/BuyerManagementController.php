<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classes\Modules;
use Response;
use View;
use App\Buyer;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use DB;

class BuyerManagementController extends Controller
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
        $module_var = new Modules();
        $is_allowed = $module_var->permittedModules();
        if($is_allowed)
        {
            $top_mod = "User Management";
            $get_main_module = $module_var->getMainModules();
            View::composer('layouts.Admin.navbar', function($view) use ($get_main_module) {
                return $view->with(['modules' => $get_main_module]);
            });
            return view('Admin/Buyer Management/home');
        }
        else
        {
             return redirect('/admin/dashboard');
        }
    }

    public function buyerList()
    {
        $buyers = DB::table('buyers')->select('buyer_name','buyer_address','buyer_email','buyer_phone')->get();
            echo json_encode($buyers);
    }

    public function addJgaBuyer(Request $request)
      {
        try {
                $name      = $request['name'];
                $email     = $request['email'];
                $phone     = $request['phone'];
                $address   = $request['address'];
                $inserted = Buyer::create([
                              'buyer_name' => $name,
                              'buyer_email'      => $email,
                              'buyer_phone'      => $phone,
                              'buyer_address'    => $address,
                            ]);
                  if($inserted != '')
                  {
                    $result = response()->json(['error'=> false,'message' =>'success'],200);
                  }
                  else
                  {
                    $result = response()->json(['error'=> true,'message' =>'Error'],400);
                  }

            } catch (\Exception $e)
            {
                   $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
            }
            return $result;
      }
    
}
