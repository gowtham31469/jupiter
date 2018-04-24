<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TescoDepartment extends Model
{
    /**
      * The table associated with the model.
      *
      * @var string
      */
      protected $table = 'tesco_department';

     /**
      * The attributes that are mass assignable.
      *
      * @var array
      */
      protected $fillable = ['department','pantone','rbg','is_enabled'];
}
