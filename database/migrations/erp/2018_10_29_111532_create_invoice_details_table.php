<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoiceDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('invoice_id')->unsigned();
            $table->integer('company_id')->unsigned();
            $table->string('order_no');
            $table->string('style_no');
            $table->string('ocn_no');
            $table->string('item');
            $table->string('reference_no');
            $table->string('size');
            $table->integer('hsn_code');
            $table->string('tax');
            $table->integer('ordered_qnty');
            $table->integer('delivered_qnty');
            $table->double('rate');
            $table->timestamps();
            $table->foreign('invoice_id')->references('id')->on('invoice')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('company')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice_details');
    }
}
