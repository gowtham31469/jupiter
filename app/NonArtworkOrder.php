<?php

namespace App;

use App\ErpOrderDetails;
use App\NonArtworkOrderQuanty;
use Illuminate\Database\Eloquent\Model;

class NonArtworkOrder extends Model
{
    //

    protected $appends = ['jc_status'];

    public function getJcStatusAttribute()
    {
        $nat_qty_id = NonArtworkOrderQuanty::where('non_artwork_order_id',$this->id)->first()->id;
        $js_status = ErpOrderDetails::where('order_qnty_table_name','non_artwork_orders_quanty')
                        ->where('order_qnty_table_id',$nat_qty_id)
                        ->first()->status;
        return $js_status;
    }
}


