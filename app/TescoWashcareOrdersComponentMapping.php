<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TescoWashcareOrdersComponentMapping extends Model
{
    protected $table = 'tesco_washcare_orders_component_mapping';

    protected $fillable = [
        'washcare_order_id',
        'garment_component_id'
    ]; 

    protected $casts = [
        'garment_component_text' => 'json',
    ];
}
