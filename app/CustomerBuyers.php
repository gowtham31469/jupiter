<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerBuyers extends Model
{
	 /**
      * The table associated with the model.
      *
      * @var string
      */
      protected $table = 'customer_buyers';

     /**
      * The attributes that are mass assignable.
      *
      * @var array
      */
      protected $fillable = ['customer_id', 'buyer_id'];
}
