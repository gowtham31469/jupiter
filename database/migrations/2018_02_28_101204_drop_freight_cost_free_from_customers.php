<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropFreightCostFreeFromCustomers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('is_freight_cost_free');
            $table->dropColumn('freight_cost');
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
            $table->boolean('is_freight_cost_free')->default('0')->after('nominated_forwarder');
            $table->integer('freight_cost')->after('is_freight_cost_free');
        });
    }
}
