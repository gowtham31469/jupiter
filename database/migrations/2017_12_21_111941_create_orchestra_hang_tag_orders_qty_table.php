<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrchestraHangTagOrdersQtyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orchestra_hang_tag_orders_qty', function (Blueprint $table) {
            $table->increments('id');
 	    $table->integer('oht_id')->unsigned();
 	    $table->string('size');
	    $table->string('uvc_tco');
            $table->integer('quantity');
            $table->timestamps();
            $table->foreign('oht_id')->references('id')->on('orchestra_hang_tag_orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orchestra_hang_tag_orders_qty');
    }
}
