<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusToOrchestraWashCareLabelOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orchestra_wash_care_label_orders', function (Blueprint $table) {
                $table->string('pi_status')->nullable()->after('status');
		$table->string('po_status')->nullable()->after('pi_status');
        });
	
	Schema::table('orchestra_hang_tag_orders', function (Blueprint $table) {
                $table->string('pi_status')->nullable()->after('status');
		$table->string('po_status')->nullable()->after('pi_status');
        });

	Schema::table('orchestra_special_tag_orders', function (Blueprint $table) {
                $table->string('pi_status')->nullable()->after('status');
		$table->string('po_status')->nullable()->after('pi_status');
        });
	
	Schema::table('orchestra_main_label_orders', function (Blueprint $table) {
                $table->string('pi_status')->nullable()->after('status');
		$table->string('po_status')->nullable()->after('pi_status');
        });

	Schema::table('orchestra_solid_carton_sticker_orders', function (Blueprint $table) {
                $table->string('pi_status')->nullable()->after('status');
		$table->string('po_status')->nullable()->after('pi_status');
        });

	Schema::table('orchestra_solid_poly_bag_sticker_orders', function (Blueprint $table) {
                $table->string('pi_status')->nullable()->after('status');
		$table->string('po_status')->nullable()->after('pi_status');
        });

	Schema::table('orchestra_standard_carton_sticker_orders', function (Blueprint $table) {
                $table->string('pi_status')->nullable()->after('status');
		$table->string('po_status')->nullable()->after('pi_status');
        });

	Schema::table('orchestra_standard_polybag_sticker_orders', function (Blueprint $table) {
                $table->string('pi_status')->nullable()->after('status');
		$table->string('po_status')->nullable()->after('pi_status');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orchestra_wash_care_label_orders', function (Blueprint $table) {
            //
        });
    }
}
