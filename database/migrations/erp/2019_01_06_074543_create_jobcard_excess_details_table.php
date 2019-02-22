<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobcardExcessDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobcard_excess_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('jobcard_main_table_id')->unsigned();  
            $table->string('ref_name'); 
            $table->integer('mtrs_for_stickers')->nullable();
            $table->integer('actual_excess')->nullable();   
            $table->integer('settings')->nullable();
            $table->integer('total_mtrs')->nullable();
            $table->timestamps();
            $table->foreign('jobcard_main_table_id')->references('id')->on('jobcard_main_table')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jobcard_excess_details');
    }
}
