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
   * Class for tesco buyer combined po upload
   * @author [Praveen] <[praveen@wizroots.com]>
   */
class TescoCombinedPOUpload
{
	/**
	 * method to upload the tesco TCL1 order PO file,delivery date
	 * @param  [type] $request, $template_id
	 * @return tesco order table id (inserted_id)
	 */
	public function uploadTCL1PO($template_id,$po_number,$destination_path_insert,$delivery_date)
	{
		try {
            $result = TescoTCL1Orders::where('id',$template_id)
                          ->update([
                          'po_status'         => "PO Uploaded",
                          'purchase_order_no' => $po_number,
                          'po_file'           => $destination_path_insert,
                          'delivery_date'     => $delivery_date
                        ]);
            
        } catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
	}
  /**
   * method to upload the tesco TCL2 order PO file,delivery date
   * @param  [type] $request, $template_id
   * @return tesco order table id (inserted_id)
   */
  public function uploadTCL2PO($template_id,$po_number,$destination_path_insert,$delivery_date)
  {
    try {
            $result = TescoTCL2Orders::where('id',$template_id)
                          ->update([
                          'po_status'         => "PO Uploaded",
                          'purchase_order_no' => $po_number,
                          'po_file'           => $destination_path_insert,
                          'delivery_date'     => $delivery_date
                        ]);
            
        } catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
  }
  /**
   * method to upload the tesco TSL1 order PO file,delivery date
   * @param  [type] $request, $template_id
   * @return tesco order table id (inserted_id)
   */
  public function uploadTSL1PO($template_id,$po_number,$destination_path_insert,$delivery_date)
  {
    try {
            $result = TescoTSL1Orders::where('id',$template_id)
                          ->update([
                          'po_status'         => "PO Uploaded",
                          'purchase_order_no' => $po_number,
                          'po_file'           => $destination_path_insert,
                          'delivery_date'     => $delivery_date
                        ]);
            
        } catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
  }
  /**
   * method to upload the tesco TCL4 order PO file,delivery date
   * @param  [type] $request, $template_id
   * @return tesco order table id (inserted_id)
   */
  public function uploadTCL4PO($template_id,$po_number,$destination_path_insert,$delivery_date)
  {
    try {
            $result = TescoTCL4Orders::where('id',$template_id)
                          ->update([
                          'po_status'         => "PO Uploaded",
                          'purchase_order_no' => $po_number,
                          'po_file'           => $destination_path_insert,
                          'delivery_date'     => $delivery_date
                        ]);
            
        } catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
  }
  /**
   * method to upload the tesco TSL3 order PO file,delivery date
   * @param  [type] $request, $template_id
   * @return tesco order table id (inserted_id)
   */
  public function uploadTSL3PO($template_id,$po_number,$destination_path_insert,$delivery_date)
  {
    try {
            $result = TescoTSL3Orders::where('id',$template_id)
                          ->update([
                          'po_status'         => "PO Uploaded",
                          'purchase_order_no' => $po_number,
                          'po_file'           => $destination_path_insert,
                          'delivery_date'     => $delivery_date
                        ]);
            
        } catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
  }
  /**
   * method to upload the tesco THL1 order PO file,delivery date
   * @param  [type] $request, $template_id
   * @return tesco order table id (inserted_id)
   */
  public function uploadTHL1PO($template_id,$po_number,$destination_path_insert,$delivery_date)
  {
    try {
            $result = TescoTHL1Orders::where('id',$template_id)
                          ->update([
                          'po_status'         => "PO Uploaded",
                          'purchase_order_no' => $po_number,
                          'po_file'           => $destination_path_insert,
                          'delivery_date'     => $delivery_date
                        ]);
            
        } catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
  }
  /**
   * method to upload the tesco THL3 order PO file,delivery date
   * @param  [type] $request, $template_id
   * @return tesco order table id (inserted_id)
   */
  public function uploadTHL3PO($template_id,$po_number,$destination_path_insert,$delivery_date)
  {
    try {
            $result = TescoTHL3Orders::where('id',$template_id)
                          ->update([
                          'po_status'         => "PO Uploaded",
                          'purchase_order_no' => $po_number,
                          'po_file'           => $destination_path_insert,
                          'delivery_date'     => $delivery_date
                        ]);
            
        } catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
  }
}
