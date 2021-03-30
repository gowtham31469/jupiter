<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNamesInReportFkInTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tesco_template', function (Blueprint $table) {
            $table->foreign('name_in_reports')->references('id')->on('reports_template_level_name')->onDelete('cascade');
        });

        Schema::table('orchestra_template', function (Blueprint $table) {
            $table->foreign('name_in_reports')->references('id')->on('reports_template_level_name')->onDelete('cascade');
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
            $table->dropForeign('tesco_template_name_in_reports_foreign');
        });

        Schema::table('orchestra_template', function (Blueprint $table) {
            $table->dropForeign('tesco_template_name_in_reports_foreign');
        });
    }
}
