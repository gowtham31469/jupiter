<?php
namespace App\Http\Controllers\ErpUser\Printmanager;

use DB;
use Auth;
use View;
use Session;
use Response;
use Carbon\Carbon;
use App\ErpOrders as Erp;
use Illuminate\Http\Request;
use App\Classes\ErpCompanySession;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
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
		$role = "PRINT MANAGER";
        $this->middleware('role_permission:'.$role);
        $this->middleware('erpuser');
    }
	
	public function index()
    {
		 try {
				$set_company_session = new ErpCompanySession();
        
        		$companies = $set_company_session->setCompanySession();
				/**
				 * Dropdown values
				 */
				$buyers  = 	DB::table('erpuser_buyer')
								->join('buyers','buyers.id','erpuser_buyer.buyer')
								->where('erpuser_buyer.erp_user', Auth::guard('erpuser')->id())
								->pluck('buyers.buyer_name','buyers.id')
								->all();
				
				$erp_dispatch_mode = DB::table('erp_dispatch_mode')->where('is_enabled','=',true)->pluck('disp_mode','id')->all();
				$erp_finish_type   = DB::table('erp_finish_type')->where('is_enabled','=',true)->pluck('finish_type','id')->all();
				$erp_machines      = DB::table('erp_machines')->where('is_enabled','=',true)->pluck('machine','id')->all();
				$erp_teeth_no      = DB::table('erp_teeth_no')->where('is_enabled','=',true)->pluck('teeth_no','id')->all();
				return view('erp.Printmanager.print-manager', ['buyers' => $buyers, 'companies' => $companies, 'disp_modes' => $erp_dispatch_mode, 'finish_types' => $erp_finish_type, 'machines' => $erp_machines, 'teeth_nos' => $erp_teeth_no]);	
			} catch (\Exception $e) {
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
				    
	
			} catch (\Exception $e) {
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

				if($buyer_id == null || $customer_id == null)
				{
					return response()->json(['error'=> true,'message' =>'Parameter Missing'], 400);
				}
				else
				{
					$jobcard_id_list  = Erp::join('erp_order_details','erp_order_details.erp_order_id','erp_orders.id')
							->where('erp_order_details.company_id', '=', session()->get('company'))
							->where('erp_orders.buyer_id', '=', $buyer_id)
							->where('erp_orders.customer_id', '=', $customer_id)
							->where('erp_orders.status','!=',4)
							->pluck('erp_orders.erp_id','erp_orders.id')
							->all();
					return response()->json(['error'=> false,'message' =>'Success', 'jobcard_id_list' => $jobcard_id_list], 200);
				}
				    
	
			} catch (\Exception $e) {
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

				if($buyer_id == null || $customer_id == null)
				{
					return response()->json(['error'=> true,'message' =>'Parameter Missing'], 400);
				}
				else
				{
					
					$buyer = strtolower(str_replace(" ", "_", DB::table('buyers')->where('id',$buyer_id)->first()->buyer_name));
				
					$jobcard_id_list  = ErpOrderDetails::join('erp_status','erp_status.id','erp_order_details.status')->join($buyer.'_template', $buyer.'_template.item_id', 'erp_order_details.template_id')->where('erp_order_details.erp_order_id', '=', $erp_id)
											->where('erp_order_details.status','!=',4)
											->where('erp_order_details.order_status_id','!=',3)
											->select(DB::raw('distinct on (erp_order_details.erp_order_id,buyer_po_number,style_no,reference_name,order_type) erp_order_details.erp_order_id'),'erp_order_details.*','erp_status.status_name',$buyer.'_template.template_file')->groupBy('erp_order_details.id','erp_order_details.buyer_po_number','erp_order_details.style_no','erp_order_details.reference_name','erp_order_details.order_type','erp_status.status_name',$buyer.'_template.template_file')
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

       //              		if($jobcard_id_list->buyer_po_number==null)
       //              		{
       //              			$jobcard_id_list->buyer_po_number='-';
							// }
							
							// format created_at date to dd-mm-yyyy
							if($jobcard_id_list->created_at!= null)
                    		{
								$date = Carbon::parse($jobcard_id_list->created_at)->toDateString();
                    			$jobcard_id_list->created_date = Carbon::parse($date)->format('d-m-Y');
                    		}
							
							if($jobcard_id_list->order_qnty_table_name == "orchestra_hang_tag_orders_qty")
							{
								$fkey = DB::table('orchestra_hang_tag_orders_qty')
								->where('id',$jobcard_id_list->order_qnty_table_id)
								->first()
								->oht_id;

								$row = DB::table('orchestra_hang_tag_orders')
								->where('id',$fkey)->first();
								
								$disney = DB::table('orchestra_parcel_details')
								->where('id',$row->orchestra_parcel_details_id)->first();
								
								$jobcard_id_list->Disney = $disney->Licence_Code;
								
								$jobcard_id_list->frontPath = DB::table('orchestra_template')
								->where('item_id',$row->template_front_id)
								->where('flag',1)
								->first()
								->template_file;

								$jobcard_id_list->backPath = DB::table('orchestra_template')
								->where('item_id',$row->template_front_id)
								->where('flag',0)
								->first()
								->template_file;

								$jobcard_id_list->ownId = $row->id;
								
							}
							
							if($jobcard_id_list->order_qnty_table_name == "orchestra_wash_care_label_orders_qty")
							{
								$fkey = DB::table('orchestra_wash_care_label_orders_qty')
								->where('id',$jobcard_id_list->order_qnty_table_id)
								->first()
								->owcl_id;

								$row = DB::table('orchestra_wash_care_label_orders')
								->where('id',$fkey)->first();
								
								$disney = DB::table('orchestra_parcel_details')
								->where('id',$row->orchestra_parcel_details_id)->first();
								
								$jobcard_id_list->Disney = $disney->Licence_Code;
								
								$jobcard_id_list->frontPath = DB::table('orchestra_template')
								->where('item_id',$row->template_id)
								->where('flag',1)
								->first()
								->template_file;

								$jobcard_id_list->backPath='/storage/app/template/Orchestra/WCL/sticker2.svg';
								$jobcard_id_list->middlePath='/storage/app/template/Orchestra/WCL/sticker3.svg';
							}
                	    });
						
						$jobcard_id_list = $jobcard_id_list->filter(function ($jobcard_id_list, $key) {
							
							switch( $jobcard_id_list->order_qnty_table_name )
							{
								case "orchestra_heat_transfer_orders_qty":
										return 0;
								case "orchestra_special_tag_orders_qty":
										return 0;
								case "orchestra_main_label_orders_qty":
										return 0;
								case "orchestra_size_label_orders_qty":
										return 0;
								default:
										return 1;
							}
							
						});

					}
					else
					{
						$jobcard_id_list->map(function ($jobcard_id_list,$key) {
							$jobcard_id_list->buyer='tesco';
							
							// format created_at date to dd-mm-yyyy
							if($jobcard_id_list->created_at!= null)
                    		{
								$date = Carbon::parse($jobcard_id_list->created_at)->toDateString();
                    			$jobcard_id_list->created_date = Carbon::parse($date)->format('d-m-Y');
                    		}
                	    });
					}

					return response()->json(['error'=> false,'message' =>'Success', 'erp_orders_list' => $jobcard_id_list], 200);
				}
				    
	
			} catch (\Exception $e) {
				return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
			}
	}
	
	
	
	
}