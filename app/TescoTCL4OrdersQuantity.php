<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TescoTCL4OrdersQuantity extends Model
{
    /**
      * The table associated with the model.
      *
      * @var string
      */
      protected $table = 'tesco_tcl4_orders_quanty';

     /**
      * The attributes that are mass assignable.
      *
      * @var array
      */
      protected $fillable = ['tcl4_order_id','tpnd','itf_barcode','units_per_carton','ean_barcode','size','case_size','orms_tpn','cases_per_carton','count','cases_per_carton', 'qr_code'];

      public function order() {

          return $this->belongsTo('App\TescoTCL4Orders', 'tcl4_order_id');
          
      }
}
