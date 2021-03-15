<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNameInReportsToTescoOrchTemplates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tesco_template', function (Blueprint $table) {
            $table->string('name_in_reports')->nullable();
        });

        Schema::table('orchestra_template', function (Blueprint $table) {
            $table->string('name_in_reports')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tesco_template', function (Blueprint $table) {
            $table->dropColumn('name_in_reports');
        });

        Schema::table('orchestra_template', function (Blueprint $table) {
            $table->dropColumn('name_in_reports');
        });
    }
}
