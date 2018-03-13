<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrderCountEmptyToTescoOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tesco_tcl1_orders', function (Blueprint $table) {
            $table->boolean('is_order_count_empty')->after('order_flag')->default('1');
        });
        Schema::table('tesco_tcl2_orders', function (Blueprint $table) {
            $table->boolean('is_order_count_empty')->after('order_flag')->default('1');
        });
        Schema::table('tesco_tsl1_orders', function (Blueprint $table) {
            $table->boolean('is_order_count_empty')->after('order_flag')->default('1');
        });
        Schema::table('tesco_tcl4_orders', function (Blueprint $table) {
            $table->boolean('is_order_count_empty')->after('order_flag')->default('1');
        });
        Schema::table('tesco_tsl3_orders', function (Blueprint $table) {
            $table->boolean('is_order_count_empty')->after('order_flag')->default('1');
        });
        Schema::table('tesco_thl1_orders', function (Blueprint $table) {
            $table->boolean('is_order_count_empty')->after('order_flag')->default('1');
        });
        Schema::table('tesco_thl3_orders', function (Blueprint $table) {
            $table->boolean('is_order_count_empty')->after('order_flag')->default('1');
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
            $table->dropColumn('is_order_count_empty');
        });
       Schema::table('tesco_tcl2_orders', function (Blueprint $table) {
            $table->dropColumn('is_order_count_empty');
        });
       Schema::table('tesco_tsl1_orders', function (Blueprint $table) {
            $table->dropColumn('is_order_count_empty');
        });
       Schema::table('tesco_tcl4_orders', function (Blueprint $table) {
            $table->dropColumn('is_order_count_empty');
        });
       Schema::table('tesco_tsl3_orders', function (Blueprint $table) {
            $table->dropColumn('is_order_count_empty');
        });
       Schema::table('tesco_thl1_orders', function (Blueprint $table) {
            $table->dropColumn('is_order_count_empty');
        });
       Schema::table('tesco_thl3_orders', function (Blueprint $table) {
            $table->dropColumn('is_order_count_empty');
        });
    }
}
