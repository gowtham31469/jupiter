<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSizeTextToTescoWashcareOrdersQtyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tesco_washcare_orders_quanty', function (Blueprint $table) {
            $table->string('uk_size')->nullable(true);
            $table->string('ce_size')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tesco_washcare_orders_quanty', function (Blueprint $table) {
            $table->dropColumn('uk_size');
            $table->dropColumn('ce_size');
        });
    }
}
