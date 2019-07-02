<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropColumnPartialStatusFromErpOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('erp_orders', function (Blueprint $table) {
            $table->dropColumn('partial_status');
        });
         Schema::table('erp_order_details', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('erp_orders', function (Blueprint $table) {
             $table->addColumn('partial_status');
        });
        Schema::table('erp_order_details', function (Blueprint $table) {
           $table->addColumn('status');
        });
    }
}
