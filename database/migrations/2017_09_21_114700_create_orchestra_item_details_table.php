<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrchestraItemDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orchestra_item_details', function (Blueprint $table)
        {
            $table->increments('id');
            $table->string('factory_code');
            $table->string('supplier_name');
            $table->string('supplier_address');
            $table->string('supplier_email');
            $table->string('supplier_country');
            $table->string('buyer_email');
            $table->string('first_etd_shipment_date');
            $table->integer('edition_number');
            $table->integer('version_number');
            $table->string('buyer_po_number');
            $table->string('prices_tags_barcode');
            $table->string('uvc');
            $table->string('tco');
            $table->string('description');
            $table->string('rayon');
            $table->string('color');
            $table->string('size');
            $table->string('stature');
            $table->string('age');
            $table->string('theme');
            $table->string('marque');
            $table->string('disney');
            $table->string('marvel');
            $table->string('pomme_framboise');
            $table->string('prix_europe');
            $table->double('prix_club_europe');
            $table->double('prix_vente_koweit');
            $table->double('prix_venete_arabie_saoudite');
            $table->double('prix_club_arabie_saoudite');
            $table->double('prix_vente_canada');
            $table->double('prix_club_canada');
            $table->double('prix_vente_suisse');
            $table->double('prix_club_suisse');
            $table->double('prix_venete_chine');
            $table->double('prix_club_chine');
            $table->double('prix_vente_turquire');
            $table->double('prix_club_turquire');
            $table->double('prix_vente_maroc');
            $table->double('prix_club_maroc');
            $table->double('prix_vente_ile_maurice');
            $table->double('prix_club_ile_maurice');
            $table->double('prix_vente_emirats_arabes_unis');
            $table->double('prix_club_emirats_arabes_unis');
            $table->double('prix_vente_tunisie');
            $table->double('prix_club_tunisie');
            $table->double('prix_vente_algerie');
            $table->double('prix_club_algerie');
            $table->integer('quantity');
            $table->string('parcel_barcode',13);
            $table->string('parcel_code');
            $table->string('hangtag_print_date');
            $table->double('prix_vente_qatar');
            $table->double('prix_club_qatar');
            $table->string('style_number');
            $table->double('prix_vente_usa');
            $table->double('prix_club_usa');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orchestra_item_details');
    }
}
