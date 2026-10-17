<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPoVerifyIndexesToErpOrdersTables extends Migration
{
    /**
     * Run the migrations.
     * Indexes used by the PO Manager verify list (PoManagerController::getPoVerifyOrders).
     *
     * @return void
     */
    public function up()
    {
        Schema::table('erp_order_details', function (Blueprint $table) {
            $table->index(['company_id', 'template_id', 'erp_order_id'], 'idx_eod_company_template_order');
        });

        Schema::table('erp_orders', function (Blueprint $table) {
            $table->index(['buyer_id', 'status'], 'idx_eo_buyer_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('erp_order_details', function (Blueprint $table) {
            $table->dropIndex('idx_eod_company_template_order');
        });

        Schema::table('erp_orders', function (Blueprint $table) {
            $table->dropIndex('idx_eo_buyer_status');
        });
    }
}
