<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TescoPrimarySize extends Model
{
    /**
      * The table associated with the model.
      *
      * @var string
      */
      protected $table = 'tesco_primary_size';

     /**
      * The attributes that are mass assignable.
      *
      * @var array
      */
      protected $fillable = ['tesco_size_department_id','department_title','uk_size','ce_size','is_flag'];
}
