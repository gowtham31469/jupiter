<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropTemplatesColumnsFromTescoStickerOrdersSizeDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tesco_sticker_orders_size_details', function (Blueprint $table) {
            $table->dropColumn('tcl1_count');
            $table->dropColumn('tsl1_count');
            $table->dropColumn('tcl2_count');
            $table->dropColumn('tcl4_count');
            $table->dropColumn('tsl3_count');
            $table->dropColumn('thl1_count');
            $table->dropColumn('thl3_count');
            $table->dropColumn('order_status');
            $table->dropColumn('artwork_status');
            $table->dropColumn('print_status');
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
            $table->addColumn('tcl1_count')->after('orms_tpn');
            $table->addColumn('tsl1_count')->after('tcl1_count');
            $table->addColumn('tcl2_count')->after('tsl1_count');    
            $table->addColumn('tcl4_count')->after('tcl2_count');
            $table->addColumn('tsl3_count')->after('tcl4_count');
            $table->addColumn('thl1_count')->after('tsl3_count');
            $table->addColumn('thl3_count')->after('thl1_count');
            $table->addColumn('order_status')->after('thl3_count');
            $table->addColumn('artwork_status')->after('order_status');
            $table->addColumn('print_status')->after('artwork_status');
        });
    }
}
