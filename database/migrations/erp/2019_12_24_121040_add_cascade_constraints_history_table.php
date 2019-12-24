<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCascadeConstraintsHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
 	Schema::table('history', function (Blueprint $table) {
		#$table->dropForeign('history_erp_order_details_id_foreign');
		$table->foreign('erp_order_details_id')
		->references('id')->on('erp_order_details')
		->onDelete('cascade');
        });

        Schema::table('event_history', function (Blueprint $table) {
		$table->dropForeign('event_history_history_id_foreign');
		$table->foreign('history_id')
		->references('id')->on('history')
		->onDelete('cascade');
 		 $table->integer('created_by')->unsigned()->change();
           	 $table->foreign('created_by')->references('id')->on('erpusers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('history', function (Blueprint $table) {
                 $table->integer('erp_order_details_id')->onDelete('null')->change();
        });

        Schema::table('event_history', function (Blueprint $table) {
                 $table->integer('history_id')->onDelete('null')->change();
		 $table->dropForeign('event_history_created_by_foreign');
        });
    }
}
