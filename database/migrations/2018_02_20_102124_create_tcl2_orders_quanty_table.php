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
            $table->bigInteger('tpnd')->default('0');
            $table->bigInteger('itf_barcode')->default('0');
            $table->integer('units_per_carton')->default('0');
            $table->bigInteger('ean_barcode')->default('0');
            $table->string('size');
            $table->integer('case_size')->default('0');
            $table->string('orms_tpn');
            $table->integer('count')->default('0');
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
