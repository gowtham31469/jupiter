<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToOrchestraStandardCartonStickerOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orchestra_standard_carton_sticker_orders', function (Blueprint $table) {
            $table->string('order_flag')->nullable();
            $table->integer('is_order_count_empty')->default(0);
            $table->integer('pi_rev_no')->default(0);
            $table->integer('order_placed_by')->default(0);
            $table->integer('edit_permission')->default(0);
            $table->integer('cancel_ord_flag')->default(0);
            $table->integer('pen_pro_flag')->default(0);
			$table->integer('new_ord_flag')->default(0);
			$table->integer('app_ord_flag')->default(0);
			$table->integer('app_pipo_flag')->default(0);
			$table->integer('pen_ord_flag')->default(0);
			$table->timestamp('order_created_at')->after('pen_ord_flag')->nullable();
			$table->timestamp('order_approved_at')->after('order_created_at')->nullable();
			$table->timestamp('order_cancelled_at')->after('order_approved_at')->nullable();		
			$table->timestamp('po_uploaded_at')->after('order_cancelled_at')->nullable();
			$table->timestamp('pi_approved_at')->after('po_uploaded_at')->nullable();
			$table->integer('pi_id')->after('pi_approved_at')->default(0);
			$table->string('pi_file')->after('pi_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orchestra_standard_carton_sticker_orders', function (Blueprint $table) {
            $table->dropColumn('order_flag')->nullable();
            $table->dropColumn('is_order_count_empty')->default(0);
            $table->dropColumn('pi_rev_no')->default(0);
            $table->dropColumn('order_placed_by')->default(0);
            $table->dropColumn('edit_permission')->default(0);
            $table->dropColumn('cancel_ord_flag')->default(0);
            $table->dropColumn('pen_pro_flag')->default(0);
			$table->dropColumn('new_ord_flag')->default(0);
			$table->dropColumn('app_ord_flag')->default(0);
			$table->dropColumn('app_pipo_flag')->default(0);
			$table->dropColumn('pen_ord_flag')->default(0);
			$table->dropColumn('order_created_at')->after('pen_ord_flag')->nullable();
			$table->dropColumn('order_approved_at')->after('order_created_at')->nullable();
			$table->dropColumn('order_cancelled_at')->after('order_approved_at')->nullable();		
			$table->dropColumn('po_uploaded_at')->after('order_cancelled_at')->nullable();
			$table->dropColumn('pi_approved_at')->after('po_uploaded_at')->nullable();
			$table->dropColumn('pi_id')->after('pi_approved_at')->default(0);
			$table->dropColumn('pi_file')->after('pi_id')->nullable();
        });
    }
}
