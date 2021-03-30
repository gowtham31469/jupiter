<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTotalFibreCompositionPercentageToTescoWashcareOrdersComponentMappingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tesco_washcare_orders_component_mapping', function (Blueprint $table) {
            $table->integer('total_composition_percentage');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tesco_washcare_orders_component_mapping', function (Blueprint $table) {
            $table->dropColumn('total_composition_percentage');
        });
    }
}
