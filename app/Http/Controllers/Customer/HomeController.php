<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

use Response;
use View;
use DB;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('customer_auth');
    }

    /**
     * Show the application dashboard.
     * @author [Praveen] <[praveen@wizroots.com]>
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $customer_id = Auth::guard('web_customer')->user()->id;
            $buyers = DB::table('buyers')
                          ->select('buyers.*')
                          ->join('customer_buyers','customer_buyers.buyer_id','buyers.id')
                          ->where('customer_buyers.customer_id',$customer_id)
                          ->get();
            $buyer_count = count($buyers);
            return view('customer/home')->with(['buyers' => $buyers,'buyer_count' => $buyer_count]);
        } catch (\Exception $e)
          {
                 return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
          }
        
    }

    /**
     * Show the customer order status.
     * @return \Illuminate\Http\Response
     */
    public function customerOrderStatus()
    {
        try{
            $customer_id = Auth::guard('web_customer')->user()->id;
            $buyers = DB::table('buyers')
                          ->select('buyers.*')
                          ->join('customer_buyers','customer_buyers.buyer_id','buyers.id')
                          ->where('customer_buyers.customer_id',$customer_id)
                          ->get();
            $buyer_count = count($buyers);
            return view('customer/customer-order-status')->with(['buyers' => $buyers,'buyer_count' => $buyer_count]);
        } catch (\Exception $e)
          {
                 return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
          }
        
    }


    /**
     * Method to get the order status of a customer for a particular buyer
     * @param  Request $request Buyer id
     * @return [type]           List of all orders with status
     */
    public function getOrderStatus() {
         try{
                $customer_id = Auth::guard('web_customer')->user()->id;
                $buyers = DB::table('buyers')
                              ->select('buyers.*')
                              ->join('customer_buyers','customer_buyers.buyer_id','buyers.id')
                              ->where('customer_buyers.customer_id',$customer_id)
                              ->get();
                $buyer_count = count($buyers);
                $data['orchestra_hang_tag_orders'] = DB::table('orchestra_hang_tag_orders')->join('orchestra_item_details','orchestra_item_details.UVC','=','orchestra_hang_tag_orders.uvc')->select('orchestra_hang_tag_orders.uvc as UVC',DB::raw('"HangTag" as Artwork'),'orchestra_item_details.SupplierName as     SupplierName','orchestra_item_details.Collection as Season','orchestra_hang_tag_orders.created_at as OrderDate','orchestra_hang_tag_orders.delivery_date as DeliveryDate','orchestra_hang_tag_orders.status as Status')->groupBy('orchestra_item_details.UVC')->get();
        
                $data['orchestra_special_tag_orders'] = DB::table('orchestra_special_tag_orders')->join('orchestra_item_details','orchestra_item_details.UVC','=','orchestra_special_tag_orders.uvc')->select('orchestra_special_tag_orders.uvc as UVC',DB::raw('"SpecialTag" as Artwork'),'orchestra_item_details.SupplierName as SupplierName','orchestra_item_details.Collection as Season','orchestra_special_tag_orders.created_at as OrderDate','orchestra_special_tag_orders.delivery_date as DeliveryDate','orchestra_special_tag_orders.status as Status','orchestra_item_details.Collection as Season')->groupBy('orchestra_item_details.UVC')->get();
            
                $data['orchestra_main_label_orders'] = DB::table('orchestra_main_label_orders')->join('orchestra_item_details','orchestra_item_details.UVC','=','orchestra_main_label_orders.uvc')->select('orchestra_main_label_orders.uvc as UVC',DB::raw('"MainLabel" as Artwork'),'orchestra_item_details.SupplierName as SupplierName','orchestra_item_details.Collection as Season','orchestra_main_label_orders.created_at as OrderDate','orchestra_main_label_orders.delivery_date as DeliveryDate','orchestra_main_label_orders.status as Status','orchestra_item_details.Collection as Season')->groupBy('orchestra_item_details.UVC')->groupBy('orchestra_main_label_orders.id')->get();
            
                $data['orchestra_wash_care_label_orders'] = DB::table('orchestra_wash_care_label_orders')->join('orchestra_item_details','orchestra_item_details.UVC','=','orchestra_wash_care_label_orders.uvc')->select('orchestra_wash_care_label_orders.uvc as UVC',DB::raw('"WashCareLabel" as Artwork'),'orchestra_item_details.SupplierName as SupplierName','orchestra_item_details.Collection as Season','orchestra_wash_care_label_orders.created_at as OrderDate','orchestra_wash_care_label_orders.delivery_date as DeliveryDate','orchestra_wash_care_label_orders.status as Status','orchestra_item_details.Collection as Season')->groupBy('orchestra_item_details.UVC')->groupBy('orchestra_wash_care_label_orders.id')->get();
                return view('customer/customer-order-status')->with(['buyers' => $buyers,'buyer_count' => $buyer_count,'order_status' => $data]);
            } catch (\Exception $e) {
                return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
            }
    }
}