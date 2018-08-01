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
/**
 * Class to cancel the tesco template orders
 * @author [Praveen] <[praveen@wizroots.com]>
 */
class CancelTescoOrders
{
  /**
   * method to cancel the tesco TCL1 orders
   * @param  [type] $template_id
   * @return tesco order table id
   */
  public function cancelTCL1Order($template_id)
  {
    try {
            return TescoTCL1Orders::where('id',$template_id)
                          ->update([
                          'order_status'    => "Order Cancelled",
                          'artwork_status'  => "Order Cancelled",
                          'print_status'    => "Order Cancelled",
                        ]);
            
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
  public function cancelTCL2Order($template_id)
  {
    try {
            return TescoTCL2Orders::where('id',$template_id)
                          ->update([
                          'order_status'    => "Order Cancelled",
                          'artwork_status'  => "Order Cancelled",
                          'print_status'    => "Order Cancelled",
                        ]);
            
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
  public function cancelTSL1Order($template_id)
  {
    try {
            return TescoTSL1Orders::where('id',$template_id)
                          ->update([
                          'order_status'    => "Order Cancelled",
                          'artwork_status'  => "Order Cancelled",
                          'print_status'    => "Order Cancelled",
                        ]);
            
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
  public function cancelTCL4Order($template_id)
  {
    try {
            return TescoTCL4Orders::where('id',$template_id)
                          ->update([
                          'order_status'    => "Order Cancelled",
                          'artwork_status'  => "Order Cancelled",
                          'print_status'    => "Order Cancelled",
                        ]);
            
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
  public function cancelTSL3Order($template_id)
  {
    try {
            return TescoTSL3Orders::where('id',$template_id)
                          ->update([
                          'order_status'    => "Order Cancelled",
                          'artwork_status'  => "Order Cancelled",
                          'print_status'    => "Order Cancelled",
                        ]);
            
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
  public function cancelTHL1Order($template_id)
  {
    try {
            return TescoTHL1Orders::where('id',$template_id)
                          ->update([
                          'order_status'    => "Order Cancelled",
                          'artwork_status'  => "Order Cancelled",
                          'print_status'    => "Order Cancelled",
                        ]);
            
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
  public function cancelTHL3Order($template_id)
  {
    try {
            return TescoTHL3Orders::where('id',$template_id)
                              ->update([
                              'order_status'    => "Order Cancelled",
                              'artwork_status'  => "Order Cancelled",
                              'print_status'    => "Order Cancelled",
                            ]);
            
        } catch (\Exception $e)
        {
             return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
  }
  
}
