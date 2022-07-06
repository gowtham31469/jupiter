<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMachineExcessDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('machine_excess_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('machine_id')->index();
            $table->integer('min_range');
            $table->integer('max_range');
            $table->double('excess');
            $table->string('excess_unit');
            $table->timestamps();

            $table->foreign('machine_id')->references('id')->on('erp_machines')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('machine_excess_details');
    }
}
