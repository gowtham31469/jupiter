<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrderPlacedByToTescoOrdersTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tesco_tcl1_orders', function (Blueprint $table) {
            $table->integer('order_placed_by')->default(null)->after('pi_status');
        });

        Schema::table('tesco_tcl2_orders', function (Blueprint $table) {
            $table->integer('order_placed_by')->default(null)->after('pi_status');
        });

        Schema::table('tesco_tsl1_orders', function (Blueprint $table) {
            $table->integer('order_placed_by')->default(null)->after('pi_status');
        });

        Schema::table('tesco_tcl4_orders', function (Blueprint $table) {
            $table->integer('order_placed_by')->default(null)->after('pi_status');
        });

        Schema::table('tesco_tsl3_orders', function (Blueprint $table) {
            $table->integer('order_placed_by')->default(null)->after('pi_status');
        });

        Schema::table('tesco_thl1_orders', function (Blueprint $table) {
            $table->integer('order_placed_by')->default(null)->after('pi_status');
        });

        Schema::table('tesco_thl3_orders', function (Blueprint $table) {
            $table->integer('order_placed_by')->default(null)->after('pi_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tesco_tcl1_orders', function (Blueprint $table) {
           $table->dropColumn('order_placed_by');
        });
        Schema::table('tesco_tcl2_orders', function (Blueprint $table) {
           $table->dropColumn('order_placed_by');
        });
        Schema::table('tesco_tsl1_orders', function (Blueprint $table) {
           $table->dropColumn('order_placed_by');
        });
        Schema::table('tesco_tcl4_orders', function (Blueprint $table) {
           $table->dropColumn('order_placed_by');
        });
        Schema::table('tesco_tsl3_orders', function (Blueprint $table) {
           $table->dropColumn('order_placed_by');
        });
        Schema::table('tesco_thl1_orders', function (Blueprint $table) {
           $table->dropColumn('order_placed_by');
        });
        Schema::table('tesco_thl3_orders', function (Blueprint $table) {
           $table->dropColumn('order_placed_by');
        });
    }
}
