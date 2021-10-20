<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TescoTSL2OrdersQuantity extends Model
{
    /**
      * The table associated with the model.
      *
      * @var string
      */
      protected $table = 'tesco_tsl2_orders_quanty';

     /**
      * The attributes that are mass assignable.
      *
      * @var array
      */
      protected $fillable = ['tsl2_order_id','tpnd','itf_barcode','units_per_carton','ean_barcode','size','case_size','orms_tpn','cases_per_carton','count','cases_per_carton', 'ratio_pack_type', 'qr_code'];

      public function order() {

          return $this->belongsTo('App\TescoTSL2Orders', 'tsl2_order_id');
          
      }
}
