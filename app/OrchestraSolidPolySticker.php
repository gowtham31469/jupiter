<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrchestraSolidPolySticker extends Model
{
    protected $table = 'orchestra_solid_poly_bag_sticker_orders';

    public function solidpolystickerqty()
    {
        return $this->hasMany(OrchestraSolidPolyStickerQty::class,'ospbs_id');
    }
}
