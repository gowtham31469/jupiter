<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropMachineDispModeFinishTypeTeethNoFromJobcardMainTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jobcard_main_table', function (Blueprint $table) {
            $table->dropColumn('dispatch_mode');
            $table->dropColumn('machine');
            $table->dropColumn('finish_type');
            $table->dropColumn('teeth_no');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jobcard_main_table', function (Blueprint $table) {
            $table->string('dispatch_mode');
            $table->integer('machine');
            $table->integer('finish_type');
            $table->integer('teeth_no');
        });
    }
}
