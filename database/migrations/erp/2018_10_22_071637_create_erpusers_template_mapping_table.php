<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateErpusersTemplateMappingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('erpusers_template_mapping', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('erp_user')->unsigned();
			$table->integer('buyer')->unsigned();
			$table->integer('templates');
            $table->timestamps();
			
			$table->foreign('erp_user')->references('id')
				->on('erpusers')->onDelete('cascade');
			$table->foreign('buyer')->references('id')
				->on('buyers')->onDelete('cascade');
			/* Indexes	*/
			$table->index(['erp_user', 'buyer']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('erpusers_template_mapping');
    }
}
