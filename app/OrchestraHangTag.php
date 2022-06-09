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

    public function template() {

        return $this->belongsTo('App\OrchestraItemMaster', 'template_front_id');
        
    }

    public function customer() {

      return $this->belongsTo('App\Customer', 'tesco_supplier_id');
      
    }
}
