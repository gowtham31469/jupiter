<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddParcelIdToOrdersQtyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orchestra_heat_transfer_orders_qty', function (Blueprint $table) {
	        $table->integer('parcel_id')->nullable();
        });
	Schema::table('orchestra_hang_tag_orders_qty', function (Blueprint $table) {
	        $table->integer('parcel_id')->nullable();
        });
	Schema::table('orchestra_size_label_orders_qty', function (Blueprint $table) {
	        $table->integer('parcel_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orchestra_heat_transfer_orders_qty', function (Blueprint $table) {
	        $table->dropColumn('parcel_id');
        });
	Schema::table('orchestra_hang_tag_orders_qty', function (Blueprint $table) {
	        $table->dropColumn('parcel_id');
        });
	Schema::table('orchestra_size_label_orders_qty', function (Blueprint $table) {
	        $table->dropColumn('parcel_id');
        });
    }
}
