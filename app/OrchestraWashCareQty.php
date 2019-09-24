<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrchestraWashCareQty extends Model
{
		protected $table = 'orchestra_wash_care_label_orders_qty';

		public function washcare()
		{
		return $this->belongsTo(OrchestraWashCare::class);
		}
}
