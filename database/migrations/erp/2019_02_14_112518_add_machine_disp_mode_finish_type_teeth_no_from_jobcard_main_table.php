<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMachineDispModeFinishTypeTeethNoFromJobcardMainTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jobcard_main_table', function (Blueprint $table) {
            $table->integer('dispatch_mode')->unsigned()->nullable();
            $table->integer('machine')->unsigned()->nullable();
            $table->integer('finish_type')->unsigned()->nullable();
            $table->integer('teeth_no')->unsigned()->nullable();

            $table->foreign('dispatch_mode')->references('id')->on('erp_dispatch_mode')->onDelete('cascade');
            $table->foreign('machine')->references('id')->on('erp_machines')->onDelete('cascade');
            $table->foreign('finish_type')->references('id')->on('erp_machines')->onDelete('cascade');
            $table->foreign('teeth_no')->references('id')->on('erp_teeth_no')->onDelete('cascade');
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
            $table->dropColumn('dispatch_mode');
            $table->dropColumn('machine');
            $table->dropColumn('finish_type');
            $table->dropColumn('teeth_no');

        });
    }
}
