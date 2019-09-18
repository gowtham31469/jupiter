<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrchestraSolidCartonStickerQty extends Model
{
	protected $table = 'orchestra_solid_carton_sticker_orders_qty';
	
    public function solidcartonsticker()
    {
        return $this->belongsTo(OrchestraSolidCartonSticker::class);
    }
}
