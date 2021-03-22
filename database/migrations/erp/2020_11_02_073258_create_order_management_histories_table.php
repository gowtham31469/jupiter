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
            $table->integer('order_status')->nullable(true)->default(1);
            $table->foreign('order_status')->references('id')->on('orders_status')->onDelete('cascade')->index();
            $table->boolean('artwork_status')->nullable(true)->default(0)->index();
            $table->boolean('po_status')->nullable(true)->default(0)->index();
            $table->boolean('pi_status')->nullable(true)->default(0)->index();
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
