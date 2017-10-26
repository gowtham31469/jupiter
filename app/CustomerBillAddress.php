<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerBillAddress extends Model
{
  protected $table = 'customer_bill_address';
  protected $fillable = [
      'customer_id', 'address', '	country','email','telephone','fax','default_address'
  ];
}
