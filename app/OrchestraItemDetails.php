<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrchestraItemDetails extends Model
{
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'FactoryCode', 'SupplierName', 'SupplierAddress','SupplierEmail', 'SupplierCountry', ' 	BuyerEmail','First_ETD_SHIPMENT_DATE', 'EditionNumber', 'VersionNumber','Collection', 'BuyerPONo', 'BarcodeForPricesTags','UVC_TCO', 'Description','Rayon', 'Color', 'Size','Stature','Age','Theme','Marque','Disney','Marvel','PommeFramboise','PrixEurope','PrixClubEurope','PrixVenteKoweit','Prix_Vente_Arabie_Saoudite','Prix_Club_Arabie_Saoudite','Prix_Vente_Canada','	Prix_Club_Canada','Prix_Vente_Suisse','Prix_Club_Suisse','Prix_Vente_Chine','Prix_Club_Chine','Prix_Club_Turquie','Prix_Vente_Maroc','Prix_Club_Maroc','	Prix_Vente_Ile_Maurice','Prix_Club_Ile_Maurice','Prix_Vente_Emirats_Arabes_Unis','Prix_Club_Emirats_Arabes_Unis','Prix_Vente_TUNISIE','Prix_Club_TUNISIE','Prix_Vente_ALGERIE','Prix_Club_ALGERIE','Quantity','UVC','ParcelBarCode','	ParcelCode','HangTag_Print_Date','Prix_Vente_QATAR','Prix_Club_QATAR','Style_Name','Prix_Vente_USA','Prix_Club_USA','created_at','updated_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}
