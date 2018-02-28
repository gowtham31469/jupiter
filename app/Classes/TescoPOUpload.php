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
   * Class to store the tesco template orders
   * @author [Praveen] <[praveen@wizroots.com]>
   */
class TescoPOUpload
{
	/**
	 * method to upload the tesco TCL1 order PO file,delivery date
	 * @param  [type] $request, $template_id
	 * @return tesco order table id (inserted_id)
	 */
	public function uploadTCL1PO($template_id,$po_number,$delivery_date,$po_file)
	{
		try {
      return $po_file;
            // $path = $po_file->getRealPath();
            // $filename = $po_file->getClientOriginalName();
            // $destination_path='/storage/app/public/po-files/';
            // $path='storage/app/public/po-files/';
            // $Orname = $po_file->getClientOriginalName();
            // $filename= str_random(6).'_'.preg_replace('/\s+/', '-', $Orname);
            // $po_file->move($path,$filename);
            // $destination_path_insert=$destination_path.$filename;

            // return TescoTCL1Orders::where('id',$template_id)
            //               ->update([
            //               'po_status'   => "PO Approved",
            //               'purchase_order_no' => $po_number,
            //               'po_file' => $destination_path_insert,
            //               'delivery_date' => $delivery_date
            //             ]);
            
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
  public function uploadTCL2PO($template_id,$po_number,$delivery_date,$po_file)
  {
    try {
      return $po_file;
            // $path = $po_file->getRealPath();
            // $filename = $po_file->getClientOriginalName();
            // $destination_path='/storage/app/public/po-files/';
            // $path='storage/app/public/po-files/';
            // $Orname = $po_file->getClientOriginalName();
            // $filename= str_random(6).'_'.preg_replace('/\s+/', '-', $Orname);
            // $po_file->move($path,$filename);
            // $destination_path_insert=$destination_path.$filename;

            // return TescoTCL2Orders::where('id',$template_id)
            //               ->update([
            //               'po_status'   => "PO Approved",
            //               'purchase_order_no' => $po_number,
            //               'po_file' => $destination_path_insert,
            //               'delivery_date' => $delivery_date
            //             ]);
            
        } catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
  }
}
