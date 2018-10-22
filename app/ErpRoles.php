<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ErpRoles extends Model
{
    /**
      * The table associated with the model.
      *
      * @var string
      */
      protected $table = 'erp_roles';

     /**
      * The attributes that are mass assignable.
      *
      * @var array
      */
      protected $fillable = ['name', 'created_at', 'updated_at', 'deleted_at'];
}
