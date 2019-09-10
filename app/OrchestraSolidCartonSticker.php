<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrchestraSolidCartonSticker extends Model
{

	 protected $table = 'orchestra_solid_carton_sticker_orders';
	 
     public function solidcartonstickerqty()
    {
        return $this->hasMany(OrchestraSolidCartonStickerQty::class,'oscs_id');
    }
}
