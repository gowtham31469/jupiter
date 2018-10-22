<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TescoMaterialMaster extends Model
{
    /**
      * The table associated with the model.
      *
      * @var string
      */
      protected $table = 'tesco_material_masters';

     /**
      * The attributes that are mass assignable.
      *
      * @var array
      */
      protected $fillable = ['material_name_employee','material_name_production','material_name_purchase','is_enabled'];
}
