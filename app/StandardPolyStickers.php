<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class StandardPolyStickers extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'orchestra_standard_poly_bag_sticker_orders';
    protected $fillable =['uvc','template_id','po','height','no_of_carton','total_polybag','final_order_qty_sets','order_qty','final_order_qty','delivery_date','orchestra_parcel_details_id','season_code','svg_path','status','group_id','pi_status','po_status','customer_id','color_code'];
}
