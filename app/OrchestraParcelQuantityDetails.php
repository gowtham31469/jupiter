<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrchestraParcelQuantityDetails extends Model
{
    protected $fillable = [
        'FactoryCode', 'EditionNumber', 'VersionNumber','BuyerPONo','Quantity','created_at','updated_at','Theme','UVC','UVC_TCO','Size','Color','BarcodeForPricesTags'
    ];
}
