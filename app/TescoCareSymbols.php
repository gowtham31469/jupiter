<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TescoCareSymbols extends Model
{
    /**
      * The table associated with the model.
      *
      * @var string
      */
      protected $table = 'tesco_care_symbols';

     /**
      * The attributes that are mass assignable.
      *
      * @var array
      */
      protected $fillable = ['category_id', 'iso_symbols', 'astms_symbol', 'care_instruction', 'degree', 'is_enabled'];
}
