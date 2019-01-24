<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ErpOrderDetails extends Model
{
	use SoftDeletes;
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
      protected $fillable = ['id', 'erp_order_id', 'style_no', 'order_quantity', 'delivered_quantity', 'size', 'delivery_date', 'order_qnty_table_name', 'order_qnty_table_id', 'status', 'created_at','updated_at', 'deleted_at', 'company_id', 'template_id', 'reference_name', 'ocn_number', 'pending_orders', 'template_type','custom_ref_name','buyer_po_number'];
	/**
     * Get the Orders.
     */
    public function orders()
    {
        return $this->belongsTo('App\ErpOrders');
    }
	/**
     * Get the Company.
     */
    public function company()
    {
        return $this->belongsTo('App\Company');
    }
	/**
     * Get the status.
     */
    public function getStatus()
    {
        return $this->belongsTo('App\ErpStatus', 'status');
    }
}
