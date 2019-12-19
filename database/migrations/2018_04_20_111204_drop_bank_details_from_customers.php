<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropBankDetailsFromCustomers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('account_no');
            $table->dropColumn('account_holder');
            $table->dropColumn('ifsc_code');
            $table->dropColumn('bank');
            $table->dropColumn('branch');
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
            $table->string('account_no')->default('null')->after('courier_choice');
            $table->string('account_holder')->default('null')->after('account_no');
            $table->string('ifsc_code')->default('null')->after('account_holder');
            $table->string('bank')->default('null')->after('ifsc_code');
            $table->string('branch')->default('null')->after('bank');
        });
    }
}
