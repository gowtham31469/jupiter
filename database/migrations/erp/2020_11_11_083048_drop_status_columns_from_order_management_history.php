<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropStatusColumnsFromOrderManagementHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_management_history', function (Blueprint $table) {
            $table->dropIndex('order_management_history_order_status_index');
            $table->dropColumn('order_status');
            $table->dropIndex('order_management_history_artwork_status_index');
            $table->dropColumn('artwork_status');
            $table->dropIndex('order_management_history_po_status_index');
            $table->dropColumn('po_status');
            $table->dropIndex('order_management_history_pi_status_index');
            $table->dropColumn('pi_status');

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
            $table->string('order_status')->default('Order Saved')->index();
            $table->string('artwork_status')->default('Artwork Approval Pending')->index();
            $table->string('po_status')->default('PO Upload Pending')->index();
            $table->string('pi_status')->default('PI Approval Pending')->index();
        });
    }
}
