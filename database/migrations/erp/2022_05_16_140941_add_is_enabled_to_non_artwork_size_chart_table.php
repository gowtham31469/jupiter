<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsEnabledToNonArtworkSizeChartTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('non_artwork_size_chart', function (Blueprint $table) {
            $table->boolean('is_enabled')->after('ce')->default('0');
            //
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('non_artwork_size_chart', function (Blueprint $table) {
            $table->dropColumn('is_enabled');
            //
        });
    }
}
