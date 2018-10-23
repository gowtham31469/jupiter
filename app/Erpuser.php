<?php

namespace App;

use App\Notifications\ErpuserResetPassword;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB;

class Erpuser extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'deleted_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ErpuserResetPassword($token));
    }
	/**
     * Fetch all the employees.
     *
     * @param  string  $token
     * @return void
     */
	 public static function users()
    {
        $base_data = Erpuser::orderBy('id', 'asc')->get();
		$companies = array();
		foreach($base_data as $base){
			$base->company = \App\ErpuserCompanyMapping::where('erp_user', $base->id)
								->join('company', 'erpuser_company_mapping.company', 'company.id')
								->select('company.name', 'company.id')->get();
			
			$base->buyer = \App\ErpuserBuyerMapping::where('erp_user', $base->id)
								->join('buyers', 'erpuser_buyer.buyer', 'buyers.id')
								->select('buyers.buyer_name as name', 'buyers.id')
								->get();
			
			$base->roles = \App\ErpUserRolesMapping::where('erp_user', $base->id)
								->join('erp_roles', 'erpusers_roles_mapping.role', 'erp_roles.id')
								->select('erp_roles.name', 'erp_roles.id')
								->get();
		}
		 
		return $base_data;
    }
}
