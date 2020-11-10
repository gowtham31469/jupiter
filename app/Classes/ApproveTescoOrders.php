<?php
namespace App\Classes;
use DB;
use App\TescoTCL1Orders;
use App\TescoTCL1OrdersQuantity;
use App\TescoTCL2Orders;
use App\TescoTCL2OrdersQuantity;
use App\TescoTCL4Orders;
use App\TescoTCL4OrdersQuantity;
use App\TescoTHL1Orders;
use App\TescoTHL1OrdersQuantity;
use App\TescoTHL3Orders;
use App\TescoTHL3OrdersQuantity;
use App\TescoTSL1Orders;
use App\TescoTSL1OrdersQuantity;
use App\TescoTSL3Orders;
use App\TescoTSL3OrdersQuantity;
use Carbon\Carbon;
use App\ErpOrders as Erp;
use App\ErpOrderDetails as ErpOrderDetails;
use App\OrderManagementHistory;
/**
 * Class to approve the tesco template orders
 * @author [Praveen] <[praveen@wizroots.com]>
 */
class ApproveTescoOrders
{

  private $current_date;
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
      // get the current time
      $this->current_date = Carbon::now('Asia/Kolkata')->toDateTimeString();
      
  }
  
  /**
   * method to approve the tesco orders
   * @param  [type] $request, $buyer_id
   * @return tesco order table id
   */
  public function approveTescoOrderMethod($request, $buyer_id)
  {
    try {
            $table = $request->table_name; 
            $table_row_id = $request->table_id; 
            $reference = explode("_",$table);

            DB::table($table)->where('id',$table_row_id)
                        ->update([
                        'order_status'      => "Order Approved",
                        'order_approved_at' => $this->current_date
                      ]);

            // update Artwork approve status as "Artwork Approved" in order history table
            OrderManagementHistory::where([['order_table_name', $table], ['order_table_id', $table_row_id]])->update([
                'artwork_status'   => "Artwork Approved"
            ]);
            
            $order_details  = DB::table($table)->where('id',$table_row_id)
                                ->select('id','order_status','tesco_supplier_id','purchase_order_no','pi_id', 'order_created_at', 'order_approved_at')
                                ->first();

            $order_qty_details  = DB::table($table.'_quanty')->where( $reference[1].'_order_id', $order_details->id)
            ->select('id', DB::raw($table."_quanty as \"table_name\""))->get();

            $order_status_id  = DB::table('orders_status')->select('id')->where('status_name', 'ilike', $order_details->order_status)->first();

            $customer_data  = DB::table('customers')->select('pi_required', 'po_required')->where('id', $order_details->tesco_supplier_id)->first();

            if ($customer_data->pi_required === 1 && $customer_data->po_required === 0) 
            {
              $purchase_order_no     = $order_details->pi_id;
            }
            else if($customer_data->po_required === 1)
            {
              $purchase_order_no     = $order_details->purchase_order_no;
            }         
            
            $erp_orders = Erp::where('buyer_id',$buyer_id)->where('customer_id',$order_details->tesco_supplier_id)->where('po_no', $purchase_order_no)->first();

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
            
            return "approved";
            
        } catch (\Exception $e)
        {
             return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
  }
  
}
