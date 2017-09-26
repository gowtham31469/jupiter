<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrchestraParcelDetails extends Model
{
    protected $fillable = [
        'FactoryCode', 'SupplierName', 'SupplierAddress','SupplierEmail', 'SupplierCountry', 'EditionNumber', 'VersionNumber','BuyerPONo','Quantity','ParcelBarCode','ParcelCode','Style_Name','created_at','updated_at','IsSTD','CatgDescr','STD','NomApple','Theme','UVC','BuyerPONo'
    ];

}
