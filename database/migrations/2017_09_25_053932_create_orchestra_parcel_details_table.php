<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrchestraParcelDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orchestra_parcel_details', function (Blueprint $table)
        {
            $table->increments('id');
            $table->string('FactoryCode')->nullable();
            $table->string('SupplierName')->nullable();
            $table->string('SupplierAddress')->nullable();
            $table->string('SupplierEmail')->nullable();
            $table->string('SupplierCountry')->nullable();
            $table->integer('EditionNumber')->nullable();
            $table->integer('VersionNumber');
            $table->string('BuyerPONo')->nullable();
            $table->string('UVC')->nullable();
            $table->string('ParcelBarCode',13)->nullable();
            $table->string('ParcelCode')->nullable();
            $table->string('Theme')->nullable();
            $table->integer('IsSTD');
            $table->string('CatgDescr')->nullable();
            $table->integer('Quantity');
            $table->string('STD')->nullable();
            $table->string('NomApple')->nullable();
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
        Schema::dropIfExists('orchestra_parcel_details');
    }
}
