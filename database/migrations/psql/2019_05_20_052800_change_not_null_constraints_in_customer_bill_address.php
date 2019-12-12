<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeNotNullConstraintsInCustomerBillAddress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_bill_address', function (Blueprint $table) {
            $table->text('address')->nullable(true)->change();
            $table->integer('pincode')->nullable(true)->change();
            $table->string('country')->nullable(true)->change();
            $table->string('email')->nullable(true)->change();
            $table->bigInteger('telephone')->nullable(true)->change();
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
            $table->text('address')->nullable(false)->change();
            $table->integer('pincode')->nullable(false)->change();
            $table->string('country')->nullable(false)->change();
            $table->string('email')->nullable(false)->change();
            $table->bigInteger('telephone')->nullable(false)->change();
        });
    }
}
