<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrchestraSolidCartonStickerOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orchestra_solid_carton_sticker_orders', function (Blueprint $table) {
             $table->increments('id');
             $table->string('uvc');
	     $table->integer('template_id');
             $table->integer('po');
             $table->integer('height');
 	     $table->date('delivery_date');
	     $table->longText('svg_path');
             $table->integer('status');
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
        Schema::dropIfExists('orchestra_solid_carton_sticker_orders');
    }
}
