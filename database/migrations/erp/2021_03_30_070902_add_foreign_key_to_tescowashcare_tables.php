<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyToTescowashcareTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tesco_washcare_orders', function (Blueprint $table) {
            $table->foreign('kids_warning_id')->references('id')->on('tesco_kids_warning_statements')->onDelete('cascade');
            $table->foreign('country_of_origin_id')->references('id')->on('tesco_countries')->onDelete('cascade');
            $table->foreign('primary_size_id')->references('id')->on('tesco_size_department')->onDelete('cascade');
            $table->foreign('wash_symbol_id')->references('id')->on('tesco_care_symbols')->onDelete('cascade');
            $table->foreign('bleach_symbol_id')->references('id')->on('tesco_care_symbols')->onDelete('cascade');
            $table->foreign('dry_symbol_id')->references('id')->on('tesco_care_symbols')->onDelete('cascade');
            $table->foreign('ironing_symbol_id')->references('id')->on('tesco_care_symbols')->onDelete('cascade');
            $table->foreign('dryclean_symbol_id')->references('id')->on('tesco_care_symbols')->onDelete('cascade');
        });

        Schema::table('tesco_washcare_orders_quanty', function (Blueprint $table) {
            $table->foreign('size_id')->references('id')->on('tesco_primary_size')->onDelete('cascade');
        });

        Schema::table('tesco_washcare_orders_additional_care_instructions', function (Blueprint $table) {
            $table->foreign('additional_care_id')->references('id')->on('tesco_care_additional_instructions')->onDelete('cascade');
        });

        Schema::table('tesco_washcare_orders_component_mapping', function (Blueprint $table) {
            $table->foreign('garment_component_id')->references('id')->on('tesco_components')->onDelete('cascade');
        });

        Schema::table('tesco_washcare_orders_component_fibre_mapping', function (Blueprint $table) {
            $table->foreign('fibre_composition_id')->references('id')->on('tesco_fabriclists')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tesco_washcare_orders', function (Blueprint $table) {
            $table->dropForeign('tesco_washcare_orders_kids_warning_id_foreign');
            $table->dropForeign('tesco_washcare_orders_country_of_origin_id_foreign');
            $table->dropForeign('tesco_washcare_orders_primary_size_id_foreign');
            $table->dropForeign('tesco_washcare_orders_wash_symbol_id_foreign');
            $table->dropForeign('tesco_washcare_orders_bleach_symbol_id_foreign');
            $table->dropForeign('tesco_washcare_orders_dry_symbol_id_foreign');
            $table->dropForeign('tesco_washcare_orders_ironing_symbol_id_foreign');
            $table->dropForeign('tesco_washcare_orders_dryclean_symbol_id_foreign');
        });

        Schema::table('tesco_washcare_orders_additional_care_instructions', function (Blueprint $table) {
            $table->dropForeign('tesco_washcare_orders_additional_care_instructions_additional_care_id_foreign');
        });

        Schema::table('tesco_washcare_orders_component_mapping', function (Blueprint $table) {
            $table->dropForeign('tesco_washcare_orders_component_mapping_garment_component_id_foreign');
        });

        Schema::table('tesco_washcare_orders_component_fibre_mapping', function (Blueprint $table) {
            $table->dropForeign('tesco_washcare_orders_component_fibre_mapping_fibre_composition_id_foreign');
        });
    }
}
