<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ErpuserCompanyMapping extends Model
{
    /**
      * The table associated with the model.
      *
      * @var string
      */
      protected $table = 'erpuser_company_mapping';

     /**
      * The attributes that are mass assignable.
      *
      * @var array
      */
      protected $fillable = ['erp_user', 'company', 'created_at', 'updated_at', 'deleted_at'];
	/**
		* Get the companies of user.
		*/
		public function users()
		{
			return $this->hasMany('App\Erpuser');
		}
}
