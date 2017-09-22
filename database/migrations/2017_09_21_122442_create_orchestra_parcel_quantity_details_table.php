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
        Schema::create('orchestra_parcel_quantity_details', function (Blueprint $table) 
        {
            $table->increments('id');
            $table->string('factory_code');
            $table->integer('edition_number');
            $table->integer('version_number');
            $table->string('buyer_po_number');
            $table->string('uvc');
            $table->string('tco');
            $table->string('theme');
            $table->integer('quantity');
            $table->string('color');
            $table->string('size');
            $table->string('prices_tags_barcode');
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
