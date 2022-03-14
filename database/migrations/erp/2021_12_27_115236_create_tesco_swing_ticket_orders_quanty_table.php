<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTescoSwingTicketOrdersQuantyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tesco_swingticket_orders_quanty', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('swingticket_order_id')->index();
            $table->unsignedInteger('size_id')->index();
            $table->string('ean_barcode')->nullable(true);
            $table->integer('quantity')->nullable(true);
            $table->timestamps();

            $table->foreign('swingticket_order_id')->references('id')->on('tesco_swingticket_orders')->onDelete('cascade');
            $table->foreign('size_id')->references('id')->on('tesco_primary_size')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tesco_swingticket_orders_quanty');
    }
}
