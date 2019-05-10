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
			while (($row = fgetcsv($handle, 2000, $delimiter)) !== FALSE)
			{
				$row =  array_map("utf8_encode", $row);
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
	          'FactoryCode'                        => $data[$key]['FACTORY CODE'] === '_'?null:$data[$key]['FACTORY CODE'],
	          'SupplierName'                       => $data[$key]['SUPPLIER NAME'] === '_'?null:$data[$key]['SUPPLIER NAME'],
	          'SupplierAddress'			           => $data[$key]['SUPPLIER ADDRESS'] === '_'?null:$data[$key]['SUPPLIER ADDRESS'],
	          'SupplierEmail'                      => $data[$key]['SUPPLIER EMAIL'] === '_'?null:$data[$key]['SUPPLIER EMAIL'],
	          'SupplierCountry'                    => $data[$key]['SUPPLIER COUNTRY'] === '_'?null:$data[$key]['SUPPLIER COUNTRY'],
	          'BuyerEmail'                         => $data[$key]['BUYER EMAIL'] === '_'?null:$data[$key]['BUYER EMAIL'],
	          'First_ETD_SHIPMENT_DATE'            => $data[$key]['FIRST ETD SHIPMENT DATE'] === '_'?null:$data[$key]['FIRST ETD SHIPMENT DATE'],
	          'Collection'                         => $data[$key]['SEASON CODE'] === '_'?null:$data[$key]['SEASON CODE'],
	          'BuyerPONo'                          => $data[$key]['PO'] === '_'?null:$data[$key]['PO'],
	          'BarcodeForPricesTags'               => $data[$key]['SKU BARCODE'] === '_'?null:$data[$key]['SKU BARCODE'],
	          'UVC_TCO'                            => $data[$key]['SKU CODE'] === '_'?null:$data[$key]['SKU CODE'],
	          'Description'                        => $data[$key]['THEME LABEL'] === '_'?null:$data[$key]['THEME LABEL'],
	          'Color'                              => $data[$key]['COLOR CODE'] === '_'?null:$data[$key]['COLOR CODE'],
	          'Size'                               => $data[$key]['SIZE LABEL'] === '_'?null:$data[$key]['SIZE LABEL'],
	          'Age'                                => $data[$key]['SIZE RANGE LABEL'] === '_'?null:$data[$key]['SIZE RANGE LABEL'],
	          'Disney'                             => $data[$key]['LICENCE CODE'] === 'DI'?1:0,
	          'Marvel'                             => $data[$key]['LICENCE CODE'] === 'MA'?1:0,
	          'Quantity'                           => $data[$key]['QTY PER SKU-PO'] === '_'?null:$data[$key]['QTY PER SKU-PO'],
	          'UVC'                                => $data[$key]['REFERENCE CODE'] === '_'?null:$data[$key]['REFERENCE CODE'],
			  'HangTag_Print_Date'                 => $data[$key]['START PRINT DATE'] === '_'?null:$data[$key]['START PRINT DATE'],
			  'Po_Line_Number'                     => $data[$key]['PO LINE NUMBER'] === '_'?null:$data[$key]['PO LINE NUMBER'],
			  'Po_Line_Suffix'                     => $data[$key]['PO LINE SUFFIX'] === '_'?null:$data[$key]['PO LINE SUFFIX'],
			  'Po_Status_Code'                     => $data[$key]['PO STATUS CODE'] === '_'?null:$data[$key]['PO STATUS CODE'],
			  'Po_Status_Code_Description'         => $data[$key]['PO STATUS CODE DESCRIPTION'] === '_'?null:$data[$key]['PO STATUS CODE DESCRIPTION'],
			  'Style_Code'                         => $data[$key]['STYLE CODE'] === '_'?null:$data[$key]['STYLE CODE'],
			  'Activity_Code'                      => $data[$key]['ACTIVITY CODE'] === '_'?null:$data[$key]['ACTIVITY CODE'],
			  'Activity_Label'                     => $data[$key]['ACTIVITY LABEL'] === '_'?null:$data[$key]['ACTIVITY LABEL'],
			  'Universe_Code'                      => $data[$key]['UNIVERSE CODE'] === '_'?null:$data[$key]['UNIVERSE CODE'],
			  'Universe_Label'                     => $data[$key]['UNIVERSE LABEL'] === '_'?null:$data[$key]['UNIVERSE LABEL'],
			  'Department_Code'                    => $data[$key]['DEPARTMENT CODE'] === '_'?null:$data[$key]['DEPARTMENT CODE'],
			  'Department_Label'                   => $data[$key]['DEPARTMENT LABEL'] === '_'?null:$data[$key]['DEPARTMENT LABEL'],
			  'Family_Code'                        => $data[$key]['FAMILY CODE'] === '_'?null:$data[$key]['FAMILY CODE'],
			  'Family_Label'                       => $data[$key]['FAMILY LABEL'] === '_'?null:$data[$key]['FAMILY LABEL'],
			  'Sub_Family_Code'                    => $data[$key]['SUB FAMILY CODE'] === '_'?null:$data[$key]['SUB FAMILY CODE'],
			  'Sub_Family_Label'                   => $data[$key]['SUB FAMILY LABEL'] === '_'?null:$data[$key]['SUB FAMILY LABEL'],
			  'Theme_Code'                         => $data[$key]['THEME CODE'] === '_'?null:$data[$key]['THEME CODE'],
			  'Brand_Code'                         => $data[$key]['BRAND CODE'] === '_'?null:$data[$key]['BRAND CODE'],
			  'Brand_Label'                        => $data[$key]['BRAND LABEL'] === '_'?null:$data[$key]['BRAND LABEL'],
			  'Product_Name'                       => $data[$key]['PRODUCT NAME'] === '_'?null:$data[$key]['PRODUCT NAME'],
			  'Description_Fr'                     => $data[$key]['DESCRIPTION FR'] === '_'?null:$data[$key]['DESCRIPTION FR'],
			  'Agent_Code'                         => $data[$key]['AGENT CODE'] === '_'?null:$data[$key]['AGENT CODE'],
			  'Agent_Name'                         => $data[$key]['AGENT NAME'] === '_'?null:$data[$key]['AGENT NAME'],
			  'Agent_Email'                        => $data[$key]['AGENT EMAIL'] === '_'?null:$data[$key]['AGENT EMAIL'],
			  'Agent_Address'                      => $data[$key]['AGENT ADDRESS'] === '_'?null:$data[$key]['AGENT ADDRESS'],
			  'Agent_Country'                      => $data[$key]['AGENT COUNTRY'] === '_'?null:$data[$key]['AGENT COUNTRY'],
			  'Supplier_Code'                      => $data[$key]['SUPPLIER CODE'] === '_'?null:$data[$key]['SUPPLIER CODE'],
			  'Factory_Name'                       => $data[$key]['FACTORY NAME'] === '_'?null:$data[$key]['FACTORY NAME'],
			  'Factory_Email'                      => $data[$key]['FACTORY EMAIL'] === '_'?null:$data[$key]['FACTORY EMAIL'],
			  'Factory_Address'                    => $data[$key]['FACTORY ADDRESS'] === '_'?null:$data[$key]['FACTORY ADDRESS'],
			  'Made_In_Code'                       => $data[$key]['MADE IN CODE'] === '_'?null:$data[$key]['MADE IN CODE'],
			  'Made_In_Label'                      => $data[$key]['MADE IN LABEL'] === '_'?null:$data[$key]['MADE IN LABEL'],
			  'Shipment_Destination_Code'          => $data[$key]['SHIPMENT  DESTINATION CODE'] === '_'?null:$data[$key]['SHIPMENT  DESTINATION CODE'],
			  'Shipment_Destination_Label'         => $data[$key]['SHIPMENT  DESTINATION LABEL'] === '_'?null:$data[$key]['SHIPMENT  DESTINATION LABEL'],
			  'Incoterms_Code'                     => $data[$key]['INCOTERMS CODE'] === '_'?null:$data[$key]['INCOTERMS CODE'],
			  'Incoterms_City_Label'               => $data[$key]['INCOTERMS CITY LABEL'] === '_'?null:$data[$key]['INCOTERMS CITY LABEL'],
			  'Set_'                               => $data[$key]['SET_'] === '_'?null:$data[$key]['SET_'],
			  'Number_Of_Pieces'                   => $data[$key]['NUMBER OF PIECES'] === '_'?null:$data[$key]['NUMBER OF PIECES'],
			  'Color_Label'                        => $data[$key]['COLOR LABEL'] === '_'?null:$data[$key]['COLOR LABEL'],
			  'Standard_Code'                      => $data[$key]['STANDARD CODE'] === '_'?null:$data[$key]['STANDARD CODE'],
			  'Standard_Barcode'                   => $data[$key]['STANDARD BARCODE'] === '_'?null:$data[$key]['STANDARD BARCODE'],
			  'Size_Range_Code'                    => $data[$key]['SIZE RANGE CODE'] === '_'?null:$data[$key]['SIZE RANGE CODE'],
			  'Size_Code'                          => $data[$key]['SIZE CODE'] === '_'?null:$data[$key]['SIZE CODE'],
			  'Size_Breakdown'                     => $data[$key]['SIZE BREAKDOWN'] === '_'?null:$data[$key]['SIZE BREAKDOWN'],
			  'Total_Standard_Solid_Parcel_Number' => $data[$key]['TOTAL STANDARD-SOLID PARCEL NUMBER'] === '_'?null:$data[$key]['TOTAL STANDARD-SOLID PARCEL NUMBER'],
			  'Product_Type'                       => $data[$key]['PRODUCT TYPE'] === '_'?null:$data[$key]['PRODUCT TYPE'],
			  'Licence_Label'                      => $data[$key]['LICENCE LABEL'] === '_'?null:$data[$key]['LICENCE LABEL'],
			  'Licence_Character_Code'             => $data[$key]['LICENCE CHARACTER CODE'] === '_'?null:$data[$key]['LICENCE CHARACTER CODE'],
			  'Licence_Character_Label'            => $data[$key]['LICENCE CHARACTER LABEL'] === '_'?null:$data[$key]['LICENCE CHARACTER LABEL'],
			  'Composition_Label'                  => $data[$key]['COMPOSITION LABEL'] === '_'?null:$data[$key]['COMPOSITION LABEL'],
			  'Po_Change_Date'                     => $data[$key]['PO CHANGE DATE'] === '_'?null:$data[$key]['PO CHANGE DATE'],
			  'Purchase_Price'                     => $data[$key]['PURCHASE PRICE'] === '_'?null:$data[$key]['PURCHASE PRICE'],
			  'Po_Amount'                          => $data[$key]['PO AMOUNT'] === '_'?null:$data[$key]['PO AMOUNT'], 
			  'Typologie'                          => $data[$key]['TYPOLOGIE'] === '_'?null:$data[$key]['TYPOLOGIE'],
			  'Payment_Condition_Code'             => $data[$key]['PAYMENT CONDITION CODE'] === '_'?null:$data[$key]['PAYMENT CONDITION CODE'],
			  'Payment_Terms'                      => $data[$key]['PAYMENT TERMS'] === '_'?null:$data[$key]['PAYMENT TERMS'],
			  'Payment_Terms_Label'                => $data[$key]['PAYMENT TERMS LABEL'] === '_'?null:$data[$key]['PAYMENT TERMS LABEL'],
			  'Po_Packing_Type'                    => $data[$key]['PO PACKING TYPE'] === 'STANDARD'?1:0,
		  ]);

		  // insert into orchestra parcel details table
		  $this->insertOrchestraParcelDetails($data,$key);
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
			'FactoryCode'                        => $data[$key]['FACTORY CODE'] === '_'?null:$data[$key]['FACTORY CODE'],
			'SupplierName'                       => $data[$key]['SUPPLIER NAME'] === '_'?null:$data[$key]['SUPPLIER NAME'],
			'SupplierAddress'			         => $data[$key]['SUPPLIER ADDRESS'] === '_'?null:$data[$key]['SUPPLIER ADDRESS'],
			'SupplierEmail'                      => $data[$key]['SUPPLIER EMAIL'] === '_'?null:$data[$key]['SUPPLIER EMAIL'],
			'SupplierCountry'                    => $data[$key]['SUPPLIER COUNTRY'] === '_'?null:$data[$key]['SUPPLIER COUNTRY'],
			'BuyerPONo'                          => $data[$key]['PO'] === '_'?null:$data[$key]['PO'],
			'UVC'                                => $data[$key]['REFERENCE CODE'] === '_'?null:$data[$key]['REFERENCE CODE'],
			'Theme'                              => $data[$key]['THEME LABEL'] === '_'?null:$data[$key]['THEME LABEL'],
			'IsSTD'                              => $data[$key]['PO PACKING TYPE'] === 'STANDARD'?1:0,
			'Quantity'                           => $data[$key]['QTY PER SKU-PO'] === '_'?null:$data[$key]['QTY PER SKU-PO'],
			'NomApple'                           => $data[$key]['PRODUCT NAME'] === '_'?null:$data[$key]['PRODUCT NAME'],
			'Season_Code'                        => $data[$key]['SEASON CODE'] === '_'?null:$data[$key]['SEASON CODE'],
			'SKU_Code'                           => $data[$key]['SKU CODE'] === '_'?null:$data[$key]['SKU CODE'],
			'Po_Line_Number'                     => $data[$key]['PO LINE NUMBER'] === '_'?null:$data[$key]['PO LINE NUMBER'],
			'Po_Line_Suffix'                     => $data[$key]['PO LINE SUFFIX'] === '_'?null:$data[$key]['PO LINE SUFFIX'],
			'Po_Status_Code'                     => $data[$key]['PO STATUS CODE'] === '_'?null:$data[$key]['PO STATUS CODE'],
			'Po_Status_Code_Description'         => $data[$key]['PO STATUS CODE DESCRIPTION'] === '_'?null:$data[$key]['PO STATUS CODE DESCRIPTION'],
			'Style_Code'                         => $data[$key]['STYLE CODE'] === '_'?null:$data[$key]['STYLE CODE'],
			'Activity_Code'                      => $data[$key]['ACTIVITY CODE'] === '_'?null:$data[$key]['ACTIVITY CODE'],
			'Activity_Label'                     => $data[$key]['ACTIVITY LABEL'] === '_'?null:$data[$key]['ACTIVITY LABEL'],
			'Universe_Code'                      => $data[$key]['UNIVERSE CODE'] === '_'?null:$data[$key]['UNIVERSE CODE'],
			'Universe_Label'                     => $data[$key]['UNIVERSE LABEL'] === '_'?null:$data[$key]['UNIVERSE LABEL'],
			'Department_Code'                    => $data[$key]['DEPARTMENT CODE'] === '_'?null:$data[$key]['DEPARTMENT CODE'],
			'Department_Label'                   => $data[$key]['DEPARTMENT LABEL'] === '_'?null:$data[$key]['DEPARTMENT LABEL'],
			'Family_Code'                        => $data[$key]['FAMILY CODE'] === '_'?null:$data[$key]['FAMILY CODE'],
			'Family_Label'                       => $data[$key]['FAMILY LABEL'] === '_'?null:$data[$key]['FAMILY LABEL'],
			'Sub_Family_Code'                    => $data[$key]['SUB FAMILY CODE'] === '_'?null:$data[$key]['SUB FAMILY CODE'],
			'Sub_Family_Label'                   => $data[$key]['SUB FAMILY LABEL'] === '_'?null:$data[$key]['SUB FAMILY LABEL'],
			'Theme_Code'                         => $data[$key]['THEME CODE'] === '_'?null:$data[$key]['THEME CODE'],
			'Brand_Code'                         => $data[$key]['BRAND CODE'] === '_'?null:$data[$key]['BRAND CODE'],
			'Brand_Label'                        => $data[$key]['BRAND LABEL'] === '_'?null:$data[$key]['BRAND LABEL'],
			'Description_Fr'                     => $data[$key]['DESCRIPTION FR'] === '_'?null:$data[$key]['DESCRIPTION FR'],
			'Buyer_Email'                        => $data[$key]['BUYER EMAIL'] === '_'?null:$data[$key]['BUYER EMAIL'],
			'Agent_Code'                         => $data[$key]['AGENT CODE'] === '_'?null:$data[$key]['AGENT CODE'],
			'Agent_Name'                         => $data[$key]['AGENT NAME'] === '_'?null:$data[$key]['AGENT NAME'],
			'Agent_Email'                        => $data[$key]['AGENT EMAIL'] === '_'?null:$data[$key]['AGENT EMAIL'],
			'Agent_Address'                      => $data[$key]['AGENT ADDRESS'] === '_'?null:$data[$key]['AGENT ADDRESS'],
			'Agent_Country'                      => $data[$key]['AGENT COUNTRY'] === '_'?null:$data[$key]['AGENT COUNTRY'],
			'Supplier_Code'                      => $data[$key]['SUPPLIER CODE'] === '_'?null:$data[$key]['SUPPLIER CODE'],
			'Factory_Name'                       => $data[$key]['FACTORY NAME'] === '_'?null:$data[$key]['FACTORY NAME'],
			'Factory_Email'                      => $data[$key]['FACTORY EMAIL'] === '_'?null:$data[$key]['FACTORY EMAIL'],
			'Factory_Address'                    => $data[$key]['FACTORY ADDRESS'] === '_'?null:$data[$key]['FACTORY ADDRESS'],
			'Made_In_Code'                       => $data[$key]['MADE IN CODE'] === '_'?null:$data[$key]['MADE IN CODE'],
			'Made_In_Label'                      => $data[$key]['MADE IN LABEL'] === '_'?null:$data[$key]['MADE IN LABEL'],
			'First_ETD_SHIPMENT_DATE'            => $data[$key]['FIRST ETD SHIPMENT DATE'] === '_'?null:$data[$key]['FIRST ETD SHIPMENT DATE'],
			'Shipment_Destination_Code'          => $data[$key]['SHIPMENT  DESTINATION CODE'] === '_'?null:$data[$key]['SHIPMENT  DESTINATION CODE'],
			'Shipment_Destination_Label'         => $data[$key]['SHIPMENT  DESTINATION LABEL'] === '_'?null:$data[$key]['SHIPMENT  DESTINATION LABEL'],
			'Incoterms_Code'                     => $data[$key]['INCOTERMS CODE'] === '_'?null:$data[$key]['INCOTERMS CODE'],
			'Incoterms_City_Label'               => $data[$key]['INCOTERMS CITY LABEL'] === '_'?null:$data[$key]['INCOTERMS CITY LABEL'],
			'Sku_Barcode'                        => $data[$key]['SKU BARCODE'] === '_'?null:$data[$key]['SKU BARCODE'],
			'Set_'                               => $data[$key]['SET_'] === '_'?null:$data[$key]['SET_'],
			'Number_Of_Pieces'                   => $data[$key]['NUMBER OF PIECES'] === '_'?null:$data[$key]['NUMBER OF PIECES'],
			'Color_Code'                         => $data[$key]['COLOR CODE'] === '_'?null:$data[$key]['COLOR CODE'],
			'Color_Label'                        => $data[$key]['COLOR LABEL'] === '_'?null:$data[$key]['COLOR LABEL'],
			'Standard_Code'                      => $data[$key]['STANDARD CODE'] === '_'?null:$data[$key]['STANDARD CODE'],
			'Standard_Barcode'                   => $data[$key]['STANDARD BARCODE'] === '_'?null:$data[$key]['STANDARD BARCODE'],
			'Size_Range_Code'                    => $data[$key]['SIZE RANGE CODE'] === '_'?null:$data[$key]['SIZE RANGE CODE'],
			'Size_Range_Label'                   => $data[$key]['SIZE RANGE LABEL'] === '_'?null:$data[$key]['SIZE RANGE LABEL'],
			'Size_Code'                          => $data[$key]['SIZE CODE'] === '_'?null:$data[$key]['SIZE CODE'],
			'Size_Label'                         => $data[$key]['SIZE LABEL'] === '_'?null:$data[$key]['SIZE LABEL'],
			'Size_Breakdown'                     => $data[$key]['SIZE BREAKDOWN'] === '_'?null:$data[$key]['SIZE BREAKDOWN'],
			'Total_Standard_Solid_Parcel_Number' => $data[$key]['TOTAL STANDARD-SOLID PARCEL NUMBER'] === '_'?null:$data[$key]['TOTAL STANDARD-SOLID PARCEL NUMBER'],
			'Product_Type'                       => $data[$key]['PRODUCT TYPE'] === '_'?null:$data[$key]['PRODUCT TYPE'],
			'Licence_Code'                       => $data[$key]['LICENCE CODE'] === '_'?null:$data[$key]['LICENCE CODE'],
			'Licence_Label'                      => $data[$key]['LICENCE LABEL'] === '_'?null:$data[$key]['LICENCE LABEL'],
			'Licence_Character_Code'             => $data[$key]['LICENCE CHARACTER CODE'] === '_'?null:$data[$key]['LICENCE CHARACTER CODE'],
			'Licence_Character_Label'            => $data[$key]['LICENCE CHARACTER LABEL'] === '_'?null:$data[$key]['LICENCE CHARACTER LABEL'],
			'Start_Print_Date'                   => $data[$key]['START PRINT DATE'] === '_'?null:$data[$key]['START PRINT DATE'],
			'Composition_Label'                  => $data[$key]['COMPOSITION LABEL'] === '_'?null:$data[$key]['COMPOSITION LABEL'],
			'Po_Change_Date'                     => $data[$key]['PO CHANGE DATE'] === '_'?null:$data[$key]['PO CHANGE DATE'],
			'Purchase_Price'                     => $data[$key]['PURCHASE PRICE'] === '_'?null:$data[$key]['PURCHASE PRICE'],
			'Po_Amount'                          => $data[$key]['PO AMOUNT'] === '_'?null:$data[$key]['PO AMOUNT'], 
			'Typologie'                          => $data[$key]['TYPOLOGIE'] === '_'?null:$data[$key]['TYPOLOGIE'],
			'Payment_Condition_Code'             => $data[$key]['PAYMENT CONDITION CODE'] === '_'?null:$data[$key]['PAYMENT CONDITION CODE'],
			'Payment_Terms'                      => $data[$key]['PAYMENT TERMS'] === '_'?null:$data[$key]['PAYMENT TERMS'],
			'Payment_Terms_Label'                => $data[$key]['PAYMENT TERMS LABEL'] === '_'?null:$data[$key]['PAYMENT TERMS LABEL'],
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