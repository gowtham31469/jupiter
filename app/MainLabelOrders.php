<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class MainLabelOrders extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'orchestra_main_label_orders';
    protected $fillable =['uvc','template_id','template_name','delivery_date','svg_path','status','pi_status','po_status'];
}
