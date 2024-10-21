<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyUkCeMarkColumnInTescoWashcareOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tesco_washcare_orders', function (Blueprint $table) {
            $table->string('uk_ce_mark')->nullable()->change();
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
            $table->string('uk_ce_mark')->nullable(false)->change();
        });
    }
}
