<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TescoSizeChart extends Model
{
    /**
      * The table associated with the model.
      *
      * @var string
      */
      protected $table = 'tesco_size_chart';

      protected $primaryKey = 'id';

     /**
      * The attributes that are mass assignable.
      *
      * @var array
      */
      protected $fillable = ['po_size','ce','uk','is_enabled'];
}
