<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOstinTemplate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ostin_template', function (Blueprint $table) {
            $table->increments('id');
	    $table->integer('item_id')->unsigned();
	    $table->integer('material_id')->unsigned();
	    $table->integer('hierarchy_id');
	    $table->string('template_name');
	    $table->string('template_description')->nullable();
	    $table->string('template_file')->nullable();
	    $table->smallInteger('disney')->nullable();
	    $table->integer('tax');
	    $table->string('gl_code');
	    $table->string('hsn_code');
	    $table->string('color');
	    $table->integer('dimension_height');
	    $table->integer('dimension_width');
	    $table->string('gl_text');
            $table->timestamps();
	    
	    $table->foreign('item_id')->references('id')
			->on('ostin_item_master')->onDelete('cascade');

            $table->foreign('material_id')->references('id')
			->on('raw_material_master')->onDelete('cascade');
	    /* Indexes	*/
	    $table->index(['item_id','material_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ostin_template');
    }
}
