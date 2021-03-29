<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTescoCareAdditionalInstructionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tesco_care_additional_instructions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('english')->nullable()->index();
            $table->string('polish')->nullable();
            $table->string('czech')->nullable();
            $table->string('slovak')->nullable();
            $table->string('hungarian')->nullable();
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
        Schema::dropIfExists('tesco_care_additional_instructions');
    }
}
