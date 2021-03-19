<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TescoSecondarySizeCategory extends Model
{
    /**
      * The table associated with the model.
      *
      * @var string
      */
      protected $table = 'tesco_secondary_size_category';

     /**
      * The attributes that are mass assignable.
      *
      * @var array
      */
      protected $fillable = ['category_name'];
}
