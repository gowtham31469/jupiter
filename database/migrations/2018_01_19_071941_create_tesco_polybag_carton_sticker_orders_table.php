<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTescoPolybagCartonStickerOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tesco_polybag_carton_sticker_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('po_number');
            $table->string('uk_style_ref');
            $table->string('ce_style_ref');
            $table->string('season_code');
            $table->mediumText('short_description');
            $table->string('eqos_site_code');
            $table->date('manufacture_date');
            $table->integer('tesco_department_id')->unsigned();
            $table->integer('tesco_brand_id')->unsigned();
            $table->integer('tesco_supplier_id')->unsigned();
            $table->boolean('rfid_compliant')->default('0');
            $table->boolean('tag_at_source')->default('0');
            $table->timestamps();
            $table->foreign('tesco_department_id')->references('id')->on('tesco_department')->onDelete('cascade');
            $table->foreign('tesco_brand_id')->references('id')->on('tesco_brand')->onDelete('cascade');
            $table->foreign('tesco_supplier_id')->references('id')->on('tesco_suppliers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tesco_polybag_carton_sticker_orders');
    }
}
