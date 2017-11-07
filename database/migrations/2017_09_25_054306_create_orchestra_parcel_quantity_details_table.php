<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrchestraParcelQuantityDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orchestra_parcel_quantity_details', function (Blueprint $table) {
            $table->increments('id');
            $table->string('FactoryCode')->nullable();
            $table->integer('EditionNumber');
            $table->integer('VersionNumber');
            $table->string('BuyerPONo')->nullable();
            $table->string('UVC')->nullable();
            $table->string('UVC_TCO')->nullable();
            $table->string('Theme')->nullable();
            $table->integer('Quantity');
            $table->string('Size')->nullable();
            $table->string('Color')->nullable();
            $table->string('BarcodeForPricesTags')->nullable();
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
        Schema::dropIfExists('orchestra_parcel_quantity_details');
    }
}
