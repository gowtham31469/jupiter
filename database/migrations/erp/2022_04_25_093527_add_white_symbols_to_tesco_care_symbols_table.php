<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWhiteSymbolsToTescoCareSymbolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tesco_care_symbols', function (Blueprint $table) {
            $table->string('white_iso_symbols')->nullable();
            $table->string('white_astms_symbol')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tesco_care_symbols', function (Blueprint $table) {
            $table->dropColumn('white_iso_symbols');
            $table->dropColumn('white_astms_symbol');
        });
    }
}
