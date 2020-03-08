<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSizeNullableToStandardTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orchestra_standard_poly_bag_sticker_orders_qty', function (Blueprint $table) {
            $table->string('size')->nullable(true)->change();
        });

	Schema::table('orchestra_standard_carton_sticker_orders_qty', function (Blueprint $table) {
           $table->string('size')->nullable(true)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orchestra_standard_poly_bag_sticker_orders_qty', function (Blueprint $table) {
            $table->string('size')->nullable(false)->change();
        });

	Schema::table('orchestra_standard_carton_sticker_orders_qty', function (Blueprint $table) {
           $table->string('size')->nullable(false)->change();
        });
    }
}
