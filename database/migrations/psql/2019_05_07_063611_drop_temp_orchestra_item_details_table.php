<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropTempOrchestraItemDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('temp_orchestra_item_details');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('temp_orchestra_item_details', function (Blueprint $table)
        {
            $table->increments('id');
            $table->string('FactoryCode')->nullable();
            $table->string('SupplierName')->nullable();
            $table->text('SupplierAddress')->nullable();
            $table->string('SupplierEmail')->nullable();
            $table->string('SupplierCountry')->nullable();
            $table->string('BuyerEmail')->nullable();
            $table->string('First_ETD_SHIPMENT_DATE')->nullable();
            $table->integer('EditionNumber')->nullable();
            $table->integer('VersionNumber')->nullable();
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
            $table->double('PrixVenteKoweit')->nullable();
            $table->double('Prix_Vente_Arabie_Saoudite')->nullable();
            $table->double('Prix_Club_Arabie_Saoudite')->nullable();
            $table->double('Prix_Vente_Canada')->nullable();
            $table->double('Prix_Club_Canada')->nullable();
            $table->double('Prix_Vente_Suisse')->nullable();
            $table->double('Prix_Club_Suisse')->nullable();
            $table->double('Prix_Vente_Chine')->nullable();
            $table->double('Prix_Club_Chine')->nullable();
            $table->double('Prix_Vente_Turquie')->nullable();
            $table->double('Prix_Club_Turquie')->nullable();
            $table->double('Prix_Vente_Maroc')->nullable();
            $table->double('Prix_Club_Maroc')->nullable();
            $table->double('Prix_Vente_Ile_Maurice')->nullable();
            $table->double('Prix_Club_Ile_Maurice')->nullable();
            $table->double('Prix_Vente_Emirats_Arabes_Unis')->nullable();
            $table->double('Prix_Club_Emirats_Arabes_Unis')->nullable();
            $table->double('Prix_Vente_TUNISIE')->nullable();
            $table->double('Prix_Club_TUNISIE')->nullable();
            $table->double('Prix_Vente_ALGERIE')->nullable();
            $table->double('Prix_Club_ALGERIE')->nullable();
            $table->integer('Quantity')->nullable();
            $table->string('UVC')->nullable();
            $table->string('ParcelBarCode',13)->nullable();
            $table->string('ParcelCode')->nullable();
            $table->string('HangTag_Print_Date')->nullable();
            $table->double('Prix_Vente_QATAR')->nullable();
            $table->double('Prix_Club_QATAR')->nullable();
            $table->string('Style_Name')->nullable();
            $table->double('Prix_Vente_USA')->nullable();
            $table->double('Prix_Club_USA')->nullable();
            $table->string('order_status')->nullable();
            $table->timestamps();
        });
    }
}
