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
		
			
		$companies   = 	DB::table('erpuser_company_mapping')
								->join('company','erpuser_company_mapping.company','company.id')
								->where('erpuser_company_mapping.erp_user',$customer_id)
								->select('erpuser_company_mapping.*','company.name')
								->get();
		
		$company_id   = 	DB::table('company')
								->select('company.*')
								->first();
		
		$templates   = 		DB::table('erpusers_template_mapping')
								->join('buyers','erpusers_template_mapping.buyer','buyers.id')
							    ->where('erpusers_template_mapping.company',session()->get('company'))
								->where('erpusers_template_mapping.erp_user',$customer_id)
								->select('erpusers_template_mapping.*','buyers.template_table','buyers.item_master_table')
								->get();
		
		$new_orders = array();

		 foreach($templates as $value)
		 {
			 print_r($value->template_table.'-'.$value->templates);
			 $arr_template =  DB::table($value->template_table)
							 ->where('hierarchy_id',$value->templates)
							 ->pluck('item_id');
			 if($arr_template)
			 {
				 array_push($new_orders, $arr_template);
			 }
			 
		 }
		
		$orders_erp = array();
	//	print_r($new_orders);
		 foreach($new_orders as $values)
		 {
			 
			foreach($values as $valuess)
			{
				print_r($valuess);
			}
			 
//			 $erp_order_details = DB::table('erp_order_details')
//						 ->where('template_id',$values->item_id)
//						 ->first();
//			 
//			 if($erp_order_details)
//			 {
//				 array_push($orders_erp, $erp_order_details->erp_order_id);
//			 }
		 }
		
		 
	 	 $orders = DB::table('erp_orders')->join('customers','erp_orders.customer_id','customers.id')->join('erp_status','erp_orders.status','erp_status.id')->whereIn('id', $orders_erp)->where('erp_orders.status',1)->orWhere('erp_orders.status',2)->select('erp_orders.*','customers.company_name','erp_status.status_name')->orderBy('id', 'asc')->get();
		 return view('erp.Pomanager.poverify',['orders' => $orders,'companies' => $companies]);
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
			
            $orderlist   = ErpOrderDetails::join('tesco_template','erp_order_details.template_id','tesco_template.id')
							->join('erp_status','erp_order_details.status','erp_status.id')
							->where('erp_order_details.erp_order_id',$id)
                            ->select('erp_order_details.*','tesco_template.template_name','erp_status.status_name')
                            ->get();
			   
			$orders   = Erp::where('erp_orders.id',$id)
                            ->select('*')
                            ->get();
			   
		   foreach($orderlist as $value)
		   {
			 // print_r($value->template_id);
			  $price =  DB::table('price_taxation')->select('price_taxation.*')->where('customer_id',$customer_id)->where('buyer_id',$buyer_id)->where('template_id',$value->template_id)->get();
			   
			  $price_range =  DB::table('price_range')->select('price_range.*')->where('price_taxation_id',$price{0}->id)->where('volume_from','<=',$value->order_quantity)->where('volume_to','>=',$value->order_quantity)->get();
			  
			   if($price_range)
			   {
					 $value->price = $price_range{0}->price;
			   }
			   else
			   {
				    $value->price = 0;
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
			   
			if($count == 0)
			{
				$data 		=   DB::table('erp_orders')->where('id',$erp_order_id)
						  		->update([
								  'status' => 3,
								  'partial_status' => 0
								]);
				
				$data_erp		=   DB::table('erp_order_details')->where('erp_order_id',$erp_order_id)
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
				
				$data_erp		=   DB::table('erp_order_details')->where('erp_order_id',$erp_order_id)
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
				
				Mail::send('mails.error-report-po',['po'=> $po,'style_no'=>$style_no,'reference'=> $reference,'qty'=>$qty,'price'=> $price,'po_complete'=>$po_complete,'comment'=>$comment,'commenttext'=>$commenttext], function ($message) use ($to_email)
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
								  'partial_status' => 0
								]);
				
				$data_erp		=   DB::table('erp_order_details')->where('erp_order_id',$erp_order_id)
						  		->update([
								  'status' => 2
								]);
				
				$to_email =  DB::table('tesco_error_report_emails')->select('*')->where('id',1)->first();
			   
				Mail::send('mails.error-report-po',['po'=> $po,'style_no'=>$style_no,'reference'=> $reference,'qty'=>$qty,'price'=> $price,'po_complete'=>$po_complete,'comment'=>$comment,'commenttext'=>$commenttext], function ($message) use ($to_email)
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