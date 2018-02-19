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
      protected $fillable = ['tesco_order_id','tpnd','itf_barcode','units_per_carton','ean_barcode','size','case_size','orms_tpn','cases_per_carton'];
}
