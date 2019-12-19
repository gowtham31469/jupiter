<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRejectedDateToOrchestrawashcareLabelOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orchestra_wash_care_label_orders', function (Blueprint $table) {
            $table->date('rejected_date')->after('approved_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orchestra_wash_care_label_orders', function (Blueprint $table) {
            $table->dropColumn('rejected_date');
        });
    }
}
