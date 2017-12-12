<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerBuyers extends Model
{
    protected $table = 'customer_buyers';
  protected $fillable = [
      'customer_id', 'buyer_id'
  ];
}
