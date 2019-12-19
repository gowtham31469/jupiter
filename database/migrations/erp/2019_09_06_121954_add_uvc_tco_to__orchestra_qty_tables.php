<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUvcTcoToOrchestraQtyTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orchestra_solid_carton_sticker_orders_qty', function (Blueprint $table) {
            $table->string('uvc_tco')->nullable();
        });

        Schema::table('orchestra_standard_carton_sticker_orders_qty', function (Blueprint $table) {
            $table->string('uvc_tco')->nullable();
        });

        Schema::table('orchestra_standard_poly_bag_sticker_orders_qty', function (Blueprint $table) {
            $table->string('uvc_tco')->nullable();
        });

        Schema::table('orchestra_solid_poly_bag_sticker_orders_qty', function (Blueprint $table) {
            $table->string('uvc_tco')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orchestra_solid_carton_sticker_orders_qty', function (Blueprint $table) {
            $table->dropColumn('uvc_tco')->nullable();
        });

        Schema::table('orchestra_standard_carton_sticker_orders_qty', function (Blueprint $table) {
            $table->dropColumn('uvc_tco')->nullable();
        });

        Schema::table('orchestra_standard_poly_bag_sticker_orders_qty', function (Blueprint $table) {
            $table->dropColumn('uvc_tco')->nullable();
        });

         Schema::table('orchestra_solid_poly_bag_sticker_orders_qty', function (Blueprint $table) {
            $table->dropColumn('uvc_tco')->nullable();
        });
    }
}
