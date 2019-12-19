<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ErpuserBuyerMapping extends Model
{
    protected $table = 'erpuser_buyer';

     /**
      * The attributes that are mass assignable.
      *
      * @var array
      */
      protected $fillable = ['erp_user', 'buyer', 'created_at', 'updated_at', 'deleted_at'];
	/**
		* Get the companies of user.
		*/
		public function users()
		{
			return $this->hasMany('App\Erpuser');
		}
}
