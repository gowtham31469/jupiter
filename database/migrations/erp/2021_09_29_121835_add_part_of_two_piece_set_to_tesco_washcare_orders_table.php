<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPartOfTwoPieceSetToTescoWashcareOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tesco_washcare_orders', function (Blueprint $table) {
 	    $table->unsignedInteger('potps_id')->nullable(true)->index();
            $table->foreign('potps_id')->references('id')->on('part_of_two_piece_set')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tesco_washcare_orders', function (Blueprint $table) {
             $table->dropColumn('potps_id');
        });
    }
}
