<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFreightTypeForwarderToCustomers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->integer('freight_type')->unsigned()->after('place_of_supply');
           // $table->foreign('freight_type')->references('id')->on('freight_terms')->onDelete('cascade');
            $table->integer('nominated_forwarder')->unsigned()->after('freight_type');
            //$table->foreign('nominated_forwarder')->references('id')->on('nominated_forwarders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
             $table->dropColumn('freight_type');
             $table->dropColumn('nominated_forwarder');
        });
    }
}
