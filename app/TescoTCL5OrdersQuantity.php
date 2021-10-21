<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TescoTCL5OrdersQuantity extends Model
{
     /**
      * The table associated with the model.
      *
      * @var string
      */
      protected $table = 'tesco_tcl5_orders_quanty';

     /**
      * The attributes that are mass assignable.
      *
      * @var array
      */
      protected $fillable = ['tcl5_order_id','tpnd','itf_barcode','units_per_carton','ean_barcode','size','case_size','orms_tpn','cases_per_carton','count','cases_per_carton', 'qr_code'];

      public function order() {

          return $this->belongsTo('App\TescoTCL5Orders', 'tcl5_order_id');
          
      }
}
