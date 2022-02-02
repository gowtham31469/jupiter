<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TescoProductBenefit extends Model
{
    //
    protected $fillable = ['english', 'polish', 'czech', 'slovak', 'hungarian', 'is_enabled','template_id'];
}
