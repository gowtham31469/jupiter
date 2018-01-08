<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class SolidPolyStickerOrdersQty extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'orchestra_solid_poly_bag_sticker_order_qty';
    protected $fillable =['ospbs_id','size','quantity','quantity_sets'];
}
