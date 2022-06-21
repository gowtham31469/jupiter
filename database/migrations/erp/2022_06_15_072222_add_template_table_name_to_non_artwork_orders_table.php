<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTemplateTableNameToNonArtworkOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('non_artwork_orders', function (Blueprint $table) {
            $table->string('template_table_name')->nullable(true);
	    $table->dropForeign('non_artwork_orders_template_id_foreign');
	    $table->renameColumn('tesco_supplier_id', 'supplier_id');
        });
    }

    /**
     * Reverse the migrations.aa
     *
     * @return void
     */
    public function down()
    {
        Schema::table('non_artwork_orders', function (Blueprint $table) {
            $table->dropColumn('template_table_name');
            $table->foreign('template_id')->references('id')->on('tesco_item_master')->onDelete('cascade');
	    $table->renameColumn('supplier_id', 'tesco_supplier_id');
        });
    }
}
