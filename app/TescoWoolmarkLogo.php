<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TescoWoolmarkLogo extends Model
{
    //
    protected $fillable = [
        'woolmark_logo',
        'woolmark_logo_text',
        'is_enabled',
    ];
}
