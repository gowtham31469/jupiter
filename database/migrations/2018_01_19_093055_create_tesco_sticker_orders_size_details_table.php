<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTescoStickerOrdersSizeDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tesco_sticker_orders_size_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tesco_order_id')->unsigned();
            $table->bigInteger('tpnd');
            $table->bigInteger('itf_barcode');
            $table->integer('units_per_carton');
            $table->bigInteger('ean_barcode');
            $table->string('size');
            $table->integer('case_size');
            $table->string('orms_tpn');
            $table->integer('tcl1_count');
            $table->boolean('tcl1_event')->default('0');
            $table->integer('tsl1_count');
            $table->boolean('tsl1_event')->default('0');
            $table->integer('tcl2_count');
            $table->boolean('tcl2_event')->default('0');
            $table->integer('tcl4_count');
            $table->boolean('tcl4_event')->default('0');
            $table->integer('tsl3_count');
            $table->boolean('tsl3_event')->default('0');
            $table->integer('thl1_count');
            $table->boolean('thl1_event')->default('0');
            $table->integer('thl3_count');
            $table->boolean('thl3_event')->default('0');
            $table->timestamps();
            $table->foreign('tesco_order_id')->references('id')->on('tesco_polybag_carton_sticker_orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tesco_sticker_orders_size_details');
    }
}
