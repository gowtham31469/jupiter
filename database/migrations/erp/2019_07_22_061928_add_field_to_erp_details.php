<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldToErpDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('erp_order_details', function (Blueprint $table) {
            $table->timestamp('aw_date')->nullable();
            $table->timestamp('order_date')->nullable();
        });    
     }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::table('erp_order_details', function (Blueprint $table) {
            $table->dropColumn('aw_date');
            $table->dropColumn('order_date');
        });
    }
}
