<?php
namespace App\Classes;
use App\DumpOrchestraPOCsv;
use App\OrchestraParcelQuantityDetails;
use App\Customer;
use App\CustomerBillAddress;
use App\CustomerContactPersons;
use DB;
use File;

class Uploads
{

	function csv_to_array($filename, $delimeter=';')
	{
		$header = [];
		$row = 0;
		# tip: dont do that every time calling csv_read(), pass handle as param instead ;)
		$handle = fopen($filename, "r"); 

		if ($handle === false) {
			return false;
		}

		while (($data = fgetcsv($handle, 10000, $delimeter)) !== false) {

			$data =  array_map("utf8_encode", $data);
			if (0 == $row) {
				$header = $data;
			} else {
				# on demand usage
				yield array_combine($header, $data);
			}

			$row++;
		}
		fclose($handle);
	}


	/**
	 * method to add customer based on supplier code
	 * @param  [array]  $customer_data 
	 * @return boolean       
	 */
	public function createCustomer($customer_data)
	{
		foreach ($customer_data as $key => $value) {
			$customer_exists = Customer::select("id")->where('factory_code', trim($value->{'SUPPLIER CODE'}))->exists();
			if (!$customer_exists) {
				$custm_details = Customer::create([
					'company_name'  => trim($value->{'SUPPLIER NAME'}) === '_' ? null : trim($value->{'SUPPLIER NAME'}),
					'factory_code' 	=> trim($value->{'SUPPLIER CODE'}) === '_' ? null : trim($value->{'SUPPLIER CODE'}),
					'email'		    => trim($value->{'SUPPLIER EMAIL'})
				]);
				CustomerBillAddress::create([
					'customer_id'   => $custm_details->id,
					'address'       => trim($value->{'SUPPLIER ADDRESS'}) === '_' ? null : trim($value->{'SUPPLIER ADDRESS'}),
					'country'       => trim($value->{'SUPPLIER COUNTRY'}) === '_' ? null : trim($value->{'SUPPLIER COUNTRY'}),
					'email'			=> trim($value->{'SUPPLIER EMAIL'})
				]);
				CustomerContactPersons::create([
					'customer_id'   => $custm_details->id
				]);
			}
		}
	}

