<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class HangTagOrders extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'orchestra_hang_tag_orders';
    protected $fillable =['uvc','template_front_id','template_back_id','delivery_date','svg_path','status'];
}
