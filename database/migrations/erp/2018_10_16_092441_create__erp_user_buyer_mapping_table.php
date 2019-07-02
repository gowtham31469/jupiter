<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateErpUserBuyerMappingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('erpuser_buyer', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('erp_user')->unsigned();
			$table->integer('buyer')->unsigned();
            $table->timestamps();
			$table->softDeletes();
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
        Schema::dropIfExists('erpuser_buyer');
    }
}
