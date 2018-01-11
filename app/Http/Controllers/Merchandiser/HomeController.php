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
use Datetime;
/**
 * Merchandiser Page controller
 * @author <Praveen>
 */
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('merchandiser_auth');
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
                          ->join('orchestra_item_details','orchestra_item_details.UVC','=','orchestra_wash_care_label_orders.uvc')
                          ->join('merchandisers','merchandisers.id','=','orchestra_wash_care_label_orders.merchandisers_id')
                          ->select('orchestra_wash_care_label_orders.uvc as UVC',DB::raw('"WashCareLabel" as Artwork'),'orchestra_item_details.SupplierName as SupplierName','orchestra_item_details.Collection as Season','orchestra_wash_care_label_orders.created_at as OrderDate','orchestra_wash_care_label_orders.delivery_date as DeliveryDate','orchestra_wash_care_label_orders.status as Status','orchestra_item_details.Collection as Season','merchandisers.merchandiser_name','orchestra_wash_care_label_orders.id')
                          ->where('orchestra_wash_care_label_orders.status','=','Pending')
                          ->groupBy('orchestra_item_details.UVC')
                          ->groupBy('orchestra_wash_care_label_orders.id')
                          ->get();
             return view('merchandiser.home',['orders' => $orders]);
        } catch (\Exception $e)
          {
             return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
          }
        
    }
    /**
       * Get orchestra wcl by ID.
       * @param wcl id
       * @author <Praveen>
       * @return \Illuminate\Http\Response
       */
      public function getWCLById(Request $request)
      {
        try {
              $id = $request['id'];
              $wcl_orders_qty = DB::table('orchestra_wash_care_label_orders_qty')->select('size','uvc_tco','quantity')->where('orchestra_wash_care_label_orders_qty.owcl_id',$id)->get();
              return response()->json(['error'=>'false','message' =>'success', 'wcl_orders_qty' => $wcl_orders_qty],200);
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
		          $date = new DateTime();         
		          $approved_date = $date->format('Y-m-d');
		          WashLabelOrders::where('id',$id)
		           ->update([
		             'merchandiser_comment' => $comment,
		             'approved_date'        => $approved_date,
		             'status'               => "Approved"
		          ]);

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
		          $date = new DateTime();         
		          $rejected_date = $date->format('Y-m-d');
		          WashLabelOrders::where('id',$id)
		           ->update([
		             'merchandiser_comment' => $comment,
		             'rejected_date'        => $rejected_date,
		             'status'               => "Rejected"
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
	                          ->join('orchestra_item_details','orchestra_item_details.UVC','=','orchestra_wash_care_label_orders.uvc')
	                          ->join('merchandisers','merchandisers.id','=','orchestra_wash_care_label_orders.merchandisers_id')
	                          ->select('orchestra_wash_care_label_orders.uvc as UVC',DB::raw('"WashCareLabel" as Artwork'),'orchestra_item_details.SupplierName as SupplierName','orchestra_item_details.Collection as Season','orchestra_wash_care_label_orders.created_at as OrderDate','orchestra_wash_care_label_orders.delivery_date as DeliveryDate','orchestra_wash_care_label_orders.status as Status','orchestra_item_details.Collection as Season','merchandisers.merchandiser_name','orchestra_wash_care_label_orders.id','orchestra_wash_care_label_orders.approved_date as ApprovedDate','orchestra_wash_care_label_orders.rejected_date as RejectedDate')
	                          ->where('orchestra_wash_care_label_orders.status','<>','Pending')
	                          ->groupBy('orchestra_item_details.UVC')
	                          ->groupBy('orchestra_wash_care_label_orders.id')
	                          ->get();
	             return view('merchandiser.past-orders',['orders' => $orders]);
	        } catch (\Exception $e)
	          {
	             return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
	          }
	        
	    }
}
