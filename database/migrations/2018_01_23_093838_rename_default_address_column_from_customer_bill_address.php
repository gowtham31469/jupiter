<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameDefaultAddressColumnFromCustomerBillAddress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_bill_address', function (Blueprint $table) {
           $table->renameColumn('default_address', 'is_billing_address');
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
            $table->renameColumn('is_billing_address', 'default_address');
        });
    }
}
