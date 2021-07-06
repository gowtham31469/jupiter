<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TescoSupplier extends Model
{
    /**
      * The table associated with the model.
      *
      * @var string
      */
      protected $table = 'tesco_suppliers';

      protected $primaryKey = 'id';

     /**
      * The attributes that are mass assignable.
      *
      * @var array
      */
      protected $fillable = ['supplier_name','supplier_code','is_enabled'];
}