	/**
	 * Parse PO CSV file data into Orchestra parcel details table 
	 *
	 * @param [array] $value
	 * @return void
	 * @author Praveen <praveen@wizroots.com>
	 */
	public function insertOrchestraParcelDetails($value, $is_price_data_exists)
	{
		DB::table('orchestra_parcel_details')->insert([
			'FactoryCode'                        => trim($value->{'SUPPLIER CODE'}) === '_'?null:trim($value->{'SUPPLIER CODE'}),
			'SupplierName'                       => trim($value->{'SUPPLIER NAME'}) === '_'?null:trim($value->{'SUPPLIER NAME'}),
			'SupplierAddress'			         => trim($value->{'SUPPLIER ADDRESS'}) === '_'?null:trim($value->{'SUPPLIER ADDRESS'}),
			'SupplierEmail'                      => trim($value->{'SUPPLIER EMAIL'}) === '_'?null:trim($value->{'SUPPLIER EMAIL'}),
			'SupplierCountry'                    => trim($value->{'SUPPLIER COUNTRY'}) === '_'?null:trim($value->{'SUPPLIER COUNTRY'}),
			'BuyerPONo'                          => trim($value->{'PO'}) === '_'?null:trim($value->{'PO'}),
			'UVC'                                => trim($value->{'REFERENCE CODE'}) === '_'?null:trim($value->{'REFERENCE CODE'}),
			'Theme'                              => trim($value->{'THEME LABEL'}) === '_'?null:trim($value->{'THEME LABEL'}),
			'IsSTD'                              => trim($value->{'PO PACKING TYPE'}) === 'STANDARD'?1:0,
			'Quantity'                           => trim($value->{'QTY PER SKU-PO'}) === '_'?null:trim($value->{'QTY PER SKU-PO'}),
			'NomApple'                           => trim($value->{'PRODUCT NAME'}) === '_'?null:trim($value->{'PRODUCT NAME'}),
			'Season_Code'                        => trim($value->{'SEASON CODE'}) === '_'?null:trim($value->{'SEASON CODE'}),
			'SKU_Code'                           => trim($value->{'SKU CODE'}) === '_'?null:trim($value->{'SKU CODE'}),
			'Po_Line_Number'                     => trim($value->{'PO LINE NUMBER'}) === '_'?null:trim($value->{'PO LINE NUMBER'}),
			'Po_Line_Suffix'                     => trim($value->{'PO LINE SUFFIX'}) === '_'?null:trim($value->{'PO LINE SUFFIX'}),
			'Po_Status_Code'                     => trim($value->{'PO STATUS CODE'}) === '_'?null:trim($value->{'PO STATUS CODE'}),
			'Po_Status_Code_Description'         => trim($value->{'PO STATUS CODE DESCRIPTION'}) === '_'?null:trim($value->{'PO STATUS CODE DESCRIPTION'}),
			'Style_Code'                         => trim($value->{'STYLE CODE'}) === '_'?null:trim($value->{'STYLE CODE'}),
			'Activity_Code'                      => trim($value->{'ACTIVITY CODE'}) === '_'?null:trim($value->{'ACTIVITY CODE'}),
			'Activity_Label'                     => trim($value->{'ACTIVITY LABEL'}) === '_'?null:trim($value->{'ACTIVITY LABEL'}),
			'Universe_Code'                      => trim($value->{'UNIVERSE CODE'}) === '_'?null:trim($value->{'UNIVERSE CODE'}),
			'Universe_Label'                     => trim($value->{'UNIVERSE LABEL'}) === '_'?null:trim($value->{'UNIVERSE LABEL'}),
			'Department_Code'                    => trim($value->{'DEPARTMENT CODE'}) === '_'?null:trim($value->{'DEPARTMENT CODE'}),
			'Department_Label'                   => trim($value->{'DEPARTMENT LABEL'}) === '_'?null:trim($value->{'DEPARTMENT LABEL'}),
			'Family_Code'                        => trim($value->{'FAMILY CODE'}) === '_'?null:trim($value->{'FAMILY CODE'}),
			'Family_Label'                       => trim($value->{'FAMILY LABEL'}) === '_'?null:trim($value->{'FAMILY LABEL'}),
			'Sub_Family_Code'                    => trim($value->{'SUB FAMILY CODE'}) === '_'?null:trim($value->{'SUB FAMILY CODE'}),
			'Sub_Family_Label'                   => trim($value->{'SUB FAMILY LABEL'}) === '_'?null:trim($value->{'SUB FAMILY LABEL'}),
			'Theme_Code'                         => trim($value->{'THEME CODE'}) === '_'?null:trim($value->{'THEME CODE'}),
			'Brand_Code'                         => trim($value->{'BRAND CODE'}) === '_'?null:trim($value->{'BRAND CODE'}),
			'Brand_Label'                        => trim($value->{'BRAND LABEL'}) === '_'?null:trim($value->{'BRAND LABEL'}),
			'Description_Fr'                     => trim($value->{'DESCRIPTION FR'}) === '_'?null:trim($value->{'DESCRIPTION FR'}),
			'Buyer_Email'                        => trim($value->{'BUYER EMAIL'}) === '_'?null:trim($value->{'BUYER EMAIL'}),
			'Agent_Code'                         => trim($value->{'AGENT CODE'}) === '_'?null:trim($value->{'AGENT CODE'}),
			'Agent_Name'                         => trim($value->{'AGENT NAME'}) === '_'?null:trim($value->{'AGENT NAME'}),
			'Agent_Email'                        => trim($value->{'AGENT EMAIL'}) === '_'?null:trim($value->{'AGENT EMAIL'}),
			'Agent_Address'                      => trim($value->{'AGENT ADDRESS'}) === '_'?null:trim($value->{'AGENT ADDRESS'}),
			'Agent_Country'                      => trim($value->{'AGENT COUNTRY'}) === '_'?null:trim($value->{'AGENT COUNTRY'}),
			'Supplier_Code'                      => trim($value->{'SUPPLIER CODE'}) === '_'?null:trim($value->{'SUPPLIER CODE'}),
			'Factory_Name'                       => trim($value->{'FACTORY NAME'}) === '_'?null:trim($value->{'FACTORY NAME'}),
			'Factory_Email'                      => trim($value->{'FACTORY EMAIL'}) === '_'?null:trim($value->{'FACTORY EMAIL'}),
			'Factory_Address'                    => trim($value->{'FACTORY ADDRESS'}) === '_'?null:trim($value->{'FACTORY ADDRESS'}),
			'Made_In_Code'                       => trim($value->{'MADE IN CODE'}) === '_'?null:trim($value->{'MADE IN CODE'}),
			'Made_In_Label'                      => trim($value->{'MADE IN LABEL'}) === '_'?null:trim($value->{'MADE IN LABEL'}),
			'First_ETD_SHIPMENT_DATE'            => trim($value->{'FIRST ETD SHIPMENT DATE'}) === '_'?null:trim($value->{'FIRST ETD SHIPMENT DATE'}),
			'Shipment_Destination_Code'          => trim($value->{'SHIPMENT  DESTINATION CODE'}) === '_'?null:trim($value->{'SHIPMENT  DESTINATION CODE'}),
			'Shipment_Destination_Label'         => trim($value->{'SHIPMENT  DESTINATION LABEL'}) === '_'?null:trim($value->{'SHIPMENT  DESTINATION LABEL'}),
			'Incoterms_Code'                     => trim($value->{'INCOTERMS CODE'}) === '_'?null:trim($value->{'INCOTERMS CODE'}),
			'Incoterms_City_Label'               => trim($value->{'INCOTERMS CITY LABEL'}) === '_'?null:trim($value->{'INCOTERMS CITY LABEL'}),
			'Sku_Barcode'                        => trim($value->{'SKU BARCODE'}) === '_'?null:trim($value->{'SKU BARCODE'}),
			'Set_'                               => trim($value->{'SET_'}) === '_'?null:trim($value->{'SET_'}),
			'Number_Of_Pieces'                   => trim($value->{'NUMBER OF PIECES'}) === '_'?null:trim($value->{'NUMBER OF PIECES'}),
			'Color_Code'                         => trim($value->{'COLOR CODE'}) === '_'?null:trim($value->{'COLOR CODE'}),
			'Color_Label'                        => trim($value->{'COLOR LABEL'}) === '_'?null:trim($value->{'COLOR LABEL'}),
			'Standard_Code'                      => trim($value->{'STANDARD CODE'}) === '_'?null:trim($value->{'STANDARD CODE'}),
			'Standard_Barcode'                   => trim($value->{'STANDARD BARCODE'}) === '_'?null:trim($value->{'STANDARD BARCODE'}),
			'Size_Range_Code'                    => trim($value->{'SIZE RANGE CODE'}) === '_'?null:trim($value->{'SIZE RANGE CODE'}),
			'Size_Range_Label'                   => trim($value->{'SIZE RANGE LABEL'}) === '_'?null:trim($value->{'SIZE RANGE LABEL'}),
			'Size_Code'                          => trim($value->{'SIZE CODE'}) === '_'?null:trim($value->{'SIZE CODE'}),
			'Size_Label'                         => trim($value->{'SIZE LABEL'}) === '_'?null:trim($value->{'SIZE LABEL'}),
			'Size_Breakdown'                     => trim($value->{'SIZE BREAKDOWN'}) === '_'?null:trim($value->{'SIZE BREAKDOWN'}),
			'Total_Standard_Solid_Parcel_Number' => trim($value->{'TOTAL STANDARD-SOLID PARCEL NUMBER'}) === '_'?null:trim($value->{'TOTAL STANDARD-SOLID PARCEL NUMBER'}),
			'Product_Type'                       => trim($value->{'PRODUCT TYPE'}) === '_'?null:trim($value->{'PRODUCT TYPE'}),
			'Licence_Code'                       => trim($value->{'LICENCE CODE'}) === '_'?null:trim($value->{'LICENCE CODE'}),
			'Licence_Label'                      => trim($value->{'LICENCE LABEL'}) === '_'?null:trim($value->{'LICENCE LABEL'}),
			'Licence_Character_Code'             => trim($value->{'LICENCE CHARACTER CODE'}) === '_'?null:trim($value->{'LICENCE CHARACTER CODE'}),
			'Licence_Character_Label'            => trim($value->{'LICENCE CHARACTER LABEL'}) === '_'?null:trim($value->{'LICENCE CHARACTER LABEL'}),
			'Start_Print_Date'                   => trim($value->{'START PRINT DATE'}) === '_'?null:trim($value->{'START PRINT DATE'}),
			'Composition_Label'                  => trim($value->{'COMPOSITION LABEL'}) === '_'?null:trim($value->{'COMPOSITION LABEL'}),
			'Po_Change_Date'                     => trim($value->{'PO CHANGE DATE'}) === '_'?null:trim($value->{'PO CHANGE DATE'}),
			'Purchase_Price'                     => trim($value->{'PURCHASE PRICE'}) === '_'?null:trim($value->{'PURCHASE PRICE'}),
			'Po_Amount'                          => trim($value->{'PO AMOUNT'}) === '_'?null:trim($value->{'PO AMOUNT'}), 
			'Typologie'                          => trim($value->{'TYPOLOGIE'}) === '_'?null:trim($value->{'TYPOLOGIE'}),
			'Payment_Condition_Code'             => trim($value->{'PAYMENT CONDITION CODE'}) === '_'?null:trim($value->{'PAYMENT CONDITION CODE'}),
			'Payment_Terms'                      => trim($value->{'PAYMENT TERMS'}) === '_'?null:trim($value->{'PAYMENT TERMS'}),
			'Payment_Terms_Label'                => trim($value->{'PAYMENT TERMS LABEL'}) === '_'?null:trim($value->{'PAYMENT TERMS LABEL'}),
			'BarcodeForPricesTags'               => trim($value->{'SKU BARCODE'}) === '_'?null:trim($value->{'SKU BARCODE'}),
			'UVC_TCO'                            => trim($value->{'SKU CODE'}) === '_'?null:trim($value->{'SKU CODE'}),
			'Size'                               => trim($value->{'SIZE LABEL'}) === '_'?null:trim($value->{'SIZE LABEL'}),
			'ParcelBarCode'                      => trim($value->{'STANDARD BARCODE'}) === '_'?null:trim($value->{'STANDARD BARCODE'}),
			'is_price_data_exists'               => $is_price_data_exists
			 ]);
	}

