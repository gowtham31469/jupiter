<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrchestraStdPolyStickerQty extends Model
{
    protected $table = 'orchestra_standard_poly_bag_sticker_orders_qty';

	public function stdpolystickerqty()
	{
	return $this->belongsTo(OrchestraStdPolyStickerQty::class);
	}
}
