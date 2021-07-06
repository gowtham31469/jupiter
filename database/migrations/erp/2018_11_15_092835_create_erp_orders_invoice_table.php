<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateErpOrdersInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('erp_orders_invoice', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('erp_order_id')->unsigned();
            $table->integer('invoice_id')->unsigned();
            $table->timestamps();
            $table->foreign('erp_order_id')->references('id')->on('erp_orders')->onDelete('cascade');
            $table->foreign('invoice_id')->references('id')->on('invoice')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('erp_orders_invoice');
    }
}
