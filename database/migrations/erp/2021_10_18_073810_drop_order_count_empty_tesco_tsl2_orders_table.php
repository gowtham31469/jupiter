<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropOrderCountEmptyTescoTsl2OrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tesco_tsl2_orders', function (Blueprint $table) {
            $table->dropColumn('is_order_count_empty');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tesco_tsl2_orders', function (Blueprint $table) {
            $table->boolean('is_order_count_empty')->default(1);
        });
    }
}
