<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TescoSwingTicketOrdersQuantity extends Model
{
    protected $table = 'tesco_swing_ticket_orders_quanty';

    protected $fillable = [
        'swing_ticket_order_id',
        'size_id',
        'ean_barcode',
        'quantity',
    ];

    /**
     * Get the order that owns the quantity details.
     */
    public function order()
    {
        return $this->belongsTo('App\TescoSwingTicketOrders', 'swing_ticket_order_id');
    }
}
