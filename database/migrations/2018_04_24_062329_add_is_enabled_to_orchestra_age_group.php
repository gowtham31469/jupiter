<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsEnabledToOrchestraAgeGroup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orchestra_age_group', function (Blueprint $table) {
            $table->boolean('is_enabled')->after('color')->default('0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orchestra_age_group', function (Blueprint $table) {
            $table->dropColumn('is_enabled');
        });
    }
}
