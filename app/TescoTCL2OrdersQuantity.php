<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TescoTCL2OrdersQuantity extends Model
{
    /**
      * The table associated with the model.
      *
      * @var string
      */
      protected $table = 'tesco_tcl2_orders_quanty';

     /**
      * The attributes that are mass assignable.
      *
      * @var array
      */
      protected $fillable = ['tcl2_order_id','tpnd','itf_barcode','units_per_carton','ean_barcode','size','case_size','orms_tpn','cases_per_carton','count'];
}
