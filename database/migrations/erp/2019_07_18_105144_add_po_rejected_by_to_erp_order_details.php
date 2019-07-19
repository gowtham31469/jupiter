<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPoRejectedByToErpOrderDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('erp_order_details', function (Blueprint $table) {
             $table->integer('po_rejected_by')->nullable()->unsigned();
             $table->foreign('po_rejected_by')->references('id')->on('erpusers');
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
            $table->dropForeign('erp_order_details_po_rejected_by_foreign');
            $table->dropColumn('po_rejected_by');
        });
    }
}
