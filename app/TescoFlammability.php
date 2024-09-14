<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TescoFlammability extends Model
{
    protected $fillable = ['flammability_text', 'is_enabled'];
}
