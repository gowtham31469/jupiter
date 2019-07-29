<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class SolidPolyStickerOrders extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'orchestra_solid_poly_bag_sticker_orders';
    protected $fillable =['uvc','template_id','po','height','delivery_date','svg_path','status','group_id','pi_status','po_status','customer_id','orchestra_parcel_details_id','season_code'];
}
