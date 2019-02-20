<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceAddress extends Model
{
   	/**
      * The table associated with the model.
      *
      * @var string
      */
      protected $table = 'invoice_address';

     /**
      * The attributes that are mass assignable.
      *
      * @var array
      */
    protected $fillable = ['customer_id', 'address', 'country','email','telephone','fax','is_billing_address','is_shipping_address','pincode'];
}
