<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Excel;
use Input;
use Validator;
use Response;
use App\OrchestraItemDetails;
use App\OrchestraParcelDetails;
use App\OrchestraParcelQuantityDetails;
use View;
use DB;
use App\Classes\Uploads;

class UploadController extends Controller
{
    /**
     * method to view the orchestra item detail upload view
     * @return [type] [description]
     */
    public function index()
    {
        return view('csv-upload');
    }
    /**
     * method to upload the orchestra item details csv into db
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function csvUpload(Request $request)
    {
        try
    	{
    		$upload_obj = new Uploads();
    		if($request->hasFile('file'))
	        {
	        	ini_set('max_execution_time', 1000);
	        	$path = $request->file('file')->getRealPath();
	        	$filename	= $request->file('file')->getClientOriginalName();
	            $extension  = $request->file('file')->getClientOriginalExtension();
	            $filename = strtok($filename, " ");
	           	if ($filename == "ItemDetails_ORCHESTRA")
	           	{
	           		$data = Excel::load($path, function($reader) {})->get();
	                $data_exists = OrchestraItemDetails::select()->get();
	                if (count($data_exists)>0)
	                {
	                	foreach ($data as $value)
		        		{	
		        			$collection_exixts = DB::table('orchestra_item_details')->where('Collection',$value->collection)->select('*')->get();
		        			if (count($collection_exixts)>0)
		        			{
		        				$order_uvc = DB::table('orchestra_item_details')->where('Collection',$value->collection)->where('UVC',$value->uvc)->select('*')->get();
		        				if (count($order_uvc)>0)
		        				{
		        					$order_po = DB::table('orchestra_item_details')->where('Collection',$value->collection)->where('UVC',$value->uvc)->where('BuyerPONo',$value->buyerpono)->select('*')->get();
		        					if (count($order_po)>0)
		        					{
		        						$order_version = DB::table('orchestra_item_details')->where('Collection',$value->collection)->where('UVC',$value->uvc)->where('BuyerPONo',$value->buyerpono)->where('VersionNumber',$value->versionnumber)->select('*')->get();
		        						if (count($order_version)>0)
		        						{
		        							$order_edition = DB::table('orchestra_item_details')->where('Collection',$value->collection)->where('UVC',$value->uvc)->where('BuyerPONo',$value->buyerpono)->where('VersionNumber',$value->versionnumber)->where('EditionNumber',$value->editionnumber)->select('*')->get();
		        							if (count($order_edition)>0)
		        							{
		        								$order_size = DB::table('orchestra_item_details')->where('Collection',$value->collection)->where('UVC',$value->uvc)->where('BuyerPONo',$value->buyerpono)->where('VersionNumber',$value->versionnumber)->where('EditionNumber',$value->editionnumber)->where('Size',$value->size)->select('*')->get();
		        								if (count($order_size)>0)
		        								{
		        									// $upload_obj->updateOrchestraItemDetails($value);
		        								}
		        								else
		        								{
		        									$upload_obj->insertOrchestraItemDetails($value);
		        								}
		        							}
		        							else
		        							{
		        								$upload_obj->insertOrchestraItemDetails($value);
		        							}
		        						}
		        						else
		        						{
		        							$upload_obj->insertOrchestraItemDetails($value);
		        						}
		        					}
		        					else
		        					{
		        						$upload_obj->insertOrchestraItemDetails($value);
		        					}
		        				}
		        				else
		        				{
		        					$upload_obj->insertOrchestraItemDetails($value);
		        				}
		        			}
		        			else
		        			{
		        				$upload_obj->insertOrchestraItemDetails($value);
		        			}
		        		}
		        		$result = response()->json(['error' =>false,'message' => 'Uploaded Successfully'], 200);
	                }
	                else
	                {
	                	foreach ($data as $value)
		        		{	
		        			$upload_obj->insertOrchestraItemDetails($value);
		        		}
		        		$result = response()->json(['error' =>false,'message' => 'Uploaded Successfully'], 200);
	                }
	           	}
                else
		        {
		        	$result = response()->json(['error' =>true,'message' => 'Invalid File'], 403);
		        }
	        }
	        else
	        {
	        	$result = response()->json(['error' =>true,'message' => 'File missing'], 403);
	        }
	    }
		catch (\Exception $e)
		{
          $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
     	}
    	return $result;
    }

    /**
     * method to view the parcel details csv upload view
     * @return blade
     */
    public function multiUploadView()
    {
        return view('multi-csv-upload');
    }

