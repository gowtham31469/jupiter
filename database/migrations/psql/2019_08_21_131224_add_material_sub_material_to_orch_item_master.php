<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMaterialSubMaterialToOrchItemMaster extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orchestra_item_master', function (Blueprint $table) {
             $table->integer('material_id')->nullable();
             $table->integer('sub_material_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orchestra_item_master', function (Blueprint $table) {
             $table->dropColumn('material_id');
             $table->dropColumn('sub_material_id');
        });
    }
}
