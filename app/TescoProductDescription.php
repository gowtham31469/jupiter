<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TescoProductDescription extends Model
{
    //
    protected $fillable = ['english', 'polish', 'czech', 'slovak', 'hungarian', 'is_enabled'];
}
