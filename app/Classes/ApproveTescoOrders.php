<?php
namespace App\Classes;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Response;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use DB;
use Auth;
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
/**
 * Class to approve the tesco template orders
 * @author [Praveen] <[praveen@wizroots.com]>
 */
class ApproveTescoOrders
{

  private $current_date;
  private $buyer_id;
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
   * @param  [type] $table_array, $buyer_id
   * @return tesco order table id
   */
  public function approveTescoOrderMethod($table_array, $buyer_id)
  {
    try {
            foreach($table_array as  $index => $data)
            {
              $table = $data['table']; 
              $template_id = $data['table_id']; 

              //Dynamic function name and database model name for each templates
              $order_model = 'App\\'."Tesco".$table."Orders";

              $order_qty_model = 'App\\'."Tesco".$table."OrdersQuantity";

              $order_model::where('id',$template_id)
                          ->update([
                          'order_status'      => "Order Approved",
                          'order_approved_at' => $this->current_date
                        ]);
              $order_details  = $order_model::where('id',$template_id)
                                  ->select('id','order_status','tesco_supplier_id','purchase_order_no','pi_id')
                                  ->first();

              $order_qty_details  = $order_qty_model::where(strtolower($table).'_order_id', $order_details->id)
              ->select('id', DB::raw("'tesco_".strtolower($table)."_orders_quanty' as \"table_name\""))->get();

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
                                      'order_status_id'  => $order_status_id->id
                                    ]);
                    }
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
