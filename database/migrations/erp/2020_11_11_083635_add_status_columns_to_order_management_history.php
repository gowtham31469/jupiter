<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusColumnsToOrderManagementHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_management_history', function (Blueprint $table) {
            // artwork, po and pi status default value is 0, 
            // which ,means " artwork, po and pi pending".
            // order status 1 for "saved" state.
            $table->integer('order_status')->nullable(true)->default(1);
            $table->foreign('order_status')->references('id')->on('orders_status')->onDelete('cascade');

            $table->boolean('artwork_status')->nullable(true)->default(0);
            $table->boolean('po_status')->nullable(true)->default(0);
            $table->boolean('pi_status')->nullable(true)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_management_history', function (Blueprint $table) {
            $table->dropForeign('order_management_history_order_status_foreign');
            $table->dropColumn('order_status');

            $table->dropColumn('artwork_status');

            $table->dropColumn('po_status');

            $table->dropColumn('pi_status');
        });
    }
}
