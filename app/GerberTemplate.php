<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GerberTemplate extends Model
{
     /**
      * The table associated with the model.
      *
      * @var string
      */
      protected $table = 'gerber_template';

     /**
      * The attributes that are mass assignable.
      *
      * @var array
      */
      protected $fillable = ['item_id', 'material_id', 'hierarchy_id', 'template_name', 'template_description', 'template_file', 'disney', 'tax', 'gl_code', 'hsn_code', 'color', 'dimension_height', 'dimension_width', 'gl_text'];
}
