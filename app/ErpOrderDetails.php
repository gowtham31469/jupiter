<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ErpOrderDetails extends Model
{
    /**
      * The table associated with the model.
      *
      * @var string
      */
      protected $table = 'erp_order_details';

     /**
      * The attributes that are mass assignable.
      *
      * @var array
      */
      protected $fillable = ['id','erp_order_id','style_no','order_quantity','delivered_quantity','size','delivery_date','order_qnty_table_name','order_qnty_table_id','status','created_at','updated_at','company_id','template_id','reference_name','ocn_number','pending_orders'];
}
