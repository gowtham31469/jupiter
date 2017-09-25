<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Merchandiser extends Authenticatable
{
    protected $fillable = [
      'email', 'password'
  	];

  //hidden attributes
   protected $hidden = [
       'password', 'remember_token',
   ];
}
