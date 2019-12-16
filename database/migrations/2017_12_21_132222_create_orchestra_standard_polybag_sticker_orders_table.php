<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrchestraStandardPolybagStickerOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orchestra_standard_polybag_sticker_orders', function (Blueprint $table) {
             $table->increments('id');
             $table->string('uvc');
	     $table->integer('template_id');
             $table->integer('po');
             $table->integer('height');
             $table->integer('no_of_carton');
             $table->integer('total_polybag');
             $table->integer('final_order_qty_sets');
             $table->integer('order_qty');
             $table->integer('final_order_qty');
 	     $table->date('delivery_date');
	     $table->longText('svg_path');
             $table->integer('status');
             $table->integer('group_id')->unsigned();
             $table->foreign('group_id')->references('id')->on('orchestra_age_group')->onDelete('cascade');
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
        Schema::dropIfExists('orchestra_standard_polybag_sticker_orders');
    }
}
