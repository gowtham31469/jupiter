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
use App\TescoTCL2Orders;
use App\TescoTCL4Orders;
use App\TescoTHL1Orders;
use App\TescoTHL3Orders;
use App\TescoTSL1Orders;
use App\TescoTSL3Orders;
use Carbon\Carbon;
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
   * method to approve the tesco TCL1 orders
   * @param  [type] $template_id
   * @return tesco order table id
   */
  public function approveTCL1Order($template_id)
  {
    try {
            return TescoTCL1Orders::where('id',$template_id)
                          ->update([
                          'order_status'      => "Order Approved",
                          'artwork_status'    => "Order Approved",
                          'print_status'      => "Order Approved",
                          'order_approved_at' => $this->current_date
                        ]);
            
        } catch (\Exception $e)
        {
             return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
  }

  /**
   * method to approve the tesco TCL2 orders
   * @param  [type] $template_id
   * @return tesco order table id
   */
  public function approveTCL2Order($template_id)
  {
    try {
            return TescoTCL2Orders::where('id',$template_id)
                          ->update([
                          'order_status'      => "Order Approved",
                          'artwork_status'    => "Order Approved",
                          'print_status'      => "Order Approved",
                          'order_approved_at' => $this->current_date
                        ]);
            
        } catch (\Exception $e)
        {
             return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
  }

  /**
   * method to approve the tesco TSL1 orders
   * @param  [type] $template_id
   * @return tesco order table id
   */
  public function approveTSL1Order($template_id)
  {
    try {
            return TescoTSL1Orders::where('id',$template_id)
                          ->update([
                          'order_status'      => "Order Approved",
                          'artwork_status'    => "Order Approved",
                          'print_status'      => "Order Approved",
                          'order_approved_at' => $this->current_date
                        ]);
            
        } catch (\Exception $e)
        {
             return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
  }

  /**
   * method to approve the tesco TCL4 orders
   * @param  [type] $template_id
   * @return tesco order table id
   */
  public function approveTCL4Order($template_id)
  {
    try {
            return TescoTCL4Orders::where('id',$template_id)
                          ->update([
                          'order_status'      => "Order Approved",
                          'artwork_status'    => "Order Approved",
                          'print_status'      => "Order Approved",
                          'order_approved_at' => $this->current_date
                        ]);
            
        } catch (\Exception $e)
        {
             return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
  }

  /**
   * method to approve the tesco TSL3 orders
   * @param  [type] $template_id
   * @return tesco order table id
   */
  public function approveTSL3Order($template_id)
  {
    try {
            return TescoTSL3Orders::where('id',$template_id)
                          ->update([
                          'order_status'      => "Order Approved",
                          'artwork_status'    => "Order Approved",
                          'print_status'      => "Order Approved",
                          'order_approved_at' => $this->current_date
                        ]);
            
        } catch (\Exception $e)
        {
             return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
  }

  /**
   * method to approve the tesco THL1 orders
   * @param  [type] $template_id
   * @return tesco order table id
   */
  public function approveTHL1Order($template_id)
  {
    try {
            return TescoTHL1Orders::where('id',$template_id)
                          ->update([
                          'order_status'      => "Order Approved",
                          'artwork_status'    => "Order Approved",
                          'print_status'      => "Order Approved",
                          'order_approved_at' => $this->current_date
                        ]);
            
        } catch (\Exception $e)
        {
             return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
  }

  /**
   * method to approve the tesco THL3 orders
   * @param  [type] $template_id
   * @return tesco order table id
   */
  public function approveTHL3Order($template_id)
  {
    try {
            return TescoTHL3Orders::where('id',$template_id)
                              ->update([
                              'order_status'      => "Order Approved",
                              'artwork_status'    => "Order Approved",
                              'print_status'      => "Order Approved",
                              'order_approved_at' => $this->current_date
                            ]);
            
        } catch (\Exception $e)
        {
             return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
  }
  
}
