<?php

namespace App\Http\Controllers\Merchandiser;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Response;
use View;
use DB;
use Auth;
use App\WashLabelOrders;
use App\WashLabelOrdersQty;
use Carbon\Carbon;
use App\ErpOrders as Erp;
use App\ErpOrderDetails as ErpOrderDetails;
/**
 * Merchandiser Page controller
 * @author <Praveen>
 */
class HomeController extends Controller
{
    private $current_date;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('merchandiser_auth');

        // get the current time
        $this->current_date = Carbon::now('Asia/Kolkata')->toDateTimeString();
    }

    /**
     * Show the merchandiser Pending orders page.
     * @author [Praveen] <[praveen@wizroots.com]>
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
             $orders = DB::table('orchestra_wash_care_label_orders')
                          ->join('orchestra_parcel_details','orchestra_parcel_details.UVC','=','orchestra_wash_care_label_orders.uvc')
                          ->join('merchandisers','merchandisers.id','=','orchestra_wash_care_label_orders.merchandisers_id')
                          ->select('orchestra_wash_care_label_orders.uvc as UVC',DB::raw("'WashCareLabel' as \"Artwork\""),'orchestra_parcel_details.SupplierName as SupplierName','orchestra_wash_care_label_orders.created_at as OrderDate','orchestra_wash_care_label_orders.delivery_date as DeliveryDate','orchestra_wash_care_label_orders.status as Status','orchestra_parcel_details.Season_Code as Season','merchandisers.merchandiser_name','orchestra_wash_care_label_orders.id')
                          ->where('orchestra_wash_care_label_orders.status','=','Buyer Approval Pending')
                          ->groupBy('orchestra_parcel_details.UVC','orchestra_wash_care_label_orders.id','orchestra_parcel_details.SupplierName','orchestra_parcel_details.Season_Code','merchandisers.merchandiser_name')
                          ->get();
              $buyers = DB::table('buyers')->where('is_active','=',1)->pluck('buyer_name','id')->all();
             return view('merchandiser.home',['orders' => $orders,'buyers' => $buyers]);
        } catch (\Exception $e)
          {
             return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
          }
        
    }
    /**
       * Get orchestra wcl by ID.
       * @param wcl id
       * @return \Illuminate\Http\Response
       */
      public function getWCLById(Request $request)
      {
        try {
				$id = $request['id'];
				$wcl_orders_qty = DB::table('orchestra_wash_care_label_orders_qty')->select('size','uvc_tco','quantity')->where('orchestra_wash_care_label_orders_qty.owcl_id',$id)->get();
			
				//To get Wash care label composition
				
			    $wcl_orders = DB::table('orchestra_wash_care_label_orders')->select('*')->where('orchestra_wash_care_label_orders.id',$id)->get();
			
			    $uvc = $wcl_orders{0}->uvc; 
			
			    $made_in_id = $wcl_orders{0}->made_in_id;
			$washing_id = $wcl_orders{0}->washing_id;
			$whitening_id = $wcl_orders{0}->whitening_id;
			$dryer_id = $wcl_orders{0}->dryer_id;
			$ironing_id = $wcl_orders{0}->ironing_id;
			$cleaning_id = $wcl_orders{0}->cleaning_id;
			
			    $care_instruction_id = explode(',',$wcl_orders{0}->care_instruction_id);
			
				 $care_instruction = DB::table('orchestra_care_instruction')->select('*')->whereIn('id',$care_instruction_id)->get(); 
			
			$washing = DB::table('orchestra_care_symbols')->select('*')->where('id',$washing_id)->get(); 
			$whitening = DB::table('orchestra_care_symbols')->select('*')->where('id',$whitening_id)->get(); 
			$dryer = DB::table('orchestra_care_symbols')->select('*')->where('id',$dryer_id)->get(); 
			$ironing = DB::table('orchestra_care_symbols')->select('*')->where('id',$ironing_id)->get(); 
			$cleaning = DB::table('orchestra_care_symbols')->select('*')->where('id',$cleaning_id)->get(); 
			
				$disney = DB::table('orchestra_template')->select('*')->where('item_id',$wcl_orders{0}->template_id)->get(); 
			
			    $made_in= DB::table('orchestra_countries')->select('*')->where('id',$made_in_id)->get();
			
				if($disney{0}->disney == 0)
				{
					$orchestra_wash_template_front="/storage/app/template/Orchestra/WCL/sticker1.svg";
					$orchestra_wash_template_back="/storage/app/template/Orchestra/WCL/sticker2.svg";
					$orchestra_wash_template_middle="/storage/app/template/Orchestra/WCL/sticker3.svg";
				}
				else
				{
					$orchestra_wash_template_front="/storage/app/template/Orchestra/WCL/sticker1-disney.svg";
					$orchestra_wash_template_back="/storage/app/template/Orchestra/WCL/sticker2.svg";
					$orchestra_wash_template_middle="/storage/app/template/Orchestra/WCL/sticker3.svg";
				}
			    
				foreach($wcl_orders as $value1)
				{
					$orchestra_wash_label_orders_qty=DB::table('orchestra_wash_care_label_orders_qty')->select('*')->where('owcl_id',$value1->id)->get();
					$value1->qty=$orchestra_wash_label_orders_qty;

					 $orchestra_wash_care_composition=DB::table('orchestra_wash_care_composition')->leftJoin('orchestra_compositions','orchestra_compositions.id','=','orchestra_wash_care_composition.composition_id')->leftJoin('orchestra_parts','orchestra_parts.id','=','orchestra_wash_care_composition.parts_id')->select('orchestra_wash_care_composition.*','orchestra_compositions.french','orchestra_compositions.english','orchestra_compositions.spanish','orchestra_compositions.portuguese','orchestra_compositions.italian','orchestra_compositions.dutch','orchestra_compositions.german','orchestra_compositions.russian','orchestra_compositions.chinese','orchestra_compositions.arabic','orchestra_compositions.greek','orchestra_compositions.turkish','orchestra_parts.french as parts_french','orchestra_parts.english as parts_english','orchestra_parts.spanish as parts_spanish','orchestra_parts.portuguese as parts_portuguese','orchestra_parts.italian as parts_italian','orchestra_parts.dutch as parts_dutch','orchestra_parts.german as parts_german','orchestra_parts.russian as parts_russian','orchestra_parts.chinese as parts_chinese','orchestra_parts.arabic as parts_arabic','orchestra_parts.greek as parts_greek','orchestra_parts.turkish as parts_turkish')->orderBy('orchestra_wash_care_composition.id','asc')->where('owcl_id',$value1->id)->get();
					 $value1->composition=$orchestra_wash_care_composition;
					$care_instructions=array();
					foreach(explode(',',$value1->care_instruction_id) as $value2)
					{
						array_push($care_instructions,$value2);
					}
					$value1->care_instruction=$care_instructions;
				}
			
				//To get Order Details like UVC,Size
			
				$order_details = DB::table('orchestra_parcel_details') ->leftJoin('orchestra_size_chart','orchestra_parcel_details.Size','=','orchestra_size_chart.age')->select('*',DB::raw("sum(\"Quantity\") as Sum"))->where('UVC',$uvc)->groupBy('Size','orchestra_parcel_details.id','orchestra_size_chart.id')->orderBy('orchestra_parcel_details.id')->get();
				
			    $code=$order_details{0}->Family_Code;
				
				//To get Product Family
			    $product_family = DB::table('orchestra_product_family')->select('*')->where('code',$code)->get();
			
                // $data['orchestra_wash_template_id']=2;
                return response()->json(['error'=>'false','message' =>'success', 'wcl_orders_qty' => $wcl_orders_qty,'order_details'=>$order_details,'care_instruction'=>$care_instruction,'orchestra_wash_care_label_orders'=>$wcl_orders,'product_family'=>$product_family,'made_in'=>$made_in,'orchestra_wash_template_front'=>$orchestra_wash_template_front,'orchestra_wash_template_back'=>$orchestra_wash_template_back,'orchestra_wash_template_middle'=>$orchestra_wash_template_middle,'washing'=>$washing,'whitening'=>$whitening,'dryer'=>$dryer,'ironing'=>$ironing,'cleaning'=>$cleaning],200);
            } catch (\Exception $e)
            {
              return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
            }
      }
      /**
     * Add merchandiser approved comment for wcl.
     * @param \Illuminate\Http\Request
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function addWCLApproveCommentById(Request $request)
      {
        try {
        	      $id        = $request['id'];
                $comment   = $request['comment'];
                $buyer_id = DB::table("buyers")->where('buyer_name','ilike','Orchestra')->pluck('id')->first();
                
                WashLabelOrders::where('id',$id)
                ->update([
                  'merchandiser_comment' => $comment,
                  'approved_date'        => $this->current_date,
                  'status'               => "Order Approved"
                ]);
                $order_details  = WashLabelOrders::where('id',$id)
                                  ->select('id','status as order_status','customer_id','po_number','pi_id', 'order_created_at', 'order_approved_at')
                                  ->first();

                $order_qty_details  = WashLabelOrdersQty::where('owcl_id', $order_details->id)
                ->select('id', DB::raw("'orchestra_wash_care_label_orders_qty' as \"table_name\""))->get();

                $order_status_id  = DB::table('orders_status')->select('id')->where('status_name', 'ilike', $order_details->order_status)->first();

                $customer_data  = DB::table('customers')->select('pi_required', 'po_required')->where('id', $order_details->customer_id)->first();

                if ($customer_data->pi_required === 1 && $customer_data->po_required === 0) 
                {
                  $po_number     = $order_details->pi_id;
                }
                else if($customer_data->po_required === 1)
                {
                  $po_number     = $order_details->po_number;
                }         
                
                $erp_orders = Erp::where('buyer_id',$buyer_id)->where('customer_id',$order_details->customer_id)->where('po_no', $po_number)->first();
                
                if ($erp_orders) 
                {
                  // update order status in erp_order_details table
                    foreach ($order_qty_details as $key => $value) 
                    {
                      $erp_order_qty = ErpOrderDetails::where('order_qnty_table_name',$value->table_name)->where('order_qnty_table_id',$value->id)->first();

                      if ($erp_order_qty) 
                      {
                        ErpOrderDetails::where('id',$erp_order_qty->id)
                              ->update([
                                        'order_status_id'  => $order_status_id->id,
                                        'order_date'       => $order_details->order_created_at,
                                        'aw_date'          => $order_details->order_approved_at
                                      ]);
                      }
                    }
                }

             $result = response()->json(['error'=> false,'message' =>'Success'],200);

            } catch (\Exception $e)
            {
              $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
            }
            return $result;
      }
      /**
     * Add merchandiser rejected comment for wcl.
     * @param \Illuminate\Http\Request
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function addWCLRejectCommentById(Request $request)
      {
        try {
        	      $id        = $request['id'];
		          $comment   = $request['comment'];
		          
		          WashLabelOrders::where('id',$id)
		           ->update([
		             'merchandiser_comment' => $comment,
		             'rejected_date'        => $this->current_date,
		             'status'               => "Order Rejected"
		          ]);

                $result = response()->json(['error'=> false,'message' =>'Success'],200);

            } catch (\Exception $e)
            {
                $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
            }
            return $result;
      }
       /**
	     * Show the merchandiser past orders page.
	     * @author [Praveen] <[praveen@wizroots.com]>
	     * @return \Illuminate\Http\Response
	     */
	    public function pastOrders()
	    {
	        try{
	             $orders = DB::table('orchestra_wash_care_label_orders')
	                          ->join('orchestra_parcel_details','orchestra_parcel_details.UVC','=','orchestra_wash_care_label_orders.uvc')
	                          ->join('merchandisers','merchandisers.id','=','orchestra_wash_care_label_orders.merchandisers_id')
	                          ->select('orchestra_wash_care_label_orders.uvc as UVC',DB::raw("'WashCareLabel' as \"Artwork\""),'orchestra_parcel_details.SupplierName as SupplierName','orchestra_wash_care_label_orders.created_at as OrderDate','orchestra_wash_care_label_orders.delivery_date as DeliveryDate','orchestra_wash_care_label_orders.status as Status','orchestra_parcel_details.Season_Code as Season','merchandisers.merchandiser_name','orchestra_wash_care_label_orders.id','orchestra_wash_care_label_orders.approved_date as ApprovedDate','orchestra_wash_care_label_orders.rejected_date as RejectedDate')
	                          ->where('orchestra_wash_care_label_orders.status','<>','Buyer Approval Pending')
	                          ->groupBy('orchestra_parcel_details.UVC','orchestra_parcel_details.SupplierName','orchestra_wash_care_label_orders.id','orchestra_parcel_details.Season_Code','merchandisers.merchandiser_name')
	                          ->get();
               $buyers = DB::table('buyers')->where('is_active','=',1)->pluck('buyer_name','id')->all();
	             return view('merchandiser.past-orders',['orders' => $orders,'buyers' => $buyers]);
	        } catch (\Exception $e)
	          {
	             return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
	          }
	        
	    }
}
