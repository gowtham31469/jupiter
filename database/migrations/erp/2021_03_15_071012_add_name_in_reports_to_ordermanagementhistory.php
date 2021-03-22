<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNameInReportsToOrdermanagementhistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_management_history', function (Blueprint $table) {
            $table->string('name_in_reports')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_management_history', function (Blueprint $table) {
            $table->dropColumn('name_in_reports');
        });
    }
}
