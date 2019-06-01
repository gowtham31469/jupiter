<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLabelPoNumberToLabelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::table('orchestra_wash_care_label_orders', function (Blueprint $table) {
            $table->string('po')->nullable();
             $table->unsignedInteger('customer_id')->nullable();
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });
		Schema::table('orchestra_hang_tag_orders', function (Blueprint $table) {
            $table->string('po')->nullable();
             $table->unsignedInteger('customer_id')->nullable();
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });
		Schema::table('orchestra_main_label_orders', function (Blueprint $table) {
            $table->string('po')->nullable();
             $table->unsignedInteger('customer_id')->nullable();
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });
		Schema::table('orchestra_special_tag_orders', function (Blueprint $table) {
            $table->string('po')->nullable();
             $table->unsignedInteger('customer_id')->nullable();
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });
		Schema::table('orchestra_solid_carton_sticker_orders', function (Blueprint $table) {
            //$table->string('po')->nullable();
             $table->unsignedInteger('customer_id')->nullable();
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });
		Schema::table('orchestra_solid_poly_bag_sticker_orders', function (Blueprint $table) {
            //$table->string('po')->nullable();
             $table->unsignedInteger('customer_id')->nullable();
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });
		Schema::table('orchestra_standard_carton_sticker_orders', function (Blueprint $table) {
            //$table->string('po')->nullable();
             $table->unsignedInteger('customer_id')->nullable();
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });
		Schema::table('orchestra_standard_polybag_sticker_orders', function (Blueprint $table) {
            //$table->string('po')->nullable();
             $table->unsignedInteger('customer_id')->nullable();
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
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
			$table->dropColumn('po')->nullable();
            $table->dropForeign('orchestra_wash_care_label_orders_customer_id_foreign');
            $table->dropColumn('customer_id');
        });
		Schema::table('orchestra_hang_tag_orders', function (Blueprint $table) {
			$table->dropColumn('po')->nullable();
            $table->dropForeign('orchestra_hang_tag_orders_customer_id_foreign');
            $table->dropColumn('customer_id');
        });
		Schema::table('orchestra_main_label_orders', function (Blueprint $table) {
			$table->dropColumn('po')->nullable();
            $table->dropForeign('orchestra_main_label_orders_customer_id_foreign');
            $table->dropColumn('customer_id');
        });
		Schema::table('orchestra_special_tag_orders', function (Blueprint $table) {
			$table->dropColumn('po')->nullable();
            $table->dropForeign('orchestra_special_tag_orders_customer_id_foreign');
            $table->dropColumn('customer_id');
        });
		Schema::table('orchestra_solid_carton_sticker_orders', function (Blueprint $table) {
			//$table->dropColumn('po')->nullable();
            $table->dropForeign('orchestra_solid_carton_sticker_orders_customer_id_foreign');
            $table->dropColumn('customer_id');
        });
		Schema::table('orchestra_solid_poly_bag_sticker_orders', function (Blueprint $table) {
			//$table->dropColumn('po')->nullable();
            $table->dropForeign('orchestra_solid_poly_bag_sticker_orders_customer_id_foreign');
            $table->dropColumn('customer_id');
        });
		Schema::table('orchestra_standard_carton_sticker_orders', function (Blueprint $table) {
			//$table->dropColumn('po')->nullable();
            $table->dropForeign('orchestra_standard_carton_sticker_orders_customer_id_foreign');
            $table->dropColumn('customer_id');
        });
		Schema::table('orchestra_standard_polybag_sticker_orders', function (Blueprint $table) {
			//$table->dropColumn('po')->nullable();
            $table->dropForeign('orchestra_standard_polybag_sticker_orders_customer_id_foreign');
            $table->dropColumn('customer_id');
        });
    }
}
