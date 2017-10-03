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
	        	ini_set('max_execution_time', 300);
	        	$path     = $request->file('file')->getRealPath();
                $data = Excel::load($path, function($reader) {})->get();
                $data_exists = OrchestraItemDetails::select()->get();
                if (count($data_exists)>0)
                {
                	foreach ($data as $value)
	        		{	
	        			$collection_exixts = DB::table('orchestra_item_details')->where('Collection',$value->collection)->select('*')->get();
	        			if (count($orders)>0)
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
	        	$result = "flase";
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
    		ini_set('max_execution_time', 300);
	        $path  		= $request->file('file')->getRealPath();
	        $filename	= $request->file('file')->getClientOriginalName();
	        $extension  = $request->file('file')->getClientOriginalExtension();
        	$data  = Excel::load($path, function($reader) {})->get();
        	$filename = preg_replace( '/\.[a-z0-9]+$/i' , '' , $filename );
        	if ($filename =="ParcelDetails_file1")
        	{
        		$data_exists = OrchestraParcelDetails::select()->get();
	            if (count($data_exists)>0)
	            {
	            	$result = response()->json(['error' =>false,'message' => 'Data Exists'], 200);
	            }
	            else
	            {
	            	foreach ($data as $value)
		        	{
		        		OrchestraParcelDetails::create([
				            'FactoryCode' 	  => $value->factorycode,
				            'SupplierName' 	  => $value->suppliername,
				            'SupplierAddress' => $value->supplieraddress,
				            'SupplierEmail' => $value->supplieremail,
				            'SupplierCountry' => $value->suppliercountry,
				            'EditionNumber' => $value->editionnumber,
				            'VersionNumber' => $value->versionnumber,
				            'BuyerPONo' => $value->buyerpono,
				            'Theme' => $value->theme,
				            'Quantity' => $value->quantity,
				            'IsSTD' => $value->isstd,
				            'UVC' => $value->uvc,
				            'ParcelBarCode' => $value->parcelbarcode,
				            'ParcelCode' => $value->parcelcode,
				            'CatgDescr' => $value->catgdescr,
				            'NomApple' => $value->nomapple,
			       		 ]);
		        	}
	            }
        		
        	}
        	else if ($filename =="ParcelDetails_file2")
        	{
        		$data_exists = OrchestraParcelQuantityDetails::select()->get();
	            if (count($data_exists)>0)
	            {
	            	$result = response()->json(['error' =>false,'message' => 'Data Exists'], 200);
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
				            'BarcodeForPricesTags' => $value->barcodeforpricestags,
			       	 	]);
		        	}
	            }
        	}
        	else
        	{
        		return "Invalid File";
        	}
	    }
		catch (\Exception $e)
		{
          $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
     	}
    	return $result;
    }
}
