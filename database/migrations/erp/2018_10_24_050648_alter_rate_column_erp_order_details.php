<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterRateColumnErpOrderDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('erp_order_details', function (Blueprint $table) {
           $table->dropColumn('rate');
        });
        Schema::table('erp_order_details', function (Blueprint $table) {
           $table->double('rate')->default(0);
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
            $table->addColumn('rate');
        });
    }
}
