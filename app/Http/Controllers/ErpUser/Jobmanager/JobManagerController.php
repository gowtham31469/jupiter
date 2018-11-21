<?php
namespace App\Http\Controllers\ErpUser\Jobmanager;

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

class JobManagerController extends Controller
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

    /**
     * Show the Po Manager List View.
     * @author [Gowtham] <[gowtham@wizroots.com]>
     * @return \Illuminate\Http\Response
     */
	public function index()
	{
		try {
			$customer_id = Auth::guard('erpuser')->id();
			
			$companies   = 	DB::table('erpuser_company_mapping')
									->join('company','erpuser_company_mapping.company','company.id')
									->where('erpuser_company_mapping.erp_user',$customer_id)
									->select('erpuser_company_mapping.*','company.name')
									->get();
			$buyers      = 	DB::table('erpuser_buyer')
									->where('erpuser_buyer.erp_user',$customer_id)
									->pluck('erpuser_buyer.buyer')
									->all();

			$company_id   = DB::table('company')
									->select('company.*')
									->first();
			
			
			if(session()->get('company') == "" && session()->get('company') == null)
			{
				session()->put('company', $company_id->id);
				session()->put('company_name', strtolower( str_replace(' ','',$company_id->name)) );
			}
			
			$templates   = 		DB::table('erpusers_template_mapping')
									->join('buyers','erpusers_template_mapping.buyer','buyers.id')
									->where('erpusers_template_mapping.company',session()->get('company'))
									->where('erpusers_template_mapping.erp_user',$customer_id)
									->select('erpusers_template_mapping.*','buyers.template_table','buyers.item_master_table','buyers.id as buyer_id')
									->get();
			
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
					$erp_order_details = DB::table('erp_orders')->join('erp_order_details', 'erp_order_details.erp_order_id', 'erp_orders.id')
											->join('customers', 'erp_orders.customer_id', 'customers.id')
											->join('buyers', 'erp_orders.buyer_id', 'buyers.id')
											->join('erp_status', 'erp_orders.status', 'erp_status.id')
											->where('erp_order_details.template_id', '=', $valuess)
											->where('erp_orders.status', '=', 3)
											->where('erp_orders.order_status_id', '=', 4)
											->whereIn('erp_orders.buyer_id', $buyers)
											->orderBy('erp_order_details.erp_order_id', 'asc')
											->select('erp_orders.*', 'erp_order_details.id as erp_detail_id','erp_order_details.reference_name', 'erp_order_details.style_no', 'erp_order_details.delivery_date', 'customers.company_name', 'erp_status.status_name', 'buyers.buyer_name')
											->get();
					
					if(count($erp_order_details) > 0)
					{
						foreach($erp_order_details as $erp_order_det)
						{
							$orders_erp[$erp_order_det->id.'-'.$erp_order_det->erp_detail_id] = $erp_order_det;
						}
						
					}
				}
				
			}
			ksort($orders_erp);
			return view('erp.Jobmanager.jobmanager', ['orders' => $orders_erp, 'companies' => $companies]);
		}
		catch (\Exception $e)
		{
		return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
		}
	}
	/**
     * Get order deatils for job card template creation by selected ids.
     * @param order details id
     * @author Praveen <praveen@wizroots.com>
     * @return \Illuminate\Http\Response
     */
    public function getOrderDetailsByIds(Request $request)
    {
      try {
			 $order_detail_ids   = $request['orders'];
			 $order_details_data = array();
			 $buyers_array       = array();
			 $customers_array    = array();
			 $company_array      = array();
			 $order_details      = array();
			 $template_array     = array();
			 $is_templates_combined = 0;

			 foreach($order_detail_ids as $order_detail_id)
			 {
				$order_details = DB::table('erp_order_details')
									->select('erp_order_details.*', 'erp_orders.*')
									->join('erp_orders', 'erp_order_details.erp_order_id', 'erp_orders.id')
									->where('erp_order_details.id', '=', $order_detail_id)
									->get();
				array_push($buyers_array,$order_details{0}->buyer_id);
				array_push($customers_array,$order_details{0}->customer_id);
				array_push($company_array,$order_details{0}->company_id);
				array_push($template_array,$order_details{0}->reference_name);
			 }

			 $buyer_first_val = current($buyers_array);
			 foreach ($buyers_array as $val) {
				 if ($buyer_first_val !== $val) {
					 return response()->json(['error'=> true,'message' =>'Different Buyers Not Acceptable'],406);
				 } else {
					 $buyer = DB::table('buyers')->select('buyer_name', 'id as buyer_id')->where('id', '=', $buyer_first_val)->first();
				 }
			 }

			 $customer_first_val = current($customers_array);
			 foreach ($customers_array as $val) {
				 if ($customer_first_val !== $val) {
					 return response()->json(['error'=> true,'message' =>'Different Customers Not Acceptable'],406);
				 } else {
					$customer = DB::table('customers')->select('company_name as customer_name', 'id')->where('id', '=', $customer_first_val)->first();
				 }
			 }

			 $company_first_val = current($company_array);
			 foreach ($company_array as $val) {
				 if ($company_first_val !== $val) {
					 return response()->json(['error'=> true,'message' =>'Different Companies Not Acceptable'],406);
				 } else {
					$company = DB::table('company')->select('name', 'id', 'address', 'logo')->where('id', '=', $company_first_val)->first();
				 }
			 }

			 $template_first_val = current($template_array);
			 foreach ($template_array as $val) {
				 if ($template_first_val !== $val) {
					$is_templates_combined = 1;
				 }
			 }
			 
          } catch (\Exception $e)
          {
            return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
          }
    }
}