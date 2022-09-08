<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TemplateColorMatchMapping extends Model
{
    protected $table = 'template_color_match_mapping';

    protected $fillable = [
            'template_board_color_id',
            'color_match_text'
    ];
}
