<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNonArtworkOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('non_artwork_orders', function (Blueprint $table) {
            $table->increments('id');
	    $table->integer('template_id')->unsigned();
$table->integer('buyer_id')->unsigned();
            $table->string('order_id');
            $table->string('zone')->nullable();
            $table->string('template_type')->nullable();
            $table->string('po_number')->nullable();
            $table->string('uk_style_ref')->nullable();
            $table->string('ce_style_ref')->nullable();
            $table->string('season_code')->nullable();
            $table->longText('short_description')->nullable();
            $table->string('eqos_site_code')->nullable();
            $table->string('manufacture_date')->nullable();
            $table->date('delivery_date')->nullable();
            $table->bigInteger('department_id')->nullable();
            $table->bigInteger('brand_id')->nullable();
            $table->bigInteger('supplier_id')->nullable();
            $table->bigInteger('metadata_supplier_id')->nullable();
	    $table->smallInteger('rfid_compliant')->nullable();
	    $table->smallInteger('tag_at_source')->nullable();
$table->string('order_status');
$table->string('order_flag');
$table->smallInteger('is_order_count_empty')->default('0');
$table->string('purchase_order_no')->nullable();
$table->string('po_file')->nullable();
$table->string('po_status')->nullable();
$table->string('pi_status')->nullable();
	    $table->integer('pi_rev_no')->default('0');
	    $table->integer('order_placed_by')->default('0');
	    $table->integer('edit_permission')->default('0');
	    $table->integer('cancel_ord_flag')->default('0');
	    $table->integer('pen_pro_flag')->default('0');
	    $table->integer('new_ord_flag')->default('0');
	    $table->integer('app_ord_flag')->default('0');
	    $table->integer('app_pipo_flag')->default('0');
	    $table->integer('pen_ord_flag')->default('0');
$table->timestamp('order_created_at')->nullable();
$table->timestamp('order_approved_at')->nullable();
$table->timestamp('order_cancelled_at')->nullable();
$table->string('ocn_number')->nullable();
$table->timestamp('po_uploaded_at')->nullable();
$table->timestamp('pi_approved_at')->nullable();
$table->integer('pi_id')->nullable();
$table->string('pi_file')->nullable();
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
        Schema::dropIfExists('non_artwork_orders');
    }
}
