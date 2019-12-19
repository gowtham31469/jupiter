<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrchestraStdCartonStickerQty extends Model
{
    protected $table = 'orchestra_standard_carton_sticker_orders_qty';
	
    public function stdpolysticker()
    {
        return $this->belongsTo(OrchestraStdCartonSticker::class);
    }
}
