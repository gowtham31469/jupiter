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
	    $table->bigInteger('item_id')->nullable(true)->index();
	    $table->bigInteger('template_id')->nullable(true)->index();
	    $table->bigInteger('buyer_id')->nullable(true)->index();
            $table->bigInteger('tesco_supplier_id')->nullable(true)->index();
            $table->string('order_id')->nullable(true);
            $table->string('po_number')->nullable(true);
            $table->string('ocn_number')->nullable(true);
            $table->string('style_ref')->nullable(true);
            $table->string('season_code')->nullable(true);
            $table->string('color')->nullable(true);
            $table->string('composition')->nullable(true);
            $table->date('delivery_date')->nullable(true);
            $table->string('order_status')->nullable();
            $table->string('order_flag')->default('newOrder');
            $table->string('order_approved_flag')->nullable(true);
            $table->boolean('is_order_count_empty')->default(1);
            $table->string('purchase_order_no')->nullable(true);
            $table->integer('order_placed_by')->default(0)->index();
            $table->integer('edit_permission')->default(0);
            $table->timestamp('order_created_at')->nullable(true)->index();
            $table->timestamp('order_approved_at')->nullable(true)->index();
            $table->timestamp('order_cancelled_at')->nullable(true)->index();
            $table->integer('total_qty')->nullable(true);
            $table->timestamps();
            $table->foreign('template_id')->references('id')->on('tesco_item_master')->onDelete('cascade');
            $table->foreign('tesco_supplier_id')->references('id')->on('customers')->onDelete('cascade');
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
