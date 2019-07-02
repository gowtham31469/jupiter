<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInsideTamilnaduToTescoTemplates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('tesco_template', function($table) {
     	   $table->string('outside_tamilnadu')->after('tax');
	   $table->string('inside_tamilnadu')->after('outside_tamilnadu');
   	 });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::table('tesco_template', function($table) {
       		$table->dropColumn('outside_tamilnadu');
		$table->dropColumn('inside_tamilnadu');
    	});
    }
}
