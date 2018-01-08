<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrderStatusToOrchestraItemDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orchestra_item_details', function (Blueprint $table)
        {
             $table->string('order_status')->after('Prix_Club_USA')->nullable() ;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orchestra_item_details', function (Blueprint $table)
        {
            $table->dropColumn('order_status');
        });
    }
}
