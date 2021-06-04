<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddChangeToTescoCareAdditionalInstructionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tesco_care_additional_instructions', function (Blueprint $table) {
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
        Schema::table('tesco_care_additional_instructions', function (Blueprint $table) {
            //
        });
    }
}
