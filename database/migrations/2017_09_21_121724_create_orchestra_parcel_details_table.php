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
            $table->string('factory_code');
            $table->string('supplier_name');
            $table->string('supplier_address');
            $table->string('supplier_email');
            $table->string('supplier_country');
            $table->integer('edition_number');
            $table->integer('version_number');
            $table->string('buyer_po_number');
            $table->string('uvc');
            $table->string('parcel_barcode',13);
            $table->string('parcel_code');
            $table->string('theme');
            $table->integer('is_std');
            $table->string('catg_descr');
            $table->integer('quantity');
            $table->string('std');
            $table->string('nom_apple');
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
