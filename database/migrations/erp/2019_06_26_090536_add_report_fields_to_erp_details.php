<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReportFieldsToErpDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('erp_order_details', function (Blueprint $table) {
              $table->timestamp('invoice_created_date')->nullable();
              $table->timestamp('order_hold_date')->nullable();
              $table->integer('po_manager_approved_by')->nullable();
              $table->integer('jc_created_by')->nullable();
              $table->integer('invoice_created_by')->nullable();
              $table->integer('order_hold_by')->nullable();
              $table->integer('order_cancelled_by')->nullable();
              $table->string('jc_number')->nullable();
              $table->string('invoice_number')->nullable();
              $table->text('reason')->nullable();
              $table->timestamp('po_reapproved_date')->nullable();
              $table->timestamp('po_rejected_date')->nullable();

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
            $table->dropColumn('invoice_created_date');
            $table->dropColumn('order_hold_date');
            $table->dropColumn('po_manager_approved_by');
            $table->dropColumn('jc_created_by');
            $table->dropColumn('invoice_created_by');
            $table->dropColumn('order_hold_by');
            $table->dropColumn('order_cancelled_by');
            $table->dropColumn('jc_number');
            $table->dropColumn('invoice_number');
            $table->dropColumn('reason');
            $table->dropColumn('po_reapproved_date');
            $table->dropColumn('po_rejected_date');
        });
    }
}
