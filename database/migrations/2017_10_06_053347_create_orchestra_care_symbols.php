<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrchestraCareSymbols extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orchestra_care_symbols', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('category_id')->unsigned();
            $table->string('cofreet_symbols');
            $table->string('astms_symbol');
            $table->string('care_instruction');
            $table->timestamps();
        });
        Schema::table('orchestra_care_symbols',function(Blueprint $table){
                   $table->foreign('category_id')
                      ->references('id')->on('orchestra_care_category')
                      ->onDelete('cascade');
              });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orchestra_care_symbols');
    }
}
