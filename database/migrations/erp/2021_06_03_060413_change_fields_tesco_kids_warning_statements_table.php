<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeFieldsTescoKidsWarningStatementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tesco_kids_warning_statements', function (Blueprint $table) {
           	$table->text('english')->change();
		$table->text('polish')->change();
		$table->text('czech')->change();
		$table->text('slovak')->change();
		$table->text('hungarian')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tesco_kids_warning_statements', function (Blueprint $table) {
            //
        });
    }
}
