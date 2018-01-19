<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TescoBrand extends Model
{
     /**
      * The table associated with the model.
      *
      * @var string
      */
      protected $table = 'tesco_brand';

     /**
      * The attributes that are mass assignable.
      *
      * @var array
      */
      protected $fillable = ['brand','is_enabled'];
}
