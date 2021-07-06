<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class WashLabelOrdersQty extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'orchestra_wash_care_label_orders_qty';
    protected $fillable =['owcl_id','size','barcode','uvc_tco','quantity'];
}
