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
   * Class for tesco buyer single po upload
   * @author [Praveen] <[praveen@wizroots.com]>
   */
class TescoSinglePOUpload
{
	/**
	 * method to upload the tesco TCL1 order PO file,delivery date
	 * @param  [type] $request, $template_id
	 * @return tesco order table id (inserted_id)
	 */
	public function uploadTCL1PO($template_id,$po_number,$po_file,$delivery_date)
	{
		try {
            $path = $po_file->getRealPath();
            $filename = $po_file->getClientOriginalName();
            $destination_path='/storage/app/public/po-files/';
            $path='storage/app/public/po-files/';
            $Orname = $po_file->getClientOriginalName();
            $filename= 'TCL1_PO_'.time().'_'.preg_replace('/\s+/', '-', $Orname);
            $po_file->move($path,$filename);
            $destination_path_insert=$destination_path.$filename;

            $result = TescoTCL1Orders::where('id',$template_id)
                          ->update([
                          'po_status'   => "PO Uploaded",
                          'purchase_order_no' => $po_number,
                          'po_file' => $destination_path_insert,
                          'delivery_date' => $delivery_date
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
  public function uploadTCL2PO($template_id,$po_number,$po_file,$delivery_date)
  {
    try {
            $path = $po_file->getRealPath();
            $filename = $po_file->getClientOriginalName();
            $destination_path='/storage/app/public/po-files/';
            $path='storage/app/public/po-files/';
            $Orname = $po_file->getClientOriginalName();
            $filename= 'TCL2_PO_'.time().'_'.preg_replace('/\s+/', '-', $Orname);
            $po_file->move($path,$filename);
            $destination_path_insert=$destination_path.$filename;

            $result = TescoTCL2Orders::where('id',$template_id)
                          ->update([
                          'po_status'   => "PO Uploaded",
                          'purchase_order_no' => $po_number,
                          'po_file' => $destination_path_insert,
                          'delivery_date' => $delivery_date
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
  public function uploadTSL1PO($template_id,$po_number,$po_file,$delivery_date)
  {
    try {
            $path = $po_file->getRealPath();
            $filename = $po_file->getClientOriginalName();
            $destination_path='/storage/app/public/po-files/';
            $path='storage/app/public/po-files/';
            $Orname = $po_file->getClientOriginalName();
            $filename= 'TSL1_PO_'.time().time().'_'.preg_replace('/\s+/', '-', $Orname);
            $po_file->move($path,$filename);
            $destination_path_insert=$destination_path.$filename;

            $result = TescoTSL1Orders::where('id',$template_id)
                          ->update([
                          'po_status'   => "PO Uploaded",
                          'purchase_order_no' => $po_number,
                          'po_file' => $destination_path_insert,
                          'delivery_date' => $delivery_date
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
  public function uploadTCL4PO($template_id,$po_number,$po_file,$delivery_date)
  {
    try {
            $path = $po_file->getRealPath();
            $filename = $po_file->getClientOriginalName();
            $destination_path='/storage/app/public/po-files/';
            $path='storage/app/public/po-files/';
            $Orname = $po_file->getClientOriginalName();
            $filename= 'TCL4_PO_'.time().'_'.preg_replace('/\s+/', '-', $Orname);
            $po_file->move($path,$filename);
            $destination_path_insert=$destination_path.$filename;

            $result = TescoTCL4Orders::where('id',$template_id)
                          ->update([
                          'po_status'   => "PO Uploaded",
                          'purchase_order_no' => $po_number,
                          'po_file' => $destination_path_insert,
                          'delivery_date' => $delivery_date
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
  public function uploadTSL3PO($template_id,$po_number,$po_file,$delivery_date)
  {
    try {
            $path = $po_file->getRealPath();
            $filename = $po_file->getClientOriginalName();
            $destination_path='/storage/app/public/po-files/';
            $path='storage/app/public/po-files/';
            $Orname = $po_file->getClientOriginalName();
            $filename= 'TSL3_PO_'.time().'_'.preg_replace('/\s+/', '-', $Orname);
            $po_file->move($path,$filename);
            $destination_path_insert=$destination_path.$filename;

            $result = TescoTSL3Orders::where('id',$template_id)
                          ->update([
                          'po_status'   => "PO Uploaded",
                          'purchase_order_no' => $po_number,
                          'po_file' => $destination_path_insert,
                          'delivery_date' => $delivery_date
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
  public function uploadTHL1PO($template_id,$po_number,$po_file,$delivery_date)
  {
    try {
            $path = $po_file->getRealPath();
            $filename = $po_file->getClientOriginalName();
            $destination_path='/storage/app/public/po-files/';
            $path='storage/app/public/po-files/';
            $Orname = $po_file->getClientOriginalName();
            $filename= 'THL1_PO_'.time().'_'.preg_replace('/\s+/', '-', $Orname);
            $po_file->move($path,$filename);
            $destination_path_insert=$destination_path.$filename;

            $result = TescoTHL1Orders::where('id',$template_id)
                          ->update([
                          'po_status'   => "PO Uploaded",
                          'purchase_order_no' => $po_number,
                          'po_file' => $destination_path_insert,
                          'delivery_date' => $delivery_date
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
  public function uploadTHL3PO($template_id,$po_number,$po_file,$delivery_date)
  {
    try {
            $path = $po_file->getRealPath();
            $filename = $po_file->getClientOriginalName();
            $destination_path='/storage/app/public/po-files/';
            $path='storage/app/public/po-files/';
            $Orname = $po_file->getClientOriginalName();
            $filename= 'THL3_PO_'.time().'_'.preg_replace('/\s+/', '-', $Orname);
            $po_file->move($path,$filename);
            $destination_path_insert=$destination_path.$filename;

            $result = TescoTHL3Orders::where('id',$template_id)
                          ->update([
                          'po_status'   => "PO Uploaded",
                          'purchase_order_no' => $po_number,
                          'po_file' => $destination_path_insert,
                          'delivery_date' => $delivery_date
                        ]);
            
        } catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
  }
}
