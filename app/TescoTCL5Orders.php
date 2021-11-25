<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TescoTCL5Orders extends Model
{
    /**
      * The table associated with the model.
      *
      * @var string
      */
      protected $table = 'tesco_tcl5_orders';

     /**
      * The attributes that are mass assignable.
      *
      * @var array
      */
      protected $fillable = ['order_id','po_number','uk_style_ref','ce_style_ref','season_code','short_description','eqos_site_code','manufacture_date','tesco_department_id','tesco_brand_id','tesco_supplier_id','rfid_compliant','tag_at_source','zone','template_type','pi_status','po_status','po_file',' purchase_order_no','order_status','template_id','delivery_date','is_order_count_empty','tesco_metadata_supplier_id','order_placed_by','pi_rev_no','edit_permission','cancel_ord_flag','order_created_at','order_approved_at','order_cancelled_at','ocn_number','po_uploaded_at','pi_id','pi_file','full_description', 'total_qty'];

      public function quantities() {

          return $this->hasMany('App\TescoTCL5OrdersQuantity','tcl5_order_id');

      }

      public function template() {

          return $this->belongsTo('App\TescoItemMasterMdl', 'template_id');
          
      }

      public function customer() {

        return $this->belongsTo('App\Customer', 'tesco_supplier_id');
        
      }
}
