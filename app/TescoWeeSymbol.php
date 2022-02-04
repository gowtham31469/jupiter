<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TescoWeeSymbol extends Model
{
    protected $fillable = [
        'wee_symbol',
        'wee_symbol_text',
        'is_enabled',
    ]; 
}
