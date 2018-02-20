<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTescoStickerOrdersCount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tesco_sticker_orders_count', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tesco_order_id')->unsigned();
            $table->string('template_name');
            $table->integer('count')->default('0');
            $table->timestamps();
            $table->foreign('tesco_order_id')->references('id')->on('tesco_sticker_orders_size_details')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tesco_sticker_orders_count');
    }
}
