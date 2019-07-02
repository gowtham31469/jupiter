<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ErpUserRolesMapping extends Model
{
    /**
      * The table associated with the model.
      *
      * @var string
      */
      protected $table = 'erpusers_roles_mapping';

     /**
      * The attributes that are mass assignable.
      *
      * @var array
      */
      protected $fillable = ['erp_user', 'role', 'created_at', 'updated_at'];
	/**
		* Get the companies of user.
		*/
		public function users()
		{
			return $this->hasMany('App\Erpuser');
		}
	/**
		* Get the roles of user.
		*/
		public function roles()
		{
			return $this->hasMany('App\ErpRoles');
		}
}
