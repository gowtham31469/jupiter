<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerBillAddress extends Model
{
	 /**
      * The table associated with the model.
      *
      * @var string
      */
      protected $table = 'customer_bill_address';

     /**
      * The attributes that are mass assignable.
      *
      * @var array
      */
      protected $fillable = ['customer_id', 'address', 'country','email','telephone','fax','is_billing_address','is_shipping_address','pincode'];
}
