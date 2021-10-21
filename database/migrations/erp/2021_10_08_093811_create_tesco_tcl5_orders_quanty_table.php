<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTescoTcl5OrdersQuantyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tesco_tcl5_orders_quanty', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('tcl5_order_id')->index();
            $table->bigInteger('tpnd')->nullable(true);
            $table->bigInteger('itf_barcode')->nullable(true);
            $table->integer('units_per_carton')->default('0');
            $table->integer('cases_per_carton')->default('0');
            $table->bigInteger('ean_barcode')->nullable(true);
            $table->string('size');
            $table->integer('case_size')->default('0');
            $table->string('orms_tpn')->nullable(true);
            $table->integer('count')->default('0');
            $table->string('qr_code')->nullable(true);
            $table->timestamps();
            $table->foreign('tcl5_order_id')->references('id')->on('tesco_tcl5_orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tesco_tcl5_orders_quanty');
    }
}
