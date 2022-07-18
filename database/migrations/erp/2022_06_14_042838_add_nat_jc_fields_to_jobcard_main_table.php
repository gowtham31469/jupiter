<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNatJcFieldsToJobcardMainTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jobcard_main_table', function (Blueprint $table) {
            $table->date('aw_approve_date')->nullable();
            $table->date('act_del_date')->nullable();
            $table->integer('no_of_repeats')->nullable();
            $table->double('print_length')->nullable(); 
            $table->string('composition')->nullable();
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
            $table->dropColumn('aw_approve_date');
            $table->dropColumn('act_del_date');
            $table->dropColumn('no_of_repeats');
            $table->dropColumn('print_length'); 
            $table->dropColumn('composition');
        });
    }
}
