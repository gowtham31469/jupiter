<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Buyer extends Model
{
     protected $fillable = [
      'buyer_name','buyer_address','buyer_email','buyer_phone'
  ];
}
