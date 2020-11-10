<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeNullableConstrintsInStatusFieldsInOrderManagementHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_management_history', function (Blueprint $table) {
            $table->string('order_status')->nullable(true)->change();
            $table->string('artwork_status')->nullable(true)->change();
            $table->string('po_status')->nullable(true)->change();
            $table->string('pi_status')->nullable(true)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_management_history', function (Blueprint $table) {
            $table->string('order_status')->nullable(false)->change();
            $table->string('artwork_status')->nullable(false)->change();
            $table->string('po_status')->nullable(false)->change();
            $table->string('pi_status')->nullable(false)->change();
        });
    }
}
