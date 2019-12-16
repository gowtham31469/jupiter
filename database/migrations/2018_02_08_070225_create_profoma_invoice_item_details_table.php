<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfomaInvoiceItemDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profoma_invoice_item_details', function (Blueprint $table)
        {
            $table->increments('id');
            $table->integer('pi_id')->unsigned();
            $table->string('material');
            $table->string('size');
            $table->string('style');
            $table->integer('quantity');
            $table->integer('unit_cost');
            $table->timestamps();
            $table->foreign('pi_id')->references('id')->on('profoma_invoices')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profoma_invoice_item_details');
    }
}
