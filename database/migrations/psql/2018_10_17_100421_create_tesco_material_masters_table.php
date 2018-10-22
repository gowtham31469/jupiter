<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTescoMaterialMastersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tesco_material_masters', function (Blueprint $table) {
            $table->increments('id');
            $table->string('material_name_employee');
            $table->string('material_name_production');
            $table->string('material_name_purchase');
            $table->boolean('is_enabled')->default('1');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tesco_material_masters');
    }
}
