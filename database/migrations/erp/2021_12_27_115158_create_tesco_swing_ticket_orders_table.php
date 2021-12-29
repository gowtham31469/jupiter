<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTescoSwingTicketOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tesco_swing_ticket_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('template_id')->index();
            $table->string('order_id')->nullable(true)->unique();
            $table->string('zone')->nullable(true)->index();
            $table->string('template_type')->nullable(true);   
            $table->string('po_number')->nullable(true);
            $table->string('uk_style_ref')->nullable(true);
            $table->string('ce_style_ref')->nullable(true);
            $table->unsignedInteger('season_code_id')->nullable(true)->index();
            $table->date('delivery_date')->nullable(true);
            $table->integer('eqos_site_code')->nullable(true);
            $table->unsignedInteger('tesco_supplier_id')->nullable(true)->index();
            $table->unsignedInteger('tesco_metadata_supplier_id')->nullable(true)->index();
            $table->string('ocn_number')->nullable(true);
            $table->unsignedInteger('country_of_origin_id')->nullable(true)->index();
            $table->json('country_of_origin_text')->nullable();
            $table->unsignedInteger('primary_size_id')->nullable(true)->index();
            $table->string('order_status')->nullable(true)->index();
            $table->string('order_flag')->default('newOrder');
            $table->smallInteger('is_order_count_empty')->default(1);
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
            $table->string('24_char_desc')->nullable(true);
            $table->bigInteger('cz_selling_price')->nullable(true);
            $table->bigInteger('sk_selling_price')->nullable(true);
            $table->bigInteger('hu_selling_price')->nullable(true);
            $table->unsignedInteger('wee_symbol_id')->nullable(true)->index();
            $table->unsignedInteger('woolmark_logo_id')->nullable(true)->index();
            $table->unsignedInteger('product_desc_id')->nullable(true)->index();
            $table->json('product_desc_text')->nullable();
            $table->unsignedInteger('product_benefit_id')->nullable(true)->index();
            $table->json('product_benefit_text')->nullable();
            $table->unsignedInteger('mm_product_benefit_id')->nullable(true)->index();
            $table->json('mm_product_benefit_text')->nullable();
            $table->boolean('is_ce_mark_required')->default(0);
            $table->boolean('is_machine_washable')->default(0);
            $table->timestamps();

            $table->foreign('pi_id')->references('id')->on('profoma_invoices')->onDelete('cascade');
            $table->foreign('template_id')->references('id')->on('tesco_item_master')->onDelete('cascade');
            $table->foreign('tesco_supplier_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('tesco_metadata_supplier_id')->references('id')->on('tesco_suppliers')->onDelete('cascade');
            $table->foreign('season_code_id')->references('id')->on('tesco_season_codes')->onDelete('cascade');
            $table->foreign('country_of_origin_id')->references('id')->on('tesco_countries')->onDelete('cascade');
            $table->foreign('primary_size_id')->references('id')->on('tesco_size_department')->onDelete('cascade');
            $table->foreign('wee_symbol_id')->references('id')->on('tesco_wee_symbols')->onDelete('cascade');
            $table->foreign('woolmark_logo_id')->references('id')->on('tesco_woolmark_logos')->onDelete('cascade');
            $table->foreign('product_desc_id')->references('id')->on('tesco_product_descriptions')->onDelete('cascade');
            $table->foreign('product_benefit_id')->references('id')->on('tesco_product_benefits')->onDelete('cascade');
            $table->foreign('mm_product_benefit_id')->references('id')->on('tesco_mm_product_benefits')->onDelete('cascade');

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
        Schema::dropIfExists('tesco_swing_ticket_orders');

    }
}
