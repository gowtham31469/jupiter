<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCompanyToErpuserTemplateMapping extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('erpusers_template_mapping', function (Blueprint $table) {
            $table->integer('company')->unsigned()->nullable();
			
			$table->foreign('company')->references('id')->on('company')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('erpusers_template_mapping', function (Blueprint $table) {
            $table->dropColumn('company');
        });
    }
}
