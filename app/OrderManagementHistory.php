<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderManagementHistory extends Model
{
    /**
      * The table associated with the model.
      *
      * @var string
      */
      protected $table = 'order_management_history';

      protected $primaryKey = 'id';

     /**
      * The attributes that are mass assignable.
      *
      * @var array
      */
      protected $fillable = ['buyer_id','season', 'customer_id', 'material', 'sub_material', 'style', 'reference', 'customer_po', 'buyer_po', 'ocn_number', 'order_details_table_name', 'order_details_table_id', 'order_status', 'artwork_status', 'po_status', 'pi_status'];
}
