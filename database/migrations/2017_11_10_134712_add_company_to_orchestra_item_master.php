<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCompanyToOrchestraItemMaster extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::table('orchestra_item_master', function($table) {
		$table->integer('company_id')->unsigned();
		$table->string('ref_no');
   	 });

	Schema::table('orchestra_item_master',function(Blueprint $table){
             $table->foreign('company_id')
                ->references('id')->on('company')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orchestra_item_master', function($table) {
       		$table->dropColumn('company_id');
		$table->dropColumn('ref_no');
   	 });
    }
}
