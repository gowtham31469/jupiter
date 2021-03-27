<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTescoKaffsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tesco_kaffs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('english')->nullable(true)->index();
            $table->string('polish')->nullable(true);
            $table->string('czech')->nullable(true);
            $table->string('slovak')->nullable(true);
            $table->string('hungarian')->nullable(true);
            $table->boolean('is_enabled')->default('0');
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
        Schema::dropIfExists('tesco_kaffs');
    }
}
