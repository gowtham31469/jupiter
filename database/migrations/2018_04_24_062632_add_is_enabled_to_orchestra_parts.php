<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsEnabledToOrchestraParts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orchestra_parts', function (Blueprint $table) {
            $table->boolean('is_enabled')->after('turkish')->default('0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orchestra_parts', function (Blueprint $table) {
            $table->dropColumn('is_enabled');
        });
    }
}
