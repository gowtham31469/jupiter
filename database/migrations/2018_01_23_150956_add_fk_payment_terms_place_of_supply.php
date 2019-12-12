<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFkPaymentTermsPlaceOfSupply extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->foreign('payment_terms')->references('id')->on('payment_terms');
            $table->foreign('place_of_supply')->references('id')->on('place_of_supply');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropForeign('customers_payment_terms_foreign');
            $table->dropForeign('customers_place_of_supply_foreign');
        });
    }
}
