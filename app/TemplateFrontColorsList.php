<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TemplateFrontColorsList extends Model
{
    protected $table = 'template_front_colors_list';

    protected $fillable = [
            'template_board_color_id',
            'front_color_text'
    ];
}
