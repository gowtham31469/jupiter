<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoices extends Model
{
    /**
      * The table associated with the model.
      *
      * @var string
      */
      protected $table = 'invoice';

     /**
      * The attributes that are mass assignable.
      *
      * @var array
      */
      protected $fillable = ['id','customer_id','buyer_id','po_no','po_file','status','created_at','updated_at', 'invoice_file','erp_id','invoice_id','status_id','round_off_amnt','grand_total','eway_no','credit_limit','dispatch_thru','place_of_supply','delivery_at','bill_to','base_amnt'];

      /**
       * Get the customer.
       */
      public function customer()
      {
          return $this->belongsTo('App\Customer');
      }
      /**
       * Get the Invoice Details.
       */
      public function invoiceDetails()
      {
          return $this->hasMany('App\InvoiceDetails', 'invoice_id');
      }
      /**
       * Get the buyer.
       */
      public function buyer()
      {
          return $this->belongsTo('App\Buyer');
      }
}
