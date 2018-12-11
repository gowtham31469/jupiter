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

class PoManagerController extends Controller
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
		$customer_id = Auth::guard('erpuser')->id();
		$new_orders_count=0;
		$hold_orders_count=0;
		
				$buyers =   DB::table('erpuser_buyer')
                                    ->where('erpuser_buyer.erp_user',$customer_id)
                                    ->pluck('erpuser_buyer.buyer')
                                    ->all();

		
		$companies   = 	DB::table('erpuser_company_mapping')
								->join('company','erpuser_company_mapping.company','company.id')
								->where('erpuser_company_mapping.erp_user',$customer_id)
								->select('erpuser_company_mapping.*','company.name')
								->get();
		
		$company_id   = DB::table('company')
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
		
		
		//return $templates;
		
		
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
										 ->join('erp_status','erp_orders.status','erp_status.id')
										 ->where('erp_order_details.template_id',$valuess)
										 ->whereIn('erp_orders.buyer_id', $buyers)
										 ->where('erp_orders.status',1)
										 ->orWhere('erp_orders.status',2)
										 ->groupBy('erp_orders.id','customers.company_name','erp_status.status_name')
										 ->select('erp_orders.*','customers.company_name','erp_status.status_name')
										 ->get();
				if(count($erp_order_details) > 0)
				{
					foreach($erp_order_details as $erp_order_det)
					{
						$orders_erp[$erp_order_det->id] = $erp_order_det;
						
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
		//return $new_orders;
		return view('erp.Pomanager.poverify',['orders' => $orders_erp,'companies' => $companies,'new_orders' => $new_orders_count,'hold_orders' => $hold_orders_count]);
	} 
	
	public function setCompanySession(Request $request)
	{
		$company_id = $request->company_id;
		$company_name = $request->company_name;
		session()->put('company', $company_id);
		session()->put('company_name', $company_name);
		$result      = response()->json(['error'=> false,'message' =>'success'],200);
		return $result;
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
			   
			if($count == 0)
			{
				$data 		=   DB::table('erp_orders')->where('id',$erp_order_id)
						  		->update([
								  'status' => 3,
								  'partial_status' => 0
								]);
				
				$data_erp		=   ErpOrderDetails::where('erp_order_id',$erp_order_id)
									->where('status','!=',4)
									->update([
									  'status' => 3
									]);
				
				
				$orderlist   = ErpOrderDetails::join('erp_orders','erp_order_details.erp_order_id','erp_orders.id')
								->where('erp_order_details.erp_order_id',$erp_order_id)
								->select('erp_order_details.*','erp_orders.customer_id','erp_orders.buyer_id')
								->get();
				 foreach($orderlist as $value)
		   		 {
					 
					 $price =  DB::table('price_taxation')->select('price_taxation.*')->where('customer_id',$value->customer_id)->where('buyer_id',$value->buyer_id)->where('template_id',$value->template_id)->get();
			   
					 $price_range =  DB::table('price_range')->select('price_range.*')->where('price_taxation_id',$price{0}->id)->where('volume_from','<=',$value->order_quantity)->where('volume_to','>=',$value->order_quantity)->get();
					 
					 DB::table('erp_order_details')->where('erp_order_id',$erp_order_id)
						  		->update([
								  'rate' => $price_range{0}->price
								]);
				 }
				
				
				$result      = response()->json(['error'=> false,'message' =>'success','status'=>true],200);
			}
		    else if($count_j == $count)
		    {
				$data 		=   DB::table('erp_orders')->where('id',$erp_order_id)
						  		->update([
								  'status' => 3,
								  'partial_status' => 1
								]);
				
				$data_erp		=   ErpOrderDetails::where('erp_order_id',$erp_order_id)
									->where('status','!=',4)
									->update([
									  'status' => 3
									]);
				
				$orderlist   = ErpOrderDetails::join('erp_orders','erp_order_details.erp_order_id','erp_orders.id')
								->where('erp_order_details.erp_order_id',$erp_order_id)
								->select('erp_order_details.*','erp_orders.customer_id','erp_orders.buyer_id')
								->get();
				 foreach($orderlist as $value)
		   		 {
					 
					 $price =  DB::table('price_taxation')->select('price_taxation.*')->where('customer_id',$value->customer_id)->where('buyer_id',$value->buyer_id)->where('template_id',$value->template_id)->get();
			   
					 $price_range =  DB::table('price_range')->select('price_range.*')->where('price_taxation_id',$price{0}->id)->where('volume_from','<=',$value->order_quantity)->where('volume_to','>=',$value->order_quantity)->get();
					 
					 DB::table('erp_order_details')->where('erp_order_id',$erp_order_id)
						  		->update([
								  'rate' => $price_range{0}->price
								]);
				 }
				
				$to_email =  DB::table('tesco_error_report_emails')->select('*')->where('id',1)->first();
				
				Mail::send('mails.error-report-po',['po'=> $po,'style_no'=>$style_no,'reference'=> $reference,'qty'=>$qty,'price'=> $price,'po_complete'=>$po_complete,'comment'=>$comment,'commenttext'=>$commenttext,'purchase_order_number'=>$purchase_order_number,'customer_name'=>$customer_name,'erp_order_id'=>$erp_order_id], function ($message) use ($to_email)
				{
					$message->from('praveen@wizroots.com');
					$message->subject('Jupiter Garments Accessories - Error Report');
					$message->to($to_email->email_address);
				});  
				$result      = response()->json(['error'=> false,'message' =>'success','status'=>true],200);
			}
			else
		    {
				
				$data 		=   DB::table('erp_orders')->where('id',$erp_order_id)
						  		->update([
								  'status' => 2,
								  'partial_status' => $count_j
								]);
				
				$data_erp		=   ErpOrderDetails::where('status','!=',3)
							    	->where('status','!=',4)
									->where('erp_order_id',$erp_order_id)
									->update([
									  'status' => 2
									]);
				
				$to_email =  DB::table('tesco_error_report_emails')->select('*')->where('id',1)->first();
			   
				Mail::send('mails.error-report-po',['po'=> $po,'style_no'=>$style_no,'reference'=> $reference,'qty'=>$qty,'price'=> $price,'po_complete'=>$po_complete,'comment'=>$comment,'commenttext'=>$commenttext,'purchase_order_number'=>$purchase_order_number,'customer_name'=>$customer_name,'erp_order_id'=>$erp_order_id], function ($message) use ($to_email)
				{
					$message->from('praveen@wizroots.com');
					$message->subject('Jupiter Garments Accessories - Error Report');
					$message->to($to_email->email_address);
				});  
				$result      = response()->json(['error'=> false,'message' =>'success','status'=>false],200);
		    }
			   
        }
        catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
	}

}