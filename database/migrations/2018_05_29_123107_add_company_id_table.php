<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCompanyIdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::table('customer_profile', function (Blueprint $table) {
            $table->integer('company_id')->unsigned()->after('role_id');
            $table->tinyInteger('status')->after('company_id');
            $table->foreign('company_id')->references('id')->on('customers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::table('customer_profile', function (Blueprint $table) {
           $table->dropForeign('company_id');
           $table->dropColumn('company_id');
           $table->dropColumn('status');
        });
    }
}
