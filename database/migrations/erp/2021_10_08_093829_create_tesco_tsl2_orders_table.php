<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTescoTsl2OrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tesco_tsl2_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('template_id')->nullable(true)->index();
            $table->string('order_id')->nullable(true);
            $table->string('zone')->nullable(true)->index();
            $table->string('template_type')->nullable(true);   
            $table->string('po_number')->nullable(true);
            $table->string('uk_style_ref')->nullable(true);
            $table->string('ce_style_ref')->nullable(true);
            $table->string('season_code')->nullable(true);
            $table->mediumText('short_description')->nullable(true);
            $table->string('eqos_site_code')->nullable(true);
            $table->string('manufacture_date')->nullable(true);
            $table->date('delivery_date')->nullable(true);
            $table->bigInteger('tesco_department_id')->nullable(true)->index();
            $table->bigInteger('tesco_brand_id')->nullable(true)->index();
            $table->bigInteger('tesco_supplier_id')->nullable(true)->index();
            $table->bigInteger('tesco_metadata_supplier_id')->nullable(true)->index();
            $table->smallInteger('rfid_compliant')->default('0');
            $table->smallInteger('tag_at_source')->default('0');
            $table->string('order_status')->nullable();
            $table->string('order_flag')->default('newOrder');
            $table->boolean('is_order_count_empty')->default(1);
            $table->string('purchase_order_no')->nullable(true);
            $table->string('po_file')->nullable(true);
            $table->string('po_status')->nullable(true)->index();
            $table->string('pi_status')->nullable(true)->index();
            $table->integer('pi_rev_no')->default(0)->nullable(true)->index();
            $table->integer('order_placed_by')->default(null)->index();
            $table->integer('edit_permission')->default(0);
            $table->integer('cancel_ord_flag')->default(0);
            $table->integer('pen_ord_flag')->default(0);
            $table->integer('new_ord_flag')->default(0);
            $table->integer('app_ord_flag')->default(0);
            $table->integer('app_pipo_flag')->default(0);
            $table->integer('pen_pro_flag')->default(0);
            $table->timestamp('order_created_at')->nullable(true)->index();
            $table->timestamp('order_approved_at')->nullable(true)->index();
            $table->timestamp('order_cancelled_at')->nullable(true)->index();
            $table->string('ocn_number')->nullable(true);
            $table->timestamp('po_uploaded_at')->nullable(true)->index();
            $table->timestamp('pi_approved_at')->nullable(true)->index();
            $table->unsignedInteger('pi_id')->nullable(true)->index();
            $table->string('pi_file')->nullable(true);
            $table->string('full_description')->nullable(true);
            $table->integer('total_qty')->nullable(true);
            $table->timestamps();

            $table->foreign('pi_id')->references('id')->on('profoma_invoices')->onDelete('cascade');
            $table->foreign('template_id')->references('id')->on('tesco_item_master')->onDelete('cascade');
            $table->foreign('tesco_department_id')->references('id')->on('tesco_department')->onDelete('cascade');
            $table->foreign('tesco_brand_id')->references('id')->on('tesco_brand')->onDelete('cascade');
            $table->foreign('tesco_supplier_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('tesco_metadata_supplier_id')->references('id')->on('tesco_suppliers')->onDelete('cascade');

            $table->index(['po_number', 'uk_style_ref']);
            $table->index(['po_number', 'ce_style_ref']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tesco_tsl2_orders');
    }
}
