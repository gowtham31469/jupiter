<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTsl3OrdersQuantyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tesco_tsl3_orders_quanty', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tsl3_order_id')->unsigned();
            $table->bigInteger('tpnd');
            $table->bigInteger('itf_barcode');
            $table->integer('units_per_carton');
            $table->bigInteger('ean_barcode');
            $table->string('size');
            $table->integer('case_size');
            $table->string('orms_tpn');
            $table->integer('count');
            $table->timestamps();
            $table->foreign('tsl3_order_id')->references('id')->on('tesco_tsl3_orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tesco_tsl3_orders_quanty');
    }
}
