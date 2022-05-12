<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNonArtworkOrdersQuantyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('non_artwork_orders_quanty', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('non_artwork_order_id')->index();
            $table->bigInteger('size_id')->nullable(true)->index();
            $table->bigInteger('uom_id')->nullable(true)->index();
            $table->integer('rate')->nullable(true);
            $table->integer('rate_value')->nullable(true);
            $table->string('size')->nullable(true)->index();
            $table->string('uom')->nullable(true)->index();
            $table->integer('quantity')->nullable(true)->default('0');
            $table->timestamps();
            $table->foreign('non_artwork_order_id')->references('id')->on('non_artwork_orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('non_artwork_orders_quanty');
    }
}
