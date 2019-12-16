<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FreightTerms extends Model
{
   
    /**
      * The table associated with the model.
      *
      * @var string
      */
      protected $table = 'freight_terms';

     /**
      * The attributes that are mass assignable.
      *
      * @var array
      */
      protected $fillable = ['freight_terms'];
}
