<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProfomaInvoiceItemDetails extends Model
{
    /**
      * The table associated with the model.
      *
      * @var string
      */
      protected $table = 'profoma_invoice_item_details';

     /**
      * The attributes that are mass assignable.
      *
      * @var array
      */
      protected $fillable = ['pi_id','material','size','style','quantity','unit_cost'];
}
