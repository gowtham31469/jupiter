<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropCompositionPrintLengthFromJobcardMainTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jobcard_main_table', function (Blueprint $table) {
            $table->dropColumn('print_length'); 
            $table->dropColumn('composition');
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
            $table->double('print_length')->nullable(); 
            $table->string('composition')->nullable();
        });
    }
}
