<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrchestraSizeLabelOrdersQtyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orchestra_size_label_orders_qty', function (Blueprint $table) {
            $table->increments('id');
 	    $table->integer('osl_id')->unsigned();
 	    $table->string('size');
	    $table->string('uvc_tco');
            $table->integer('quantity');
            $table->timestamps();
            $table->foreign('osl_id')->references('id')->on('orchestra_size_label_orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orchestra_size_label_orders_qty');
    }
}
