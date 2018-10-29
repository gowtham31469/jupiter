<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TescoTaxCode extends Model
{
   /**
      * The table associated with the model.
      *
      * @var string
      */
      protected $table = 'tax_code';

     /**
      * The attributes that are mass assignable.
      *
      * @var array
      */
      protected $fillable = ['tax_type','tax_code', 'is_enabled', 'tax'];
}