	/**
	 * Parse Price CSV file data into Orchestra parcel quantity details table 
	 *
	 * @param [array] $value
	 * @param [integer] $parcel_details_datas
	 * @return void
	 * @author Praveen <praveen@wizroots.com>
	 */
	public function insertOrchestraParcelQuantityDetails($value, $parcel_details_data)
	{
		DB::table('orchestra_parcel_quantity_details')->insert([
			'FactoryCode' 	              => trim($parcel_details_data->{'SUPPLIER CODE'}) === '_'?null:trim($parcel_details_data->{'SUPPLIER CODE'}),
			'BuyerPONo'                   => trim($parcel_details_data->{'PO'}) === '_'?null:trim($parcel_details_data->{'PO'}),
			'Theme'                       => trim($parcel_details_data->{'THEME LABEL'}) === '_'?null:trim($parcel_details_data->{'THEME LABEL'}),
			'UVC_TCO'                     => trim($value->{'SKU CODE'}) === '_'?null:trim($value->{'SKU CODE'}),
			'Quantity'                    => trim($parcel_details_data->{'QTY PER SKU-PO'}) === '_'?null:trim($parcel_details_data->{'QTY PER SKU-PO'}),
			'Color'                       => trim($parcel_details_data->{'COLOR CODE'}) === '_'?null:trim($parcel_details_data->{'COLOR CODE'}),
			'UVC'                         => trim($parcel_details_data->{'REFERENCE CODE'}) === '_'?null:trim($parcel_details_data->{'REFERENCE CODE'}),
			'Size'                        => trim($parcel_details_data->{'SIZE LABEL'}) === '_'?null:trim($parcel_details_data->{'SIZE LABEL'}),
			'BarcodeForPricesTags'        => trim($value->{'SKU BARCODE'}) === '_'?null:trim($value->{'SKU BARCODE'}),
			'Country_Code'                => trim($value->{'COUNTRY CODE'}) === '_'?null:trim($value->{'COUNTRY CODE'}),
			'Season_Code'                 => trim($value->{'SEASON CODE'}) === '_'?null:trim($value->{'SEASON CODE'}),
			'Currency'                    => trim($value->{'CURRENCY'}) === '_'?null:trim($value->{'CURRENCY'}),
			'Price'                       => trim($value->{'PRICE'}) === '_'?null:trim($value->{'PRICE'}),
			'Club_Price'                  => trim($value->{'CLUB PRICE'}) === '_'?null:trim($value->{'CLUB PRICE'}),
			'Nom_Appel'                   => trim($value->{'NOM_APPEL'}) === '_'?null:trim($value->{'NOM_APPEL'}),
			'Last_Change_Date'            => trim($value->{'LAST_CHANGE_DATE'}) === '_'?null:trim($value->{'LAST_CHANGE_DATE'}),
			'Supplier_Code'               => trim($value->{'SUPPLIER_CODE'}) === '_'?null:trim($value->{'SUPPLIER_CODE'}),
			'Supplier_Name'               => trim($value->{'SUPPLIER_NAME'}) === '_'?null:trim($value->{'SUPPLIER_NAME'}),
			'Po_Status_Code'              => trim($parcel_details_data->{'PO STATUS CODE'}) === '_'?null:trim($parcel_details_data->{'PO STATUS CODE'}),
			'Po_Status_Code_Description'  => trim($parcel_details_data->{'PO STATUS CODE DESCRIPTION'}) === '_'?null:trim($parcel_details_data->{'PO STATUS CODE DESCRIPTION'}),
			]);
	}

