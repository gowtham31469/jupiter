<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCasesPerCartonForTescoOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tesco_tcl1_orders_quanty', function (Blueprint $table) {
            $table->integer('cases_per_carton')->after('units_per_carton');
        });
        Schema::table('tesco_tcl2_orders_quanty', function (Blueprint $table) {
            $table->integer('cases_per_carton')->after('units_per_carton');
        });
        Schema::table('tesco_tcl4_orders_quanty', function (Blueprint $table) {
            $table->integer('cases_per_carton')->after('units_per_carton');
        });
        Schema::table('tesco_tsl1_orders_quanty', function (Blueprint $table) {
            $table->integer('cases_per_carton')->after('units_per_carton');
        });
        Schema::table('tesco_tsl3_orders_quanty', function (Blueprint $table) {
            $table->integer('cases_per_carton')->after('units_per_carton');
        });
        Schema::table('tesco_thl1_orders_quanty', function (Blueprint $table) {
            $table->integer('cases_per_carton')->after('units_per_carton');
        });
        Schema::table('tesco_thl3_orders_quanty', function (Blueprint $table) {
            $table->integer('cases_per_carton')->after('units_per_carton');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tesco_tcl1_orders_quanty', function (Blueprint $table) {
            $table->dropColumn('cases_per_carton');
        });
        Schema::table('tesco_tsl1_orders_quanty', function (Blueprint $table) {
            $table->dropColumn('cases_per_carton');
        });
        Schema::table('tesco_tsl3_orders_quanty', function (Blueprint $table) {
            $table->dropColumn('cases_per_carton');
        });
        Schema::table('tesco_tcl2_orders_quanty', function (Blueprint $table) {
            $table->dropColumn('cases_per_carton');
        });
        Schema::table('tesco_tcl4_orders_quanty', function (Blueprint $table) {
            $table->dropColumn('cases_per_carton');
        });
        Schema::table('tesco_thl1_orders_quanty', function (Blueprint $table) {
            $table->dropColumn('cases_per_carton');
        });
        Schema::table('tesco_thl3_orders_quanty', function (Blueprint $table) {
            $table->dropColumn('cases_per_carton');
        });
    }
}
