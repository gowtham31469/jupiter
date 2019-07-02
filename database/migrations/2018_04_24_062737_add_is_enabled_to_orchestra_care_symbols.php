<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsEnabledToOrchestraCareSymbols extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orchestra_care_symbols', function (Blueprint $table) {
            $table->boolean('is_enabled')->after('care_instruction')->default('0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orchestra_care_symbols', function (Blueprint $table) {
            $table->dropColumn('is_enabled');
        });
    }
}