	/**
	 * Parse PO CSV file data into Temp Orchestra parcel details table, bcoz of csv data conflicts
	 *
	 * @param [array] $value
	 * @return void
	 * @author Praveen <praveen@wizroots.com>
	 */
	public function updateOrchestraTempParcelDetails($value, $is_price_data_exists)
	{
		$data = [];

		// check if UVC_TCO, UVC and PO change date already exists or not
		$parcel_details_exists = DB::table('temp_orchestra_parcel_details')
		->select("id")
		->where([
			['UVC', trim($value->{'REFERENCE CODE'})],
			['BuyerPONo', trim($value->PO)],
			['SKU_Code', trim($value->{'SKU CODE'})],
			['Po_Change_Date', trim($value->{'PO CHANGE DATE'})],
			['Po_Status_Code_Description', trim($value->{'PO STATUS CODE DESCRIPTION'})],
			['Quantity', trim($value->{'QTY PER SKU-PO'})],
		])->exists();
		
		if(!$parcel_details_exists)
		{
			DB::table('temp_orchestra_parcel_details')->insert([
				'FactoryCode'                        => trim($value->{'SUPPLIER CODE'}) === '_'?null:trim($value->{'SUPPLIER CODE'}),
				'SupplierName'                       => trim($value->{'SUPPLIER NAME'}) === '_'?null:trim($value->{'SUPPLIER NAME'}),
				'SupplierAddress'			               => trim($value->{'SUPPLIER ADDRESS'}) === '_'?null:trim($value->{'SUPPLIER ADDRESS'}),
				'SupplierEmail'                      => trim($value->{'SUPPLIER EMAIL'}) === '_'?null:trim($value->{'SUPPLIER EMAIL'}),
				'SupplierCountry'                    => trim($value->{'SUPPLIER COUNTRY'}) === '_'?null:trim($value->{'SUPPLIER COUNTRY'}),
				'BuyerPONo'                          => trim($value->{'PO'}) === '_'?null:trim($value->{'PO'}),
				'UVC'                                => trim($value->{'REFERENCE CODE'}) === '_'?null:trim($value->{'REFERENCE CODE'}),
				'Theme'                              => trim($value->{'THEME LABEL'}) === '_'?null:trim($value->{'THEME LABEL'}),
				'IsSTD'                              => trim($value->{'PO PACKING TYPE'}) === 'STANDARD'?1:0,
				'Quantity'                           => trim($value->{'QTY PER SKU-PO'}) === '_'?null:trim($value->{'QTY PER SKU-PO'}),
				'NomApple'                           => trim($value->{'PRODUCT NAME'}) === '_'?null:trim($value->{'PRODUCT NAME'}),
				'Season_Code'                        => trim($value->{'SEASON CODE'}) === '_'?null:trim($value->{'SEASON CODE'}),
				'SKU_Code'                           => trim($value->{'SKU CODE'}) === '_'?null:trim($value->{'SKU CODE'}),
				'Po_Line_Number'                     => trim($value->{'PO LINE NUMBER'}) === '_'?null:trim($value->{'PO LINE NUMBER'}),
				'Po_Line_Suffix'                     => trim($value->{'PO LINE SUFFIX'}) === '_'?null:trim($value->{'PO LINE SUFFIX'}),
				'Po_Status_Code'                     => trim($value->{'PO STATUS CODE'}) === '_'?null:trim($value->{'PO STATUS CODE'}),
				'Po_Status_Code_Description'         => trim($value->{'PO STATUS CODE DESCRIPTION'}) === '_'?null:trim($value->{'PO STATUS CODE DESCRIPTION'}),
				'Style_Code'                         => trim($value->{'STYLE CODE'}) === '_'?null:trim($value->{'STYLE CODE'}),
				'Activity_Code'                      => trim($value->{'ACTIVITY CODE'}) === '_'?null:trim($value->{'ACTIVITY CODE'}),
				'Activity_Label'                     => trim($value->{'ACTIVITY LABEL'}) === '_'?null:trim($value->{'ACTIVITY LABEL'}),
				'Universe_Code'                      => trim($value->{'UNIVERSE CODE'}) === '_'?null:trim($value->{'UNIVERSE CODE'}),
				'Universe_Label'                     => trim($value->{'UNIVERSE LABEL'}) === '_'?null:trim($value->{'UNIVERSE LABEL'}),
				'Department_Code'                    => trim($value->{'DEPARTMENT CODE'}) === '_'?null:trim($value->{'DEPARTMENT CODE'}),
				'Department_Label'                   => trim($value->{'DEPARTMENT LABEL'}) === '_'?null:trim($value->{'DEPARTMENT LABEL'}),
				'Family_Code'                        => trim($value->{'FAMILY CODE'}) === '_'?null:trim($value->{'FAMILY CODE'}),
				'Family_Label'                       => trim($value->{'FAMILY LABEL'}) === '_'?null:trim($value->{'FAMILY LABEL'}),
				'Sub_Family_Code'                    => trim($value->{'SUB FAMILY CODE'}) === '_'?null:trim($value->{'SUB FAMILY CODE'}),
				'Sub_Family_Label'                   => trim($value->{'SUB FAMILY LABEL'}) === '_'?null:trim($value->{'SUB FAMILY LABEL'}),
				'Theme_Code'                         => trim($value->{'THEME CODE'}) === '_'?null:trim($value->{'THEME CODE'}),
				'Brand_Code'                         => trim($value->{'BRAND CODE'}) === '_'?null:trim($value->{'BRAND CODE'}),
				'Brand_Label'                        => trim($value->{'BRAND LABEL'}) === '_'?null:trim($value->{'BRAND LABEL'}),
				'Description_Fr'                     => trim($value->{'DESCRIPTION FR'}) === '_'?null:trim($value->{'DESCRIPTION FR'}),
				'Buyer_Email'                        => trim($value->{'BUYER EMAIL'}) === '_'?null:trim($value->{'BUYER EMAIL'}),
				'Agent_Code'                         => trim($value->{'AGENT CODE'}) === '_'?null:trim($value->{'AGENT CODE'}),
				'Agent_Name'                         => trim($value->{'AGENT NAME'}) === '_'?null:trim($value->{'AGENT NAME'}),
				'Agent_Email'                        => trim($value->{'AGENT EMAIL'}) === '_'?null:trim($value->{'AGENT EMAIL'}),
				'Agent_Address'                      => trim($value->{'AGENT ADDRESS'}) === '_'?null:trim($value->{'AGENT ADDRESS'}),
				'Agent_Country'                      => trim($value->{'AGENT COUNTRY'}) === '_'?null:trim($value->{'AGENT COUNTRY'}),
				'Supplier_Code'                      => trim($value->{'SUPPLIER CODE'}) === '_'?null:trim($value->{'SUPPLIER CODE'}),
				'Factory_Name'                       => trim($value->{'FACTORY NAME'}) === '_'?null:trim($value->{'FACTORY NAME'}),
				'Factory_Email'                      => trim($value->{'FACTORY EMAIL'}) === '_'?null:trim($value->{'FACTORY EMAIL'}),
				'Factory_Address'                    => trim($value->{'FACTORY ADDRESS'}) === '_'?null:trim($value->{'FACTORY ADDRESS'}),
				'Made_In_Code'                       => trim($value->{'MADE IN CODE'}) === '_'?null:trim($value->{'MADE IN CODE'}),
				'Made_In_Label'                      => trim($value->{'MADE IN LABEL'}) === '_'?null:trim($value->{'MADE IN LABEL'}),
				'First_ETD_SHIPMENT_DATE'            => trim($value->{'FIRST ETD SHIPMENT DATE'}) === '_'?null:trim($value->{'FIRST ETD SHIPMENT DATE'}),
				'Shipment_Destination_Code'          => trim($value->{'SHIPMENT  DESTINATION CODE'}) === '_'?null:trim($value->{'SHIPMENT  DESTINATION CODE'}),
				'Shipment_Destination_Label'         => trim($value->{'SHIPMENT  DESTINATION LABEL'}) === '_'?null:trim($value->{'SHIPMENT  DESTINATION LABEL'}),
				'Incoterms_Code'                     => trim($value->{'INCOTERMS CODE'}) === '_'?null:trim($value->{'INCOTERMS CODE'}),
				'Incoterms_City_Label'               => trim($value->{'INCOTERMS CITY LABEL'}) === '_'?null:trim($value->{'INCOTERMS CITY LABEL'}),
				'Sku_Barcode'                        => trim($value->{'SKU BARCODE'}) === '_'?null:trim($value->{'SKU BARCODE'}),
				'Set_'                               => trim($value->{'SET_'}) === '_'?null:trim($value->{'SET_'}),
				'Number_Of_Pieces'                   => trim($value->{'NUMBER OF PIECES'}) === '_'?null:trim($value->{'NUMBER OF PIECES'}),
				'Color_Code'                         => trim($value->{'COLOR CODE'}) === '_'?null:trim($value->{'COLOR CODE'}),
				'Color_Label'                        => trim($value->{'COLOR LABEL'}) === '_'?null:trim($value->{'COLOR LABEL'}),
				'Standard_Code'                      => trim($value->{'STANDARD CODE'}) === '_'?null:trim($value->{'STANDARD CODE'}),
				'Standard_Barcode'                   => trim($value->{'STANDARD BARCODE'}) === '_'?null:trim($value->{'STANDARD BARCODE'}),
				'Size_Range_Code'                    => trim($value->{'SIZE RANGE CODE'}) === '_'?null:trim($value->{'SIZE RANGE CODE'}),
				'Size_Range_Label'                   => trim($value->{'SIZE RANGE LABEL'}) === '_'?null:trim($value->{'SIZE RANGE LABEL'}),
				'Size_Code'                          => trim($value->{'SIZE CODE'}) === '_'?null:trim($value->{'SIZE CODE'}),
				'Size_Label'                         => trim($value->{'SIZE LABEL'}) === '_'?null:trim($value->{'SIZE LABEL'}),
				'Size_Breakdown'                     => trim($value->{'SIZE BREAKDOWN'}) === '_'?null:trim($value->{'SIZE BREAKDOWN'}),
				'Total_Standard_Solid_Parcel_Number' => trim($value->{'TOTAL STANDARD-SOLID PARCEL NUMBER'}) === '_'?null:trim($value->{'TOTAL STANDARD-SOLID PARCEL NUMBER'}),
				'Product_Type'                       => trim($value->{'PRODUCT TYPE'}) === '_'?null:trim($value->{'PRODUCT TYPE'}),
				'Licence_Code'                       => trim($value->{'LICENCE CODE'}) === '_'?null:trim($value->{'LICENCE CODE'}),
				'Licence_Label'                      => trim($value->{'LICENCE LABEL'}) === '_'?null:trim($value->{'LICENCE LABEL'}),
				'Licence_Character_Code'             => trim($value->{'LICENCE CHARACTER CODE'}) === '_'?null:trim($value->{'LICENCE CHARACTER CODE'}),
				'Licence_Character_Label'            => trim($value->{'LICENCE CHARACTER LABEL'}) === '_'?null:trim($value->{'LICENCE CHARACTER LABEL'}),
				'Start_Print_Date'                   => trim($value->{'START PRINT DATE'}) === '_'?null:trim($value->{'START PRINT DATE'}),
				'Composition_Label'                  => trim($value->{'COMPOSITION LABEL'}) === '_'?null:trim($value->{'COMPOSITION LABEL'}),
				'Po_Change_Date'                     => trim($value->{'PO CHANGE DATE'}) === '_'?null:trim($value->{'PO CHANGE DATE'}),
				'Purchase_Price'                     => trim($value->{'PURCHASE PRICE'}) === '_'?null:trim($value->{'PURCHASE PRICE'}),
				'Po_Amount'                          => trim($value->{'PO AMOUNT'}) === '_'?null:trim($value->{'PO AMOUNT'}), 
				'Typologie'                          => trim($value->{'TYPOLOGIE'}) === '_'?null:trim($value->{'TYPOLOGIE'}),
				'Payment_Condition_Code'             => trim($value->{'PAYMENT CONDITION CODE'}) === '_'?null:trim($value->{'PAYMENT CONDITION CODE'}),
				'Payment_Terms'                      => trim($value->{'PAYMENT TERMS'}) === '_'?null:trim($value->{'PAYMENT TERMS'}),
				'Payment_Terms_Label'                => trim($value->{'PAYMENT TERMS LABEL'}) === '_'?null:trim($value->{'PAYMENT TERMS LABEL'}),
				'BarcodeForPricesTags'               => trim($value->{'SKU BARCODE'}) === '_'?null:trim($value->{'SKU BARCODE'}),
				'UVC_TCO'                            => trim($value->{'SKU CODE'}) === '_'?null:trim($value->{'SKU CODE'}),
				'Size'                               => trim($value->{'SIZE LABEL'}) === '_'?null:trim($value->{'SIZE LABEL'}),
				'ParcelBarCode'                      => trim($value->{'STANDARD BARCODE'}) === '_'?null:trim($value->{'STANDARD BARCODE'}),
				'is_price_data_exists'               => $is_price_data_exists
				 ]);
		}

		return $data;
	}

