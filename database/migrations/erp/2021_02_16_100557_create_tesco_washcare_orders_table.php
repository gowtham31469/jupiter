<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTescoWashcareOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tesco_washcare_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('template_id')->index();
            $table->string('order_id')->nullable(true)->unique();
            $table->string('zone')->nullable(true)->index();
            $table->string('template_type')->nullable(true);   
            $table->string('po_number')->nullable(true);
            $table->string('uk_style_ref')->nullable(true);
            $table->string('ce_style_ref')->nullable(true);
            $table->string('season_code')->nullable(true);
            $table->date('delivery_date')->nullable(true);
            $table->integer('eqos_site_code')->nullable(true);
            $table->unsignedInteger('tesco_supplier_id')->nullable(true)->index();
            $table->unsignedInteger('tesco_metadata_supplier_id')->nullable(true)->index();
            $table->string('ocn_number')->nullable(true);
            $table->string('fabric_batch_code')->nullable(true);
            $table->string('leather_suede_code')->nullable(true);
            $table->unsignedInteger('kaff_id')->nullable(true)->index();
            $table->unsignedInteger('country_of_origin_id')->nullable(true)->index();
            $table->unsignedInteger('kids_warning_id')->nullable(true)->index();
            $table->unsignedInteger('primary_size_id')->nullable(true)->index();
            $table->unsignedInteger('tog_id')->nullable(true)->index();
            $table->unsignedInteger('wash_symbol_id')->nullable(true)->index();
            $table->unsignedInteger('bleach_symbol_id')->nullable(true)->index();
            $table->unsignedInteger('dry_symbol_id')->nullable(true)->index();
            $table->unsignedInteger('ironing_symbol_id')->nullable(true)->index();
            $table->unsignedInteger('dryclean_symbol_id')->nullable(true)->index();
            $table->boolean('include_btn_sew_space')->default(0);
            $table->boolean('exclusive_trims')->default(0);
            $table->boolean('contains_non_textile_part')->default(0);
            $table->boolean('part_of_set')->default(0);
            $table->boolean('bs_en_declaration')->default(0);
            $table->boolean('baby_sleeping_bag_symbol')->default(0);
            $table->string('order_status')->nullable(true)->index();
            $table->string('order_flag')->default('newOrder');
            $table->boolean('is_order_count_empty')->default(1);
            $table->string('po_file')->nullable(true);
            $table->string('purchase_order_no')->nullable(true);
            $table->string('po_status')->nullable(true)->index();
            $table->string('pi_file')->nullable(true);
            $table->unsignedInteger('pi_id')->nullable(true)->index();
            $table->integer('pi_rev_no')->default(0);
            $table->string('pi_status')->nullable(true)->index();
            $table->integer('total_qty')->nullable(true);
            $table->boolean('edit_permission')->default(0);
            $table->boolean('pen_pro_flag')->default(0);
            $table->boolean('new_ord_flag')->default(0);
            $table->boolean('app_ord_flag')->default(0);
            $table->boolean('app_pipo_flag')->default(0);
            $table->boolean('pen_ord_flag')->default(0);
            $table->boolean('cancel_ord_flag')->default(0);
            $table->integer('order_placed_by')->default(null)->nullable(true)->index();
            $table->timestamp('order_created_at')->nullable(true)->index();
            $table->timestamp('order_approved_at')->nullable(true)->index();
            $table->timestamp('order_cancelled_at')->nullable(true)->index();
            $table->timestamp('po_uploaded_at')->nullable(true)->index();
            $table->timestamp('pi_approved_at')->nullable(true)->index();
            $table->timestamps();

            $table->foreign('pi_id')->references('id')->on('profoma_invoices')->onDelete('cascade');
            $table->foreign('template_id')->references('id')->on('tesco_item_master')->onDelete('cascade');
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
        Schema::dropIfExists('tesco_washcare_orders');
    }
}
