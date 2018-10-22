<?php
namespace App\Http\Controllers\ErpUser\Pomanager;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Response;
use View;
use DB;
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
		 $orders = DB::table('erp_orders')->join('customers','erp_orders.customer_id','customers.id')->select('erp_orders.*','customers.company_name')->get();
		
		 return view('erp.Pomanager.poverify',['orders' => $orders]);
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
							->where('erp_order_details.erp_order_id',$id)
                            ->select('erp_order_details.*','tesco_template.template_name')
                            ->get();
			   
		   foreach($orderlist as $value)
		   {
			 // print_r($value->template_id);
			  $price =  DB::table('price_taxation')->select('price_taxation.*')->where('customer_id',$customer_id)->where('buyer_id',$buyer_id)->where('template_id',$value->template_id)->get();
			   
			  $price_range =  DB::table('price_range')->select('price_range.*')->where('price_taxation_id',$price{0}->id)->where('volume_from','<=',$value->order_quantity)->where('volume_to','>=',$value->order_quantity)->get();
			   
			  
			   
				   
			   $value->price = $price_range{0}->price;
		   }
			   
           $result      = response()->json(['error'=> false,'message' =>'success','orderlist'=>$orderlist ],200);
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
			   
			   
			   
           $result      = response()->json(['error'=> false,'message' =>'success','orderlist'=>$orderlist ],200);
        }
        catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
	}

}