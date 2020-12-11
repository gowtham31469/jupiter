<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTotalQtyToTescoOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tesco_tcl1_orders', function (Blueprint $table) {
            $table->integer('total_qty')->nullable(true);
        });

        Schema::table('tesco_tcl2_orders', function (Blueprint $table) {
            $table->integer('total_qty')->nullable(true);
        });

        Schema::table('tesco_tcl4_orders', function (Blueprint $table) {
            $table->integer('total_qty')->nullable(true);
        });

        Schema::table('tesco_tsl1_orders', function (Blueprint $table) {
            $table->integer('total_qty')->nullable(true);
        });

        Schema::table('tesco_tsl3_orders', function (Blueprint $table) {
            $table->integer('total_qty')->nullable(true);
        });

        Schema::table('tesco_thl1_orders', function (Blueprint $table) {
            $table->integer('total_qty')->nullable(true);
        });

        Schema::table('tesco_thl3_orders', function (Blueprint $table) {
            $table->integer('total_qty')->nullable(true);
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
            $table->dropColumn('total_qty');
        });

        Schema::table('tesco_tcl2_orders', function (Blueprint $table) {
            $table->dropColumn('total_qty');
        });

        Schema::table('tesco_tcl4_orders', function (Blueprint $table) {
            $table->dropColumn('total_qty');
        });

        Schema::table('tesco_tsl1_orders', function (Blueprint $table) {
            $table->dropColumn('total_qty');
        });

        Schema::table('tesco_tsl3_orders', function (Blueprint $table) {
            $table->dropColumn('total_qty');
        });

        Schema::table('tesco_thl1_orders', function (Blueprint $table) {
            $table->dropColumn('total_qty');
        });

        Schema::table('tesco_thl3_orders', function (Blueprint $table) {
            $table->dropColumn('total_qty');
        });
    }
}
