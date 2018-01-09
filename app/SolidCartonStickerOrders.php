<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class SolidCartonStickerOrders extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'orchestra_solid_carton_sticker_orders';
    protected $fillable =['uvc','template_id','po','height','delivery_date','svg_path','status'];
}
