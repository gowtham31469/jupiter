<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldToHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('history', function (Blueprint $table) {
            $table->integer('disp_mode_id')->nullable();
            $table->foreign('disp_mode_id')->references('id')->on('erp_dispatch_mode')->onDelete('cascade');
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
            $table->dropForeign('history_disp_mode_id_foreign');
            $table->dropColumn('disp_mode_id');
        });
    }
}
