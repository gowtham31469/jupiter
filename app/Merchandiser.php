<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Merchandiser extends Authenticatable
{
    protected $fillable = [
      'email', 'password','buyer_id','customer_id','merchandiser_name','merchandiser_address','merchandiser_phone'
  	];

  //hidden attributes
   protected $hidden = [
       'password', 'remember_token',
   ];
}
