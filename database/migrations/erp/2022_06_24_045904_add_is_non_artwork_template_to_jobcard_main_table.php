<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsNonArtworkTemplateToJobcardMainTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jobcard_main_table', function (Blueprint $table) {
            $table->boolean('is_non_artwork_template')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jobcard_main_table', function (Blueprint $table) {
            $table->dropColumn('is_non_artwork_template');
        });
    }
}
