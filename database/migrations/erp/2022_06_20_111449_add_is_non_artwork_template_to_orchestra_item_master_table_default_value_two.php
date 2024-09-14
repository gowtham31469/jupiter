<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsNonArtworkTemplateToOrchestraItemMasterTableDefaultValueTwo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orchestra_item_master', function (Blueprint $table) {
            $table->boolean('is_non_artwork_template')->default('0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orchestra_item_master', function (Blueprint $table) {
            $table->dropColumn('is_non_artwork_template');
        });
    }
}
