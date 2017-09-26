<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrchestraParcelDetails extends Model
{
    protected $fillable = [
        'FactoryCode', 'SupplierName', 'SupplierAddress','SupplierEmail', 'SupplierCountry', 'EditionNumber', 'VersionNumber','Collection', 'BuyerPONo', 'BarcodeForPricesTags','Description','Rayon', 'Color', 'Size','Stature','Age','Theme','Marque','Disney','Marvel','PommeFramboise','Quantity','ParcelBarCode','	ParcelCode','HangTag_Print_Date','Prix_Vente_QATAR','Prix_Club_QATAR','Style_Name','Prix_Vente_USA','Prix_Club_USA','created_at','updated_at'
    ];

}
