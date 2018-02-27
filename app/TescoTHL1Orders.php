<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TescoTHL1Orders extends Model
{
    /**
      * The table associated with the model.
      *
      * @var string
      */
      protected $table = 'tesco_thl1_orders';

     /**
      * The attributes that are mass assignable.
      *
      * @var array
      */
      protected $fillable = ['order_id','po_number','uk_style_ref','ce_style_ref','season_code','short_description','eqos_site_code','manufacture_date','tesco_department_id','tesco_brand_id','tesco_supplier_id','rfid_compliant','tag_at_source','zone','template_type','pi_status','po_status','po_file',' purchase_order_no','order_status','artwork_status','print_status','template_id','delivery_date'];
}
