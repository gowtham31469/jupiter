<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TescoStickerOrdersCount extends Model
{
    /**
      * The table associated with the model.
      *
      * @var string
      */
      protected $table = 'tesco_sticker_orders_count';

     /**
      * The attributes that are mass assignable.
      *
      * @var array
      */
      protected $fillable = ['tesco_order_id','template_name','count','order_status','artwork_status','print_status','pi_status','po_status','po_number','po_file'];
}
