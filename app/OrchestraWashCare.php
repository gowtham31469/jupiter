<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrchestraWashCare extends Model
{
	
	protected $table = 'orchestra_wash_care_label_orders';

	public function washcarelabelQty()
	{
	return $this->hasMany(OrchestraWashCareQty::class,'owcl_id');
	}

	public function template() {

        return $this->belongsTo('App\OrchestraItemMaster', 'template_id');
        
    }

    public function customer() {

      return $this->belongsTo('App\Customer', 'tesco_supplier_id');
      
    }
}