  /**
	 * Parse Price CSV file data into Temp Orchestra parcel quantity details table 
	 *
	 * @param [array] $value
	 * @param [integer] $parcel_details_datas
	 * @return void
	 * @author Praveen <praveen@wizroots.com>
	 */
	public function updateOrchestraTempParcelQuantityDetails($value, $parcel_details_data)
	{
		DB::table('temp_orchestra_parcel_quantity_details')->insert([
			'FactoryCode' 	              => trim($parcel_details_data->{'SUPPLIER CODE'}) === '_'?null:trim($parcel_details_data->{'SUPPLIER CODE'}),
			'BuyerPONo'                   => trim($parcel_details_data->{'PO'}) === '_'?null:trim($parcel_details_data->{'PO'}),
			'Theme'                       => trim($parcel_details_data->{'THEME LABEL'}) === '_'?null:trim($parcel_details_data->{'THEME LABEL'}),
			'UVC_TCO'                     => trim($value->{'SKU CODE'}) === '_'?null:trim($value->{'SKU CODE'}),
			'Quantity'                    => trim($parcel_details_data->{'QTY PER SKU-PO'}) === '_'?null:trim($parcel_details_data->{'QTY PER SKU-PO'}),
			'Color'                       => trim($parcel_details_data->{'COLOR CODE'}) === '_'?null:trim($parcel_details_data->{'COLOR CODE'}),
			'UVC'                         => trim($parcel_details_data->{'REFERENCE CODE'}) === '_'?null:trim($parcel_details_data->{'REFERENCE CODE'}),
			'Size'                        => trim($parcel_details_data->{'SIZE LABEL'}) === '_'?null:trim($parcel_details_data->{'SIZE LABEL'}),
			'BarcodeForPricesTags'        => trim($value->{'SKU BARCODE'}) === '_'?null:trim($value->{'SKU BARCODE'}),
			'Country_Code'                => trim($value->{'COUNTRY CODE'}) === '_'?null:trim($value->{'COUNTRY CODE'}),
			'Season_Code'                 => trim($value->{'SEASON CODE'}) === '_'?null:trim($value->{'SEASON CODE'}),
			'Currency'                    => trim($value->{'CURRENCY'}) === '_'?null:trim($value->{'CURRENCY'}),
			'Price'                       => trim($value->{'PRICE'}) === '_'?null:trim($value->{'PRICE'}),
			'Club_Price'                  => trim($value->{'CLUB PRICE'}) === '_'?null:trim($value->{'CLUB PRICE'}),
			'Nom_Appel'                   => trim($value->{'NOM_APPEL'}) === '_'?null:trim($value->{'NOM_APPEL'}),
			'Last_Change_Date'            => trim($value->{'LAST_CHANGE_DATE'}) === '_'?null:trim($value->{'LAST_CHANGE_DATE'}),
			'Supplier_Code'               => trim($value->{'SUPPLIER_CODE'}) === '_'?null:trim($value->{'SUPPLIER_CODE'}),
			'Supplier_Name'               => trim($value->{'SUPPLIER_NAME'}) === '_'?null:trim($value->{'SUPPLIER_NAME'}),
			'Po_Status_Code'              => trim($parcel_details_data->{'PO STATUS CODE'}) === '_'?null:trim($parcel_details_data->{'PO STATUS CODE'}),
			'Po_Status_Code_Description'  => trim($parcel_details_data->{'PO STATUS CODE DESCRIPTION'}) === '_'?null:trim($parcel_details_data->{'PO STATUS CODE DESCRIPTION'}),
			]);
	}


