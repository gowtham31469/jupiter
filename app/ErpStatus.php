<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ErpStatus extends Model
{
    /**
      * The table associated with the model.
      *
      * @var string
      */
      protected $table = 'erp_status';

     /**
      * The attributes that are mass assignable.
      *
      * @var array
      */
      protected $fillable = ['id','status_name','status_desc'];
}