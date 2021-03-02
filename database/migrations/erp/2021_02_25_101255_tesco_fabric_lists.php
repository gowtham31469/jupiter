<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TescoFabricLists extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tesco_fabriclists', function (Blueprint $table) {
            $table->increments('id');
            $table->string('english');
            $table->string('polish');
            $table->string('czech');
            $table->string('slovak');
            $table->string('hungarian');
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
        Schema::dropIfExists('tesco_fabriclists');
    }
}
