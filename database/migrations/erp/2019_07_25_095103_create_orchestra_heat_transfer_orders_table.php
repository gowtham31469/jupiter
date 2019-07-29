<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrchestraHeatTransferOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orchestra_heat_transfer_orders', function (Blueprint $table) {
            $table->increments('id');
   	    $table->string('uvc');
	    $table->integer('template_id');
	    $table->integer('customer_id');
	    $table->integer('pi_id')->nullable();
 	    $table->date('delivery_date')->nullable();
	    $table->longText('svg_path')->nullable();
   	    $table->string('po_number')->nullable();
   	    $table->string('po')->nullable();
   	    $table->string('po_file')->nullable();
   	    $table->string('pi_file')->nullable();
            $table->string('status');
            $table->string('pi_status');
            $table->string('po_status');
            $table->string('order_flag')->nullable();
	    $table->integer('is_order_count_empty')->nullable();
	    $table->integer('pi_rev_no')->nullable();
	    $table->integer('order_placed_by')->nullable();
	    $table->integer('edit_permission')->nullable();
	    $table->integer('cancel_ord_flag')->nullable();
	    $table->integer('pen_pro_flag')->nullable();
	    $table->integer('new_ord_flag')->nullable();
	    $table->integer('app_ord_flag')->nullable();
	    $table->integer('app_pipo_flag')->nullable();
	    $table->integer('pen_ord_flag')->nullable();
	    $table->timestamp('order_created_at')->nullable();
	    $table->timestamp('order_approved_at')->nullable();
	    $table->timestamp('order_cancelled_at')->nullable();
	    $table->timestamp('po_uploaded_at')->nullable();
	    $table->timestamp('pi_approved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orchestra_heat_transfer_orders');
    }
}
