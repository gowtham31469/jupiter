<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlaceOfSupply extends Model
{
    /**
      * The table associated with the model.
      *
      * @var string
      */
      protected $table = 'place_of_supply';

     /**
      * The attributes that are mass assignable.
      *
      * @var array
      */
      protected $fillable = ['place_of_supply'];
}
