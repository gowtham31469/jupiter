<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class SpecialTagOrders extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'orchestra_special_tag_orders';
    protected $fillable =['uvc','template_id','delivery_date','svg_path','final_qty','status','pi_status','po_status','customer_id','orchestra_parcel_details_id','season_code','color_code'];
}
