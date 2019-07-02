<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDeliveryAtToInvoiceTable2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoice', function (Blueprint $table) {
            $table->dropColumn('delivery_at');
        });
        Schema::table('invoice', function (Blueprint $table) {
            $table->integer('delivery_at')->unsigned()->nullable();;
            $table->foreign('delivery_at')->references('id')->on('invoice_address');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoice', function (Blueprint $table) {
            $table->date('delivery_at')->nullable();
        });
        Schema::table('invoice', function (Blueprint $table) {
            $table->dropColumn('delivery_at');
        });
    }
}
