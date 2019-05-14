<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropOrchestraItemDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('orchestra_item_details');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('orchestra_item_details', function (Blueprint $table)
        {
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
            $table->string('PrixEurope')->nullable();
            $table->string('PrixClubEurope')->nullable();
            $table->string('PrixVenteKoweit')->nullable();
            $table->string('Prix_Vente_Arabie_Saoudite')->nullable();
            $table->string('Prix_Club_Arabie_Saoudite')->nullable();
            $table->string('Prix_Vente_Canada')->nullable();
            $table->string('Prix_Club_Canada')->nullable();
            $table->string('Prix_Vente_Suisse')->nullable();
            $table->string('Prix_Club_Suisse')->nullable();
            $table->string('Prix_Vente_Chine')->nullable();
            $table->string('Prix_Club_Chine')->nullable();
            $table->string('Prix_Vente_Turquie')->nullable();
            $table->string('Prix_Club_Turquie')->nullable();
            $table->string('Prix_Vente_Maroc')->nullable();
            $table->string('Prix_Club_Maroc')->nullable();
            $table->string('Prix_Vente_Ile_Maurice')->nullable();
            $table->string('Prix_Club_Ile_Maurice')->nullable();
            $table->string('Prix_Vente_Emirats_Arabes_Unis')->nullable();
            $table->string('Prix_Club_Emirats_Arabes_Unis')->nullable();
            $table->string('Prix_Vente_TUNISIE')->nullable();
            $table->string('Prix_Club_TUNISIE')->nullable();
            $table->string('Prix_Vente_ALGERIE')->nullable();
            $table->string('Prix_Club_ALGERIE')->nullable();
            $table->bigInteger('Quantity')->nullable();
            $table->string('UVC')->nullable();
            $table->string('ParcelBarCode',13)->nullable();
            $table->string('ParcelCode')->nullable();
            $table->string('HangTag_Print_Date')->nullable();
            $table->string('Prix_Vente_QATAR')->nullable();
            $table->string('Prix_Club_QATAR')->nullable();
            $table->string('Style_Name')->nullable();
            $table->string('Prix_Vente_USA')->nullable();
            $table->string('Prix_Club_USA')->nullable();
            $table->string('order_status')->nullable();
            $table->timestamps();
        });
    }
}
