<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWhiteWoolmarkLogoToTescoWoolmarkLogosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tesco_woolmark_logos', function (Blueprint $table) {
            $table->string('white_woolmark_logo')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tesco_woolmark_logos', function (Blueprint $table) {
            $table->dropColumn('white_woolmark_logo');
        });
    }
}
