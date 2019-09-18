<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrchestraStdPolySticker extends Model
{
    protected $table = 'orchestra_standard_poly_bag_sticker_orders';

	public function stdpolystickerqty()
	{
	return $this->hasMany(OrchestraStdPolyStickerQty::class,'ospbs_id');
	}
}
