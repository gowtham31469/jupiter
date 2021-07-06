<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrchestraGarments extends Model
{
    
    /**
      * The table associated with the model.
      *
      * @var string
      */
      protected $table = 'orchestra_garments';

     /**
      * The attributes that are mass assignable.
      *
      * @var array
      */
      protected $fillable = ['garment_name','is_enabled'];
}
