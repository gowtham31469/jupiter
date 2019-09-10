<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrchestraSolidPolyStickerQty extends Model
{
    protected $table = 'orchestra_solid_poly_bag_sticker_orders_qty';

    public function solidpolysticker()
    {
        return $this->belongsTo(OrchestraSolidPolySticker::class);
    }
}
