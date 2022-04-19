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

    public function template() {

        return $this->belongsTo('App\OrchestraItemMaster', 'template_id');
        
    }

    public function customer() {

      return $this->belongsTo('App\Customer', 'tesco_supplier_id');
      
    }
}
