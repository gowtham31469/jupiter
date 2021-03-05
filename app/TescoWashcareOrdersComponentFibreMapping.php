<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TescoWashcareOrdersComponentFibreMapping extends Model
{
    protected $fillable = [
        'washcare_component_id',
        'fibre_composition_id',
        'fibre_composition_percentage'
    ]; 

    protected $casts = [
        'fibre_composition_text' => 'json',
    ];
}
