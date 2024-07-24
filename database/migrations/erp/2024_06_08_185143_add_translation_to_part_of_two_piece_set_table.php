<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTranslationToPartOfTwoPieceSetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('part_of_two_piece_set', function (Blueprint $table) {
            // Adding new columns with nullable text type
            $table->text('polish')->nullable();
            $table->text('czech')->nullable();
            $table->text('slovak')->nullable();
            $table->text('hungarian')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('part_of_two_piece_set', function (Blueprint $table) {
            // Dropping the columns when rolling back
            $table->dropColumn('polish');
            $table->dropColumn('czech');
            $table->dropColumn('slovak');
            $table->dropColumn('hungarian');
        });
    }
}
