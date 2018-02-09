<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProfomaInvoice extends Model
{
     /**
      * The table associated with the model.
      *
      * @var string
      */
      protected $table = 'profoma_invoices';

     /**
      * The attributes that are mass assignable.
      *
      * @var array
      */
      protected $fillable = ['company_id','customer_id','shipping_address','billing_address','total_quantity','sub_total','tax','bill_amount'];
}
