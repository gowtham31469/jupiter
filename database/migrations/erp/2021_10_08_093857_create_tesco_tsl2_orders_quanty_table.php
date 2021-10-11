<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTescoTsl2OrdersQuantyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tesco_tsl2_orders_quanty', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('tsl2_order_id')->index();
            $table->bigInteger('tpnd')->default('0');
            $table->bigInteger('itf_barcode')->default('0');
            $table->integer('units_per_carton')->default('0');
            $table->integer('cases_per_carton')->default('0');
            $table->bigInteger('ean_barcode')->default('0');
            $table->string('size');
            $table->integer('case_size')->default('0');
            $table->string('orms_tpn');
            $table->integer('count')->default('0');
            $table->string('ratio_pack_type');
            $table->string('qr_code');
            $table->timestamps();
            $table->foreign('tsl2_order_id')->references('id')->on('tesco_tsl2_orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tesco_tsl2_orders_quanty');
    }
}
