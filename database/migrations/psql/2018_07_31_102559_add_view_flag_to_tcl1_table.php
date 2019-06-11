<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddViewFlagToTcl1Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tesco_tcl1_orders', function (Blueprint $table) {
                $table->integer('pen_pro_flag')->default(0);
		$table->integer('new_ord_flag')->default(0);
		$table->integer('app_ord_flag')->default(0);
		$table->integer('app_pipo_flag')->default(0);
		$table->integer('pen_ord_flag')->default(0);
        });
	Schema::table('tesco_tcl2_orders', function (Blueprint $table) {
                $table->integer('pen_pro_flag')->default(0);
		$table->integer('new_ord_flag')->default(0);
		$table->integer('app_ord_flag')->default(0);
		$table->integer('app_pipo_flag')->default(0);
		$table->integer('pen_ord_flag')->default(0);
        });
	Schema::table('tesco_tcl4_orders', function (Blueprint $table) {
                $table->integer('pen_pro_flag')->default(0);
		$table->integer('new_ord_flag')->default(0);
		$table->integer('app_ord_flag')->default(0);
		$table->integer('app_pipo_flag')->default(0);
		$table->integer('pen_ord_flag')->default(0);
        });
	Schema::table('tesco_tsl1_orders', function (Blueprint $table) {
		 $table->integer('pen_pro_flag')->default(0);
		$table->integer('new_ord_flag')->default(0);
		$table->integer('app_ord_flag')->default(0);
		$table->integer('app_pipo_flag')->default(0);
		$table->integer('pen_ord_flag')->default(0);
	});
	Schema::table('tesco_tsl3_orders', function (Blueprint $table) {
		$table->integer('pen_pro_flag')->default(0);
		$table->integer('new_ord_flag')->default(0);
		$table->integer('app_ord_flag')->default(0);
		$table->integer('app_pipo_flag')->default(0);
		$table->integer('pen_ord_flag')->default(0);
	});
	Schema::table('tesco_thl1_orders', function (Blueprint $table) {
		$table->integer('pen_pro_flag')->default(0);
		$table->integer('new_ord_flag')->default(0);
		$table->integer('app_ord_flag')->default(0);
		$table->integer('app_pipo_flag')->default(0);
		$table->integer('pen_ord_flag')->default(0);
	});
	Schema::table('tesco_thl3_orders', function (Blueprint $table) {
		$table->integer('pen_pro_flag')->default(0);
		$table->integer('new_ord_flag')->default(0);
		$table->integer('app_ord_flag')->default(0);
		$table->integer('app_pipo_flag')->default(0);
		$table->integer('pen_ord_flag')->default(0);
	});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tesco_tcl1_orders', function (Blueprint $table) {
           	$table->dropColumn('pen_pro_flag')->default(0);
		$table->dropColumn('new_ord_flag')->default(0);
		$table->dropColumn('app_ord_flag')->default(0);
		$table->dropColumn('app_pipo_flag')->default(0);
		$table->dropColumn('pen_ord_flag')->default(0);
        });
	Schema::table('tesco_tcl2_orders', function (Blueprint $table) {
                $table->dropColumn('pen_pro_flag')->default(0);
		$table->dropColumn('new_ord_flag')->default(0);
		$table->dropColumn('app_ord_flag')->default(0);
		$table->dropColumn('app_pipo_flag')->default(0);
		$table->dropColumn('pen_ord_flag')->default(0);
        });
	Schema::table('tesco_tcl4_orders', function (Blueprint $table) {
                $table->dropColumn('pen_pro_flag')->default(0);
		$table->dropColumn('new_ord_flag')->default(0);
		$table->dropColumn('app_ord_flag')->default(0);
		$table->dropColumn('app_pipo_flag')->default(0);
		$table->dropColumn('pen_ord_flag')->default(0);
        });
	Schema::table('tesco_tsl1_orders', function (Blueprint $table) {
	        $table->dropColumn('pen_pro_flag')->default(0);
		$table->dropColumn('new_ord_flag')->default(0);
		$table->dropColumn('app_ord_flag')->default(0);
		$table->dropColumn('app_pipo_flag')->default(0);
		$table->dropColumn('pen_ord_flag')->default(0);
	});
	Schema::table('tesco_tsl3_orders', function (Blueprint $table) {
	        $table->dropColumn('pen_pro_flag')->default(0);
		$table->dropColumn('new_ord_flag')->default(0);
		$table->dropColumn('app_ord_flag')->default(0);
		$table->dropColumn('app_pipo_flag')->default(0);
		$table->dropColumn('pen_ord_flag')->default(0);
	});
	Schema::table('tesco_thl1_orders', function (Blueprint $table) {
	        $table->dropColumn('pen_pro_flag')->default(0);
		$table->dropColumn('new_ord_flag')->default(0);
		$table->dropColumn('app_ord_flag')->default(0);
		$table->dropColumn('app_pipo_flag')->default(0);
		$table->dropColumn('pen_ord_flag')->default(0);
	});
	Schema::table('tesco_thl3_orders', function (Blueprint $table) {
	    	$table->dropColumn('pen_pro_flag')->default(0);
		$table->dropColumn('new_ord_flag')->default(0);
		$table->dropColumn('app_ord_flag')->default(0);
		$table->dropColumn('app_pipo_flag')->default(0);
		$table->dropColumn('pen_ord_flag')->default(0);
	});

    }
}
