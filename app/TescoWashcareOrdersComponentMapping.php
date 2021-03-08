<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TescoWashcareOrdersComponentMapping extends Model
{
    protected $fillable = [
        'washcare_order_id',
        'garment_component_id'
    ]; 

    protected $casts = [
        'garment_component_text' => 'json',
    ];
}
