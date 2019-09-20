<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrchestraStdCartonSticker extends Model
{
	protected $table = 'orchestra_standard_carton_sticker_orders';

	public function stdcartonstickerqty()
	{
	return $this->hasMany(OrchestraStdCartonStickerQty::class,'oscs_id');
	}
}
