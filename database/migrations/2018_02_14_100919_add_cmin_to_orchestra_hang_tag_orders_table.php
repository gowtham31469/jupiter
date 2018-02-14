<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCminToOrchestraHangTagOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orchestra_hang_tag_orders', function (Blueprint $table) {
                $table->integer('cm')->nullable()->after('po_file');
		$table->integer('in')->nullable()->after('cm');
        });
 	Schema::table('orchestra_wash_care_label_orders', function (Blueprint $table) {
                $table->integer('cm')->nullable()->after('po_file');
		$table->integer('in')->nullable()->after('cm');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orchestra_hang_tag_orders', function (Blueprint $table) {
            //
        });
    }
}
