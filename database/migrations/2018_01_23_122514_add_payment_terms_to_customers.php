<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPaymentTermsToCustomers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('payment_terms')->default('null')->after('pan_number');
            $table->string('place_of_supply')->default('null')->after('payment_terms');
            $table->boolean('is_advance_payment')->default('0')->after('place_of_supply');
            $table->boolean('is_freight_cost_free')->default('0')->after('is_advance_payment');
            $table->integer('freight_cost')->default('0')->after('is_freight_cost_free');
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
            $table->dropColumn('payment_terms');
            $table->dropColumn('place_of_supply');
            $table->dropColumn('is_advance_payment');
            $table->dropColumn('is_freight_cost_free');
            $table->dropColumn('freight_cost');
        });
    }
}
