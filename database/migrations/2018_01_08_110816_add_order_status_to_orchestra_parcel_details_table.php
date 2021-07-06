<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrderStatusToOrchestraParcelDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orchestra_parcel_details', function (Blueprint $table) {
             $table->string('order_status')->after('NomApple')->nullable() ;
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orchestra_parcel_details', function (Blueprint $table) {
            $table->dropColumn('order_status');
        });
    }
}
