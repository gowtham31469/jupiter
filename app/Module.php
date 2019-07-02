<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $table = 'module';
    protected $fillable = [
        'parent_id', 'module_name', 'image','url_path','bg_color'
    ];

}
