<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropEventsFromTescoStickerOrdersSizeDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tesco_sticker_orders_size_details', function (Blueprint $table) {
            $table->dropColumn('tcl1_event');
            $table->dropColumn('tsl1_event');
            $table->dropColumn('tcl2_event');
            $table->dropColumn('tcl4_event');
            $table->dropColumn('tsl3_event');
            $table->dropColumn('thl1_event');
            $table->dropColumn('thl3_event');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tesco_sticker_orders_size_details', function (Blueprint $table) {
            $table->addColumn('tcl1_event')->after('tcl1_count');
            $table->addColumn('tsl1_event')->after('tsl1_count');
            $table->addColumn('tcl2_event')->after('tcl2_count');    
            $table->addColumn('tcl4_event')->after('tcl4_count');
            $table->addColumn('tsl3_event')->after('tsl3_count');
            $table->addColumn('thl1_event')->after('thl1_count');
            $table->addColumn('thl3_event')->after('thl3_count');
        });
    }
}
