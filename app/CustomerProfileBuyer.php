<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerProfileBuyer extends Model
{
     /**
      * The table associated with the model.
      *
      * @var string
      */
      protected $table = 'customer_profile_buyer';

     /**
      * The attributes that are mass assignable.
      *
      * @var array
      */
      protected $fillable = ['buyer_id', 'customer_profile_id' , 'created_at' , 'updated_at'];
}
