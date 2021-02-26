<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTescoWashcareOrdersComponentFibreMappingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tesco_washcare_orders_component_fibre_mapping', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('washcare_component_id')->index();
            $table->unsignedInteger('fibre_composition_id')->index();
            $table->integer('fibre_composition_percentage');
            $table->timestamps();

            $table->foreign('washcare_component_id')->references('id')->on('tesco_washcare_orders_component_mapping')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tesco_washcare_orders_component_fibre_mapping');
    }
}
