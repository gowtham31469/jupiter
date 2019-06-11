<?php
namespace App\Http\Controllers\ErpUser\Printmanager;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Response;
use View;
use Auth;
use DB;
use Session;
use App\ErpOrders as Erp;
use App\ErpOrderDetails as ErpOrderDetails;

class PrintManagerController extends Controller
{
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('erpuser');
   
    }
	
	public function index()
    {
			$customer_id = Auth::guard('erpuser')->id();
			$new_orders_count=0;
			$hold_orders_count=0;
			
			$buyers = DB::table('erpuser_buyer')
								->where('erpuser_buyer.erp_user',$customer_id)
								->pluck('erpuser_buyer.buyer')
								->all();
			
			$companies = 	DB::table('erpuser_company_mapping')
										->join('company','erpuser_company_mapping.company','company.id')
										->where('erpuser_company_mapping.erp_user',$customer_id)
										->select('erpuser_company_mapping.*','company.name', 'company.logo')
										->get();
			
			$company_id = DB::table('company')
										->select('company.*')
										->first();
			
			if(session()->get('company') == "" && session()->get('company') == null)
			{
				session()->put('company', $company_id->id);
				session()->put('company_name', strtolower( str_replace(' ','',$company_id->name)) );
			}
			
			if($customer_id != 1)
			{
				$templates   = 		DB::table('erpusers_template_mapping')
									->join('buyers','erpusers_template_mapping.buyer','buyers.id')
										->where('erpusers_template_mapping.company',session()->get('company'))
									->where('erpusers_template_mapping.erp_user',$customer_id)
									->select('erpusers_template_mapping.*','buyers.template_table','buyers.item_master_table','buyers.id as buyer_id')
									->get();
			}
			else
			{
				$templates   = 		DB::table('erpusers_template_mapping')
									->join('buyers','erpusers_template_mapping.buyer','buyers.id')
									->where('erpusers_template_mapping.company',session()->get('company'))
									->select('erpusers_template_mapping.*','buyers.template_table','buyers.item_master_table','buyers.id as buyer_id')
									->get();
			}
		
		 $new_orders = array();
		 $i=1;
		 foreach($templates as $value)
		 {
			 $arr_template =  DB::table($value->template_table)
							 ->where('hierarchy_id',$value->templates)
							 ->pluck('item_id');
			 if($arr_template)
			 {
				$new_orders[$value->buyer_id."-".$i] = $arr_template;
			 }
			 $i++;
		 }
		
		 $orders_erp = array();
		
		
		 foreach($new_orders as $key => $values)
		 {
			foreach($values as $valuess)
			{
				$buyer_id = explode("-",$key);
				
				$erp_order_details = Erp::join('erp_order_details','erp_order_details.erp_order_id','erp_orders.id')
										 ->join('customers','erp_orders.customer_id','customers.id')
										 ->join('erp_status','erp_orders.status','erp_status.id')->join('tesco_template', 'tesco_template.item_id', 'erp_order_details.template_id')
										 ->where('erp_order_details.template_id',$valuess)
										 ->whereIn('erp_orders.buyer_id', $buyers)
										 ->where('erp_orders.status','!=',4)
										 ->groupBy('erp_orders.id','customers.company_name','erp_status.status_name','erp_order_details.created_at','erp_order_details.template_id','erp_order_details.buyer_po_number','erp_order_details.order_type','erp_order_details.style_no','erp_order_details.zone','erp_order_details.reference_name','tesco_template.template_file')
										 ->select('erp_orders.*','customers.company_name','erp_status.status_name','erp_order_details.created_at as po_created_date','erp_order_details.template_id','erp_order_details.buyer_po_number','erp_order_details.order_type','erp_order_details.style_no','erp_order_details.zone','erp_order_details.reference_name','tesco_template.template_file')
										 ->get();
										
				if(count($erp_order_details) > 0)
				{
					foreach($erp_order_details as $erp_order_det)
					{
						if (array_key_exists($erp_order_det->id,$orders_erp))
						{
							if($orders_erp[$erp_order_det->id]['po_created_date'] < $erp_order_det['po_created_date'])
							{
								$orders_erp[$erp_order_det->id]['po_created_date']=$erp_order_det['po_created_date'];
							}
						}
						else
						{
							$orders_erp[$erp_order_det->id.'-'.$erp_order_det->template_id.'-'.$erp_order_det->buyer_po_number.'-'.$erp_order_det->order_type] = $erp_order_det;
						}
					}
				}
			}
			 
		 }
		
		asort($orders_erp);

		return view('erp.Printmanager.print-manager',['orders' => $orders_erp,'companies' => $companies]);
	}
	
	
}