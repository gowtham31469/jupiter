<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TescoTemplate extends Model
{
    /**
      * The table associated with the model.
      *
      * @var string
      */
      protected $table = 'tesco_template';

     /**
      * The attributes that are mass assignable.
      *
      * @var array
      */
      protected $fillable = ['item_id', 'template_name', 'template_description', 'template_file', 'disney', 'tax', 'created_at', 'updated_at', 'gl_code', 'hsn_code', 'color', 'dimension_height', 'dimension_width', 'gl_text', 'material_id', 'hierarchy_id', 'flag', 'name_in_reports'];
}
