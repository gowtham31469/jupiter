<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateErpUserCompanyMappingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('erpuser_company_mapping', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('erp_user')->unsigned();
			$table->integer('company')->unsigned();
            $table->timestamps();
			$table->softDeletes();
			/* Indexes	*/
			$table->index(['erp_user', 'company']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('erpuser_company_mapping');
    }
}
