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


class UploadController extends Controller
{
    public function index()
    {
        return view('csv-upload');
    }
    public function csvUpload(Request $request)
    {
        try
    	{
    		if($request->hasFile('file'))
	        {
	        	ini_set('max_execution_time', 300);
	        	$path     = $request->file('file')->getRealPath();
                $data = Excel::load($path, function($reader) {})->get();
                foreach ($data as $value)
        		{	OrchestraItemDetails::create([
			            'FactoryCode' 	  => $value->factorycode,
			            'SupplierName' 	  => $value->suppliername,
			            'SupplierAddress' => $value->supplieraddress,
			            'SupplierEmail' => $value->supplieremail,
			            'SupplierCountry' => $value->suppliername,
			            'BuyerEmail' => $value->suppliername,
			            'First_ETD_SHIPMENT_DATE' => $value->suppliername,
			            'EditionNumber' => 111,
			            'VersionNumber' => 22,
			            'Collection' => $value->suppliername,
			            'BuyerPONo' => $value->suppliername,
			            'BarcodeForPricesTags' => $value->suppliername,
			            'UVC_TCO' => $value->suppliername,
			            'Description' => $value->suppliername,
			            'Rayon' => $value->suppliername,
			            'Color' => $value->suppliername,
			            'Size' => $value->suppliername,
			            'Stature' => $value->suppliername,
			            'Age' => $value->suppliername,
			            'Theme' => $value->suppliername,
			            'Marque' => $value->suppliername,
			            'Disney' => $value->suppliername,
			            'Marvel' => $value->suppliername,
			            'PommeFramboise' => $value->suppliername,
			            'PrixEurope' => 1,
			            'PrixClubEurope' => 1,
			            'PrixVenteKoweit' => 2,
			            'Prix_Vente_Arabie_Saoudite' => 2,
			            'Prix_Club_Arabie_Saoudite' => 2,
			            'Prix_Vente_Canada' => 1,
			            'Prix_Club_Canada' => 4,
			            'Prix_Vente_Suisse' => 3,
			            'Prix_Club_Suisse' => 2,
			            'Prix_Vente_Chine' => 2,
			            'Prix_Club_Chine' => 2,
			            'Prix_Vente_Turquie' => 2,
			            'Prix_Club_Turquie' => 2,
			            'Prix_Vente_Maroc' => 2,
			            'Prix_Club_Maroc' => 2,
			            'Prix_Vente_Ile_Maurice' => 2,
			            'Prix_Club_Ile_Maurice' => 2,
			            'Prix_Vente_Emirats_Arabes_Unis' => 2,
			            'Prix_Club_Emirats_Arabes_Unis' => 2,
			            'Prix_Vente_TUNISIE' => 2,
			            'Prix_Club_TUNISIE' => 2,
			            'Prix_Vente_ALGERIE' => 2,
			            'Prix_Club_ALGERIE' => 2,
			            'Quantity' => 2,
			            'UVC' => $value->suppliername,
			            'ParcelBarCode' => 111111111,
			            'ParcelCode' => $value->suppliername,
			            'HangTag_Print_Date' => $value->suppliername,
			            'Prix_Vente_QATAR' => 1,
			            'Prix_Club_QATAR' =>1,
			            'Style_Name' => $value->suppliername,
			            'Prix_Vente_USA' => 2,
			            'Prix_Club_USA' => 2,
			        ]);
        		}
        		$result = "true";
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

    public function multiUploadView()
    {
        return view('multi-csv-upload');
    }

    public function csvMultiUpload(Request $request)
    {
        try
    	{
    		ini_set('max_execution_time', 300);
	        $path  		= $request->file('file')->getRealPath();
	        $filename	= $request->file('file')->getClientOriginalName();
	        $extension  = $request->file('file')->getClientOriginalExtension();
	        if ($extension !='csv')
	        {
	        	return "failed" ;
	        }
	        else
	        {
	        	$data  = Excel::load($path, function($reader) {})->get();
	        	$filename = preg_replace( '/\.[a-z0-9]+$/i' , '' , $filename );
	        	return "success";
	        	if ($filename =="ParcelDetails_file1")
	        	{
	        		foreach ($data as $vals)
		        	{
		        		return $vals;
		        	}
	        	}
	        	else if ($filename =="ParcelDetails_file2")
	        	{
	        		foreach ($data as $vals)
		        	{
		        		return $vals;
		        	}
	        	}
	        	else
	        	{
	        		return "Invalid File";
	        	}
	        }

	    }
		catch (\Exception $e)
		{
          $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
     	}
    	return $result;
    }

    public function uploadFiles(Request $request)
    {
 
        $input = $request->all();
 		
        $rules = array(
            'file' => 'image|max:3000',
        );
 
        $validation = Validator::make($input, $rules);
 
        if ($validation->fails()) {
            return Response::make($validation->errors->first(), 400);
        }
 		$path     = $request->file('file')->getRealPath();
        $data = Excel::load($path, function($reader) {})->get();

        // $destinationPath = 'uploads'; // upload path
        // $extension = Input::file('file')->getClientOriginalExtension(); // getting file extension
        // $fileName = rand(11111, 99999) . '.' . $extension; // renameing image
        // $upload_success = Input::file('file')->move($destinationPath, $fileName); // uploading file to given path
 
        // if ($upload_success) {
        //     return Response::json('success', 200);
        // } else {
        //     return Response::json('error', 400);
        // }
    }
}
