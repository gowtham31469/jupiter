<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDefaultValueInOrchestraHeatTransferOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orchestra_heat_transfer_orders', function (Blueprint $table) {
            $table->integer('is_order_count_empty')->nullable(false)->default(0)->change();
            $table->integer('pi_rev_no')->nullable(false)->default(0)->change();
            $table->integer('order_placed_by')->nullable(false)->default(0)->change();
            $table->integer('edit_permission')->nullable(false)->default(0)->change();
            $table->integer('cancel_ord_flag')->nullable(false)->default(0)->change();
            $table->integer('pen_pro_flag')->nullable(false)->default(0)->change();
            $table->integer('new_ord_flag')->nullable(false)->default(0)->change();
            $table->integer('app_ord_flag')->nullable(false)->default(0)->change();
            $table->integer('app_pipo_flag')->nullable(false)->default(0)->change();
            $table->integer('pen_ord_flag')->nullable(false)->default(0)->change();
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orchestra_heat_transfer_orders', function (Blueprint $table) {
            $table->integer('is_order_count_empty')->nullable(true)->default(null)->change();
            $table->integer('pi_rev_no')->nullable(true)->default(null)->change();
            $table->integer('order_placed_by')->nullable(true)->default(null)->change();
            $table->integer('edit_permission')->nullable(true)->default(null)->change();
            $table->integer('cancel_ord_flag')->nullable(true)->default(null)->change();
            $table->integer('pen_pro_flag')->nullable(true)->default(null)->change();
            $table->integer('new_ord_flag')->nullable(true)->default(null)->change();
            $table->integer('app_ord_flag')->nullable(true)->default(null)->change();
            $table->integer('app_pipo_flag')->nullable(true)->default(null)->change();
            $table->integer('pen_ord_flag')->nullable(true)->default(null)->change();
       });
    }
}
