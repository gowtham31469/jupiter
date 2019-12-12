<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class StandardCartonStickersQty extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'orchestra_standard_carton_sticker_orders_qty';
    protected $fillable =['oscs_id','size','barcode','uvc_tco','quantity','quantity_sets'];
}
