<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderManagementHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_management_history', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('buyer_id')->nullable()->index();
            $table->foreign('buyer_id')->references('id')->on('buyers')->onDelete('cascade');

            $table->bigInteger('customer_id')->nullable()->index();
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');

            $table->string('season')->nullable();
            $table->string('material')->nullable();
            $table->string('sub_material')->nullable();
            $table->string('style')->nullable()->index();
            $table->string('reference')->nullable()->index();
            $table->string('customer_po')->nullable()->index();
            $table->string('buyer_po')->nullable()->index();
            $table->string('ocn_number')->nullable()->index();
            $table->string('order_table_name')->nullable();
            $table->bigInteger('order_table_id')->nullable();
            $table->string('order_status')->default('Order Saved')->index();
            $table->string('artwork_status')->default('Artwork Approval Pending')->index();
            $table->string('po_status')->default('PO Upload Pending')->index();
            $table->string('pi_status')->default('PI Approval Pending')->index();
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
        Schema::dropIfExists('order_management_history');
    }
}
