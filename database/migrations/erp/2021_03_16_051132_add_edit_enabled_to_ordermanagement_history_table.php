<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEditEnabledToOrdermanagementHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_management_history', function (Blueprint $table) {
            $table->boolean('order_edit_enabled')->default(false);
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
            $table->dropColumn('order_edit_enabled');
        });
    }
}
