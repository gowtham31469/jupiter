<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Customer extends Authenticatable
{
    protected $fillable = [
      'company_name','contact_person','factory_code','contact_person','contact_emails','email', 'password','phone_no','courier_choice','account_no','tin_number','pan_number','gstn_number'
  ];

  //hidden attributes
   protected $hidden = [
       'password', 'remember_token',
   ];
}
