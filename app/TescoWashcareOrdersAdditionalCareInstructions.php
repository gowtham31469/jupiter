<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TescoWashcareOrdersAdditionalCareInstructions extends Model
{
    protected $fillable = [
        'washcare_order_id',
        'additional_care_id'
    ]; 

    protected $casts = [
        'additional_care_text' => 'json',
    ];
}
