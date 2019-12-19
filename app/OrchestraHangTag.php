<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrchestraHangTag extends Model
{
    protected $table = 'orchestra_hang_tag_orders';
	 
     public function hangtagqty()
    {
        return $this->hasMany(OrchestraHangTagQty::class,'oht_id');
    }
}