	/**
	 * Update PO details in orchestra_parcel_quantity_table
	 *
	 * @param [integer] $parcel_details_data
	 * @return void
	 * @author Praveen <praveen@wizroots.com>
	 */
	public function updateOrchestraParcelQuantityPODetails($parcel_details_data)
	{
		OrchestraParcelQuantityDetails::where('UVC_TCO', trim($parcel_details_data->{'SKU CODE'}) === '_'?null:trim($parcel_details_data->{'SKU CODE'}))
				->update([
					'FactoryCode' 	              => trim($parcel_details_data->{'SUPPLIER CODE'}) === '_'?null:trim($parcel_details_data->{'SUPPLIER CODE'}),
					'BuyerPONo'                   => trim($parcel_details_data->{'PO'}) === '_'?null:trim($parcel_details_data->{'PO'}),
					'Theme'                       => trim($parcel_details_data->{'THEME LABEL'}) === '_'?null:trim($parcel_details_data->{'THEME LABEL'}),
					'UVC_TCO'                     => trim($parcel_details_data->{'SKU CODE'}) === '_'?null:trim($parcel_details_data->{'SKU CODE'}),
					'Quantity'                    => trim($parcel_details_data->{'QTY PER SKU-PO'}) === '_'?null:trim($parcel_details_data->{'QTY PER SKU-PO'}),
					'Color'                       => trim($parcel_details_data->{'COLOR CODE'}) === '_'?null:trim($parcel_details_data->{'COLOR CODE'}),
					'UVC'                         => trim($parcel_details_data->{'REFERENCE CODE'}) === '_'?null:trim($parcel_details_data->{'REFERENCE CODE'}),
					'Size'                        => trim($parcel_details_data->{'SIZE LABEL'}) === '_'?null:trim($parcel_details_data->{'SIZE LABEL'}),
					'Po_Status_Code'              => trim($parcel_details_data->{'PO STATUS CODE'}) === '_'?null:trim($parcel_details_data->{'PO STATUS CODE'}),
					'Po_Status_Code_Description'  => trim($parcel_details_data->{'PO STATUS CODE DESCRIPTION'}) === '_'?null:trim($parcel_details_data->{'PO STATUS CODE DESCRIPTION'}),
					]);
	}

