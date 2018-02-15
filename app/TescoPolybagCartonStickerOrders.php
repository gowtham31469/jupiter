<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TescoPolybagCartonStickerOrders extends Model
{
    /**
      * The table associated with the model.
      *
      * @var string
      */
      protected $table = 'tesco_polybag_carton_sticker_orders';

     /**
      * The attributes that are mass assignable.
      *
      * @var array
      */
      protected $fillable = ['order_id','po_number','uk_style_ref','ce_style_ref','season_code','short_description','eqos_site_code','manufacture_date','tesco_department_id','tesco_brand_id','tesco_supplier_id','rfid_compliant','tag_at_source','zone','template_type'];
}
