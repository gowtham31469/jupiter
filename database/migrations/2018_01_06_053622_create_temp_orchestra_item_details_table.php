<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTempOrchestraItemDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temp_orchestra_item_details', function (Blueprint $table) {
             $table->increments('id');
            $table->string('FactoryCode')->nullable();
            $table->string('SupplierName')->nullable();
            $table->string('SupplierAddress')->nullable();
            $table->string('SupplierEmail')->nullable();
            $table->string('SupplierCountry')->nullable();
            $table->string('BuyerEmail')->nullable();
            $table->string('First_ETD_SHIPMENT_DATE')->nullable();
            $table->integer('EditionNumber');
            $table->integer('VersionNumber');
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
            $table->string('PrixEurope')->nullable();
            $table->double('PrixClubEurope');
            $table->double('PrixVenteKoweit');
            $table->double('Prix_Vente_Arabie_Saoudite');
            $table->double('Prix_Club_Arabie_Saoudite');
            $table->double('Prix_Vente_Canada');
            $table->double('Prix_Club_Canada');
            $table->double('Prix_Vente_Suisse');
            $table->double('Prix_Club_Suisse');
            $table->double('Prix_Vente_Chine');
            $table->double('Prix_Club_Chine');
            $table->double('Prix_Vente_Turquie');
            $table->double('Prix_Club_Turquie');
            $table->double('Prix_Vente_Maroc');
            $table->double('Prix_Club_Maroc');
            $table->double('Prix_Vente_Ile_Maurice');
            $table->double('Prix_Club_Ile_Maurice');
            $table->double('Prix_Vente_Emirats_Arabes_Unis');
            $table->double('Prix_Club_Emirats_Arabes_Unis');
            $table->double('Prix_Vente_TUNISIE');
            $table->double('Prix_Club_TUNISIE');
            $table->double('Prix_Vente_ALGERIE');
            $table->double('Prix_Club_ALGERIE');
            $table->integer('Quantity');
            $table->string('UVC')->nullable();
            $table->string('ParcelBarCode',13)->nullable();
            $table->string('ParcelCode')->nullable();
            $table->string('HangTag_Print_Date')->nullable();
            $table->double('Prix_Vente_QATAR');
            $table->double('Prix_Club_QATAR');
            $table->string('Style_Name')->nullable();
            $table->double('Prix_Vente_USA');
            $table->double('Prix_Club_USA');
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
        Schema::dropIfExists('temp_orchestra_item_details');
    }
}
