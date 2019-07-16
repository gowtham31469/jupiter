<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrchestraColorCode extends Model
{
    protected $table = 'orchestra_color_code';
    protected $fillable = ['code','french','english','is_enabled'];
}
