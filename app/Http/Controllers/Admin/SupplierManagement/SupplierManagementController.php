<?php

namespace App\Http\Controllers\Admin\SupplierManagement;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classes\Modules;
use Response;
use View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use DB;
use App\Customer;

class SupplierManagementController extends Controller
{
  /**
   * Create a new controller instance.
   * @author <Praveen>
   * @return void
   */
   public function __construct()
   {
      $this->middleware('auth');
   }
   /**
    * Show the Supplier-Management home page.
    * @author <Praveen>
    * @return \Illuminate\Http\Response
    */
   public function index()
   {
     try {
           $module_var = new Modules();
           $is_allowed = $module_var->permittedModules();
           if($is_allowed)
           {
               $get_main_module = $module_var->getMainModules();
               View::composer('layouts.Admin.navbar', function($view) use ($get_main_module) {
                   return $view->with(['modules' => $get_main_module]);
               });
               return view('Admin/Supplier Management/home');
           }
           else
           {
                return redirect('/admin/dashboard');
           }
         } catch (\Exception $e)
         {
                return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
         }
   }
   /**
    * Show the orchestra suppliers list view on ajax.
    * @author <Praveen>
    * @return \Illuminate\Http\Response
    */
    public function suppliers()
    {
      try{
            $suppliers = DB::table('customers')->select('id','company_name','factory_code','contact_person','contact_email','phone_no','courier_choice','account_no','tin_number','pan_number','email')->get();
            return response()->json(['error'=>'false','message' =>'success', 'suppliers' => $suppliers]);
          } catch (\Exception $e)
          {
            return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
          }
    }
}
