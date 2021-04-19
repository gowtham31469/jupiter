<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropTescoCareOrdersAdditionalInstructionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('tesco_washcare_orders_additional_care_instructions');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('tesco_washcare_orders_additional_care_instructions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('washcare_order_id')->index();
            $table->unsignedInteger('additional_care_id')->nullable(true)->index();
            $table->json('additional_care_text')->nullable();
            $table->timestamps();

            $table->foreign('washcare_order_id')->references('id')->on('tesco_washcare_orders')->onDelete('cascade');
            $table->foreign('additional_care_id')->references('id')->on('tesco_care_additional_instructions')->onDelete('cascade');
        });
    }
}
