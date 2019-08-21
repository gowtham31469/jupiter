<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColorCodeToOrchestraWclTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orchestra_wash_care_label_orders', function (Blueprint $table) {
            $table->string('color_code')->nullable();
        }); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orchestra_wash_care_label_orders', function (Blueprint $table) {
            $table->dropColumn('color_code');
        });
    }
}
