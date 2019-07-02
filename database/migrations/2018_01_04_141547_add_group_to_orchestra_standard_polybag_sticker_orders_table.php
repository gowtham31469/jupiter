<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGroupToOrchestraStandardPolybagStickerOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

	Schema::table('orchestra_standard_polybag_sticker_orders', function (Blueprint $table) {
		 $table->dropForeign('orchestra_standard_polybag_sticker_orders_group_id_foreign');
            $table->dropColumn('group_id');
            
        });
	Schema::table('orchestra_standard_polybag_sticker_orders', function (Blueprint $table) {
           $table->integer('group_id')->unsigned()->after('status');
	});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orchestra_standard_polybag_sticker_orders', function (Blueprint $table) {
            //
        });
    }
}
