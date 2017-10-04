<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrchestraCompositions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orchestra_compositions', function (Blueprint $table) {
            $table->increments('id');
	    $table->string('french');
	    $table->string('english');
            $table->string('spanish');
            $table->string('portuguese');
	    $table->string('italian');
	    $table->string('dutch');
            $table->string('german');
	    $table->string('russian');
	    $table->string('chinese');
 	    $table->string('arabic');
            $table->string('greek');
            $table->string('turkish');
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
        Schema::dropIfExists('orchestra_compositions');
    }
}
