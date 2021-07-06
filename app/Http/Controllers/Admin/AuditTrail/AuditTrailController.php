<?php

namespace App\Http\Controllers\Admin\AuditTrail;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classes\Modules;
use View;
use DB;
use Illuminate\Support\Facades\Redis;
use Session;
use App\FinancialYearResetDate;
use Carbon\Carbon;


/**
 * Audit Trail controller
 * @author <Praveen>
 */
class AuditTrailController extends Controller
{
    private $year;
    private $financial_year_date;
    private $financial_year_start;
    private $financial_year_end;
    private $financial_year_date_format;
    private $today_date;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Modules $module)
    {
       $this->middleware('auth');
       $this->module = $module;

       // get year as "2020"
        $this->year = Carbon::now('Asia/Kolkata')->format('Y');

        $this->financial_year_date = FinancialYearResetDate::value('financial_year_date');

        // format financial year date as "yyyy-dd-mm" 
        $this->financial_year_date_format = $this->year.'-'.$this->financial_year_date;

        // get the current date in "dd-mm-yyyy" format
        $this->today_date = Carbon::now('Asia/Kolkata')->format('Y-m-d');
            
            // financial year identification
        if($this->today_date < $this->financial_year_date_format){
          $this->financial_year_start = Carbon::parse($this->financial_year_date_format)->subYear()->toDateString();
          $this->financial_year_end = Carbon::parse($this->financial_year_date_format)->subDay()->toDateString();
          $this->financial_year = Carbon::parse($this->financial_year_date_format)->subYear()->format('y');
        } else {
          $this->financial_year_start = $this->financial_year_date_format;
          $this->financial_year_end = Carbon::parse($this->financial_year_date_format)->addYear()->subDay()->toDateString();
          $this->financial_year = Carbon::parse($this->financial_year_date_format)->format('y');
        }
    }
    /**
     * Show the Audits list page.
     *
     * @return \Illuminate\Http\Response
     * @author <Praveen>
     */
    public function index()
    {
        $is_allowed = $this->module->permittedModules();
        if($is_allowed)
        {
            $get_main_module = $this->module->getMainModules();
            $get_sub_module = $this->module->getSubModules(44);

            if(Session::get('fiscal-year') != null) {
              $year = explode('-', Session::get('fiscal-year'));
              $from_date = $year[0].'-'.$this->financial_year_date;
              $to_date =Carbon::parse($from_date)->addYear()->subDay()->toDateString(); 
            } else {
              $from_date = $this->financial_year_start;
              $to_date =$this->financial_year_end; 
            }

            $audit_data = DB::table('audits')
                          ->join('users','users.id','audits.user_id')
                          ->select('audits.id','audits.module','audits.ip_address','audits.user_agent','audits.event','audits.url','audits.created_at','users.name','users.email')->whereBetween('audits.created_at', [$from_date, $to_date])
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

            /***********REDIS***************/
            /** get auditing data from redis */
            $comparison_data = Redis::get('price:audit:'.$id);
            
            if(!$comparison_data){
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
              unset($value);
               
              /***********REDIS***************/
              /** set auditing data into redis */ 
              Redis::set('price:audit:'.$id, json_encode($comparison_data));
            } else {
              $comparison_data = json_decode($comparison_data);
            }
            
            return response()->json(['error'=>'false','message' =>'success', 'comparison_data' => $comparison_data]);
          } catch (\Exception $e)
          {
            return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
          }
    }
}
