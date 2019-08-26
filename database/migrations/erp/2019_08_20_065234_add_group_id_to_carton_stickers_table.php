<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGroupIdToCartonStickersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orchestra_solid_carton_sticker_orders', function (Blueprint $table) {
	         $table->bigInteger('group_id')->nullable();
        });
	Schema::table('orchestra_standard_carton_sticker_orders', function (Blueprint $table) {
	         $table->bigInteger('group_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orchestra_solid_carton_sticker_orders', function (Blueprint $table) {
	        $table->dropColumn('group_id');
        });
	Schema::table('orchestra_standard_carton_sticker_orders', function (Blueprint $table) {
	        $table->dropColumn('group_id');
        });
    }
}
