<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCancelOrdFlagToOrderTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('tesco_tcl1_orders', function (Blueprint $table) {
            $table->integer('cancel_ord_flag')->after('order_placed_by')->default(0);
        });

        Schema::table('tesco_tcl2_orders', function (Blueprint $table) {
            $table->integer('cancel_ord_flag')->after('order_placed_by')->default(0);
        });

        Schema::table('tesco_tsl1_orders', function (Blueprint $table) {
            $table->integer('cancel_ord_flag')->after('order_placed_by')->default(0);
        });

        Schema::table('tesco_tcl4_orders', function (Blueprint $table) {
            $table->integer('cancel_ord_flag')->after('order_placed_by')->default(0);
        });

        Schema::table('tesco_tsl3_orders', function (Blueprint $table) {
            $table->integer('cancel_ord_flag')->after('order_placed_by')->default(0);
        });

        Schema::table('tesco_thl1_orders', function (Blueprint $table) {
            $table->integer('cancel_ord_flag')->after('order_placed_by')->default(0);
        });

        Schema::table('tesco_thl3_orders', function (Blueprint $table) {
            $table->integer('cancel_ord_flag')->after('order_placed_by')->default(0);
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
           $table->dropColumn('cancel_ord_flag');
        });
        Schema::table('tesco_tcl2_orders', function (Blueprint $table) {
           $table->dropColumn('cancel_ord_flag');
        });
        Schema::table('tesco_tsl1_orders', function (Blueprint $table) {
           $table->dropColumn('cancel_ord_flag');
        });
        Schema::table('tesco_tcl4_orders', function (Blueprint $table) {
           $table->dropColumn('cancel_ord_flag');
        });
        Schema::table('tesco_tsl3_orders', function (Blueprint $table) {
           $table->dropColumn('edit_permission');
        });
        Schema::table('tesco_thl1_orders', function (Blueprint $table) {
           $table->dropColumn('cancel_ord_flag');
        });
        Schema::table('tesco_thl3_orders', function (Blueprint $table) {
           $table->dropColumn('cancel_ord_flag');
        });
    }
}
