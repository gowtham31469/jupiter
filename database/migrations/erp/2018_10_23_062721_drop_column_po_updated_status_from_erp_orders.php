<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropColumnPoUpdatedStatusFromErpOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('erp_orders', function (Blueprint $table) {
            $table->dropColumn('po_updated_status');
        });
        Schema::table('erp_orders', function (Blueprint $table) {
            $table->tinyInteger('po_updated_status')->default(0);
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
            $table->addColumn('po_updated_status');
        });
        Schema::table('erp_orders', function (Blueprint $table) {
            $table->dropColumn('po_updated_status');
        });
    }
}
