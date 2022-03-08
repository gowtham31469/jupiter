<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTextToTescoCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tesco_countries', function (Blueprint $table) {
            $table->text('english')->nullable(true)->change();
            $table->text('polish')->nullable(true)->change();
            $table->text('czech')->nullable(true)->change();
            $table->text('slovak')->nullable(true)->change();
            $table->text('hungarian')->nullable(true)->change();
        });

	Schema::table('tesco_components', function (Blueprint $table) {
            $table->text('english')->nullable(true)->change();
            $table->text('polish')->nullable(true)->change();
            $table->text('czech')->nullable(true)->change();
            $table->text('slovak')->nullable(true)->change();
            $table->text('hungarian')->nullable(true)->change();
        });

	Schema::table('tesco_fabriclists', function (Blueprint $table) {
            $table->text('english')->nullable(true)->change();
            $table->text('polish')->nullable(true)->change();
            $table->text('czech')->nullable(true)->change();
            $table->text('slovak')->nullable(true)->change();
            $table->text('hungarian')->nullable(true)->change();
        });

	Schema::table('tesco_care_additional_instructions', function (Blueprint $table) {
            $table->text('english')->nullable(true)->change();
            $table->text('polish')->nullable(true)->change();
            $table->text('czech')->nullable(true)->change();
            $table->text('slovak')->nullable(true)->change();
            $table->text('hungarian')->nullable(true)->change();
        });

	Schema::table('tesco_kids_warning_statements', function (Blueprint $table) {
            $table->text('english')->nullable(true)->change();
            $table->text('polish')->nullable(true)->change();
            $table->text('czech')->nullable(true)->change();
            $table->text('slovak')->nullable(true)->change();
            $table->text('hungarian')->nullable(true)->change();
        });

	Schema::table('tesco_secondary_size_translation', function (Blueprint $table) {
            $table->text('english')->nullable(true)->change();
            $table->text('polish')->nullable(true)->change();
            $table->text('czech')->nullable(true)->change();
            $table->text('slovak')->nullable(true)->change();
            $table->text('hungarian')->nullable(true)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tesco_countries', function (Blueprint $table) {
            $table->string('english')->nullable(false)->change();
            $table->string('polish')->nullable(false)->change();
            $table->string('czech')->nullable(false)->change();
            $table->string('slovak')->nullable(false)->change();
            $table->string('hungarian')->nullable(false)->change();
        });

	Schema::table('tesco_components', function (Blueprint $table) {
            $table->string('english')->nullable(false)->change();
            $table->string('polish')->nullable(false)->change();
            $table->string('czech')->nullable(false)->change();
            $table->string('slovak')->nullable(false)->change();
            $table->string('hungarian')->nullable(false)->change();
        });

	Schema::table('tesco_fabriclists', function (Blueprint $table) {
            $table->string('english')->nullable(false)->change();
            $table->string('polish')->nullable(false)->change();
            $table->string('czech')->nullable(false)->change();
            $table->string('slovak')->nullable(false)->change();
            $table->string('hungarian')->nullable(false)->change();
        });

	Schema::table('tesco_care_additional_instructions', function (Blueprint $table) {
            $table->string('english')->nullable(false)->change();
            $table->string('polish')->nullable(false)->change();
            $table->string('czech')->nullable(false)->change();
            $table->string('slovak')->nullable(false)->change();
            $table->string('hungarian')->nullable(false)->change();
        });

	Schema::table('tesco_kids_warning_statements', function (Blueprint $table) {
            $table->string('english')->nullable(false)->change();
            $table->string('polish')->nullable(false)->change();
            $table->string('czech')->nullable(false)->change();
            $table->string('slovak')->nullable(false)->change();
            $table->string('hungarian')->nullable(false)->change();
        });

	Schema::table('tesco_secondary_size_translation', function (Blueprint $table) {
            $table->string('english')->nullable(false)->change();
            $table->string('polish')->nullable(false)->change();
            $table->string('czech')->nullable(false)->change();
            $table->string('slovak')->nullable(false)->change();
            $table->string('hungarian')->nullable(false)->change();
        });
    }
}
