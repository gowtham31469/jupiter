<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsEnabledToOrchestraProductFamily extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orchestra_product_family', function (Blueprint $table) {
            $table->boolean('is_enabled')->after('arabic')->default('0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orchestra_product_family', function (Blueprint $table) {
            $table->dropColumn('is_enabled');
        });
    }
}
