<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateErpusersRolesMappingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('erpusers_roles_mapping', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('erp_user')->unsigned();
            $table->integer('role')->unsigned();
            $table->timestamps();
			
			$table->foreign('erp_user')->references('id')
				->on('erpusers')->onDelete('cascade');
			$table->foreign('role')->references('id')
				->on('erp_roles')->onDelete('cascade');
			/* Indexes	*/
			$table->index(['erp_user', 'role']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('erpusers_roles_mapping');
    }
}
