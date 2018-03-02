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
use App\Classes\TescoSinglePOUpload;
use App\Classes\TescoCombinedPOUpload;
/**
   * Class for tesco buyer po upload templates switching.
   * @author [Praveen] <[praveen@wizroots.com]>
   */
class TescoPOUploadTemplatesSwitch
{
  /**
   * method to tesco buyers single po upload template 1 switching
   * @param  [type] $request, $template_id
   * @return tesco order table id (inserted_id)
   */
  public function singlePOUploadTemplate1Switch($template,$request)
  {
    try {
          /** [ Creating new object for TescoSinglePOUpload() class ] */
          $tesco_po_obj = new TescoSinglePOUpload(); 
        	switch ($template) 
          {
            case 'TCL1':
              $result = $tesco_po_obj->uploadTCL1PO($request['template_id_1'],$request['po_number_1'],$request['po_file_1'],$request['delivery_date_1']);
              break;
            case 'TCL2':
              $result = $tesco_po_obj->uploadTCL2PO($request['template_id_1'],$request['po_number_1'],$request['po_file_1'],$request['delivery_date_1']);
              break;
            case 'TSL1':
              $result = $tesco_po_obj->uploadTSL1PO($request['template_id_1'],$request['po_number_1'],$request['po_file_1'],$request['delivery_date_1']);
              break;
            case 'TCL4':
              $result = $tesco_po_obj->uploadTCL4PO($request['template_id_1'],$request['po_number_1'],$request['po_file_1'],$request['delivery_date_1']);
              break;
            case 'TSL3':
              $result = $tesco_po_obj->uploadTSL3PO($request['template_id_1'],$request['po_number_1'],$request['po_file_1'],$request['delivery_date_1']);
              break;
            case 'THL1':
              $result = $tesco_po_obj->uploadTHL1PO($request['template_id_1'],$request['po_number_1'],$request['po_file_1'],$request['delivery_date_1']);
              break;
            case 'THL3':
              $result = $tesco_po_obj->uploadTHL3PO($request['template_id_1'],$request['po_number_1'],$request['po_file_1'],$request['delivery_date_1']);
              break;
            default:
              $result = "Invalid Template";
              break;
          }
   } catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
  }
  /**
   * method to tesco buyers single po upload template 2 switching
   * @param  [type] $request, $template_id
   * @return tesco order table id (inserted_id)
   */
  public function singlePOUploadTemplate2Switch($template,$request)
  {
    try {
          /** [ Creating new object for TescoSinglePOUpload() class ] */
          $tesco_po_obj = new TescoSinglePOUpload(); 
          switch ($template) 
          {
            case 'TCL1':
              $result = $tesco_po_obj->uploadTCL1PO($request['template_id_2'],$request['po_number_2'],$request['po_file_2'],$request['delivery_date_2']);
              break;
            case 'TCL2':
              $result = $tesco_po_obj->uploadTCL2PO($request['template_id_2'],$request['po_number_2'],$request['po_file_2'],$request['delivery_date_2']);
              break;
            case 'TSL1':
              $result = $tesco_po_obj->uploadTSL1PO($request['template_id_2'],$request['po_number_2'],$request['po_file_2'],$request['delivery_date_2']);
              break;
            case 'TCL4':
              $result = $tesco_po_obj->uploadTCL4PO($request['template_id_2'],$request['po_number_2'],$request['po_file_2'],$request['delivery_date_2']);
              break;
            case 'TSL3':
              $result = $tesco_po_obj->uploadTSL3PO($request['template_id_2'],$request['po_number_2'],$request['po_file_2'],$request['delivery_date_2']);
              break;
            case 'THL1':
              $result = $tesco_po_obj->uploadTHL1PO($request['template_id_2'],$request['po_number_2'],$request['po_file_2'],$request['delivery_date_2']);
              break;
            case 'THL3':
              $result = $tesco_po_obj->uploadTHL3PO($request['template_id_2'],$request['po_number_2'],$request['po_file_2'],$request['delivery_date_2']);
              break;
            default:
              $result = "Invalid Template";
              break;
          }
   } catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
  }
  /**
   * method to tesco buyers single po upload template 3 switching
   * @param  [type] $request, $template_id
   * @return tesco order table id (inserted_id)
   */
  public function singlePOUploadTemplate3Switch($template,$request)
  {
    try {
          /** [ Creating new object for TescoSinglePOUpload() class ] */
          $tesco_po_obj = new TescoSinglePOUpload(); 
          switch ($template) 
          {
            case 'TCL1':
              $result = $tesco_po_obj->uploadTCL1PO($request['template_id_3'],$request['po_number_3'],$request['po_file_3'],$request['delivery_date_3']);
              break;
            case 'TCL2':
              $result = $tesco_po_obj->uploadTCL2PO($request['template_id_3'],$request['po_number_3'],$request['po_file_3'],$request['delivery_date_3']);
              break;
            case 'TSL1':
              $result = $tesco_po_obj->uploadTSL1PO($request['template_id_3'],$request['po_number_3'],$request['po_file_3'],$request['delivery_date_3']);
              break;
            case 'TCL4':
              $result = $tesco_po_obj->uploadTCL4PO($request['template_id_3'],$request['po_number_3'],$request['po_file_3'],$request['delivery_date_3']);
              break;
            case 'TSL3':
              $result = $tesco_po_obj->uploadTSL3PO($request['template_id_3'],$request['po_number_3'],$request['po_file_3'],$request['delivery_date_3']);
              break;
            case 'THL1':
              $result = $tesco_po_obj->uploadTHL1PO($request['template_id_3'],$request['po_number_3'],$request['po_file_3'],$request['delivery_date_3']);
              break;
            case 'THL3':
              $result = $tesco_po_obj->uploadTHL3PO($request['template_id_3'],$request['po_number_3'],$request['po_file_3'],$request['delivery_date_3']);
              break;
            default:
              $result = "Invalid Template";
              break;
          }
   } catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
  }
  /**
   * method to tesco buyers combined po upload template 1 switching
   * @param  [type] $request, $template_id
   * @return tesco order table id (inserted_id)
   */
  public function combinedPOUploadTemplate1Switch($template,$request,$destination_path_insert)
  {
    try {
            /** [ Creating new object for TescoCombinedPOUpload() class ] */
            $tesco_po_obj = new TescoCombinedPOUpload(); 
            
            switch ($template) 
            {
              case 'TCL1':
                $result = $tesco_po_obj->uploadTCL1PO($request['template_id_1'],$request['po_number'],$destination_path_insert,$request['delivery_date']);
                break;
              case 'TCL2':
                $result = $tesco_po_obj->uploadTCL2PO($request['template_id_1'],$request['po_number'],$destination_path_insert,$request['delivery_date']);
                break;
              case 'TSL1':
                $result = $tesco_po_obj->uploadTSL1PO($request['template_id_1'],$request['po_number'],$destination_path_insert,$request['delivery_date']);
                break;
              case 'TCL4':
                $result = $tesco_po_obj->uploadTCL4PO($request['template_id_1'],$request['po_number'],$destination_path_insert,$request['delivery_date']);
                break;
              case 'TSL3':
                $result = $tesco_po_obj->uploadTSL3PO($request['template_id_1'],$request['po_number'],$destination_path_insert,$request['delivery_date']);
                break;
              /** THL1, THL3 Not required in combined Buyer PO upload */
              default:
                $result = "Invalid Template";
                break;
            }
   } catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
  }
  /**
   * method to tesco buyers combined po upload template 2 switching
   * @param  [type] $request, $template_id
   * @return tesco order table id (inserted_id)
   */
  public function combinedPOUploadTemplate2Switch($template,$request,$destination_path_insert)
  {
    try {
            /** [ Creating new object for TescoCombinedPOUpload() class ] */
            $tesco_po_obj = new TescoCombinedPOUpload(); 
            
            switch ($template) 
            {
              case 'TCL1':
                $result = $tesco_po_obj->uploadTCL1PO($request['template_id_2'],$request['po_number'],$destination_path_insert,$request['delivery_date']);
                break;
              case 'TCL2':
                $result = $tesco_po_obj->uploadTCL2PO($request['template_id_2'],$request['po_number'],$destination_path_insert,$request['delivery_date']);
                break;
              case 'TSL1':
                $result = $tesco_po_obj->uploadTSL1PO($request['template_id_2'],$request['po_number'],$destination_path_insert,$request['delivery_date']);
                break;
              case 'TCL4':
                $result = $tesco_po_obj->uploadTCL4PO($request['template_id_2'],$request['po_number'],$destination_path_insert,$request['delivery_date']);
                break;
              case 'TSL3':
                $result = $tesco_po_obj->uploadTSL3PO($request['template_id_2'],$request['po_number'],$destination_path_insert,$request['delivery_date']);
                break;
              /** THL1, THL3 Not required in combined Buyer PO upload */
              default:
                $result = "Invalid Template";
                break;
            }
   } catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
  }
  /**
   * method to tesco buyers combined po upload template 3 switching
   * @param  [type] $request, $template_id
   * @return tesco order table id (inserted_id)
   */
  public function combinedPOUploadTemplate3Switch($template,$request,$destination_path_insert)
  {
    try {
            /** [ Creating new object for TescoCombinedPOUpload() class ] */
            $tesco_po_obj = new TescoCombinedPOUpload(); 
            
            switch ($template) 
            {
              case 'TCL1':
                $result = $tesco_po_obj->uploadTCL1PO($request['template_id_3'],$request['po_number'],$destination_path_insert,$request['delivery_date']);
                break;
              case 'TCL2':
                $result = $tesco_po_obj->uploadTCL2PO($request['template_id_3'],$request['po_number'],$destination_path_insert,$request['delivery_date']);
                break;
              case 'TSL1':
                $result = $tesco_po_obj->uploadTSL1PO($request['template_id_3'],$request['po_number'],$destination_path_insert,$request['delivery_date']);
                break;
              case 'TCL4':
                $result = $tesco_po_obj->uploadTCL4PO($request['template_id_3'],$request['po_number'],$destination_path_insert,$request['delivery_date']);
                break;
              case 'TSL3':
                $result = $tesco_po_obj->uploadTSL3PO($request['template_id_3'],$request['po_number'],$destination_path_insert,$request['delivery_date']);
                break;
              /** THL1, THL3 Not required in combined Buyer PO upload */
              default:
                $result = "Invalid Template";
                break;
            }
   } catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
  }
}
