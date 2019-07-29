<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class HeatTransferOrders extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'orchestra_heat_transfer_orders';
    protected $fillable =['uvc','template_id','delivery_date','svg_path','status','pi_status','po_status','customer_id','orchestra_parcel_details_id','season_code'];
}
