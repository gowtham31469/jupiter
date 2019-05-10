<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewCsvOrchestraItemDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orchestra_item_details', function (Blueprint $table) {
            $table->increments('id');
            $table->string('FactoryCode')->nullable();
            $table->string('SupplierName')->nullable();
            $table->text('SupplierAddress')->nullable();
            $table->string('SupplierEmail')->nullable();
            $table->string('SupplierCountry')->nullable();
            $table->string('BuyerEmail')->nullable();
            $table->string('First_ETD_SHIPMENT_DATE')->nullable();
            $table->bigInteger('EditionNumber')->nullable();
            $table->bigInteger('VersionNumber')->nullable();
            $table->string('Collection')->nullable();
            $table->string('BuyerPONo')->nullable();
            $table->string('BarcodeForPricesTags')->nullable();
            $table->string('UVC_TCO')->nullable();
            $table->string('Description')->nullable();
            $table->string('Rayon')->nullable();
            $table->string('Color')->nullable();
            $table->string('Size')->nullable();
            $table->string('Stature')->nullable();
            $table->string('Age')->nullable();
            $table->string('Theme')->nullable();
            $table->string('Marque')->nullable();
            $table->string('Disney')->nullable();
            $table->string('Marvel')->nullable();
            $table->string('PommeFramboise')->nullable();
            $table->double('PrixEurope')->nullable();
            $table->double('PrixClubEurope')->nullable();
            $table->double('Prix_Vente_Arabie_Saoudite')->nullable();
            $table->double('Prix_Club_Arabie_Saoudite')->nullable();
            $table->double('Prix_Vente_Canada')->nullable();
            $table->double('Prix_Club_Canada')->nullable();
            $table->double('Prix_Vente_Suisse')->nullable();
            $table->double('Prix_Club_Suisse')->nullable();
            $table->double('Prix_Vente_Maroc')->nullable();
            $table->double('Prix_Club_Maroc')->nullable();
            $table->double('Prix_Vente_Ile_Maurice')->nullable();
            $table->double('Prix_Club_Ile_Maurice')->nullable();
            $table->double('Prix_Vente_ALGERIE')->nullable();
            $table->double('Prix_Club_ALGERIE')->nullable();
            $table->double('Prix_Vente_USA')->nullable();
            $table->double('Prix_Club_USA')->nullable();
            $table->bigInteger('Quantity')->nullable();
            $table->string('UVC')->nullable();
            $table->string('ParcelBarCode')->nullable();
            $table->string('ParcelCode')->nullable();
            $table->string('HangTag_Print_Date')->nullable();
            $table->string('Style_Name')->nullable();
            $table->bigInteger('Po_Line_Number')->nullable();
            $table->bigInteger('Po_Line_Suffix')->nullable();
            $table->bigInteger('Po_Status_Code')->nullable();
            $table->string('Po_Status_Code_Description')->nullable();
            $table->string('Style_Code')->nullable();
            $table->string('Activity_Code')->nullable();
            $table->string('Activity_Label')->nullable();
            $table->string('Universe_Code')->nullable();
            $table->string('Universe_Label')->nullable();
            $table->string('Department_Code')->nullable();
            $table->string('Department_Label')->nullable();
            $table->bigInteger('Family_Code')->nullable();
            $table->string('Family_Label')->nullable();
            $table->string('Sub_Family_Code')->nullable();
            $table->string('Sub_Family_Label')->nullable();
            $table->string('Theme_Code')->nullable();
            $table->string('Brand_Code')->nullable();
            $table->string('Brand_Label')->nullable();
            $table->string('Product_Name')->nullable();
            $table->string('Description_Fr')->nullable();
            $table->string('Agent_Code')->nullable();
            $table->string('Agent_Name')->nullable();
            $table->string('Agent_Email')->nullable();
            $table->text('Agent_Address')->nullable();
            $table->string('Agent_Country')->nullable();
            $table->string('Supplier_Code')->nullable();
            $table->string('Factory_Name')->nullable();
            $table->string('Factory_Email')->nullable();
            $table->text('Factory_Address')->nullable();
            $table->string('Made_In_Code')->nullable();
            $table->string('Made_In_Label')->nullable();
            $table->string('Shipment_Destination_Code')->nullable();
            $table->string('Shipment_Destination_Label')->nullable();
            $table->string('Incoterms_Code')->nullable();
            $table->string('Incoterms_City_Label')->nullable();
            $table->string('Set_')->nullable();
            $table->string('Number_Of_Pieces')->nullable();
            $table->string('Color_Label')->nullable();
            $table->string('Standard_Code')->nullable();
            $table->bigInteger('Standard_Barcode')->nullable();
            $table->string('Size_Range_Code')->nullable();
            $table->string('Size_Code')->nullable();
            $table->bigInteger('Size_Breakdown')->nullable();
            $table->bigInteger('Total_Standard_Solid_Parcel_Number')->nullable();
            $table->string('Product_Type')->nullable();
            $table->string('Licence_Label')->nullable();
            $table->string('Licence_Character_Code')->nullable();
            $table->string('Licence_Character_Label')->nullable();
            $table->string('Composition_Label')->nullable();
            $table->string('Po_Change_Date')->nullable();
            $table->double('Purchase_Price')->nullable();
            $table->double('Po_Amount')->nullable();
            $table->string('Typologie')->nullable();
            $table->bigInteger('Payment_Condition_Code')->nullable();
            $table->string('Payment_Terms')->nullable();
            $table->string('Payment_Terms_Label')->nullable();
            $table->string('Country_Code')->nullable();
            $table->string('Currency')->nullable();
            $table->double('Price')->nullable();
            $table->double('Club_Price')->nullable();
            $table->string('Nom_Appel')->nullable();
            $table->bigInteger('Last_Change_Date')->nullable();
            $table->string('order_status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orchestra_item_details');
    }
}
