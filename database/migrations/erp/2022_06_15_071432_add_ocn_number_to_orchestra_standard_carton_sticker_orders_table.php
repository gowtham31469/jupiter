<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOcnNumberToOrchestraStandardCartonStickerOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orchestra_standard_carton_sticker_orders', function (Blueprint $table) { 
            $table->string('ocn_number')->nullable(true);
        });
	Schema::table('orchestra_standard_poly_bag_sticker_orders', function (Blueprint $table) { 
		    $table->string('ocn_number')->nullable(true);
		});
	Schema::table('orchestra_solid_carton_sticker_orders', function (Blueprint $table) { 
		    $table->string('ocn_number')->nullable(true);
		});
	Schema::table('orchestra_solid_poly_bag_sticker_orders', function (Blueprint $table) { 
		    $table->string('ocn_number')->nullable(true);
		});
	Schema::table('orchestra_size_label_orders', function (Blueprint $table) { 
		    $table->string('ocn_number')->nullable(true);
		});
	Schema::table('orchestra_special_tag_orders', function (Blueprint $table) { 
		    $table->string('ocn_number')->nullable(true);
		});
	Schema::table('orchestra_wash_care_label_orders', function (Blueprint $table) { 
		    $table->string('ocn_number')->nullable(true);
		});
	Schema::table('orchestra_main_label_orders', function (Blueprint $table) { 
		    $table->string('ocn_number')->nullable(true);
		});
	Schema::table('orchestra_heat_transfer_orders', function (Blueprint $table) { 
		    $table->string('ocn_number')->nullable(true);
		});
	Schema::table('orchestra_hang_tag_orders', function (Blueprint $table) { 
		    $table->string('ocn_number')->nullable(true);
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orchestra_standard_carton_sticker_orders', function (Blueprint $table) {
            $table->dropColumn('ocn_number');
        });
	Schema::table('orchestra_standard_poly_bag_sticker_orders', function (Blueprint $table) {
		    $table->dropColumn('ocn_number');
		});
	Schema::table('orchestra_solid_carton_sticker_orders', function (Blueprint $table) {
		    $table->dropColumn('ocn_number');
		});
	Schema::table('orchestra_solid_poly_bag_sticker_orders', function (Blueprint $table) {
		    $table->dropColumn('ocn_number');
		});
	Schema::table('orchestra_size_label_orders', function (Blueprint $table) {
		    $table->dropColumn('ocn_number');
		});
	Schema::table('orchestra_special_tag_orders', function (Blueprint $table) {
		    $table->dropColumn('ocn_number');
		});
	Schema::table('orchestra_wash_care_label_orders', function (Blueprint $table) {
		    $table->dropColumn('ocn_number');
		});
	Schema::table('orchestra_main_label_orders', function (Blueprint $table) {
		    $table->dropColumn('ocn_number');
		});
	Schema::table('orchestra_heat_transfer_orders', function (Blueprint $table) {
		    $table->dropColumn('ocn_number');
		});
	Schema::table('orchestra_hang_tag_orders', function (Blueprint $table) {
		    $table->dropColumn('ocn_number');
		});
    }
}
