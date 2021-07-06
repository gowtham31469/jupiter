<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrdersStatus extends Model
{
    /**
      * The table associated with the model.
      *
      * @var string
      */
      protected $table = 'orders_status';

     /**
      * The attributes that are mass assignable.
      *
      * @var array
      */
      protected $fillable = ['id','status_name','status_desc'];
}
