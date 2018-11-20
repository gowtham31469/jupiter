<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ErpOrdersInvoice extends Model
{
    /**
      * The table associated with the model.
      *
      * @var string
      */
      protected $table = 'erp_orders_invoice';

     /**
      * The attributes that are mass assignable.
      *
      * @var array
      */

      protected $fillable = ['id','erp_order_id','invoice_id','created_at','updated_at'];
}
