<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TescoComponents extends Model
{
     /**
      * The table associated with the model.
      *
      * @var string
      */
      protected $table = 'tesco_components';

     /**
      * The attributes that are mass assignable.
      *
      * @var array
      */
      protected $fillable = ['english', 'polish', 'czech', 'slovak', 'hungarian', 'is_enabled'];
}
