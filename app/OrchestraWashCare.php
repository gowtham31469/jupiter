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
}
