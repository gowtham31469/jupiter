<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NonArtworkOrderQuanty extends Model
{
    protected $table = 'non_artwork_orders_quanty';
    protected $fillable=['non_artwork_order_id',
                            'quantity',
                            'size',
                            'size_id',
                            'rate',
                            'uom',
                            'uom_id',
                            'rate_value' ];
}