	public function bulkDumpOrchCsvData($csv_file, $original_file_name, $success)
	{
		DB::connection()->disableQueryLog();
		$file_name = time() . '.csv';
		$delimiter = ";";

		$csv_file->move(
			public_path('uploads'),
			$file_name
		);

		try {
			if (preg_match('/ORC_M3_PO_FILE/', $original_file_name)) {

			DB::table('dump_orchestra_po_csv')->truncate();

			DB::statement(DB::raw("COPY dump_orchestra_po_csv FROM '" . public_path('uploads/' . $file_name) . "' DELIMITER '" . $delimiter . "' CSV HEADER encoding 'windows-1251'"));
			} else {

			DB::table('dump_orchestra_price_csv')->truncate();

			DB::statement(DB::raw("COPY dump_orchestra_price_csv FROM '" . public_path('uploads/' . $file_name) . "' DELIMITER '" . $delimiter . "' CSV HEADER encoding 'windows-1251'"));
			}

			File::delete(public_path('uploads/' . $file_name));
			$success++;
		} catch (\Exception $e) {

			DB::table('dump_orchestra_po_csv')->truncate();
			DB::table('dump_orchestra_price_csv')->truncate();

			File::delete(public_path('uploads/' . $file_name));
		}

		return $success;
		
	}

