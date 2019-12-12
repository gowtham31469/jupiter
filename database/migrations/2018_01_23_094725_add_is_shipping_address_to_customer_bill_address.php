<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsShippingAddressToCustomerBillAddress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_bill_address', function (Blueprint $table) {
            $table->boolean('is_shipping_address')->default('0')->after('is_billing_address');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_bill_address', function (Blueprint $table) {
            $table->dropColumn('is_shipping_address');
        });
    }
}
