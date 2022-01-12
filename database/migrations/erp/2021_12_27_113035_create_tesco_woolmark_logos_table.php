<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTescoWoolmarkLogosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tesco_woolmark_logos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('woolmark_logo');
            $table->string('woolmark_logo_text');
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
        Schema::dropIfExists('tesco_woolmark_logos');
    }
}
