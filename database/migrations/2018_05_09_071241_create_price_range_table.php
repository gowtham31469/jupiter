<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePriceRangeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('price_range', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('price_taxation_id')->unsigned();
            $table->integer('volume_from');
            $table->integer('volume_to');
            $table->double('price');
            $table->timestamps();
            $table->foreign('price_taxation_id')->references('id')->on('price_taxation')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('price_range');
    }
}
