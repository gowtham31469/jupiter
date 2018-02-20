<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTcl2OrdersQuantyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tesco_tcl2_orders_quanty', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tcl2_order_id')->unsigned();
            $table->bigInteger('tpnd');
            $table->bigInteger('itf_barcode');
            $table->integer('units_per_carton');
            $table->bigInteger('ean_barcode');
            $table->string('size');
            $table->integer('case_size');
            $table->string('orms_tpn');
            $table->integer('count');
            $table->timestamps();
            $table->foreign('tcl2_order_id')->references('id')->on('tesco_tcl2_orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tesco_tcl2_orders_quanty');
    }
}
