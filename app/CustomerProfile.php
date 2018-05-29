<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerProfile extends Model
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
