<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TescoPrimarySize extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tesco_primary_size', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tesco_size_department_id')->unsigned();
            $table->string('department_title');
            $table->string('uk_size');
            $table->string('ce_size');
            $table->boolean('is_flag')->default(0);
            $table->timestamps();
            $table->foreign('tesco_size_department_id')->references('id')->on('tesco_size_department')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tesco_primary_size');
    }
}
