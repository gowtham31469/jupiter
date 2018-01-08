<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class WashLabelOrders extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'orchestra_wash_care_label_orders';
    protected $fillable =['uvc','template_id','merchandisers_id','washing_id','whitening_id','dryer_id','ironing_id','cleaning_id','made_in_id','care_instruction_id','template_name','delivery_date','svg_path','status'];
}
