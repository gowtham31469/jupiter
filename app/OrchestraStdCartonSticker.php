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

	public function template() {

        return $this->belongsTo('App\OrchestraItemMaster', 'template_id');
        
    }

    public function customer() {

      return $this->belongsTo('App\Customer', 'tesco_supplier_id');
      
    }
}
