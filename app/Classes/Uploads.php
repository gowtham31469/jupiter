<?php
namespace App\Classes;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Response;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use App\OrchestraItemDetails;
use App\TempOrchestraItemDetails;
use App\OrchestraParcelDetails;
use App\OrchestraParcelQuantityDetails;
use App\Customer;
use App\CustomerBillAddress;
use DB;
use Auth;
use App\RolePermissionMdl;

class Uploads
{
	public function csv_to_array($filename='', $delimiter=';')
	{
		if(!file_exists($filename) || !is_readable($filename))
			return FALSE;
		
		$header = NULL;
		$data = array();
		if (($handle = fopen($filename, 'r')) !== FALSE)
		{
			while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE)
			{
				if(!$header)
					$header = $row;
				else
					$data[] = array_combine($header, $row);
			}
			fclose($handle);
		}
		return $data;
	}


	/**
	 * method to add customer based on factory code
	 * @param  [type]  $data [description]
	 * @return boolean       [description]
	 */
	public function isCustomerExists($data)
	{
		foreach ($data as $key => $value)
		{
			if(Customer::where('factory_code',$data[$key]['FactoryCode'])->count() == 0)
			{
				$custm_details = Customer::create([
									'company_name'  => $data[$key]['SupplierName'],
				       				'factory_code' 	=> $data[$key]['FactoryCode'],
				       				'email'			=> $data[$key]['SupplierEmail']
								  ]);				
				CustomerBillAddress::create([
			                        'customer_id'   => $custm_details->id,
			                        'address'       => $data[$key]['SupplierAddress'],
			                        'country'       => $data[$key]['SupplierCountry'],
						       		'email'			=> $data[$key]['SupplierEmail']
			                        ]);
			}
		}
		
	}

	public function insertOrchestraItemDetails($data,$key)
	{
	    OrchestraItemDetails::create([
	          'FactoryCode'     => $data[$key]['FactoryCode'],
	          'SupplierName'    => $data[$key]['SupplierName'],
	          'SupplierAddress' => $data[$key]['SupplierAddress'],
	          'SupplierEmail' => $data[$key]['SupplierEmail'],
	          'SupplierCountry' => $data[$key]['SupplierCountry'],
	          'BuyerEmail' => $data[$key]['BuyerEmail'],
	          'First_ETD_SHIPMENT_DATE' => $data[$key]['First_ETD_SHIPMENT_DATE'],
	          'EditionNumber' => $data[$key]['EditionNumber'],
	          'VersionNumber' => $data[$key]['VersionNumber'],
	          'Collection' => $data[$key]['Collection'],
	          'BuyerPONo' => $data[$key]['BuyerPONo'],
	          'BarcodeForPricesTags' => $data[$key]['BarcodeForPricesTags'],
	          'UVC_TCO' => $data[$key]['UVC_TCO'],
	          'Description' => $data[$key]['Description'],
	          'Rayon' => $data[$key]['Rayon'],
	          'Color' => $data[$key]['Color'],
	          'Size' => $data[$key]['Size'],
	          'Stature' => $data[$key]['Stature'],
	          'Age' => $data[$key]['Age'],
	          'Theme' => $data[$key]['Theme'],
	          'Marque' => $data[$key]['Marque'],
	          'Disney' => $data[$key]['Disney'],
	          'Marvel' => $data[$key]['Marvel'],
	          'PommeFramboise' => $data[$key]['PommeFramboise'],
	          'PrixEurope' => $data[$key]['PrixEurope'],
	          'PrixClubEurope' => $data[$key]['PrixClubEurope'],
	          'PrixVenteKoweit' => $data[$key]['PrixVenteKoweit'],
	          'Prix_Vente_Arabie_Saoudite' => $data[$key]['Prix_Vente_Arabie_Saoudite'],
	          'Prix_Club_Arabie_Saoudite' => $data[$key]['Prix_Club_Arabie_Saoudite'],
	          'Prix_Vente_Canada' => $data[$key]['Prix_Vente_Canada'],
	          'Prix_Club_Canada' => $data[$key]['Prix_Club_Canada'],
	          'Prix_Vente_Suisse' => $data[$key]['Prix_Vente_Suisse'],
	          'Prix_Club_Suisse' => $data[$key]['Prix_Club_Suisse'],
	          'Prix_Vente_Chine' => $data[$key]['Prix_Vente_Chine'],
	          'Prix_Club_Chine' => $data[$key]['Prix_Club_Chine'],
	          'Prix_Vente_Turquie' => $data[$key]['Prix_Vente_Turquie'],
	          'Prix_Club_Turquie' => $data[$key]['Prix_Club_Turquie'],
	          'Prix_Vente_Maroc' => $data[$key]['Prix_Vente_Maroc'],
	          'Prix_Club_Maroc' => $data[$key]['Prix_Club_Maroc'],
	          'Prix_Vente_Ile_Maurice' => $data[$key]['Prix_Vente_Ile_Maurice'],
	          'Prix_Club_Ile_Maurice' => $data[$key]['Prix_Club_Ile_Maurice'],
	          'Prix_Vente_Emirats_Arabes_Unis' => $data[$key]['Prix_Vente_Emirats_Arabes_Unis'],
	          'Prix_Club_Emirats_Arabes_Unis' => $data[$key]['Prix_Club_Emirats_Arabes_Unis'],
	          'Prix_Vente_TUNISIE' => $data[$key]['Prix_Vente_TUNISIE'],
	          'Prix_Club_TUNISIE' => $data[$key]['Prix_Club_TUNISIE'],
	          'Prix_Vente_ALGERIE' =>$data[$key]['Prix_Vente_ALGERIE'],
	          'Prix_Club_ALGERIE' => $data[$key]['Prix_Club_ALGERIE'],
	          'Quantity' => $data[$key]['Quantity'],
	          'UVC' => $data[$key]['UVC'],
	          'ParcelBarCode' => $data[$key]['ParcelBarCode'],
	          'ParcelCode' => $data[$key]['ParcelCode'],
	          'HangTag_Print_Date' => $data[$key]['HangTag_Print_Date'],
	          'Prix_Vente_QATAR' => $data[$key]['Prix_Vente_QATAR'],
	          'Prix_Club_QATAR' =>$data[$key]['Prix_Club_QATAR'],
	          'Style_Name' => $data[$key]['Style_Name'],
	          'Prix_Vente_USA' => $data[$key]['Prix_Vente_USA'],
	          'Prix_Club_USA' => $data[$key]['Prix_Club_USA'],
	      ]);
	}

  /**
   * method to update the orchestra item details in DB
   * @param  [type] $data [description]
   * @return [type]       [description]
   */
	public function updateOrchestraItemDetails($order_size,$data,$key)
	{
	    foreach ($order_size as $key => $value)
	    {
	    	if ($value->order_status=="Submitted")
	    	{
	    		$order_exists = DB::table('temp_orchestra_item_details')->where('Collection',$data[$key]['Collection'])->where('UVC',$data[$key]['UVC'])->where('BuyerPONo',$data[$key]['BuyerPONo'])->where('VersionNumber',$data[$key]['VersionNumber'])->where('Size',$data[$key]['Size'])->select('*')->get();
	    		if (count($order_exists)>0)
	    		{
	    		 	 TempOrchestraItemDetails::where('Collection',$data[$key]['Collection'])
				      ->where('UVC',$data[$key]['UVC'])
				      ->where('BuyerPONo',$data[$key]['BuyerPONo'])
				      ->where('VersionNumber',$data[$key]['VersionNumber'])
				      // ->where('EditionNumber',$data[$key]['EditionNumber'])
				      ->where('Size',$data[$key]['Size'])
		              ->update(['FactoryCode' => $data[$key]['FactoryCode'],
			          'SupplierName'  => $data[$key]['SupplierName'],
			          'SupplierAddress' => $data[$key]['SupplierAddress'],
			          'SupplierEmail' => $data[$key]['SupplierEmail'],
			          'SupplierCountry' => $data[$key]['SupplierCountry'],
			          'BuyerEmail' => $data[$key]['BuyerEmail'],
			          'First_ETD_SHIPMENT_DATE' => $data[$key]['First_ETD_SHIPMENT_DATE'],
			          'BarcodeForPricesTags' => (float)$data[$key]['BarcodeForPricesTags'],
			          'UVC_TCO' => $data[$key]['UVC_TCO'],
			          'Description' => $data[$key]['Description'],
			          'Rayon' => $data[$key]['Rayon'],
			          'Color' => $data[$key]['Color'],
			          'Stature' => $data[$key]['Stature'],
			          'Age' => $data[$key]['Age'],
			          'Theme' => $data[$key]['Theme'],
			          'Marque' => $data[$key]['Marque'],
			          'Disney' => $data[$key]['Disney'],
			          'Marvel' => $data[$key]['Marvel'],
			          'PommeFramboise' => $data[$key]['PommeFramboise'],
			          'PrixEurope' => $data[$key]['PrixEurope'],
			          'PrixClubEurope' => $data[$key]['PrixClubEurope'],
			          'PrixVenteKoweit' => $data[$key]['PrixVenteKoweit'],
			          'Prix_Vente_Arabie_Saoudite' => $data[$key]['Prix_Vente_Arabie_Saoudite'],
			          'Prix_Club_Arabie_Saoudite' => $data[$key]['Prix_Club_Arabie_Saoudite'],
			          'Prix_Vente_Canada' => $data[$key]['Prix_Vente_Canada'],
			          'Prix_Club_Canada' => $data[$key]['Prix_Club_Canada'],
			          'Prix_Vente_Suisse' => $data[$key]['Prix_Vente_Suisse'],
			          'Prix_Club_Suisse' => $data[$key]['Prix_Club_Suisse'],
			          'Prix_Vente_Chine' => $data[$key]['Prix_Vente_Chine'],
			          'Prix_Club_Chine' => $data[$key]['Prix_Club_Chine'],
			          'Prix_Vente_Turquie' => $data[$key]['Prix_Vente_Turquie'],
			          'Prix_Club_Turquie' => $data[$key]['Prix_Club_Turquie'],
			          'Prix_Vente_Maroc' => $data[$key]['Prix_Vente_Maroc'],
			          'Prix_Club_Maroc' => $data[$key]['Prix_Club_Maroc'],
			          'Prix_Vente_Ile_Maurice' => $data[$key]['Prix_Vente_Ile_Maurice'],
			          'Prix_Club_Ile_Maurice' => $data[$key]['Prix_Club_Ile_Maurice'],
			          'Prix_Vente_Emirats_Arabes_Unis'=> $data[$key]['Prix_Vente_Emirats_Arabes_Unis'],
			          'Prix_Club_Emirats_Arabes_Unis' => $data[$key]['Prix_Club_Emirats_Arabes_Unis'],
			          'Prix_Vente_TUNISIE' => $data[$key]['Prix_Vente_TUNISIE'],
			          'Prix_Club_TUNISIE' => $data[$key]['Prix_Club_TUNISIE'],
			          'Prix_Vente_ALGERIE' =>$data[$key]['Prix_Vente_ALGERIE'],
			          'Prix_Club_ALGERIE' => $data[$key]['Prix_Club_ALGERIE'],
			          'Quantity' => $data[$key]['Quantity'],
			          'ParcelBarCode' => $data[$key]['ParcelBarCode'],
			          'ParcelCode' => $data[$key]['ParcelCode'],
			          'HangTag_Print_Date' => $data[$key]['HangTag_Print_Date'],
			          'Prix_Vente_QATAR' => $data[$key]['Prix_Vente_QATAR'],
			          'Prix_Club_QATAR' =>$data[$key]['Prix_Club_QATAR'],
			          'Style_Name' => $data[$key]['Style_Name'],
			          'Prix_Vente_USA' => $data[$key]['Prix_Vente_USA'],
			          'Prix_Club_USA' => $data[$key]['Prix_Club_USA']]);
	    		}
	    		else
	    		{
	    			 TempOrchestraItemDetails::create([
			          'FactoryCode'     => $data[$key]['FactoryCode'],
			          'SupplierName'    => $data[$key]['SupplierName'],
			          'SupplierAddress' => $data[$key]['SupplierAddress'],
			          'SupplierEmail' => $data[$key]['SupplierEmail'],
			          'SupplierCountry' => $data[$key]['SupplierCountry'],
			          'BuyerEmail' => $data[$key]['BuyerEmail'],
			          'First_ETD_SHIPMENT_DATE' => $data[$key]['First_ETD_SHIPMENT_DATE'],
			          'EditionNumber' => $data[$key]['EditionNumber'],
			          'VersionNumber' => $data[$key]['VersionNumber'],
			          'Collection' => $data[$key]['Collection'],
			          'BuyerPONo' => $data[$key]['BuyerPONo'],
			          'BarcodeForPricesTags' => $data[$key]['BarcodeForPricesTags'],
			          'UVC_TCO' => $data[$key]['UVC_TCO'],
			          'Description' => $data[$key]['Description'],
			          'Rayon' => $data[$key]['Rayon'],
			          'Color' => $data[$key]['Color'],
			          'Size' => $data[$key]['Size'],
			          'Stature' => $data[$key]['Stature'],
			          'Age' => $data[$key]['Age'],
			          'Theme' => $data[$key]['Theme'],
			          'Marque' => $data[$key]['Marque'],
			          'Disney' => $data[$key]['Disney'],
			          'Marvel' => $data[$key]['Marvel'],
			          'PommeFramboise' => $data[$key]['PommeFramboise'],
			          'PrixEurope' => $data[$key]['PrixEurope'],
			          'PrixClubEurope' => $data[$key]['PrixClubEurope'],
			          'PrixVenteKoweit' => $data[$key]['PrixVenteKoweit'],
			          'Prix_Vente_Arabie_Saoudite' => $data[$key]['Prix_Vente_Arabie_Saoudite'],
			          'Prix_Club_Arabie_Saoudite' => $data[$key]['Prix_Club_Arabie_Saoudite'],
			          'Prix_Vente_Canada' => $data[$key]['Prix_Vente_Canada'],
			          'Prix_Club_Canada' => $data[$key]['Prix_Club_Canada'],
			          'Prix_Vente_Suisse' => $data[$key]['Prix_Vente_Suisse'],
			          'Prix_Club_Suisse' => $data[$key]['Prix_Club_Suisse'],
			          'Prix_Vente_Chine' => $data[$key]['Prix_Vente_Chine'],
			          'Prix_Club_Chine' => $data[$key]['Prix_Club_Chine'],
			          'Prix_Vente_Turquie' => $data[$key]['Prix_Vente_Turquie'],
			          'Prix_Club_Turquie' => $data[$key]['Prix_Club_Turquie'],
			          'Prix_Vente_Maroc' => $data[$key]['Prix_Vente_Maroc'],
			          'Prix_Club_Maroc' => $data[$key]['Prix_Club_Maroc'],
			          'Prix_Vente_Ile_Maurice' => $data[$key]['Prix_Vente_Ile_Maurice'],
			          'Prix_Club_Ile_Maurice' => $data[$key]['Prix_Club_Ile_Maurice'],
			          'Prix_Vente_Emirats_Arabes_Unis' => $data[$key]['Prix_Vente_Emirats_Arabes_Unis'],
			          'Prix_Club_Emirats_Arabes_Unis' => $data[$key]['Prix_Club_Emirats_Arabes_Unis'],
			          'Prix_Vente_TUNISIE' => $data[$key]['Prix_Vente_TUNISIE'],
			          'Prix_Club_TUNISIE' => $data[$key]['Prix_Club_TUNISIE'],
			          'Prix_Vente_ALGERIE' =>$data[$key]['Prix_Vente_ALGERIE'],
			          'Prix_Club_ALGERIE' => $data[$key]['Prix_Club_ALGERIE'],
			          'Quantity' => $data[$key]['Quantity'],
			          'UVC' => $data[$key]['UVC'],
			          'ParcelBarCode' => $data[$key]['ParcelBarCode'],
			          'ParcelCode' => $data[$key]['ParcelCode'],
			          'HangTag_Print_Date' => $data[$key]['HangTag_Print_Date'],
			          'Prix_Vente_QATAR' => $data[$key]['Prix_Vente_QATAR'],
			          'Prix_Club_QATAR' =>$data[$key]['Prix_Club_QATAR'],
			          'Style_Name' => $data[$key]['Style_Name'],
			          'Prix_Vente_USA' => $data[$key]['Prix_Vente_USA'],
			          'Prix_Club_USA' => $data[$key]['Prix_Club_USA'],
			      ]);
	    		}    		
	    	}
	    	else
	    	{
	    		 OrchestraItemDetails::where('Collection',$data[$key]['Collection'])
			      ->where('UVC',$data[$key]['UVC'])
			      ->where('BuyerPONo',$data[$key]['BuyerPONo'])
			      ->where('VersionNumber',$data[$key]['VersionNumber'])
			      // ->where('EditionNumber',$data[$key]['EditionNumber'])
			      ->where('Size',$data[$key]['Size'])
	              ->update(['FactoryCode' => $data[$key]['FactoryCode'],
		          'SupplierName'  => $data[$key]['SupplierName'],
		          'SupplierAddress' => $data[$key]['SupplierAddress'],
		          'SupplierEmail' => $data[$key]['SupplierEmail'],
		          'SupplierCountry' => $data[$key]['SupplierCountry'],
		          'BuyerEmail' => $data[$key]['BuyerEmail'],
		          'First_ETD_SHIPMENT_DATE' => $data[$key]['First_ETD_SHIPMENT_DATE'],
		          'BarcodeForPricesTags' => (float)$data[$key]['BarcodeForPricesTags'],
		          'UVC_TCO' => $data[$key]['UVC_TCO'],
		          'Description' => $data[$key]['Description'],
		          'Rayon' => $data[$key]['Rayon'],
		          'Color' => $data[$key]['Color'],
		          'Stature' => $data[$key]['Stature'],
		          'Age' => $data[$key]['Age'],
		          'Theme' => $data[$key]['Theme'],
		          'Marque' => $data[$key]['Marque'],
		          'Disney' => $data[$key]['Disney'],
		          'Marvel' => $data[$key]['Marvel'],
		          'PommeFramboise' => $data[$key]['PommeFramboise'],
		          'PrixEurope' => $data[$key]['PrixEurope'],
		          'PrixClubEurope' => $data[$key]['PrixClubEurope'],
		          'PrixVenteKoweit' => $data[$key]['PrixVenteKoweit'],
		          'Prix_Vente_Arabie_Saoudite' => $data[$key]['Prix_Vente_Arabie_Saoudite'],
		          'Prix_Club_Arabie_Saoudite' => $data[$key]['Prix_Club_Arabie_Saoudite'],
		          'Prix_Vente_Canada' => $data[$key]['Prix_Vente_Canada'],
		          'Prix_Club_Canada' => $data[$key]['Prix_Club_Canada'],
		          'Prix_Vente_Suisse' => $data[$key]['Prix_Vente_Suisse'],
		          'Prix_Club_Suisse' => $data[$key]['Prix_Club_Suisse'],
		          'Prix_Vente_Chine' => $data[$key]['Prix_Vente_Chine'],
		          'Prix_Club_Chine' => $data[$key]['Prix_Club_Chine'],
		          'Prix_Vente_Turquie' => $data[$key]['Prix_Vente_Turquie'],
		          'Prix_Club_Turquie' => $data[$key]['Prix_Club_Turquie'],
		          'Prix_Vente_Maroc' => $data[$key]['Prix_Vente_Maroc'],
		          'Prix_Club_Maroc' => $data[$key]['Prix_Club_Maroc'],
		          'Prix_Vente_Ile_Maurice' => $data[$key]['Prix_Vente_Ile_Maurice'],
		          'Prix_Club_Ile_Maurice' => $data[$key]['Prix_Club_Ile_Maurice'],
		          'Prix_Vente_Emirats_Arabes_Unis'=> $data[$key]['Prix_Vente_Emirats_Arabes_Unis'],
		          'Prix_Club_Emirats_Arabes_Unis' => $data[$key]['Prix_Club_Emirats_Arabes_Unis'],
		          'Prix_Vente_TUNISIE' => $data[$key]['Prix_Vente_TUNISIE'],
		          'Prix_Club_TUNISIE' => $data[$key]['Prix_Club_TUNISIE'],
		          'Prix_Vente_ALGERIE' =>$data[$key]['Prix_Vente_ALGERIE'],
		          'Prix_Club_ALGERIE' => $data[$key]['Prix_Club_ALGERIE'],
		          'Quantity' => $data[$key]['Quantity'],
		          'ParcelBarCode' => $data[$key]['ParcelBarCode'],
		          'ParcelCode' => $data[$key]['ParcelCode'],
		          'HangTag_Print_Date' => $data[$key]['HangTag_Print_Date'],
		          'Prix_Vente_QATAR' => $data[$key]['Prix_Vente_QATAR'],
		          'Prix_Club_QATAR' =>$data[$key]['Prix_Club_QATAR'],
		          'Style_Name' => $data[$key]['Style_Name'],
		          'Prix_Vente_USA' => $data[$key]['Prix_Vente_USA'],
		          'Prix_Club_USA' => $data[$key]['Prix_Club_USA']]);
	    	}
	    }
	}


	public function insertOrchestraParcelDetails($data,$key)
	{
		OrchestraParcelDetails::create([
            'FactoryCode' 	  => $data[$key]['FactoryCode'],
            'SupplierName' 	  => $data[$key]['SupplierName'],
            'SupplierAddress' => $data[$key]['SupplierAddress'],
            'SupplierEmail' => $data[$key]['SupplierEmail'],
            'SupplierCountry' => $data[$key]['SupplierCountry'],
            'EditionNumber' => $data[$key]['EditionNumber'],
            'VersionNumber' => $data[$key]['VersionNumber'],
            'BuyerPONo' => $data[$key]['BuyerPONo'],
            'Theme' => $data[$key]['Theme'],
            'Quantity' => $data[$key]['Quantity'],
            'IsSTD' => $data[$key]['IsSTD'],
            'UVC' => $data[$key]['UVC'],
            'ParcelBarCode' => (float)$data[$key]['ParcelBarCode'],
            'ParcelCode' => $data[$key]['ParcelCode'],
            'CatgDescr' => $data[$key]['CatgDescr'],
            'NomApple' => $data[$key]['NomApple'],
   		]);
	}
	public function updateOrchestraParcelDetails($data,$key)
	{
		OrchestraParcelDetails::where('UVC',$data[$key]['UVC'])
			->where('BuyerPONo',$data[$key]['BuyerPONo'])
			->where('VersionNumber',$data[$key]['VersionNumber'])
			->where('EditionNumber',$data[$key]['EditionNumber'])
			->where('IsSTD',$data[$key]['IsSTD'])
            ->update(['FactoryCode' => $data[$key]['FactoryCode'],
            'SupplierName' 	  => $data[$key]['SupplierName'],
            'SupplierAddress' => $data[$key]['SupplierAddress'],
            'SupplierEmail' => $data[$key]['SupplierEmail'],
            'SupplierCountry' => $data[$key]['SupplierCountry'],
            'EditionNumber' => $data[$key]['EditionNumber'],
            'VersionNumber' => $data[$key]['VersionNumber'],
            'BuyerPONo' => $data[$key]['BuyerPONo'],
            'Theme' => $data[$key]['Theme'],
            'Quantity' => $data[$key]['Quantity'],
            'IsSTD' => $data[$key]['IsSTD'],
            'UVC' => $data[$key]['UVC'],
            'ParcelBarCode' => (float)$data[$key]['ParcelBarCode'],
            'ParcelCode' => $data[$key]['ParcelCode'],
            'CatgDescr' => $data[$key]['CatgDescr'],
            'NomApple' => $data[$key]['NomApple']]);
	}

	public function insertOrchestraParcelQuantityDetails($data,$key)
	{
		OrchestraParcelQuantityDetails::create([
            'FactoryCode' 	  => $data[$key]['FactoryCode'],
            'EditionNumber' => $data[$key]['EditionNumber'],
            'VersionNumber' => $data[$key]['VersionNumber'],
            'BuyerPONo' => $data[$key]['BuyerPONo'],
            'Theme' => $data[$key]['Theme'],
            'UVC_TCO' => $data[$key]['UVC_TCO'],
            'Quantity' => $data[$key]['Quantity'],
            'Color' => $data[$key]['Color'],
            'UVC' => $data[$key]['UVC'],
            'Size' => $data[$key]['Size'],
            'BarcodeForPricesTags' => $data[$key]['BarcodeForPricesTags']
   	 	]);
	}

	public function updateOrchestraParcelQuantityDetails($data,$key)
	{
		OrchestraParcelQuantityDetails::where('UVC',$data[$key]['UVC'])
			->where('BuyerPONo',$data[$key]['BuyerPONo'])
			->where('VersionNumber',$data[$key]['VersionNumber'])
			->where('EditionNumber',$data[$key]['EditionNumber'])
			->where('Size',$data[$key]['Size'])
			->update(['FactoryCode' => $data[$key]['FactoryCode'],
            'EditionNumber' => $data[$key]['EditionNumber'],
            'VersionNumber' => $data[$key]['VersionNumber'],
            'BuyerPONo' => $data[$key]['BuyerPONo'],
            'Theme' => $data[$key]['Theme'],
            'UVC_TCO' => $data[$key]['UVC_TCO'],
            'Quantity' => $data[$key]['Quantity'],
            'Color' => $data[$key]['Color'],
            'UVC' => $data[$key]['UVC'],
            'Size' => $data[$key]['Size'],
            'BarcodeForPricesTags' => $data[$key]['BarcodeForPricesTags']]);
	}
}