    /**
     * method to upload the parcel details csv files into the db
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function csvMultiUpload(Request $request)
    {
        try
    	{
    		$upload_obj = new Uploads();
    		ini_set('max_execution_time', 1000);
	        $path  		= $request->file('file')->getRealPath();
	        $filename	= $request->file('file')->getClientOriginalName();
	        $extension  = $request->file('file')->getClientOriginalExtension();
        	$data  = Excel::load($path, function($reader) {})->get();
        	$filename = strtok($filename, " ");
        	if ($filename =="ParcelDetails_File1_ORCHESTRA")
        	{
        		$data_exists = OrchestraParcelDetails::select()->get();
	            if (count($data_exists)>0)
	            {
	            	foreach ($data as $value)
		        	{
	            		print_r($value->parcelbarcode);
	            		$parcel_uvc = DB::table('orchestra_parcel_details')->where('UVC',$value->uvc)->select('*')->get();
	            		if (count($parcel_uvc)>0)
	            		{
	            			$parcel_po = DB::table('orchestra_parcel_details')->where('UVC',$value->uvc)->where('BuyerPONo',$value->buyerpono)->select('*')->get();
	            			if (count($parcel_po)>0)
	            			{
	            				$parcel_version = DB::table('orchestra_parcel_details')->where('UVC',$value->uvc)->where('BuyerPONo',$value->buyerpono)->where('VersionNumber',$value->versionnumber)->select('*')->get();
	            				if (count($parcel_version)>0)
	            				{
	            					$parcel_edition = DB::table('orchestra_parcel_details')->where('UVC',$value->uvc)->where('BuyerPONo',$value->buyerpono)->where('VersionNumber',$value->versionnumber)->where('EditionNumber',$value->editionnumber)->select('*')->get();
	            					if (count($parcel_edition))
	            					{
	            						$parcel_std = DB::table('orchestra_parcel_details')->where('UVC',$value->uvc)->where('BuyerPONo',$value->buyerpono)->where('VersionNumber',$value->versionnumber)->where('EditionNumber',$value->editionnumber)->where('IsSTD',$value->isstd)->select('*')->get();
	            						if (count($parcel_std)>0)
	            						{
	            							foreach ($parcel_std as $vals)
	            							{
	            								if ($vals->IsSTD == 1)
	            								{
	            									DB::table('orchestra_parcel_quantity_details')
													   ->where('UVC',$value->uvc)
													   ->where('BuyerPONo',$value->buyerpono)
													   ->where('VersionNumber',$value->versionnumber)
													   ->where('EditionNumber',$value->editionnumber)
													   ->delete();
	            								}
	            								// $upload_obj->updateOrchestraParcelDetails($value);
	            							}
	            						}
	            						else
	            						{
	            							$upload_obj->insertOrchestraParcelDetails($value);
	            						}
	            					}
	            					else
	            					{
	            						$upload_obj->insertOrchestraParcelDetails($value);
	            					}
	            				}
	            				else
	            				{
	            					$upload_obj->insertOrchestraParcelDetails($value);
	            				}
	            			}
	            			else
	            			{
	            				$upload_obj->insertOrchestraParcelDetails($value);
	            			}
	            		}
	            		else
	            		{
	            			$upload_obj->insertOrchestraParcelDetails($value);
	            		}
	            	}
	            	$result = response()->json(['error' =>false,'message' => 'Uploaded Successfully'], 200);
	            }
	            else
	            {
	            	foreach ($data as $value)
		        	{
		        		$upload_obj->insertOrchestraParcelDetails($value);
		        	}
		        	$result = response()->json(['error' =>false,'message' => 'Uploaded Successfully'], 200);
	            }
        	}
        	else if ($filename =="ParcelDetails_File2_ORCHESTRA")
        	{
        		$data_exists = OrchestraParcelQuantityDetails::select()->get();
	            if (count($data_exists)>0)
	            {
	            	foreach ($data as $value)
		        	{
		        		$quantity_uvc = DB::table('orchestra_parcel_quantity_details')->where('UVC',$value->uvc)->select('*')->get();
		        		if (count($quantity_uvc)>0)
		        		{
		        			$quantity_po = DB::table('orchestra_parcel_quantity_details')->where('UVC',$value->uvc)->where('BuyerPONo',$value->buyerpono)->select('*')->get();
		        			if (count($quantity_po)>0)
		        			{
		        				$quantity_version = DB::table('orchestra_parcel_quantity_details')->where('UVC',$value->uvc)->where('BuyerPONo',$value->buyerpono)->where('VersionNumber',$value->versionnumber)->select('*')->get();
		        				if (count($quantity_version)>0)
		        				{
		        					$quantity_edition = DB::table('orchestra_parcel_quantity_details')->where('UVC',$value->uvc)->where('BuyerPONo',$value->buyerpono)->where('VersionNumber',$value->versionnumber)->where('EditionNumber',$value->editionnumber)->select('*')->get();
		        					if (count($quantity_edition)>0)
		        					{
		        						$quantity_size = DB::table('orchestra_parcel_quantity_details')->where('UVC',$value->uvc)->where('BuyerPONo',$value->buyerpono)->where('VersionNumber',$value->versionnumber)->where('EditionNumber',$value->editionnumber)->where('Size',$value->size)->select('*')->get();
		        						if (count($quantity_size)>0)
		        						{
		        							// $upload_obj->updateOrchestraParcelQuantityDetails($value);
		        						}
		        						else
		        						{
		        							$upload_obj->insertOrchestraParcelQuantityDetails($value);
		        						}
		        					}
		        					else
		        					{
		        						$upload_obj->insertOrchestraParcelQuantityDetails($value);
		        					}
		        				}
		        				else
		        				{
		        					$upload_obj->insertOrchestraParcelQuantityDetails($value);
		        				}
		        			}
		        			else
		        			{	
		        				$upload_obj->insertOrchestraParcelQuantityDetails($value);
		        			}
		        		}
		        		else
		        		{
		        			$upload_obj->insertOrchestraParcelQuantityDetails($value);
		        		}
		        	}
		        	$result = response()->json(['error' =>false,'message' => 'Uploaded Successfully'], 200);
	            }
	            else
	            {
	            	foreach ($data as $value)
		        	{
		        		OrchestraParcelQuantityDetails::create([
				            'FactoryCode' 	  => $value->factorycode,
				            'EditionNumber' => $value->editionnumber,
				            'VersionNumber' => $value->versionnumber,
				            'BuyerPONo' => $value->buyerpono,
				            'Theme' => $value->theme,
				            'UVC_TCO' => $value->uvc_tco,
				            'Quantity' => $value->quantity,
				            'Color' => $value->color,
				            'UVC' => $value->uvc,
				            'Size' => $value->size,
				            'BarcodeForPricesTags' => $value->barcodeforpricestags
				   	 	]);
		        	}
		        	$result = response()->json(['error' =>false,'message' => 'Uploaded Successfully'], 200);
	            }
        	}
        	else
        	{
        		$result = response()->json(['error' =>true,'message' => 'Invalid File'], 403);
        	}
	    }
		catch (\Exception $e)
		{
          $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
     	}
    	return $result;
    }
}
