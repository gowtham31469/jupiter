<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrchestraTemplate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orchestra_template', function (Blueprint $table) {
            $table->increments('id');
	    $table->integer('item_id')->unsigned();
	    $table->integer('buyer_id')->unsigned();
	    $table->string('template_name');
	    $table->string('template_description');
	    $table->string('template_file');
            $table->timestamps();
        });
	Schema::table('orchestra_template',function(Blueprint $table){
             $table->foreign('item_id')
                ->references('id')->on('orchestra_item_master')
                ->onDelete('cascade');
        });
	Schema::table('orchestra_template',function(Blueprint $table){
             $table->foreign('buyer_id')
                ->references('id')->on('buyers')
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
        Schema::dropIfExists('orchestra_template');
    }
}
