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
//			$customer_id = Auth::guard('erpuser')->id();
//			$new_orders_count=0;
//			$hold_orders_count=0;
//			
//			$buyers = DB::table('erpuser_buyer')
//								->where('erpuser_buyer.erp_user',$customer_id)
//								->pluck('erpuser_buyer.buyer')
//								->all();
//			
//			$companies = 	DB::table('erpuser_company_mapping')
//										->join('company','erpuser_company_mapping.company','company.id')
//										->where('erpuser_company_mapping.erp_user',$customer_id)
//										->select('erpuser_company_mapping.*','company.name', 'company.logo')
//										->get();
//			
//			$company_id = DB::table('company')
//										->select('company.*')
//										->first();
//			
//			if(session()->get('company') == "" && session()->get('company') == null)
//			{
//				session()->put('company', $company_id->id);
//				session()->put('company_name', strtolower( str_replace(' ','',$company_id->name)) );
//			}
//			
//			if($customer_id != 1)
//			{
//				$templates   = 		DB::table('erpusers_template_mapping')
//									->join('buyers','erpusers_template_mapping.buyer','buyers.id')
//										->where('erpusers_template_mapping.company',session()->get('company'))
//									->where('erpusers_template_mapping.erp_user',$customer_id)
//									->select('erpusers_template_mapping.*','buyers.template_table','buyers.item_master_table','buyers.id as buyer_id')
//									->get();
//			}
//			else
//			{
//				$templates   = 		DB::table('erpusers_template_mapping')
//									->join('buyers','erpusers_template_mapping.buyer','buyers.id')
//									->where('erpusers_template_mapping.company',session()->get('company'))
//									->select('erpusers_template_mapping.*','buyers.template_table','buyers.item_master_table','buyers.id as buyer_id')
//									->get();
//			}
//		
//		 $new_orders = array();
//		 $i=1;
//		 foreach($templates as $value)
//		 {
//			 $arr_template =  DB::table($value->template_table)
//							 ->where('hierarchy_id',$value->templates)
//							 ->pluck('item_id');
//			 if($arr_template)
//			 {
//				$new_orders[$value->buyer_id."-".$i] = $arr_template;
//			 }
//			 $i++;
//		 }
//		
//		 $orders_erp = array();
//		
//		
//		 foreach($new_orders as $key => $values)
//		 {
//			foreach($values as $valuess)
//			{
//				$buyer_id = explode("-",$key);
//				
//				$erp_order_details = Erp::join('erp_order_details','erp_order_details.erp_order_id','erp_orders.id')
//										 ->join('customers','erp_orders.customer_id','customers.id')
//										 ->join('erp_status','erp_orders.status','erp_status.id')->join('tesco_template', 'tesco_template.item_id', 'erp_order_details.template_id')
//										 ->where('erp_order_details.template_id',$valuess)
//										 ->whereIn('erp_orders.buyer_id', $buyers)
//										 ->where('erp_orders.status','!=',4)
//										 ->groupBy('erp_orders.id','customers.company_name','erp_status.status_name','erp_order_details.created_at','erp_order_details.template_id','erp_order_details.buyer_po_number','erp_order_details.order_type','erp_order_details.style_no','erp_order_details.zone','erp_order_details.reference_name','tesco_template.template_file')
//										 ->select('erp_orders.*','customers.company_name','erp_status.status_name','erp_order_details.created_at as po_created_date','erp_order_details.template_id','erp_order_details.buyer_po_number','erp_order_details.order_type','erp_order_details.style_no','erp_order_details.zone','erp_order_details.reference_name','tesco_template.template_file')
//										 ->get();
//										
//				if(count($erp_order_details) > 0)
//				{
//					foreach($erp_order_details as $erp_order_det)
//					{
//						if (array_key_exists($erp_order_det->id,$orders_erp))
//						{
//							if($orders_erp[$erp_order_det->id]['po_created_date'] < $erp_order_det['po_created_date'])
//							{
//								$orders_erp[$erp_order_det->id]['po_created_date']=$erp_order_det['po_created_date'];
//							}
//						}
//						else
//						{
//							$orders_erp[$erp_order_det->id.'-'.$erp_order_det->template_id.'-'.$erp_order_det->buyer_po_number.'-'.$erp_order_det->order_type] = $erp_order_det;
//						}
//					}
//				}
//			}
//			 
//		 }
//		
//		asort($orders_erp);
//
//		return view('erp.Printmanager.print-manager',['orders' => $orders_erp,'companies' => $companies]);
		
		 try {
				$customer_id = Auth::guard('erpuser')->id();
				
				$companies   = 	DB::table('erpuser_company_mapping')
										->join('company','erpuser_company_mapping.company','company.id')
										->where('erpuser_company_mapping.erp_user',$customer_id)
										->select('erpuser_company_mapping.*','company.name', 'company.logo','company.id')
										->get();
				if($companies)
				{
					$company_id   = DB::table('company')
									->select('company.*')
									->where('company.id', '=', $companies{0}->company)
									->first();
				}
				else
				{
					return view('erp.home');
				}
				
				
				if(session()->get('company') == "" && session()->get('company') == null)
				{
					session()->put('company', $company_id->id);
					session()->put('company_name', strtolower( str_replace(' ','',$company_id->name)) );
				}
				/**
				 * Dropdown values
				 */
			// print_r($customer_id);
				$buyers  = 	DB::table('erpuser_buyer')
								->join('buyers','buyers.id','erpuser_buyer.buyer')
								->where('erpuser_buyer.erp_user',$customer_id)
								->pluck('buyers.buyer_name','buyers.id')
								->all();
				
				$erp_dispatch_mode = DB::table('erp_dispatch_mode')->where('is_enabled','=',true)->pluck('disp_mode','id')->all();
				$erp_finish_type   = DB::table('erp_finish_type')->where('is_enabled','=',true)->pluck('finish_type','id')->all();
				$erp_machines      = DB::table('erp_machines')->where('is_enabled','=',true)->pluck('machine','id')->all();
				$erp_teeth_no      = DB::table('erp_teeth_no')->where('is_enabled','=',true)->pluck('teeth_no','id')->all();
				return view('erp.Printmanager.print-manager', ['buyers' => $buyers, 'companies' => $companies, 'disp_modes' => $erp_dispatch_mode, 'finish_types' => $erp_finish_type, 'machines' => $erp_machines, 'teeth_nos' => $erp_teeth_no]);	
			} catch (\Exctesteption $e) {
				return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
			}
	}
	
	/**
     * Function to get customers list by  buyer id.
     * @author <Praveen>
	 * @param  $buyer_id
     * @return equivalent customer list
     */  
    public function getCustomersListByBuyerId(Request $request)
    {
        try {
				$buyer_id = $request['selected_buyer_id'];

				if(session()->get('company') == "" || session()->get('company') == null)
				{
					return view('erp.home');
				}

				if($buyer_id == null)
				{
					return response()->json(['error'=> true,'message' =>'Parameter Missing'], 400);
				}
				else
				{
					$customers_list  = 	DB::table('customers')
										->join('customer_buyers', 'customer_buyers.customer_id', 'customers.id')
										->where('customer_buyers.buyer_id', '=', $buyer_id)
										->pluck('customers.company_name','customers.id')
										->all();
					return response()->json(['error'=> false,'message' =>'Success', 'customers_list' => $customers_list], 200);
				}
				    
	
			} catch (\Exctesteption $e) {
				return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
			}
	}
	
	
	/**
     * Function to get jobcard id list by  buyer id and customer id.
     * @author <Praveen>
	 * @param  $buyer_id, $customer_id
     * @return equivalent jobcard ids list
     */  
    public function getStyleListByBuyerCustomerId(Request $request)
    {
        try {
				$buyer_id    = $request['selected_buyer_id'];
				$customer_id = $request['selected_customer_id'];

				if(session()->get('company') == "" || session()->get('company') == null)
				{
					return view('erp.home');
				}

				if($buyer_id == null || $customer_id == null)
				{
					return response()->json(['error'=> true,'message' =>'Parameter Missing'], 400);
				}
				else
				{
					$jobcard_id_list  = Erp::where('erp_orders.buyer_id', '=', $buyer_id)
											->where('erp_orders.customer_id', '=', $customer_id)
											->where('erp_orders.status','!=',4)
											->pluck('erp_orders.erp_id','erp_orders.id')
											->all();
					return response()->json(['error'=> false,'message' =>'Success', 'jobcard_id_list' => $jobcard_id_list], 200);
				}
				    
	
			} catch (\Exctesteption $e) {
				return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
			}
	} 
	
	public function getErpOrderListByBuyerCustomerId(Request $request)
    {
    	// dd($request->all());
        try {
				$buyer_id    = $request['selected_buyer_id'];

				$customer_id = $request['selected_customer_id'];
				$erp_id = $request['erp_id'];

				if(session()->get('company') == "" || session()->get('company') == null)
				{
					return view('erp.home');
				}

				if($buyer_id == null || $customer_id == null)
				{
					return response()->json(['error'=> true,'message' =>'Parameter Missing'], 400);
				}
				else
				{
					
					$buyer = strtolower(DB::table('buyers')->where('id',$buyer_id)->first()->buyer_name);
				
					$jobcard_id_list  = ErpOrderDetails::join('erp_status','erp_status.id','erp_order_details.status')->join($buyer.'_template', $buyer.'_template.item_id', 'erp_order_details.template_id')->where('erp_order_details.erp_order_id', '=', $erp_id)
											->where('erp_order_details.status','!=',4)
											->select(DB::raw('distinct on (erp_order_details.erp_order_id,buyer_po_number,style_no,reference_name) erp_order_details.erp_order_id'),'erp_order_details.*','erp_status.status_name',$buyer.'_template.template_file')->groupBy('erp_order_details.id','erp_order_details.buyer_po_number','erp_order_details.style_no','erp_order_details.reference_name','erp_status.status_name',$buyer.'_template.template_file')
											->get();
// dd($jobcard_id_list);
					if($buyer=="orchestra")
					{	
						

						$jobcard_id_list->map(function ($jobcard_id_list,$key) {
                    		$jobcard_id_list->buyer='orchestra';
                    		$jobcard_id_list->zone='-';

                    		if($jobcard_id_list->style_no==null)
                    		{
                    			$jobcard_id_list->style_no='-';
                    		}

                    		if($jobcard_id_list->buyer_po_number==null)
                    		{
                    			$jobcard_id_list->buyer_po_number='-';
                    		}
                	    });

					
// dd($jobcard_id_list);
                	    if($jobcard_id_list->first()->order_qnty_table_name ==  "orchestra_hang_tag_orders_qty")
                	    {

                	    
                	    	// $key = $jobcard_id_list->first()->order_qnty_table_id
                	    	// ->first()->oht_id;
                	    	$fkey = DB::table('orchestra_hang_tag_orders_qty')
                	    	->where('id',$jobcard_id_list->first()->order_qnty_table_id)
                	    	->first()
                	    	->oht_id;

                	    	$row = DB::table('orchestra_hang_tag_orders')
                	    	->where('id',$fkey)->first();

                	    	$jobcard_id_list->map(function ($jobcard_id_list,$key) use ($row){
                    		
                    		$jobcard_id_list->frontPath = DB::table('orchestra_template')
                    		->where('item_id',$row->template_front_id)
                    		->where('flag',1)
                    		->first()
                    		->template_file;

                    		$jobcard_id_list->backPath = DB::table('orchestra_template')
                    		->where('item_id',$row->template_back_id)
                    		->where('flag',0)
                    		->first()
                    		->template_file;

                    		$jobcard_id_list->ownId = $row->id;
                	    });
                	    }

                	     if($jobcard_id_list->first()->order_qnty_table_name ==  "orchestra_wash_care_label_orders_qty")
                	    {
                	    	$jobcard_id_list->map(function ($jobcard_id_list,$key) {
                    		$jobcard_id_list->backPath='/storage/app/template/Orchestra/WCL/sticker2.svg';
                    		$jobcard_id_list->middlePath='/storage/app/template/Orchestra/WCL/sticker3.svg';
                	    });
                	    }

					}
					else
					{
						$jobcard_id_list->map(function ($jobcard_id_list,$key) {
                    		$jobcard_id_list->buyer='tesco';
                	    });
					}

					return response()->json(['error'=> false,'message' =>'Success', 'erp_orders_list' => $jobcard_id_list], 200);
				}
				    
	
			} catch (\Exctesteption $e) {
				return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
			}
	}
	
	
	
	
}