<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrchestraSolidCartonStickerOrdersQtyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orchestra_solid_carton_sticker_orders_qty', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('oscs_id')->unsigned();
 	    $table->string('size');
            $table->integer('quantity');
	    $table->integer('quantity_sets');
            $table->foreign('oscs_id')->references('id')->on('orchestra_solid_carton_sticker_orders')->onDelete('cascade');
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
        Schema::dropIfExists('orchestra_solid_carton_sticker_orders_qty');
    }
}
