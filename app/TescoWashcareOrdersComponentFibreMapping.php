<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TescoWashcareOrdersComponentFibreMapping extends Model
{
    protected $table = 'tesco_washcare_orders_component_fibre_mapping';

    protected $fillable = [
        'washcare_component_id',
        'fibre_composition_id',
        'fibre_composition_text',
        'fibre_composition_percentage'
    ]; 

    protected $casts = [
        'fibre_composition_text' => 'array',
    ];
}
