<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSelectedSecondarySizeToTescoWashcareOrdersQuanty extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tesco_washcare_orders_quanty', function (Blueprint $table) {
            $table->json('selected_secondary_sizes')->nullable();
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
            $table->dropColumn('selected_secondary_sizes');
        });
    }
}
