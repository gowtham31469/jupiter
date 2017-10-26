<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDefaultAddressToCustomerBillAddress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_bill_address', function (Blueprint $table) {
            $table->boolean('default_address')->after('fax');
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
            $table->dropColumn('default_address');
        });
    }
}
