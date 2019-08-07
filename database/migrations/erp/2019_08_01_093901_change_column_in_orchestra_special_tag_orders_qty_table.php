<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeColumnInOrchestraSpecialTagOrdersQtyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orchestra_special_tag_orders_qty', function (Blueprint $table) {
            $table->string('size')->nullable()->change();	  
	    $table->string('uvc_tco')->nullable()->change();	
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orchestra_special_tag_orders_qty', function (Blueprint $table) {
            //
        });
    }
}
