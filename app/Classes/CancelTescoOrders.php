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
use App\ErpOrderDetails;
/**
 * Class to cancel the tesco template orders
 * @author [Praveen] <[praveen@wizroots.com]>
 */
class CancelTescoOrders
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
   * method to cancel the tesco TCL1 orders
   * @param  [type] $template_id
   * @return tesco order table id
   */
  public function cancelTCL1Order($template_id, $buyer_id)
  {
    try {
            TescoTCL1Orders::where('id',$template_id)
                          ->update([
                          'order_status'       => "Order Cancelled",
                          'order_cancelled_at' => $this->current_date
                        ]);
            $order_details  = TescoTCL1Orders::where('id',$template_id)
                                ->select('order_status','tesco_supplier_id','purchase_order_no','pi_id')
                                ->first();
            $order_status_id  = DB::table('orders_status')->select('id')->where('status_name', 'ilike', $order_details->order_status)->first();

            $customer_data  = DB::table('customers')->select('pi_required', 'po_required')->where('id', $order_details->tesco_supplier_id)->first();

            if ($customer_data->pi_required == 1 && $customer_data->po_required== 0) 
            {
              $purchase_order_no     = $order_details->pi_id;
            }
            else if($customer_data->po_required== 1)
            {
              $purchase_order_no     = $order_details->purchase_order_no;
            }         

            $erp_orders = Erp::where('buyer_id',$buyer_id)->where('customer_id',$order_details->tesco_supplier_id)->where('po_no', $purchase_order_no)->first();

            if ($erp_orders) {
              /** @var array [Pass parameters to updateERPCancelStatus function] */
              $tableName = "tesco_tcl1_orders_quanty";
              $modelName = "TescoTCL1OrdersQuantity";
              $updated_erp_table = $this->updateERPCancelStatus($template_id, $tableName, $erp_orders, $order_status_id, $modelName);
            }
            return "cancelled";
            
        } catch (\Exception $e)
        {
             return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
  }

  /**
   * method to cancel the tesco TCL2 orders
   * @param  [type] $template_id
   * @return tesco order table id
   */
  public function cancelTCL2Order($template_id, $buyer_id)
  {
    try {
            TescoTCL2Orders::where('id',$template_id)
                          ->update([
                          'order_status'       => "Order Cancelled",
                          'order_cancelled_at' => $this->current_date
                        ]);
            $order_details  = TescoTCL2Orders::where('id',$template_id)
                                ->select('order_status','tesco_supplier_id','purchase_order_no','pi_id')
                                ->first();
            $order_status_id  = DB::table('orders_status')->select('id')->where('status_name', 'ilike', $order_details->order_status)->first();

            $customer_data  = DB::table('customers')->select('pi_required', 'po_required')->where('id', $order_details->tesco_supplier_id)->first();

            if ($customer_data->pi_required == 1 && $customer_data->po_required== 0) 
            {
              $purchase_order_no     = $order_details->pi_id;
            }
            else if($customer_data->po_required== 1)
            {
              $purchase_order_no     = $order_details->purchase_order_no;
            }         

            $erp_orders = Erp::where('buyer_id',$buyer_id)->where('customer_id',$order_details->tesco_supplier_id)->where('po_no', $purchase_order_no)->first();

            if ($erp_orders) {
              /** @var array [Pass parameters to updateERPCancelStatus function] */
              $tableName = "tesco_tcl2_orders_quanty";
              $modelName = "TescoTCL2OrdersQuantity";
              $updated_erp_table = $this->updateERPCancelStatus($template_id, $tableName, $erp_orders, $order_status_id, $modelName);
            }
            return "cancelled";
            
        } catch (\Exception $e)
        {
             return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
  }

  /**
   * method to cancel the tesco TSL1 orders
   * @param  [type] $template_id
   * @return tesco order table id
   */
  public function cancelTSL1Order($template_id, $buyer_id)
  {
    try {
            TescoTSL1Orders::where('id',$template_id)
                          ->update([
                          'order_status'       => "Order Cancelled",
                          'order_cancelled_at' => $this->current_date
                        ]);
            $order_details  = TescoTSL1Orders::where('id',$template_id)
                                ->select('order_status','tesco_supplier_id','purchase_order_no','pi_id')
                                ->first();
            $order_status_id  = DB::table('orders_status')->select('id')->where('status_name', 'ilike', $order_details->order_status)->first();

            $customer_data  = DB::table('customers')->select('pi_required', 'po_required')->where('id', $order_details->tesco_supplier_id)->first();

            if ($customer_data->pi_required == 1 && $customer_data->po_required== 0) 
            {
              $purchase_order_no     = $order_details->pi_id;
            }
            else if($customer_data->po_required== 1)
            {
              $purchase_order_no     = $order_details->purchase_order_no;
            }         

            $erp_orders = Erp::where('buyer_id',$buyer_id)->where('customer_id',$order_details->tesco_supplier_id)->where('po_no', $purchase_order_no)->first();

            if ($erp_orders) {
              /** @var array [Pass parameters to updateERPCancelStatus function] */
              $tableName = "tesco_tsl1_orders_quanty";
              $modelName = "TescoTSL1OrdersQuantity";
              $updated_erp_table = $this->updateERPCancelStatus($template_id, $tableName, $erp_orders, $order_status_id, $modelName);
            }
            return "cancelled";
            
        } catch (\Exception $e)
        {
             return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
  }

  /**
   * method to cancel the tesco TCL4 orders
   * @param  [type] $template_id
   * @return tesco order table id
   */
  public function cancelTCL4Order($template_id, $buyer_id)
  {
    try {
            TescoTCL4Orders::where('id',$template_id)
                          ->update([
                          'order_status'       => "Order Cancelled",
                          'order_cancelled_at' => $this->current_date
                        ]);
            $order_details  = TescoTCL4Orders::where('id',$template_id)
                                ->select('order_status','tesco_supplier_id','purchase_order_no','pi_id')
                                ->first();
            $order_status_id  = DB::table('orders_status')->select('id')->where('status_name', 'ilike', $order_details->order_status)->first();

            $customer_data  = DB::table('customers')->select('pi_required', 'po_required')->where('id', $order_details->tesco_supplier_id)->first();

            if ($customer_data->pi_required == 1 && $customer_data->po_required== 0) 
            {
              $purchase_order_no     = $order_details->pi_id;
            }
            else if($customer_data->po_required== 1)
            {
              $purchase_order_no     = $order_details->purchase_order_no;
            }         

            $erp_orders = Erp::where('buyer_id',$buyer_id)->where('customer_id',$order_details->tesco_supplier_id)->where('po_no', $purchase_order_no)->first();

            if ($erp_orders) {
              /** @var array [Pass parameters to updateERPCancelStatus function] */
              $tableName = "tesco_tcl4_orders_quanty";
              $modelName = "TescoTCL4OrdersQuantity";
              $updated_erp_table = $this->updateERPCancelStatus($template_id, $tableName, $erp_orders, $order_status_id, $modelName);
            }
            return "cancelled";
            
        } catch (\Exception $e)
        {
             return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
  }

  /**
   * method to cancel the tesco TSL3 orders
   * @param  [type] $template_id
   * @return tesco order table id
   */
  public function cancelTSL3Order($template_id, $buyer_id)
  {
    try {
            TescoTSL3Orders::where('id',$template_id)
                          ->update([
                          'order_status'       => "Order Cancelled",
                          'order_cancelled_at' => $this->current_date
                        ]);
            $order_details  = TescoTSL3Orders::where('id',$template_id)
                                ->select('order_status','tesco_supplier_id','purchase_order_no','pi_id')
                                ->first();
            $order_status_id  = DB::table('orders_status')->select('id')->where('status_name', 'ilike', $order_details->order_status)->first();
            $customer_data  = DB::table('customers')->select('pi_required', 'po_required')->where('id', $order_details->tesco_supplier_id)->first();

            if ($customer_data->pi_required == 1 && $customer_data->po_required== 0) 
            {
              $purchase_order_no     = $order_details->pi_id;
            }
            else if($customer_data->po_required== 1)
            {
              $purchase_order_no     = $order_details->purchase_order_no;
            }         

            $erp_orders = Erp::where('buyer_id',$buyer_id)->where('customer_id',$order_details->tesco_supplier_id)->where('po_no', $purchase_order_no)->first();

            if ($erp_orders) {
              /** @var array [Pass parameters to updateERPCancelStatus function] */
              $tableName = "tesco_tsl3_orders_quanty";
              $modelName = "TescoTSL3OrdersQuantity";
              $updated_erp_table = $this->updateERPCancelStatus($template_id, $tableName, $erp_orders, $order_status_id, $modelName);
            }
            return "cancelled";
            
        } catch (\Exception $e)
        {
             return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
  }

  /**
   * method to cancel the tesco THL1 orders
   * @param  [type] $template_id
   * @return tesco order table id
   */
  public function cancelTHL1Order($template_id, $buyer_id)
  {
    try {
            TescoTHL1Orders::where('id',$template_id)
                          ->update([
                          'order_status'       => "Order Cancelled",
                          'order_cancelled_at' => $this->current_date
                        ]);
            $order_details  = TescoTHL1Orders::where('id',$template_id)
                                ->select('order_status','tesco_supplier_id','purchase_order_no','pi_id')
                                ->first();
            $order_status_id  = DB::table('orders_status')->select('id')->where('status_name', 'ilike', $order_details->order_status)->first();

            $customer_data  = DB::table('customers')->select('pi_required', 'po_required')->where('id', $order_details->tesco_supplier_id)->first();

            if ($customer_data->pi_required == 1 && $customer_data->po_required== 0) 
            {
              $purchase_order_no     = $order_details->pi_id;
            }
            else if($customer_data->po_required== 1)
            {
              $purchase_order_no     = $order_details->purchase_order_no;
            }         

            $erp_orders = Erp::where('buyer_id',$buyer_id)->where('customer_id',$order_details->tesco_supplier_id)->where('po_no', $purchase_order_no)->first();

            if ($erp_orders) {
              /** @var array [Pass parameters to updateERPCancelStatus function] */
              $tableName = "tesco_thl1_orders_quanty";
              $modelName = "TescoTHL1OrdersQuantity";
              $updated_erp_table = $this->updateERPCancelStatus($template_id, $tableName, $erp_orders, $order_status_id, $modelName);
            }
            return "cancelled";
            
        } catch (\Exception $e)
        {
             return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
  }

  /**
   * method to cancel the tesco THL3 orders
   * @param  [type] $template_id
   * @return tesco order table id
   */
  public function cancelTHL3Order($template_id, $buyer_id)
  {
    try {
          TescoTHL3Orders::where('id',$template_id)
                            ->update([
                            'order_status'       => "Order Cancelled",
                            'order_cancelled_at' => $this->current_date
                          ]);
          $order_details  = TescoTHL3Orders::where('id',$template_id)
                              ->select('order_status','tesco_supplier_id','purchase_order_no','pi_id')
                              ->first();
          
          $order_status_id  = DB::table('orders_status')->select('id')->where('status_name', 'ilike', $order_details->order_status)->first();

          $customer_data  = DB::table('customers')->select('pi_required', 'po_required')->where('id', $order_details->tesco_supplier_id)->first();

          if ($customer_data->pi_required == 1 && $customer_data->po_required== 0) 
          {
            $purchase_order_no     = $order_details->pi_id;
          }
          else if($customer_data->po_required== 1)
          {
            $purchase_order_no     = $order_details->purchase_order_no;
          }         
          
          $erp_orders = Erp::where('buyer_id',$buyer_id)->where('customer_id',$order_details->tesco_supplier_id)->where('po_no', $purchase_order_no)->first();
          
          if ($erp_orders) {
            /** @var array [Pass parameters to updateERPCancelStatus function] */
            $tableName = "tesco_thl3_orders_quanty";
            $modelName = "TescoTHL3OrdersQuantity";
            $updated_erp_table = $this->updateERPCancelStatus($template_id, $tableName, $erp_orders, $order_status_id, $modelName);
          }
          
          return "cancelled";
            
        } catch (\Exception $e)
        {
             return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
  }

  /**
   * method to update cancel tesco orders status in erp_orders and erp_order_details table
   * @param  [type] $row_id, $order_qty_table_name, $erp_orders, $order_status_id
   * @return tesco order table id
   */
  public function updateERPCancelStatus($row_id, $order_qty_table_name, $erp_orders, $order_status_id, $model_name)
  {
    try {
          $template_name  = str_replace("tesco_","", str_replace("s_quanty","", $order_qty_table_name));
          
          $erp_id = $erp_orders->id;
          // Get Erp status table id for 'Moved To JC Queue' status.
          $erp_status_id = DB::table('erp_status')->where('status_name', 'ilike', 'Cancelled')->value('id');
          
          //Dynamic function name and database model name for each templates
          $model_name = 'App\\'.$model_name;
          $order_qty_ids = $model_name::where($template_name.'_id',$row_id)->pluck('id')->all();
          
          if ($order_qty_ids) 
          {
            foreach($order_qty_ids as $order_qty_id)
            {
              ErpOrderDetails::where('order_qnty_table_id', $order_qty_id)
                    ->where('order_qnty_table_name', $order_qty_table_name)
                    ->where('erp_order_id', $erp_id)
                    ->update([
                              'status'  => $erp_status_id
                            ]);
            }
            /** Update order cancelled status erp orders table(iff, all the orders in the order details table have same status. ie, after cancelling all orders) */
						
            $erp_order_table_status = DB::table('erp_order_details')
                ->where('erp_order_details.erp_order_id', $erp_id)
                ->pluck('status')->all();
            
            if(count(array_unique($erp_order_table_status)) === 1)
            {
              DB::table('erp_orders')
                ->where('erp_orders.id', $erp_id)
                ->update([
                  'status'  => $erp_order_table_status[0],
                  'order_status_id'  => $order_status_id->id
                ]);
            }
            else
            {
              DB::table('erp_orders')
                ->where('erp_orders.id', $erp_id)
                ->update([
                  'order_status_id'  => $order_status_id->id
                ]);
            }
            
          }
          return "cancelled";
            
        } catch (\Exception $e)
        {
             return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
  }
  
}