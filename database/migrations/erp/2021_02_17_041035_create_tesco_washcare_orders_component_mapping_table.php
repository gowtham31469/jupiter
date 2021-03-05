<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTescoWashcareOrdersComponentMappingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tesco_washcare_orders_component_mapping', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('washcare_order_id')->index();
            $table->unsignedInteger('garment_component_id')->index();
            $table->json('garment_component_text')->nullable();
            $table->timestamps();

            $table->foreign('washcare_order_id')->references('id')->on('tesco_washcare_orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tesco_washcare_orders_component_mapping');
    }
}
