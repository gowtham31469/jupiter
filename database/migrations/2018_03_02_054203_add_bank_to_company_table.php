<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBankToCompanyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company', function (Blueprint $table)
        {
            $table->string('account_no')->default('null')->after('gstin');
            $table->string('account_holder')->default('null')->after('account_no');
            $table->string('ifsc_code')->default('null')->after('account_holder');
            $table->string('bank')->default('null')->after('ifsc_code');
            $table->string('branch')->default('null')->after('bank');
            $table->string('account_type')->default('null')->after('branch');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('company', function (Blueprint $table)
        {
            $table->dropColumn('account_no');
            $table->dropColumn('account_holder');
            $table->dropColumn('ifsc_code');
            $table->dropColumn('bank');
            $table->dropColumn('branch');
            $table->dropColumn('account_type');
        });
    }
}
