<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrchestraHangTagQty extends Model
{
    protected $table = 'orchestra_hang_tag_orders_qty';
	
    public function hangtag()
    {
        return $this->belongsTo(OrchestraHangTag::class);
    }
}
