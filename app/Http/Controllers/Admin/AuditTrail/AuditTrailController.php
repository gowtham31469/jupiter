<?php

namespace App\Http\Controllers\Admin\AuditTrail;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classes\Modules;
use Response;
use View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use DB;

/**
 * Audit Trail controller
 * @author <Praveen>
 */
class AuditTrailController extends Controller
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
     * Show the Audits list page.
     *
     * @return \Illuminate\Http\Response
     * @author <Praveen>
     */
    public function index()
    {
        $module_var = new Modules();
        $is_allowed = $module_var->permittedModules();
        if($is_allowed)
        {
            $get_main_module = $module_var->getMainModules();
            $get_sub_module = $module_var->getSubModules(44);
            $audit_data = DB::table('audits')
                          ->join('users','users.id','audits.user_id')
                          ->select('audits.id','audits.module','audits.ip_address','audits.user_agent','audits.event','audits.url','audits.created_at','users.name','users.email')
                          ->get();
            View::composer('layouts.Admin.navbar', function($view) use ($get_main_module,$get_sub_module) {
                return $view->with(['modules' => $get_main_module,'sub_modules' => $get_sub_module]);
            });
            return view('Admin/Audit Trail/home')->with(['audits' => $audit_data]);
        }
        else
        {
             return redirect('/admin/dashboard');
        }
    }
    
    /**
     * Get audits old and new audits data for comparison
     * @param audit id
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function getComparisonDataById(Request $request)
    {
      try {
            $id = $request['id'];
            $comparison_data = DB::table('audits')
                                ->select('audits.id','audits.module','audits.old_values','audits.new_values')
                                ->where('audits.id',$id)
                                ->get();
            foreach ($comparison_data as $key => $value) 
            {
               // get company, buyer and template name from old values json object
               $old_data = json_decode($value->old_values);
               if($old_data)
               {
                    $company_name = DB::table('customers')
                                     ->select('customers.company_name')
                                     ->where('customers.id',$old_data->company_id)
                                     ->first();
                    $old_data->company = $company_name->company_name;
                    $buyer_name = DB::table('buyers')
                                     ->select('buyers.buyer_name')
                                     ->where('buyers.id',$old_data->buyer_id)
                                     ->first();
                    $old_data->buyer = $buyer_name->buyer_name;
                    $template = DB::table('tesco_item_master')
                                     ->select('tesco_item_master.item_name')
                                     ->where('tesco_item_master.id',$old_data->template_id)
                                     ->where('tesco_item_master.company_id',1)
                                     ->first();
                    $old_data->template = $template->item_name;
                    unset($old_data->company_id);
                    unset($old_data->buyer_id);
                    unset($old_data->template_id);
                    $value->old_values = json_encode($old_data);
               }
               // get company, buyer and template name from new values json object
               $new_data = json_decode($value->new_values);
               $company_name = DB::table('customers')
                                ->select('customers.company_name')
                                ->where('customers.id',$new_data->company_id)
                                ->first();
               $new_data->company = $company_name->company_name;
               $buyer_name = DB::table('buyers')
                                ->select('buyers.buyer_name')
                                ->where('buyers.id',$new_data->buyer_id)
                                ->first();
               $new_data->buyer = $buyer_name->buyer_name;
               $template = DB::table('tesco_item_master')
                                ->select('tesco_item_master.item_name')
                                ->where('tesco_item_master.id',$new_data->template_id)
                                ->where('tesco_item_master.company_id',1)
                                ->first();
               $new_data->template = $template->item_name;
               unset($new_data->company_id);
               unset($new_data->buyer_id);
               unset($new_data->template_id);
               $value->new_values = json_encode($new_data);
            }
            return response()->json(['error'=>'false','message' =>'success', 'comparison_data' => $comparison_data]);
          } catch (\Exception $e)
          {
            return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
          }
    }
}
