<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class CustomerProfile extends Authenticatable
{
     /**
      * The table associated with the model.
      *
      * @var string
      */
      protected $table = 'customer_profile';

     /**
      * The attributes that are mass assignable.
      *
      * @var array
      */
      protected $fillable = ['title', 'name' ,'email' ,'password' , 'role_id' , 'remember_token' , 'created_at' , 'updated_at'];
}
