<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TempOrchestraParcelQuantityDetails extends Model
{
    protected $fillable = [
        'FactoryCode', 'EditionNumber', 'VersionNumber','BuyerPONo','Quantity','created_at','updated_at','Theme','UVC','UVC_TCO','Size','Color','BarcodeForPricesTags'
    ];
}
