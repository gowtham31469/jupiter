<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TescoWASHCAREOrdersQuantity extends Model
{
    protected $table = 'tesco_washcare_orders_quanty';

    protected $fillable = [
        'washcare_order_id',
        'size_id',
        'uk_ean',
        'ce_ean',
        'quantity',
        'selected_secondary_sizes'
    ];

    /**
     * Get the order that owns the quantity details.
     */
    public function order()
    {
        return $this->belongsTo('App\TescoWASHCAREOrders', 'washcare_order_id');
    }
}
