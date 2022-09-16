<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NullableToOrchestraTemplateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orchestra_template', function (Blueprint $table) {
            $table->string('template_description')->nullable(true)->change();
            $table->string('template_file')->nullable(true)->change();
            $table->smallInteger('disney')->nullable(true)->change();
            $table->integer('tax')->nullable(true)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orchestra_template', function (Blueprint $table) {
            $table->string('template_description')->nullable(false)->change();
            $table->string('template_file')->nullable(false)->change();
            $table->smallInteger('disney')->nullable(false)->change();
            $table->integer('tax')->nullable(false)->change();
        });
    }
}
