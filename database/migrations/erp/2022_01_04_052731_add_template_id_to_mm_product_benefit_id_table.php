<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTemplateIdToMmProductBenefitIdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mm_product_benefit_id', function (Blueprint $table) {
            $table->unsignedInteger('template_id')->nullable(true)->index();
            $table->foreign('template_id')->references('id')->on('tesco_item_master')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mm_product_benefit_id', function (Blueprint $table) {
            //
        });
    }
}
