<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TescoSizeDepartment extends Model
{
    /**
      * The table associated with the model.
      *
      * @var string
      */
      protected $table = 'tesco_size_department';

     /**
      * The attributes that are mass assignable.
      *
      * @var array
      */
      protected $fillable = ['department_name'];
}
