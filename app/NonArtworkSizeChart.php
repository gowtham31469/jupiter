<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NonArtworkSizeChart extends Model
{
    /**
      * The table associated with the model.
      *
      * @var string
      */
      protected $table = 'non_artwork_size_chart';

      protected $primaryKey = 'id';

     /**
      * The attributes that are mass assignable.
      *
      * @var array
      */
      protected $fillable = ['size','is_enabled'];
}
