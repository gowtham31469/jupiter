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
			while (($row = fgetcsv($handle, 5000, $delimiter)) !== FALSE)
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
	/**
	 * Parse Price CSV file data into Orchestra item details table 
	 *
	 * @param [array] $data
	 * @param [integer] $key
	 * @return void
	 * @author Praveen <praveen@wizroots.com>
	 */
	public function insertOrchestraItemDetails($data,$key)
	{
	    OrchestraItemDetails::create([
	          'FactoryCode'                        => trim($data[$key]['FACTORY CODE']) === '_'?null:trim($data[$key]['FACTORY CODE']),
	          'SupplierName'                       => trim($data[$key]['SUPPLIER NAME']) === '_'?null:trim($data[$key]['SUPPLIER NAME']),
	          'SupplierAddress'			           => trim($data[$key]['SUPPLIER ADDRESS']) === '_'?null:trim($data[$key]['SUPPLIER ADDRESS']),
	          'SupplierEmail'                      => trim($data[$key]['SUPPLIER EMAIL']) === '_'?null:trim($data[$key]['SUPPLIER EMAIL']),
	          'SupplierCountry'                    => trim($data[$key]['SUPPLIER COUNTRY']) === '_'?null:trim($data[$key]['SUPPLIER COUNTRY']),
	          'BuyerEmail'                         => trim($data[$key]['BUYER EMAIL']) === '_'?null:trim($data[$key]['BUYER EMAIL']),
	          'First_ETD_SHIPMENT_DATE'            => trim($data[$key]['FIRST ETD SHIPMENT DATE']) === '_'?null:trim($data[$key]['FIRST ETD SHIPMENT DATE']),
	          'Collection'                         => trim($data[$key]['SEASON CODE']) === '_'?null:trim($data[$key]['SEASON CODE']),
	          'BuyerPONo'                          => trim($data[$key]['PO']) === '_'?null:trim($data[$key]['PO']),
	          'BarcodeForPricesTags'               => trim($data[$key]['SKU BARCODE']) === '_'?null:trim($data[$key]['SKU BARCODE']),
	          'UVC_TCO'                            => trim($data[$key]['SKU CODE']) === '_'?null:trim($data[$key]['SKU CODE']),
	          'Description'                        => trim($data[$key]['THEME LABEL']) === '_'?null:trim($data[$key]['THEME LABEL']),
	          'Color'                              => trim($data[$key]['COLOR CODE']) === '_'?null:trim($data[$key]['COLOR CODE']),
	          'Size'                               => trim($data[$key]['SIZE LABEL']) === '_'?null:trim($data[$key]['SIZE LABEL']),
	          'Age'                                => trim($data[$key]['SIZE RANGE LABEL']) === '_'?null:trim($data[$key]['SIZE RANGE LABEL']),
	          'Disney'                             => trim($data[$key]['LICENCE CODE']) === 'DI'?1:0,
	          'Marvel'                             => trim($data[$key]['LICENCE CODE']) === 'MA'?1:0,
	          'Quantity'                           => trim($data[$key]['QTY PER SKU-PO']) === '_'?null:trim($data[$key]['QTY PER SKU-PO']),
	          'UVC'                                => trim($data[$key]['REFERENCE CODE']) === '_'?null:trim($data[$key]['REFERENCE CODE']),
			  'HangTag_Print_Date'                 => trim($data[$key]['START PRINT DATE']) === '_'?null:trim($data[$key]['START PRINT DATE']),
			  'Po_Line_Number'                     => trim($data[$key]['PO LINE NUMBER']) === '_'?null:trim($data[$key]['PO LINE NUMBER']),
			  'Po_Line_Suffix'                     => trim($data[$key]['PO LINE SUFFIX']) === '_'?null:trim($data[$key]['PO LINE SUFFIX']),
			  'Po_Status_Code'                     => trim($data[$key]['PO STATUS CODE']) === '_'?null:trim($data[$key]['PO STATUS CODE']),
			  'Po_Status_Code_Description'         => trim($data[$key]['PO STATUS CODE DESCRIPTION']) === '_'?null:trim($data[$key]['PO STATUS CODE DESCRIPTION']),
			  'Style_Code'                         => trim($data[$key]['STYLE CODE']) === '_'?null:trim($data[$key]['STYLE CODE']),
			  'Activity_Code'                      => trim($data[$key]['ACTIVITY CODE']) === '_'?null:trim($data[$key]['ACTIVITY CODE']),
			  'Activity_Label'                     => trim($data[$key]['ACTIVITY LABEL']) === '_'?null:trim($data[$key]['ACTIVITY LABEL']),
			  'Universe_Code'                      => trim($data[$key]['UNIVERSE CODE']) === '_'?null:trim($data[$key]['UNIVERSE CODE']),
			  'Universe_Label'                     => trim($data[$key]['UNIVERSE LABEL']) === '_'?null:trim($data[$key]['UNIVERSE LABEL']),
			  'Department_Code'                    => trim($data[$key]['DEPARTMENT CODE']) === '_'?null:trim($data[$key]['DEPARTMENT CODE']),
			  'Department_Label'                   => trim($data[$key]['DEPARTMENT LABEL']) === '_'?null:trim($data[$key]['DEPARTMENT LABEL']),
			  'Family_Code'                        => trim($data[$key]['FAMILY CODE']) === '_'?null:trim($data[$key]['FAMILY CODE']),
			  'Family_Label'                       => trim($data[$key]['FAMILY LABEL']) === '_'?null:trim($data[$key]['FAMILY LABEL']),
			  'Sub_Family_Code'                    => trim($data[$key]['SUB FAMILY CODE']) === '_'?null:trim($data[$key]['SUB FAMILY CODE']),
			  'Sub_Family_Label'                   => trim($data[$key]['SUB FAMILY LABEL']) === '_'?null:trim($data[$key]['SUB FAMILY LABEL']),
			  'Theme_Code'                         => trim($data[$key]['THEME CODE']) === '_'?null:trim($data[$key]['THEME CODE']),
			  'Brand_Code'                         => trim($data[$key]['BRAND CODE']) === '_'?null:trim($data[$key]['BRAND CODE']),
			  'Brand_Label'                        => trim($data[$key]['BRAND LABEL']) === '_'?null:trim($data[$key]['BRAND LABEL']),
			  'Product_Name'                       => trim($data[$key]['PRODUCT NAME']) === '_'?null:trim($data[$key]['PRODUCT NAME']),
			  'Description_Fr'                     => trim($data[$key]['DESCRIPTION FR']) === '_'?null:trim($data[$key]['DESCRIPTION FR']),
			  'Agent_Code'                         => trim($data[$key]['AGENT CODE']) === '_'?null:trim($data[$key]['AGENT CODE']),
			  'Agent_Name'                         => trim($data[$key]['AGENT NAME']) === '_'?null:trim($data[$key]['AGENT NAME']),
			  'Agent_Email'                        => trim($data[$key]['AGENT EMAIL']) === '_'?null:trim($data[$key]['AGENT EMAIL']),
			  'Agent_Address'                      => trim($data[$key]['AGENT ADDRESS']) === '_'?null:trim($data[$key]['AGENT ADDRESS']),
			  'Agent_Country'                      => trim($data[$key]['AGENT COUNTRY']) === '_'?null:trim($data[$key]['AGENT COUNTRY']),
			  'Supplier_Code'                      => trim($data[$key]['SUPPLIER CODE']) === '_'?null:trim($data[$key]['SUPPLIER CODE']),
			  'Factory_Name'                       => trim($data[$key]['FACTORY NAME']) === '_'?null:trim($data[$key]['FACTORY NAME']),
			  'Factory_Email'                      => trim($data[$key]['FACTORY EMAIL']) === '_'?null:trim($data[$key]['FACTORY EMAIL']),
			  'Factory_Address'                    => trim($data[$key]['FACTORY ADDRESS']) === '_'?null:trim($data[$key]['FACTORY ADDRESS']),
			  'Made_In_Code'                       => trim($data[$key]['MADE IN CODE']) === '_'?null:trim($data[$key]['MADE IN CODE']),
			  'Made_In_Label'                      => trim($data[$key]['MADE IN LABEL']) === '_'?null:trim($data[$key]['MADE IN LABEL']),
			  'Shipment_Destination_Code'          => trim($data[$key]['SHIPMENT  DESTINATION CODE']) === '_'?null:trim($data[$key]['SHIPMENT  DESTINATION CODE']),
			  'Shipment_Destination_Label'         => trim($data[$key]['SHIPMENT  DESTINATION LABEL']) === '_'?null:trim($data[$key]['SHIPMENT  DESTINATION LABEL']),
			  'Incoterms_Code'                     => trim($data[$key]['INCOTERMS CODE']) === '_'?null:trim($data[$key]['INCOTERMS CODE']),
			  'Incoterms_City_Label'               => trim($data[$key]['INCOTERMS CITY LABEL']) === '_'?null:trim($data[$key]['INCOTERMS CITY LABEL']),
			  'Set_'                               => trim($data[$key]['SET_']) === '_'?null:trim($data[$key]['SET_']),
			  'Number_Of_Pieces'                   => trim($data[$key]['NUMBER OF PIECES']) === '_'?null:trim($data[$key]['NUMBER OF PIECES']),
			  'Color_Label'                        => trim($data[$key]['COLOR LABEL']) === '_'?null:trim($data[$key]['COLOR LABEL']),
			  'Standard_Code'                      => trim($data[$key]['STANDARD CODE']) === '_'?null:trim($data[$key]['STANDARD CODE']),
			  'Standard_Barcode'                   => trim($data[$key]['STANDARD BARCODE']) === '_'?null:trim($data[$key]['STANDARD BARCODE']),
			  'Size_Range_Code'                    => trim($data[$key]['SIZE RANGE CODE']) === '_'?null:trim($data[$key]['SIZE RANGE CODE']),
			  'Size_Code'                          => trim($data[$key]['SIZE CODE']) === '_'?null:trim($data[$key]['SIZE CODE']),
			  'Size_Breakdown'                     => trim($data[$key]['SIZE BREAKDOWN']) === '_'?null:trim($data[$key]['SIZE BREAKDOWN']),
			  'Total_Standard_Solid_Parcel_Number' => trim($data[$key]['TOTAL STANDARD-SOLID PARCEL NUMBER']) === '_'?null:trim($data[$key]['TOTAL STANDARD-SOLID PARCEL NUMBER']),
			  'Product_Type'                       => trim($data[$key]['PRODUCT TYPE']) === '_'?null:trim($data[$key]['PRODUCT TYPE']),
			  'Licence_Label'                      => trim($data[$key]['LICENCE LABEL']) === '_'?null:trim($data[$key]['LICENCE LABEL']),
			  'Licence_Character_Code'             => trim($data[$key]['LICENCE CHARACTER CODE']) === '_'?null:trim($data[$key]['LICENCE CHARACTER CODE']),
			  'Licence_Character_Label'            => trim($data[$key]['LICENCE CHARACTER LABEL']) === '_'?null:trim($data[$key]['LICENCE CHARACTER LABEL']),
			  'Composition_Label'                  => trim($data[$key]['COMPOSITION LABEL']) === '_'?null:trim($data[$key]['COMPOSITION LABEL']),
			  'Po_Change_Date'                     => trim($data[$key]['PO CHANGE DATE']) === '_'?null:trim($data[$key]['PO CHANGE DATE']),
			  'Purchase_Price'                     => trim($data[$key]['PURCHASE PRICE']) === '_'?null:trim($data[$key]['PURCHASE PRICE']),
			  'Po_Amount'                          => trim($data[$key]['PO AMOUNT']) === '_'?null:trim($data[$key]['PO AMOUNT']), 
			  'Typologie'                          => trim($data[$key]['TYPOLOGIE']) === '_'?null:trim($data[$key]['TYPOLOGIE']),
			  'Payment_Condition_Code'             => trim($data[$key]['PAYMENT CONDITION CODE']) === '_'?null:trim($data[$key]['PAYMENT CONDITION CODE']),
			  'Payment_Terms'                      => trim($data[$key]['PAYMENT TERMS']) === '_'?null:trim($data[$key]['PAYMENT TERMS']),
			  'Payment_Terms_Label'                => trim($data[$key]['PAYMENT TERMS LABEL']) === '_'?null:trim($data[$key]['PAYMENT TERMS LABEL']),
			  'Po_Packing_Type'                    => trim($data[$key]['PO PACKING TYPE']) === 'STANDARD'?1:0,
		  ]);
	}

  /**
   * method to update the price and country details in orchestra item details in DB
   * @param  [type] $data
   * @param  [type] $key
   * @param  [type] $item_details_datas
   * @author Praveen <praveen@wizroots.com>
   */
	public function updateOrchestraItemPriceDetails($data, $key, $item_details_datas)
	{
		$currency = trim($data[$key]['CURRENCY']);
		foreach( $item_details_datas as $item_details_data)
		{
			switch ($currency) {
				case "EUR":
					OrchestraItemDetails::where('id',$item_details_data->id)
					->update(['Nom_Appel' => trim($data[$key]['NOM_APPEL']) === '_'?null:trim($data[$key]['NOM_APPEL']),
					'Last_Change_Date'    => trim($data[$key]['LAST_CHANGE_DATE']) === '_'?null:trim($data[$key]['LAST_CHANGE_DATE']),
					'PrixEurope'          => trim($data[$key]['PRICE']) === '_'?null:trim($data[$key]['PRICE']),
					'PrixClubEurope'      => trim($data[$key]['CLUB PRICE']) === '_'?null:trim($data[$key]['CLUB PRICE'])]);
					break;
				case "SAR":
					OrchestraItemDetails::where('id',$item_details_data->id)
					->update(['Nom_Appel'        => trim($data[$key]['NOM_APPEL']) === '_'?null:trim($data[$key]['NOM_APPEL']),
					'Last_Change_Date'           => trim($data[$key]['LAST_CHANGE_DATE']) === '_'?null:trim($data[$key]['LAST_CHANGE_DATE']),
					'Prix_Vente_Arabie_Saoudite' => trim($data[$key]['PRICE']) === '_'?null:trim($data[$key]['PRICE']),
					'Prix_Club_Arabie_Saoudite'  => trim($data[$key]['CLUB PRICE']) === '_'?null:trim($data[$key]['CLUB PRICE'])]);
					break;
				case "CAD":
					OrchestraItemDetails::where('id',$item_details_data->id)
					->update(['Nom_Appel' => trim($data[$key]['NOM_APPEL']) === '_'?null:trim($data[$key]['NOM_APPEL']),
					'Last_Change_Date'    => trim($data[$key]['LAST_CHANGE_DATE']) === '_'?null:trim($data[$key]['LAST_CHANGE_DATE']),
					'Prix_Vente_Canada'   => trim($data[$key]['PRICE']) === '_'?null:trim($data[$key]['PRICE']),
					'Prix_Club_Canada'    => trim($data[$key]['CLUB PRICE']) === '_'?null:trim($data[$key]['CLUB PRICE'])]);
					break;
				case "CHF":
					OrchestraItemDetails::where('id',$item_details_data->id)
					->update(['Nom_Appel' => trim($data[$key]['NOM_APPEL']) === '_'?null:trim($data[$key]['NOM_APPEL']),
					'Last_Change_Date'    => trim($data[$key]['LAST_CHANGE_DATE']) === '_'?null:trim($data[$key]['LAST_CHANGE_DATE']),
					'Prix_Vente_Suisse'   => trim($data[$key]['PRICE']) === '_'?null:trim($data[$key]['PRICE']),
					'Prix_Club_Suisse'    => trim($data[$key]['CLUB PRICE']) === '_'?null:trim($data[$key]['CLUB PRICE'])]);
					break;
				case "MAD":
					OrchestraItemDetails::where('id',$item_details_data->id)
					->update(['Nom_Appel' => trim($data[$key]['NOM_APPEL']) === '_'?null:trim($data[$key]['NOM_APPEL']),
					'Last_Change_Date'    => trim($data[$key]['LAST_CHANGE_DATE']) === '_'?null:trim($data[$key]['LAST_CHANGE_DATE']),
					'Prix_Vente_Maroc'    => trim($data[$key]['PRICE']) === '_'?null:trim($data[$key]['PRICE']),
					'Prix_Club_Maroc'     => trim($data[$key]['CLUB PRICE']) === '_'?null:trim($data[$key]['CLUB PRICE'])]);
					break;
				case "MUR":
					OrchestraItemDetails::where('id',$item_details_data->id)
					->update(['Nom_Appel'    => trim($data[$key]['NOM_APPEL']) === '_'?null:trim($data[$key]['NOM_APPEL']),
					'Last_Change_Date'       => trim($data[$key]['LAST_CHANGE_DATE']) === '_'?null:trim($data[$key]['LAST_CHANGE_DATE']),
					'Prix_Vente_Ile_Maurice' => trim($data[$key]['PRICE']) === '_'?null:trim($data[$key]['PRICE']),
					'Prix_Club_Ile_Maurice'  => trim($data[$key]['CLUB PRICE']) === '_'?null:trim($data[$key]['CLUB PRICE'])]);
					break;
				case "DZD":
					OrchestraItemDetails::where('id',$item_details_data->id)
					->update(['Nom_Appel' => trim($data[$key]['NOM_APPEL']) === '_'?null:trim($data[$key]['NOM_APPEL']),
					'Last_Change_Date'    => trim($data[$key]['LAST_CHANGE_DATE']) === '_'?null:trim($data[$key]['LAST_CHANGE_DATE']),
					'Prix_Vente_ALGERIE'  => trim($data[$key]['PRICE']) === '_'?null:trim($data[$key]['PRICE']),
					'Prix_Club_ALGERIE'   => trim($data[$key]['CLUB PRICE']) === '_'?null:trim($data[$key]['CLUB PRICE'])]);
					break;
				case "USD":
					OrchestraItemDetails::where('id',$item_details_data->id)
					->update(['Nom_Appel' => trim($data[$key]['NOM_APPEL']) === '_'?null:trim($data[$key]['NOM_APPEL']),
					'Last_Change_Date'    => trim($data[$key]['LAST_CHANGE_DATE']) === '_'?null:trim($data[$key]['LAST_CHANGE_DATE']),
					'Prix_Vente_USA'      => trim($data[$key]['PRICE']) === '_'?null:trim($data[$key]['PRICE']),
					'Prix_Club_USA'       => trim($data[$key]['CLUB PRICE']) === '_'?null:trim($data[$key]['CLUB PRICE'])]);
					break;
				default:
					OrchestraItemDetails::where('id',$item_details_data->id)
					->update(['Nom_Appel' => trim($data[$key]['NOM_APPEL']) === '_'?null:trim($data[$key]['NOM_APPEL']),
					'Last_Change_Date'    => trim($data[$key]['LAST_CHANGE_DATE']) === '_'?null:trim($data[$key]['LAST_CHANGE_DATE'])]);
			}
		}
	    
	}

	/**
	 * Parse PO CSV file data into Orchestra parcel details table 
	 *
	 * @param [array] $data
	 * @param [integer] $key
	 * @return void
	 * @author Praveen <praveen@wizroots.com>
	 */
	public function insertOrchestraParcelDetails($data,$key)
	{
		OrchestraParcelDetails::create([
			'FactoryCode'                        => trim($data[$key]['FACTORY CODE']) === '_'?null:trim($data[$key]['FACTORY CODE']),
			'SupplierName'                       => trim($data[$key]['SUPPLIER NAME']) === '_'?null:trim($data[$key]['SUPPLIER NAME']),
			'SupplierAddress'			         => trim($data[$key]['SUPPLIER ADDRESS']) === '_'?null:trim($data[$key]['SUPPLIER ADDRESS']),
			'SupplierEmail'                      => trim($data[$key]['SUPPLIER EMAIL']) === '_'?null:trim($data[$key]['SUPPLIER EMAIL']),
			'SupplierCountry'                    => trim($data[$key]['SUPPLIER COUNTRY']) === '_'?null:trim($data[$key]['SUPPLIER COUNTRY']),
			'BuyerPONo'                          => trim($data[$key]['PO']) === '_'?null:trim($data[$key]['PO']),
			'UVC'                                => trim($data[$key]['REFERENCE CODE']) === '_'?null:trim($data[$key]['REFERENCE CODE']),
			'Theme'                              => trim($data[$key]['THEME LABEL']) === '_'?null:trim($data[$key]['THEME LABEL']),
			'IsSTD'                              => trim($data[$key]['PO PACKING TYPE']) === 'STANDARD'?1:0,
			'Quantity'                           => trim($data[$key]['QTY PER SKU-PO']) === '_'?null:trim($data[$key]['QTY PER SKU-PO']),
			'NomApple'                           => trim($data[$key]['PRODUCT NAME']) === '_'?null:trim($data[$key]['PRODUCT NAME']),
			'Season_Code'                        => trim($data[$key]['SEASON CODE']) === '_'?null:trim($data[$key]['SEASON CODE']),
			'SKU_Code'                           => trim($data[$key]['SKU CODE']) === '_'?null:trim($data[$key]['SKU CODE']),
			'Po_Line_Number'                     => trim($data[$key]['PO LINE NUMBER']) === '_'?null:trim($data[$key]['PO LINE NUMBER']),
			'Po_Line_Suffix'                     => trim($data[$key]['PO LINE SUFFIX']) === '_'?null:trim($data[$key]['PO LINE SUFFIX']),
			'Po_Status_Code'                     => trim($data[$key]['PO STATUS CODE']) === '_'?null:trim($data[$key]['PO STATUS CODE']),
			'Po_Status_Code_Description'         => trim($data[$key]['PO STATUS CODE DESCRIPTION']) === '_'?null:trim($data[$key]['PO STATUS CODE DESCRIPTION']),
			'Style_Code'                         => trim($data[$key]['STYLE CODE']) === '_'?null:trim($data[$key]['STYLE CODE']),
			'Activity_Code'                      => trim($data[$key]['ACTIVITY CODE']) === '_'?null:trim($data[$key]['ACTIVITY CODE']),
			'Activity_Label'                     => trim($data[$key]['ACTIVITY LABEL']) === '_'?null:trim($data[$key]['ACTIVITY LABEL']),
			'Universe_Code'                      => trim($data[$key]['UNIVERSE CODE']) === '_'?null:trim($data[$key]['UNIVERSE CODE']),
			'Universe_Label'                     => trim($data[$key]['UNIVERSE LABEL']) === '_'?null:trim($data[$key]['UNIVERSE LABEL']),
			'Department_Code'                    => trim($data[$key]['DEPARTMENT CODE']) === '_'?null:trim($data[$key]['DEPARTMENT CODE']),
			'Department_Label'                   => trim($data[$key]['DEPARTMENT LABEL']) === '_'?null:trim($data[$key]['DEPARTMENT LABEL']),
			'Family_Code'                        => trim($data[$key]['FAMILY CODE']) === '_'?null:trim($data[$key]['FAMILY CODE']),
			'Family_Label'                       => trim($data[$key]['FAMILY LABEL']) === '_'?null:trim($data[$key]['FAMILY LABEL']),
			'Sub_Family_Code'                    => trim($data[$key]['SUB FAMILY CODE']) === '_'?null:trim($data[$key]['SUB FAMILY CODE']),
			'Sub_Family_Label'                   => trim($data[$key]['SUB FAMILY LABEL']) === '_'?null:trim($data[$key]['SUB FAMILY LABEL']),
			'Theme_Code'                         => trim($data[$key]['THEME CODE']) === '_'?null:trim($data[$key]['THEME CODE']),
			'Brand_Code'                         => trim($data[$key]['BRAND CODE']) === '_'?null:trim($data[$key]['BRAND CODE']),
			'Brand_Label'                        => trim($data[$key]['BRAND LABEL']) === '_'?null:trim($data[$key]['BRAND LABEL']),
			'Description_Fr'                     => trim($data[$key]['DESCRIPTION FR']) === '_'?null:trim($data[$key]['DESCRIPTION FR']),
			'Buyer_Email'                        => trim($data[$key]['BUYER EMAIL']) === '_'?null:trim($data[$key]['BUYER EMAIL']),
			'Agent_Code'                         => trim($data[$key]['AGENT CODE']) === '_'?null:trim($data[$key]['AGENT CODE']),
			'Agent_Name'                         => trim($data[$key]['AGENT NAME']) === '_'?null:trim($data[$key]['AGENT NAME']),
			'Agent_Email'                        => trim($data[$key]['AGENT EMAIL']) === '_'?null:trim($data[$key]['AGENT EMAIL']),
			'Agent_Address'                      => trim($data[$key]['AGENT ADDRESS']) === '_'?null:trim($data[$key]['AGENT ADDRESS']),
			'Agent_Country'                      => trim($data[$key]['AGENT COUNTRY']) === '_'?null:trim($data[$key]['AGENT COUNTRY']),
			'Supplier_Code'                      => trim($data[$key]['SUPPLIER CODE']) === '_'?null:trim($data[$key]['SUPPLIER CODE']),
			'Factory_Name'                       => trim($data[$key]['FACTORY NAME']) === '_'?null:trim($data[$key]['FACTORY NAME']),
			'Factory_Email'                      => trim($data[$key]['FACTORY EMAIL']) === '_'?null:trim($data[$key]['FACTORY EMAIL']),
			'Factory_Address'                    => trim($data[$key]['FACTORY ADDRESS']) === '_'?null:trim($data[$key]['FACTORY ADDRESS']),
			'Made_In_Code'                       => trim($data[$key]['MADE IN CODE']) === '_'?null:trim($data[$key]['MADE IN CODE']),
			'Made_In_Label'                      => trim($data[$key]['MADE IN LABEL']) === '_'?null:trim($data[$key]['MADE IN LABEL']),
			'First_ETD_SHIPMENT_DATE'            => trim($data[$key]['FIRST ETD SHIPMENT DATE']) === '_'?null:trim($data[$key]['FIRST ETD SHIPMENT DATE']),
			'Shipment_Destination_Code'          => trim($data[$key]['SHIPMENT  DESTINATION CODE']) === '_'?null:trim($data[$key]['SHIPMENT  DESTINATION CODE']),
			'Shipment_Destination_Label'         => trim($data[$key]['SHIPMENT  DESTINATION LABEL']) === '_'?null:trim($data[$key]['SHIPMENT  DESTINATION LABEL']),
			'Incoterms_Code'                     => trim($data[$key]['INCOTERMS CODE']) === '_'?null:trim($data[$key]['INCOTERMS CODE']),
			'Incoterms_City_Label'               => trim($data[$key]['INCOTERMS CITY LABEL']) === '_'?null:trim($data[$key]['INCOTERMS CITY LABEL']),
			'Sku_Barcode'                        => trim($data[$key]['SKU BARCODE']) === '_'?null:trim($data[$key]['SKU BARCODE']),
			'Set_'                               => trim($data[$key]['SET_']) === '_'?null:trim($data[$key]['SET_']),
			'Number_Of_Pieces'                   => trim($data[$key]['NUMBER OF PIECES']) === '_'?null:trim($data[$key]['NUMBER OF PIECES']),
			'Color_Code'                         => trim($data[$key]['COLOR CODE']) === '_'?null:trim($data[$key]['COLOR CODE']),
			'Color_Label'                        => trim($data[$key]['COLOR LABEL']) === '_'?null:trim($data[$key]['COLOR LABEL']),
			'Standard_Code'                      => trim($data[$key]['STANDARD CODE']) === '_'?null:trim($data[$key]['STANDARD CODE']),
			'Standard_Barcode'                   => trim($data[$key]['STANDARD BARCODE']) === '_'?null:trim($data[$key]['STANDARD BARCODE']),
			'Size_Range_Code'                    => trim($data[$key]['SIZE RANGE CODE']) === '_'?null:trim($data[$key]['SIZE RANGE CODE']),
			'Size_Range_Label'                   => trim($data[$key]['SIZE RANGE LABEL']) === '_'?null:trim($data[$key]['SIZE RANGE LABEL']),
			'Size_Code'                          => trim($data[$key]['SIZE CODE']) === '_'?null:trim($data[$key]['SIZE CODE']),
			'Size_Label'                         => trim($data[$key]['SIZE LABEL']) === '_'?null:trim($data[$key]['SIZE LABEL']),
			'Size_Breakdown'                     => trim($data[$key]['SIZE BREAKDOWN']) === '_'?null:trim($data[$key]['SIZE BREAKDOWN']),
			'Total_Standard_Solid_Parcel_Number' => trim($data[$key]['TOTAL STANDARD-SOLID PARCEL NUMBER']) === '_'?null:trim($data[$key]['TOTAL STANDARD-SOLID PARCEL NUMBER']),
			'Product_Type'                       => trim($data[$key]['PRODUCT TYPE']) === '_'?null:trim($data[$key]['PRODUCT TYPE']),
			'Licence_Code'                       => trim($data[$key]['LICENCE CODE']) === '_'?null:trim($data[$key]['LICENCE CODE']),
			'Licence_Label'                      => trim($data[$key]['LICENCE LABEL']) === '_'?null:trim($data[$key]['LICENCE LABEL']),
			'Licence_Character_Code'             => trim($data[$key]['LICENCE CHARACTER CODE']) === '_'?null:trim($data[$key]['LICENCE CHARACTER CODE']),
			'Licence_Character_Label'            => trim($data[$key]['LICENCE CHARACTER LABEL']) === '_'?null:trim($data[$key]['LICENCE CHARACTER LABEL']),
			'Start_Print_Date'                   => trim($data[$key]['START PRINT DATE']) === '_'?null:trim($data[$key]['START PRINT DATE']),
			'Composition_Label'                  => trim($data[$key]['COMPOSITION LABEL']) === '_'?null:trim($data[$key]['COMPOSITION LABEL']),
			'Po_Change_Date'                     => trim($data[$key]['PO CHANGE DATE']) === '_'?null:trim($data[$key]['PO CHANGE DATE']),
			'Purchase_Price'                     => trim($data[$key]['PURCHASE PRICE']) === '_'?null:trim($data[$key]['PURCHASE PRICE']),
			'Po_Amount'                          => trim($data[$key]['PO AMOUNT']) === '_'?null:trim($data[$key]['PO AMOUNT']), 
			'Typologie'                          => trim($data[$key]['TYPOLOGIE']) === '_'?null:trim($data[$key]['TYPOLOGIE']),
			'Payment_Condition_Code'             => trim($data[$key]['PAYMENT CONDITION CODE']) === '_'?null:trim($data[$key]['PAYMENT CONDITION CODE']),
			'Payment_Terms'                      => trim($data[$key]['PAYMENT TERMS']) === '_'?null:trim($data[$key]['PAYMENT TERMS']),
			'Payment_Terms_Label'                => trim($data[$key]['PAYMENT TERMS LABEL']) === '_'?null:trim($data[$key]['PAYMENT TERMS LABEL']),
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

	/**
	 * Parse Price CSV file data into Orchestra parcel quantity details table 
	 *
	 * @param [array] $data
	 * @param [integer] $key
	 * @param [integer] $parcel_details_datas
	 * @return void
	 * @author Praveen <praveen@wizroots.com>
	 */
	public function insertOrchestraParcelQuantityDetails($data, $key, $parcel_details_data)
	{
		OrchestraParcelQuantityDetails::create([
			'FactoryCode' 	       => $parcel_details_data->FactoryCode,
			'BuyerPONo'            => $parcel_details_data->BuyerPONo,
			'Theme'                => $parcel_details_data->Theme,
			'UVC_TCO'              => trim($data[$key]['SKU CODE']) === '_'?null:trim($data[$key]['SKU CODE']),
			'Quantity'             => $parcel_details_data->Quantity,
			'Color'                => $parcel_details_data->Color_Code,
			'UVC'                  => trim($parcel_details_data->UVC) === '_'?null:trim($parcel_details_data->UVC),
			'Size'                 => $parcel_details_data->Size_Label,
			'BarcodeForPricesTags' => $data[$key]['SKU BARCODE'],
			'Country_Code'         => $data[$key]['COUNTRY CODE'],
			'Season_Code'          => $data[$key]['SEASON CODE'],
			'Currency'             => $data[$key]['CURRENCY'],
			'Price'                => $data[$key]['PRICE'],
			'Club_Price'           => $data[$key]['CLUB PRICE'],
			'Nom_Appel'            => $data[$key]['NOM_APPEL'],
			'Last_Change_Date'     => $data[$key]['LAST_CHANGE_DATE'],
			'Supplier_Code'        => $data[$key]['SUPPLIER_CODE'],
			'Supplier_Name'        => $data[$key]['SUPPLIER_NAME'],
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