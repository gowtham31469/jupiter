<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsEnabledToOrchestraSizeChart extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orchestra_size_chart', function (Blueprint $table) {
            $table->boolean('is_enabled')->after('in')->default('0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orchestra_size_chart', function (Blueprint $table) {
            $table->dropColumn('is_enabled');
        });
    }
}
