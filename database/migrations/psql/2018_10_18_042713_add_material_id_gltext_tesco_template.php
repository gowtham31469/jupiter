<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMaterialIdGltextTescoTemplate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('tesco_template', function (Blueprint $table) {
           $table->string('gl_text')->nullable();
           $table->unsignedBigInteger('material_id')->nullable();
           $table->foreign('material_id')->references('id')->on('tesco_material_masters')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::table('tesco_template', function (Blueprint $table) {
           $table->dropForeign('tesco_template_material_id_foreign');
           $table->dropColumn('material_id');
           $table->dropColumn('gl_text');
        });
    }
}
