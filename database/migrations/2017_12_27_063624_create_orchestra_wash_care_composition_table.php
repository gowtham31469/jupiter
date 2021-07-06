<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrchestraWashCareCompositionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orchestra_wash_care_composition', function (Blueprint $table) {
             $table->increments('id');
	     $table->integer('owcl_id')->unsigned();
	     $table->integer('parts_id')->unsigned();
	     $table->integer('composition_id')->unsigned();
             $table->integer('percentage');
             $table->integer('page_id');
             $table->foreign('parts_id')->references('id')->on('orchestra_parts')->onDelete('cascade');
             $table->foreign('composition_id')->references('id')->on('orchestra_compositions')->onDelete('cascade');
             $table->foreign('owcl_id')->references('id')->on('orchestra_wash_care_label_orders')->onDelete('cascade');
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
        Schema::dropIfExists('orchestra_wash_care_composition');
    }
}
