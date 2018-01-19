<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TescoStickerOrdersSizeDetails extends Model
{
    /**
      * The table associated with the model.
      *
      * @var string
      */
      protected $table = 'tesco_sticker_orders_size_details';

     /**
      * The attributes that are mass assignable.
      *
      * @var array
      */
      protected $fillable = ['tesco_order_id','tpnd','itf_barcode','units_per_carton','ean_barcode','size','case_size','orms_tpn','tcl1_count','tcl1_event','tsl1_count','tsl1_event','tcl2_count','tcl2_event','tcl4_count','tcl4_event','tsl3_count','tsl3_event','thl1_count','thl1_event','thl3_count','thl3_event'];
}
