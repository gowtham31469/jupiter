<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSeasonFieldInHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
            Schema::table('history', function (Blueprint $table) {
                 $table->string('season')->nullable(true)->change();
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
                $table->string('season')->nullable(false)->change();
        });
    }
}
