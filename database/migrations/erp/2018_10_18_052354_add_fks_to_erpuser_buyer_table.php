<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFksToErpuserBuyerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('erpuser_buyer', function (Blueprint $table) {
            $table->foreign('erp_user')->references('id')
				->on('erpusers')->onDelete('cascade');
			$table->foreign('buyer')->references('id')
				->on('buyers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('erpuser_buyer', function (Blueprint $table) {
            //
        });
    }
}
