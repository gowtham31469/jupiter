<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddParcelDetailsIdToTagsnlabelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orchestra_heat_transfer_orders', function (Blueprint $table) {
	        $table->integer('orchestra_parcel_details_id')->nullable();
		$table->string('season_code')->nullable();
        });
	Schema::table('orchestra_hang_tag_orders', function (Blueprint $table) {
	        $table->integer('orchestra_parcel_details_id')->nullable();
		$table->string('season_code')->nullable();
        });
	Schema::table('orchestra_main_label_orders', function (Blueprint $table) {
	        $table->integer('orchestra_parcel_details_id')->nullable();
		$table->string('season_code')->nullable();
        });
	Schema::table('orchestra_solid_carton_sticker_orders', function (Blueprint $table) {
	        $table->integer('orchestra_parcel_details_id')->nullable();
		$table->string('season_code')->nullable();
        });
	Schema::table('orchestra_solid_poly_bag_sticker_orders', function (Blueprint $table) {
	        $table->integer('orchestra_parcel_details_id')->nullable();
		$table->string('season_code')->nullable();
        });
	Schema::table('orchestra_special_tag_orders', function (Blueprint $table) {
	        $table->integer('orchestra_parcel_details_id')->nullable();
		$table->string('season_code')->nullable();
        });
	Schema::table('orchestra_standard_carton_sticker_orders', function (Blueprint $table) {
	        $table->integer('orchestra_parcel_details_id')->nullable();
		$table->string('season_code')->nullable();
        });
	Schema::table('orchestra_standard_poly_bag_sticker_orders', function (Blueprint $table) {
	        $table->integer('orchestra_parcel_details_id')->nullable();
		$table->string('season_code')->nullable();
        });
	Schema::table('orchestra_wash_care_label_orders', function (Blueprint $table) {
	        $table->integer('orchestra_parcel_details_id')->nullable();
		$table->string('season_code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
