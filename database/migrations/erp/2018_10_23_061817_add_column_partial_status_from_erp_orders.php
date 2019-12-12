<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnPartialStatusFromErpOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('erp_orders', function (Blueprint $table) {
            $table->tinyInteger('partial_status')->default(0);
            $table->integer('status')->unsigned();
            $table->foreign('status')->references('id')->on('erp_status')->onDelete('cascade');
        });
        Schema::table('erp_order_details', function (Blueprint $table) {
            $table->integer('status')->unsigned();
            $table->foreign('status')->references('id')->on('erp_status')->onDelete('cascade');
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
            $table->dropColumn('partial_status');
            $table->dropColumn('status');
        });
        Schema::table('erp_order_details', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
