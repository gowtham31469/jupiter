<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history', function (Blueprint $table) {
        $table->increments('id');
        $table->integer('buyer_id')->unsigned();
        $table->foreign('buyer_id')->references('id')->on('buyers');
        $table->integer('erp_order_details_id')->unsigned();
        $table->foreign('erp_order_details_id')->references('id')->on('erp_order_details')->onDelete('cascade');
        $table->string('template');
        $table->string('season');
        $table->string('country')->nullable();
        $table->integer('customer_id')->unsigned();
        $table->foreign('customer_id')->references('id')->on('customers');
        $table->string('material');
        $table->string('style');
        $table->string('mat_sub');
        $table->string('reference');
        $table->string('po');
        $table->string('buyer_po');
        $table->string('ocn');
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('history');
    }
}
