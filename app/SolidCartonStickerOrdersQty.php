<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class SolidCartonStickerOrdersQty extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'orchestra_solid_carton_sticker_orders_qty';
    protected $fillable =['oscs_id','size','quantity','quantity_sets'];
}
