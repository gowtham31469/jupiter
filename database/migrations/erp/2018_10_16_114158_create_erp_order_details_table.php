<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateErpOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('erp_order_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('erp_order_id')->unsigned();
            $table->string('style_no');
            $table->integer('order_quantity');
            $table->integer('delivered_quantity')->nullable();
            $table->string('size')->nullable();
            $table->string('delivery_date')->nullable();
            $table->string('order_qnty_table_name');
            $table->integer('order_qnty_table_id');
            $table->string('status');
            $table->timestamps();
            $table->foreign('erp_order_id')->references('id')->on('erp_orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('erp_order_details');
    }
}
