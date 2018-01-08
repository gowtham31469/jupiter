<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNullableToOrchestraSolidCartonStickerOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
 	Schema::table('orchestra_solid_carton_sticker_orders', function (Blueprint $table) {
            $table->dropColumn('height');
            
        });
	Schema::table('orchestra_solid_carton_sticker_orders', function (Blueprint $table) {
           $table->string('height')->nullable()->after('po');
	});

	Schema::table('orchestra_solid_poly_bag_sticker_orders', function (Blueprint $table) {
            $table->dropColumn('height');
            
        });
	Schema::table('orchestra_solid_poly_bag_sticker_orders', function (Blueprint $table) {
           $table->string('height')->nullable()->after('po');
	});

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orchestra_solid_carton_sticker_orders', function (Blueprint $table) {
            //
        });
    }
}
