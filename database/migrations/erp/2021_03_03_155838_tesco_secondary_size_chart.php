<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TescoSecondarySizeChart extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tesco_secondary_size_chart', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tesco_primary_size_id')->unsigned();
            $table->integer('tesco_secondary_size_category_id')->unsigned();
            $table->string('secondary_size');
            $table->timestamps();
            $table->foreign('tesco_primary_size_id')->references('id')->on('tesco_primary_size')->onDelete('cascade');
            $table->foreign('tesco_secondary_size_category_id')->references('id')->on('tesco_secondary_size_category')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tesco_secondary_size_chart');
    }
}
