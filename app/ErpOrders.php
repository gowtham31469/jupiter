<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ErpOrders extends Model
{
	use SoftDeletes;
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
      protected $fillable = ['id','customer_id','buyer_id','po_no','po_file','status','created_at','updated_at', 'deleted_at'];
	
	/**
     * Get the customer.
     */
    public function customer()
    {
        return $this->belongsTo('App\Customer');
    }
	/**
     * Get the buyer.
     */
    public function buyer()
    {
        return $this->belongsTo('App\Buyer');
    }
	/**
     * Get the status.
     */
    public function getStatus()
    {
        return $this->belongsTo('App\ErpStatus', 'status');
    }
	
	/**
      * Get Order Details.
      *
      * @var array
      */
	
	public static function orderDetails() {
		
    	$orders 	= \App\ErpOrders::withTrashed()->get();
		foreach($orders as $order){
			$customer 			= \App\ErpOrders::find($order->id);
			$order->customer	= $customer->customer->company_name;
			$order->buyer	 	= $customer->buyer->buyer_name;
			$order->status	 	= $customer->getStatus->status_name;
		}
		return $orders;
	} 
}
