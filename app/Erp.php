<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Erp extends Authenticatable
{
    protected $fillable = [
      'name','email', 'password'
  ];

  //hidden attributes
   protected $hidden = [
       'password', 'remember_token',
   ];
}
