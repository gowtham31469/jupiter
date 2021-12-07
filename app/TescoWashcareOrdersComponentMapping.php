<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TescoWashcareOrdersComponentMapping extends Model
{
    protected $table = 'tesco_washcare_orders_component_mapping';

    protected $fillable = [
        'washcare_order_id',
        'garment_component_id',
        'garment_component_text',
        'total_composition_percentage'
    ]; 

    protected $casts = [
        'garment_component_text' => 'array',
    ];

    public function fibers()
    {
        return $this->hasMany('App\TescoWashcareOrdersComponentFibreMapping', 'washcare_component_id')->orderBy('id', 'asc');
    }
}
