<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrchestraParcelQuantityDetails extends Model
{
    protected $fillable = [
        'FactoryCode',
        'EditionNumber',
        'VersionNumber',
        'BuyerPONo',
        'Theme',
        'UVC_TCO',
        'Quantity', 
        'Color', 
        'UVC',
        'Size',
        'BarcodeForPricesTags',
        'Country_Code',
        'Season_Code',
        'Currency', 
        'Price',
        'Club_Price',
        'Nom_Appel',
        'Last_Change_Date', 
        'Supplier_Code',
        'Supplier_Name',
        'Po_Status_Code',
        'Po_Status_Code_Description'
    ];
}
