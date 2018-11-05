<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ErpOrders extends Model
{
    /**
      * The table associated with the model.
      *
      * @var string
      */
      protected $table = 'erp_orders';

     /**
      * The attributes that are mass assignable.
      *
      * @var array
      */
      protected $fillable = ['id','customer_id','buyer_id','po_no','po_file','status','created_at','updated_at','order_status_id'];
}
