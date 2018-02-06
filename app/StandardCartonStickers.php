<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class StandardCartonStickers extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'orchestra_standard_carton_sticker_orders';
    protected $fillable =['uvc','template_id','po','height','no_of_carton','total_polybag','final_order_qty_sets','order_qty','final_order_qty','delivery_date','svg_path','status','pi_status','po_status'];
}
