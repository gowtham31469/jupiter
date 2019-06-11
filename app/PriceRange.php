<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PriceRange extends Model
{
    /**
      * The table associated with the model.
      *
      * @var string
      */
      protected $table = 'price_range';

     /**
      * The attributes that are mass assignable.
      *
      * @var array
      */
      protected $fillable = ['price_taxation_id','volume_from','volume_to','price'];
}
