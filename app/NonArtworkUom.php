<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NonArtworkUom extends Model
{
    /**
      * The table associated with the model.
      *
      * @var string
      */
      protected $table = 'non_artwork_uom';

      protected $primaryKey = 'id';

     /**
      * The attributes that are mass assignable.
      *
      * @var array
      */
      protected $fillable = ['uom','is_enabled'];
}
