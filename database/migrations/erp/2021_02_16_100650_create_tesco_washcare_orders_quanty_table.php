<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTescoWashcareOrdersQuantyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tesco_washcare_orders_quanty', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('washcare_order_id')->index();
            $table->unsignedInteger('size_id')->index();
            $table->string('uk_ean')->nullable(true);
            $table->string('ce_ean')->nullable(true);
            $table->integer('quantity')->nullable(true);
            $table->timestamps();

            $table->foreign('washcare_order_id')->references('id')->on('tesco_washcare_orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tesco_washcare_orders_quanty');
    }
}
