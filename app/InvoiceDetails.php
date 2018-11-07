<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceDetails extends Model
{
    /**
      * The table associated with the model.
      *
      * @var string
      */
      protected $table = 'invoice_details';

     /**
      * The attributes that are mass assignable.
      *
      * @var array
      */
      protected $fillable = ['id','erp_order_id','style_no','ordered_qnty','delivered_qnty','size','delivery_date','order_qnty_table_name','order_qnty_table_id','status','created_at','updated_at','company_id','template_id','reference_no','ocn_no','pending_orders','rate','invoice_id','hsn_code','order_no','item','tax'];
}
