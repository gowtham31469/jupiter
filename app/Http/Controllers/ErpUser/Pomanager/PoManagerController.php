<?php
namespace App\Http\Controllers\ErpUser\Pomanager;

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
use Carbon\Carbon;
use App\History;

class PoManagerController extends Controller
{
	private $current_date;
	
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
		$this->middleware('erpuser');
		// get the current time
		$this->current_date = Carbon::now('Asia/Kolkata')->toDateTimeString();
   
    }

    /**
     * Show the Po Manager List View.
     * @author [Gowtham] <[gowtham@wizroots.com]>
     * @return \Illuminate\Http\Response
     */
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
							//->where('flag',1)
							->distinct('hierarchy_id')->pluck('item_id');
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
										 ->join('erp_status','erp_orders.status','erp_status.id')
										 ->where('erp_order_details.template_id',$valuess)
										 ->whereIn('erp_orders.buyer_id', $buyers)
										 ->where('erp_orders.status',1)
										 ->orWhere('erp_orders.status',2)
										 ->groupBy('erp_orders.id','customers.company_name','erp_status.status_name','erp_order_details.created_at')
										 ->select('erp_orders.*','customers.company_name','erp_status.status_name','erp_order_details.created_at as po_created_date')
										 ->get();
						// dd($erp_order_details);			
				if(count($erp_order_details) > 0)
				{
					foreach($erp_order_details as $erp_order_det)
					{
						// format po created date to dd-mm-yyyy
						if($erp_order_det["po_created_date"] != null)
						{
							 $erp_order_det["po_created_date"] = Carbon::parse($erp_order_det["po_created_date"])->format('d-m-Y');
						}

						if (array_key_exists($erp_order_det->id,$orders_erp))
						{
							if($orders_erp[$erp_order_det->id]['po_created_date'] < $erp_order_det['po_created_date'])
							{
								$orders_erp[$erp_order_det->id]['po_created_date']=$erp_order_det['po_created_date'];
							}
						}
						else
						{
							$orders_erp[$erp_order_det->id] = $erp_order_det;
						}
					}
				}
			}
			 
		 }

		foreach($orders_erp as $orders_erp_count)
		{
			
				if($orders_erp_count->status == 1)
				{
					$new_orders_count++;
				}
				else if($orders_erp_count->status == 2)
				{
					$hold_orders_count++;
				}
		}
		return view('erp.Pomanager.poverify',['orders' => $orders_erp,'companies' => $companies,'new_orders' => $new_orders_count,'hold_orders' => $hold_orders_count]);
	} 
	
	public function poOrdersList()
	{
		
		$customer_id = Auth::guard('erpuser')->id();
		
		$companies   = 	DB::table('erpuser_company_mapping')
								->join('company','erpuser_company_mapping.company','company.id')
								->where('erpuser_company_mapping.erp_user',$customer_id)
								->select('erpuser_company_mapping.*','company.name', 'company.logo')
								->get();
		
		return view('erp.Pomanager.po-orders-list',['companies' => $companies]);
	}
	
	public function getOrderDetails(Request $request)
	{
		  try {
            $id = $request->erp_id; 
			  
			$data   = 	Erp::where('erp_orders.id',$id)
								->select('erp_orders.*')
								->get();
			  
			if(count($data) > 0)
			{
					$buyer_id = $data{0}->buyer_id;
					$customer_id = $data{0}->customer_id;


				   $arr_template =  DB::table('buyers')
									 ->where('id',$buyer_id)
									 ->first();

					$orderlist   = ErpOrderDetails::join($arr_template->template_table,'erp_order_details.template_id',$arr_template->template_table.'.item_id')
									->join('erp_status','erp_order_details.status','erp_status.id')
									->where('erp_order_details.erp_order_id',$id)
									->select('erp_order_details.*',$arr_template->template_table.'.template_name','erp_status.status_name')
									->get();

						$orders   = Erp::where('erp_orders.id',$id)
									->join('customers','erp_orders.customer_id','customers.id')
									->select('erp_orders.*','customers.company_name')
									->get();

				   foreach($orderlist as $value)
				   {
					  $price =  DB::table('price_taxation')->select('price_taxation.*')->where('customer_id',$customer_id)->where('buyer_id',$buyer_id)->where('template_id',$value->template_id)->first();

					   if (empty($price)) {
						  $value->price = 0;
					   }
					   else
					   {
						  $price_range =  DB::table('price_range')->select('price_range.*')->where('price_taxation_id',$price->id)->where('volume_from','<=',$value->order_quantity)->where('volume_to','>=',$value->order_quantity)->first();

						   if(empty($price_range))
						   {
							   $value->price = 0;	 
						   }
						   else
						   {
								$value->price = $price_range->price;
						   }
					   }


				   }
				
				   $result      = response()->json(['error'=> false,'message' =>'success','orderlist'=>$orderlist,'orders'=>$orders],200);
			}
		    else
		    {
					$result      = response()->json(['error'=> false,'message' =>'empty'],200);
		    }
			  
		
			   
           
        }
        catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
	}
	
	/**
	 * setCompanySession function
	 * 
	 * Store company data into session.
	 * @param Request $request
	 * @return void
	 */
	public function setCompanySession(Request $request)
	{
		$company_id = $request->company_id;
		$company_name = $request->company_name;

		if (session()->has('company') && session()->has('company_name')) {
			// delete existing company session 
			session()->forget('company');
			session()->forget('company_name');

			// update company session data
			session()->put('company', $company_id);
			session()->put('company_name', $company_name);
		}
		else 
		{
			// create company session data
			session(['company' => $company_id, 'company_name' => $company_name]);
		}
		
		return response()->json(['error'=> false,'message' =>'success'],200);
		 
	}
	
	/**
     * Update the status of PO.
     * @author [Gowtham] <[gowtham@wizroots.com]>
     * @return \Illuminate\Http\Response
     */
    public function changePoUpdatedStatus(Request $request)
    {
		
		$erp_order_id = $request->id;
		try { 
          $data 		=   DB::table('erp_orders')->where('id',$erp_order_id)
						  		->update([
								  'po_updated_status' => 0
								]);
		 $result        = response()->json(['error'=> false,'message' =>'success'],200);
        }
        catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
		
	}
	
	 /**
     * Show the Erp order details.
     * @author [Gowtham] <[gowtham@wizroots.com]>
     * @return \Illuminate\Http\Response
     */
	public function orderDetails(Request $request)
    {
		   try {
            $id = $request->id; 
			$buyer_id = $request->buyer_id;
			$customer_id = $request->customer_id;
			
			   
			   $arr_template =  DB::table('buyers')
								 ->where('id',$buyer_id)
								 ->first();
			   
            $orderlist   = ErpOrderDetails::join($arr_template->template_table,'erp_order_details.template_id',$arr_template->template_table.'.item_id')
							->join('erp_status','erp_order_details.status','erp_status.id')
							->where('erp_order_details.erp_order_id',$id)
                            ->select('erp_order_details.*',$arr_template->template_table.'.template_name','erp_status.status_name')
							->orderBy('erp_order_details.buyer_po_number','asc')
							->orderBy('erp_order_details.reference_name','asc')
                            ->get();
			   
				$orders   = Erp::where('erp_orders.id',$id)
							->join('customers','erp_orders.customer_id','customers.id')
                            ->select('erp_orders.*','customers.company_name')
                            ->get();
			
		   foreach($orderlist as $value)
		   {
			  $price =  DB::table('price_taxation')->select('price_taxation.*')->where('customer_id',$customer_id)->where('buyer_id',$buyer_id)->where('template_id',$value->template_id)->first();
			   	
			   if (empty($price)) {
				  $value->price = 0;
				 
			   }
			   else
			   {
				  $price_range =  DB::table('price_range')->select('price_range.*')->where('price_taxation_id',$price->id)->where('volume_from','<=',$value->order_quantity)->where('volume_to','>=',$value->order_quantity)->first();

				   if(empty($price_range))
				   {
					   $value->price = 0;	 
				   }
				   else
				   {
						$value->price = $price_range->price;
				   }
			   }
		   }
			   
           $result      = response()->json(['error'=> false,'message' =>'success','orderlist'=>$orderlist,'orders'=>$orders],200);
        }
        catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
	}
	/**
     * Submit Po.
     * @author [Gowtham] <[gowtham@wizroots.com]>
     * @return \Illuminate\Http\Response
     */
	public function submitPoVerify(Request $request)
	{
		try {
			$po = $request->po;
			$style_no = $request->style_no;
			$reference = $request->reference;
			$qty = $request->qty;
			$price = $request->price;
			$po_complete = $request->po_complete;
			$comment = $request->comment;
			$commenttext = $request->commenttext;
			$count = $request->count_i;
			$count_j = $request->count_j;
			$erp_order_id = $request->erp_order_id;
			$purchase_order_number = $request->purchase_order_number;
			$customer_name = $request->customer_name;
			$erp_user     = Auth::guard('erpuser')->id();

			// Get Erp status table id for 'Jobcard Created' and 'Cancelled' status.
			$erp_status_ids = DB::table('erp_status')->whereIn('status_name', ['Jobcard Created', 'Cancelled', 'Moved To JC Queue', 'Invoice Generated', 'Partial Invoice'])->pluck('id')->all();

			$orderlist   = ErpOrderDetails::join('erp_orders', 'erp_order_details.erp_order_id', 'erp_orders.id')
					->where('erp_order_details.erp_order_id', $erp_order_id)
					->whereNotIn('erp_order_details.status', $erp_status_ids)
					->select('erp_order_details.*', 'erp_orders.customer_id', 'erp_orders.buyer_id')
					->get();
			// Approve orders
			if ($count == 0) {

				foreach ($orderlist as $value) {
					$po_rejected_date = $value->po_rejected_date;
					$price =  DB::table('price_taxation')->select('price_taxation.id')->where('customer_id', $value->customer_id)->where('buyer_id', $value->buyer_id)->where('template_id', $value->template_id)->get();

					if(sizeof($price) > 0) {
						$price_range =  DB::table('price_range')->select('price_range.price')->where('price_taxation_id', $price{0}->id)->where('volume_from', '<=', $value->order_quantity)->where('volume_to', '>=', $value->order_quantity)->get();
					} else {
                        return response()->json(['error' => true, 'message' => 'Price details should be within a range'], 422);
					}

					if(sizeof($price_range) > 0) {
						// For first time approving, update po approved date .
						// if approving a rejected order , then update reapproved date
						$query = ErpOrderDetails::where('id', $value->id)
						->whereNotIn('status', $erp_status_ids);

						if($po_rejected_date === null || $po_rejected_date === 'null') {
							$action_id  = DB::table('actions')->where('action', 'ilike', 'PO Manager Verified')->value('id');
							$query = $query->update([
										'rate' => $price_range{0}->price,
										'status' => 3,
										'reason' => $commenttext,
										'po_manager_approved_by' => $erp_user,
										'po_verified_date' => $this->current_date
									]);
						} else {
							$action_id  = DB::table('actions')->where('action', 'ilike', 'PO Manager Reapproved')->value('id');
							$query->update([
								'rate' => $price_range{0}->price,
								'status' => 3,
								'reason' => $commenttext,
								'po_manager_approved_by' => $erp_user,
								'po_reapproved_date' => $this->current_date
							]);
						}

						// insert  into history table with action id
						$res = $this->insertHistoryData($value, $action_id, $erp_user, $commenttext);
					} else {
                        return response()->json(['error' => true, 'message' => 'Price details should be within a range'], 422);
					}
				}
				
				$data 		=   DB::table('erp_orders')->where('id', $erp_order_id)
					->update([
						'status' => 3,
						'partial_status' => 0
					]);
				$result      = response()->json(['error' => false, 'message' => 'success', 'status' => true], 200);

			} else if ($count_j == $count) {

				foreach ($orderlist as $value) {
					$po_rejected_date = $value->po_rejected_date;
					$price =  DB::table('price_taxation')->select('price_taxation.id')->where('customer_id', $value->customer_id)->where('buyer_id', $value->buyer_id)->where('template_id', $value->template_id)->get();

					if(sizeof($price) > 0) {
						$price_range =  DB::table('price_range')->select('price_range.price')->where('price_taxation_id', $price{0}->id)->where('volume_from', '<=', $value->order_quantity)->where('volume_to', '>=', $value->order_quantity)->get();
					} else {
                        return response()->json(['error' => true, 'message' => 'Price details should be within a range'], 422);
					}

					if(sizeof($price_range) > 0) {
						// For first time approving, update po approved date .
						// if approving a rejected order , then update reapproved date
						$query = ErpOrderDetails::where('id', $value->id)
						->whereNotIn('status', $erp_status_ids);

						if($po_rejected_date === null || $po_rejected_date === 'null') {
							$action_id  = DB::table('actions')->where('action', 'ilike', 'PO Manager Verified')->value('id');
							$query = $query->update([
										'rate' => $price_range{0}->price,
										'status' => 3,
										'reason' => $commenttext,
										'po_manager_approved_by' => $erp_user,
										'po_verified_date' => $this->current_date
									]);
						} else {
							$action_id  = DB::table('actions')->where('action', 'ilike', 'PO Manager Reapproved')->value('id');
							$query->update([
								'rate' => $price_range{0}->price,
								'status' => 3,
								'reason' => $commenttext,
								'po_manager_approved_by' => $erp_user,
								'po_reapproved_date' => $this->current_date
							]);
						}

						// insert  into history table with action id
						$res = $this->insertHistoryData($value, $action_id, $erp_user, $commenttext);
					} else {
                        return response()->json(['error' => true, 'message' => 'Price details should be within a range'], 422);
					}
				}

				// approve partial orders
				$data 	=   DB::table('erp_orders')->where('id', $erp_order_id)
								->update([
									'status' => 3,
									'partial_status' => 1
								]);

				$to_email =  DB::table('tesco_error_report_emails')->select('*')->where('id', 1)->first();

				Mail::send('mails.error-report-po', ['po' => $po, 'style_no' => $style_no, 'reference' => $reference, 'qty' => $qty, 'price' => $price, 'po_complete' => $po_complete, 'comment' => $comment, 'commenttext' => $commenttext, 'purchase_order_number' => $purchase_order_number, 'customer_name' => $customer_name, 'erp_order_id' => $erp_order_id], function ($message) use ($to_email) {
					$message->from('admin@jga.com');
					$message->subject('Jupiter Garments Accessories - Error Report');
					$message->to($to_email->email_address);
				});
				$result      = response()->json(['error' => false, 'message' => 'success', 'status' => true], 200);

			} else {
				$action_id  = DB::table('actions')->where('action', 'ilike', 'PO Manager Rejected')->value('id');
				$data 		=   DB::table('erp_orders')->where('id', $erp_order_id)
					->update([
						'status' => 2,
						'partial_status' => $count_j
					]);

				$data_erp		=   ErpOrderDetails::where('erp_order_id', $erp_order_id)
					->whereNotIn('status', $erp_status_ids)
					->update([
						'status' => 2,
						'reason' => $commenttext,
						'po_rejected_date' => $this->current_date,
						'po_rejected_by' => $erp_user,
					]);
				// insert  into history table with action id
				foreach ($orderlist as $value) {

					$this->insertHistoryData($value, $action_id, $erp_user, $commenttext);	
				}	
				$to_email =  DB::table('tesco_error_report_emails')->select('*')->where('id', 1)->first();

				Mail::send('mails.error-report-po', ['po' => $po, 'style_no' => $style_no, 'reference' => $reference, 'qty' => $qty, 'price' => $price, 'po_complete' => $po_complete, 'comment' => $comment, 'commenttext' => $commenttext, 'purchase_order_number' => $purchase_order_number, 'customer_name' => $customer_name, 'erp_order_id' => $erp_order_id], function ($message) use ($to_email) {
					$message->from('admin@jga.com');
					$message->subject('Jupiter Garments Accessories - Error Report');
					$message->to($to_email->email_address);
				});
				$result      = response()->json(['error' => false, 'message' => 'success', 'status' => false], 200);
			}

		} catch (\Exception $e) {
			$result = response()->json(['Message' => $e->getMessage(), 'Line' => $e->getLine(), 'File' => $e->getFile()]);
		}
		return $result;
	}

	/** Function to insert OR update history table
	 * @author praveen <praveen@wizroots.com>
	 * @param mixed $data, $action_id, $erp_user
	 */
	public function insertHistoryData($data, $action_id, $erp_user, $commenttext)
	{
		try {
				$buyer_id       = $data->buyer_id;
				$buyer          = DB::table('buyers')->select('buyer_name')->where('id', '=', $buyer_id)->first();
				$template       = 'Available';
				$qty_row_id     = $data->order_qnty_table_id;
				$qnty_table     = $data->order_qnty_table_name;
				$customer_id    = $data->customer_id;

				if($buyer->buyer_name === 'Tesco'){

					$main_table     = str_replace("_quanty","",$qnty_table);
					$qnty_id        = str_replace("_orders_quanty","_order_id",str_replace("tesco_","",$qnty_table));
					$order_details  = DB::table($qnty_table)
										->select($main_table.'.*')
										->where($qnty_table.'.id', $qty_row_id)
										->join($main_table, $main_table.'.id', $qnty_table.'.'.$qnty_id)
										->first();
					$season         = $order_details->season_code;
					$country        = $order_details->zone;
					$style_zone     = strtolower($country).'_style_ref';
					$template_id    = $order_details->template_id;
					$template_level = DB::table('tesco_item_master as t1')
										->select('t3.item_name as material_sub', 't4.item_name as material', 't1.item_name as ref')
										->leftJoin('tesco_item_master as t2', 't2.id','=','t1.parent_id')
										->leftJoin('tesco_item_master as t3', 't3.id','=','t2.parent_id')
										->leftJoin('tesco_item_master as t4', 't4.id','=','t3.parent_id')
										->where('t1.id', $template_id)
										->first();
					
					$material_name  = $template_level->material;
					$material_sub   = $template_level->material_sub;
					$material_ref   = $template_level->ref;
					$customer_po    = $order_details->purchase_order_no;
					$buyer_po       = $order_details->po_number;
					$style          = $order_details->$style_zone;
					$ocn_number     = $order_details->ocn_number;
					

				} else if($buyer->buyer_name === 'Orchestra'){

					$main_table     = str_replace("_qty","",$qnty_table);
					$result_str     = str_replace("_"," ",str_replace("_orders","",$main_table));
					$qnty_id        = '';
					foreach (explode(' ', $result_str) as $word)
					{
					   $qnty_id .= strtolower($word[0]);
					}
					
					$order_details  = DB::table($qnty_table)->select($main_table.'.*')->where($qnty_table.'.id', $qty_row_id)->join($main_table, $main_table.'.id', $qnty_table.'.'.$qnty_id.'_id')
									->first();
					
					// template front and back id storing in orchestra_hang_tag_orders table
					$template_id = $main_table === 'orchestra_hang_tag_orders'?$order_details->template_front_id:$order_details->template_id;

					$template_data = DB::table('orchestra_item_master')
									->where('orchestra_item_master.id', $template_id)
									->select('orchestra_item_master.material_id', 'orchestra_item_master.item_name', 'orchestra_item_master.sub_material_id')->first();
					$material_name  = DB::table('orchestra_item_master')->where('id', '=', $template_data->material_id)->value('item_name');
					$material_sub  = DB::table('orchestra_item_master')->where('id', '=', $template_data->sub_material_id)->value('item_name');
					
					$material_ref   = $template_data->item_name;
					$customer_po    = $order_details->po_number;
					$buyer_po       = $order_details->po;
					$style          = $order_details->uvc;
					$ocn_number     = null;
					$season         = $order_details->season_code;
					$country        = null;

				} else {

					$template       = 'Not Available';

				}


				$history_id = History::updateOrCreate(['erp_order_details_id' => $data->id], [
					'buyer_id'             => $buyer_id,
					'template'             => $template,
					'season'               => $season,
					'country'              => $country,
					'customer_id'          => $customer_id,
					'material'             => $material_name,
					'mat_sub'              => $material_sub,
					'reference'            => $material_ref,
					'style'                => $style,
					'po'                   => $customer_po,
					'buyer_po'             => $buyer_po,
					'ocn'                  => $ocn_number,
					'created_at'           => $this->current_date,
					'updated_at'           => $this->current_date,
				]);

				DB::table('event_history')
				->insert([
					'history_id'  => $history_id->id,
					'action_id'   => $action_id,
					'created_by'  => $erp_user,
					'reason'      => $commenttext,
					'created_at'  => $this->current_date,
					'updated_at'  => $this->current_date,
				]);

			return "success";
		} catch (\Exception $e) {
			return response()->json(['Message' => $e->getMessage(), 'Line' => $e->getLine(), 'File' => $e->getFile()]);
		}
	}
}
