<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Customer extends Authenticatable
{
    protected $fillable = [
      'company_name','factory_code','email', 'password','courier_choice','account_no','tin_number','pan_number','gstin_number','is_active'
  ];

  //hidden attributes
   protected $hidden = [
       'password', 'remember_token',
   ];
}
