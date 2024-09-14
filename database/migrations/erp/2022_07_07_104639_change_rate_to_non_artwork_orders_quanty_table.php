<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeRateToNonArtworkOrdersQuantyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('non_artwork_orders_quanty', function (Blueprint $table) {
		    $table->dropColumn('rate');
        });
        Schema::table('non_artwork_orders_quanty', function (Blueprint $table) {
            $table->decimal('rate',8,2)->nullable();
        });

	Schema::table('non_artwork_orders_quanty', function (Blueprint $table) {
		    $table->dropColumn('rate_value');
        });
        Schema::table('non_artwork_orders_quanty', function (Blueprint $table) {
            $table->decimal('rate_value',8,2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('non_artwork_orders_quanty', function (Blueprint $table) {
            $table->dropColumn('rate');
        });
        Schema::table('non_artwork_orders_quanty', function (Blueprint $table) {
	        $table->integer('rate')->nullable();
        });

	Schema::table('non_artwork_orders_quanty', function (Blueprint $table) {
            $table->dropColumn('rate_value');
        });
        Schema::table('non_artwork_orders_quanty', function (Blueprint $table) {
	        $table->integer('rate_value')->nullable();
        });
    }
}
