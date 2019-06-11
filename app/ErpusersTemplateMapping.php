<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ErpusersTemplateMapping extends Model
{
    /**
      * The table associated with the model.
      *
      * @var string
      */
      protected $table = 'erpusers_template_mapping';

     /**
      * The attributes that are mass assignable.
      *
      * @var array
      */
      protected $fillable = ['erp_user', 'buyer', 'templates', 'company', 'created_at', 'updated_at'];
	
	/**
		* Get the Templates of user.
		*/
		public function users()
		{
			return $this->hasMany('App\Erpuser');
		}
}
