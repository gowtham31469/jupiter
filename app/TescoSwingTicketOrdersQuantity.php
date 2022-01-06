<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TescoSwingTicketOrdersQuantity extends Model
{
    protected $table = 'tesco_swingticket_orders_quanty';

    protected $fillable = [
        'swingticket_order_id',
        'size_id',
        'ean_barcode',
        'quantity',
    ];

    /**
     * Get the order that owns the quantity details.
     */
    public function order()
    {
        return $this->belongsTo('App\TescoSwingTicketOrders', 'swingticket_order_id');
    }
}