	public function validateCsvData()
	{
		DB::connection()->disableQueryLog();

		$po_data = DumpOrchestraPOCsv::with('price_data')->get();

		$customer_data = $po_data->whereNotIn('SUPPLIER EMAIL', ['_','',null])->unique('SUPPLIER CODE');

		$this->createCustomer($customer_data);

		foreach ($po_data as $value) {

				$is_price_data_exists = $value->price_data()->count() === 0?0:1;
				
				if (DB::table('orchestra_parcel_details')->select("id")->where('UVC', trim($value->{'REFERENCE CODE'}))->exists())
				{
					$parcel_details_po = DB::table('orchestra_parcel_details')->select("id")->where([
																	['UVC', trim($value->{'REFERENCE CODE'})], 
																	['BuyerPONo', trim($value->PO)],
																	])->exists();
				if ($parcel_details_po)
				{  
					$parcel_details_sku_code = DB::table('orchestra_parcel_details')->select("id")->where([
																			['UVC', trim($value->{'REFERENCE CODE'})],
																			['BuyerPONo', trim($value->PO)],
																			['SKU_Code', trim($value->{'SKU CODE'})],
																			])->exists();
					if($parcel_details_sku_code)
					{
					$parcel_details_last_date = DB::table('orchestra_parcel_details')->select("id")->where([
																				['UVC', trim($value->{'REFERENCE CODE'})],
																				['BuyerPONo', trim($value->PO)],
																				['SKU_Code', trim($value->{'SKU CODE'})],
																				['Po_Change_Date', '<>', trim($value->{'PO CHANGE DATE'})],
																				])->exists();
		
					if($parcel_details_last_date)
					{
						// collision. update temp parcel details table
						$this->updateOrchestraTempParcelDetails($value, $is_price_data_exists);
						continue;
					}
					}
					else
					{
						$parcel_details_last_date = DB::table('orchestra_parcel_details')->select("id")->where([
																				['UVC', trim($value->{'REFERENCE CODE'})],
																				['BuyerPONo', trim($value->PO)],
																				['Po_Change_Date','<>',trim($value->{'PO CHANGE DATE'})],
																				])->exists();
		
					if($parcel_details_last_date)
					{
						//collision. update temp parcel details table 
						$this->updateOrchestraTempParcelDetails($value, $is_price_data_exists);
						continue;
					}
					else 
					{
						$this->insertOrchestraParcelDetails($value, $is_price_data_exists);
						continue;
					}
					}
				}
				else
				{
					$this->insertOrchestraParcelDetails($value, $is_price_data_exists);
					continue;
				}
				}
				else
				{
				$this->insertOrchestraParcelDetails($value, $is_price_data_exists);
				continue;
				}
				
			foreach ($value->price_data as $key => $price_row) {

			// check sku code already exists or not 
			if (DB::table('orchestra_parcel_quantity_details')->select("id")->where('UVC_TCO', trim($price_row->{'SKU CODE'}))->exists()) {

				// check PO details missing for this sku code (ie, PO number = null).
				$po_missing = DB::table('orchestra_parcel_quantity_details')->select("id")->where([
					['UVC_TCO', trim($price_row->{'SKU CODE'})],
					['BuyerPONo', null],
				])->exists();

				if ($po_missing) {
					// get po data for current uvc_tco value and update in parcel_quantity_details table.
					$this->updateOrchestraParcelQuantityPODetails($value);
					continue;
				}

				// check price details already exists for this sku code with same Country_Code.
				$order_with_same_countrycode = DB::table('orchestra_parcel_quantity_details')->select("id")->where([
					['UVC_TCO', trim($price_row->{'SKU CODE'})],
					['Country_Code', trim($price_row->{'COUNTRY CODE'})],
				])->exists();

				if ($order_with_same_countrycode) {
					// check price details already exists with same UVC_TCO ,COUNTRY CODE & different Last_Change_Date
					$quantity_date = DB::table('orchestra_parcel_quantity_details')->select("id")->where([
						['UVC_TCO', trim($price_row->{'SKU CODE'})],
						['Country_Code', trim($price_row->{'COUNTRY CODE'})],
						['Last_Change_Date', trim($price_row->LAST_CHANGE_DATE)],
					])->exists();

					if (!$quantity_date) {
						// collision. update temp orchestra parcel quantity details table 
						$this->updateOrchestraTempParcelQuantityDetails($price_row, $value);
						continue;
					}
				} else {
					// check price details already exists with same UVC_TCO & Last_Change_Date
					$quantity_date = DB::table('orchestra_parcel_quantity_details')->select("id")->where([
						['UVC_TCO', trim($price_row->{'SKU CODE'})],
						['Last_Change_Date', trim($price_row->LAST_CHANGE_DATE)],
					])->exists();

					// insert price details, iff order with same sku code, country code and last change date doesn't exists.
					if ($quantity_date) {
						// insert price details in orchestra_parcel_quantity_details, iff po details exists for this sku code and COUNTRY CODE.
						
						$this->insertOrchestraParcelQuantityDetails($price_row, $value);
						continue;

					} else {
						// collision. update temp orchestra parcel quantity details table 
						$this->updateOrchestraTempParcelQuantityDetails($price_row, $value);
						continue;
					}
				}
			} else {
				// insert price details in orchestra_parcel_quantity_details, iff po details exists for this sku code and COUNTRY CODE.
				$this->insertOrchestraParcelQuantityDetails($price_row, $value);
				continue;
				
			}
			
			}
		}
	}
	
}