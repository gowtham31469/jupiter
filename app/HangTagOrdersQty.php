<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class HangTagOrdersQty extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'orchestra_hang_tag_orders_qty';
    protected $fillable =['oht_id','size','uvc_tco','quantity','svg_path','status','parcel_id'];
}
