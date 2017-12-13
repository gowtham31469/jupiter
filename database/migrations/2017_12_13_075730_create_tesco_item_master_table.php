<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTescoItemMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tesco_item_master', function (Blueprint $table) {
            $table->increments('id');
	    $table->integer('company_id')->unsigned();;
	    $table->integer('parent_id')->unsigned();;
 	    $table->string('item_name');
            $table->string('ref_no');
            $table->timestampsTz();
	    $table->foreign('company_id')->references('id')->on('company')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tesco_item_master');
    }
}
