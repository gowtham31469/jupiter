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
    		if($request->hasFile('file'))
	        {
	        	ini_set('max_execution_time', 300);
	        	$path     = $request->file('file')->getRealPath();
                $data = Excel::load($path, function($reader) {})->get();
                $data_exists = OrchestraItemDetails::select()->get();
                if (count($data_exists)>0)
                {
                	$result = response()->json(['error' =>false,'message' => 'Data Exists'], 200);
                }
                else
                {
                	foreach ($data as $value)
	        		{	
	        			OrchestraItemDetails::create([
				            'FactoryCode' 	  => $value->factorycode,
				            'SupplierName' 	  => $value->suppliername,
				            'SupplierAddress' => $value->supplieraddress,
				            'SupplierEmail' => $value->supplieremail,
				            'SupplierCountry' => $value->suppliercountry,
				            'BuyerEmail' => $value->buyeremail,
				            'First_ETD_SHIPMENT_DATE' => $value->first_etd_shipment_date,
				            'EditionNumber' => $value->editionnumber,
				            'VersionNumber' => $value->versionnumber,
				            'Collection' => $value->collection,
				            'BuyerPONo' => $value->buyerpono,
				            'BarcodeForPricesTags' => $value->barcodeforpricestags,
				            'UVC_TCO' => $value->uvc_tco,
				            'Description' => $value->description,
				            'Rayon' => $value->rayon,
				            'Color' => $value->color,
				            'Size' => $value->size,
				            'Stature' => $value->stature,
				            'Age' => $value->age,
				            'Theme' => $value->theme,
				            'Marque' => $value->marque,
				            'Disney' => $value->disney,
				            'Marvel' => $value->marvel,
				            'PommeFramboise' => $value->pommeframboise,
				            'PrixEurope' => $value->prixeurope,
				            'PrixClubEurope' => $value->prixclubeurope,
				            'PrixVenteKoweit' => $value->prixventekoweit,
				            'Prix_Vente_Arabie_Saoudite' => $value->prix_vente_arabie_saoudite,
				            'Prix_Club_Arabie_Saoudite' => $value->prix_club_arabie_saoudite,
				            'Prix_Vente_Canada' => $value->prix_vente_canada,
				            'Prix_Club_Canada' => $value->prix_club_canada,
				            'Prix_Vente_Suisse' => $value->prix_vente_suisse,
				            'Prix_Club_Suisse' => $value->prix_club_suisse,
				            'Prix_Vente_Chine' => $value->prix_vente_chine,
				            'Prix_Club_Chine' => $value->prix_club_chine,
				            'Prix_Vente_Turquie' => $value->prix_vente_turquie,
				            'Prix_Club_Turquie' => $value->prix_club_turquie,
				            'Prix_Vente_Maroc' => $value->prix_vente_maroc,
				            'Prix_Club_Maroc' => $value->prix_club_maroc,
				            'Prix_Vente_Ile_Maurice' => $value->prix_vente_ile_maurice,
				            'Prix_Club_Ile_Maurice' => $value->prix_club_ile_maurice,
				            'Prix_Vente_Emirats_Arabes_Unis' => $value->prix_vente_emirats_arabes_unis,
				            'Prix_Club_Emirats_Arabes_Unis' => $value->prix_club_emirats_arabes_unis,
				            'Prix_Vente_TUNISIE' => $value->prix_vente_tunisie,
				            'Prix_Club_TUNISIE' => $value->prix_club_tunisie,
				            'Prix_Vente_ALGERIE' => $value->prix_vente_algerie,
				            'Prix_Club_ALGERIE' => $value->prix_club_algerie,
				            'Quantity' => $value->quantity,
				            'UVC' => $value->uvc,
				            'ParcelBarCode' => $value->parcelbarcode,
				            'ParcelCode' => $value->parcelcode,
				            'HangTag_Print_Date' => $value->hangtag_print_date,
				            'Prix_Vente_QATAR' => $value->prix_vente_qatar,
				            'Prix_Club_QATAR' =>$value->prix_club_qatar,
				            'Style_Name' => $value->style_name,
				            'Prix_Vente_USA' => $value->prix_vente_usa,
				            'Prix_Club_USA' => $value->prix_club_usa,
				        ]);
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
