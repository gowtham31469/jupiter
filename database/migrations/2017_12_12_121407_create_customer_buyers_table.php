<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerBuyersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('customer_buyers', function (Blueprint $table) {
        //     $table->increments('id');
        //     $table->integer('customer_id')->unsigned();
        //     $table->integer('buyer_id')->unsigned();
        //     $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        //     $table->foreign('buyer_id')->references('id')->on('buyers')->onDelete('cascade');
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_buyers');
    }
}
