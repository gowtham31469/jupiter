<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPartialStatusToErpOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      	 Schema::table('erp_orders', function($table) {
        	$table->tinyInteger('partial_status');
		$table->tinyInteger('po_updated_status');
   	 });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('erp_orders', function($table) {
        	$table->dropColumn('partial_status');
		$table->dropColumn('po_updated_status');
   	 });
    }
}
