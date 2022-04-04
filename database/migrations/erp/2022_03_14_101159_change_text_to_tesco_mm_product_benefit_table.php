<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTextToTescoMmProductBenefitTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tesco_mm_product_benefits', function (Blueprint $table) {
                $table->text('english')->nullable()->change();
		$table->text('polish')->nullable()->change();
		$table->text('czech')->nullable()->change();
		$table->text('slovak')->nullable()->change();
		$table->text('hungarian')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tesco_mm_product_benefits', function (Blueprint $table) {
             $table->string('english')->change();
		$table->string('polish')->change();
		$table->string('czech')->change();
		$table->string('slovak')->change();
		$table->string('hungarian')->change();
        });
    }
}
