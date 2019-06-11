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
        $this->notify(new ErpuserResetPassword($token,$this->name));
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
	/**
     * Fetch specified erpuser details.
     *
     * @param  erpuserid  $id
     * @return void
     */
	 public static function user($id)
    {
        $base_data = Erpuser::where('id', $id)->get();
		$companies = array();
		foreach($base_data as $base){
			
			$base->buyer = \App\ErpuserBuyerMapping::where('erp_user', $base->id)
								->join('buyers', 'erpuser_buyer.buyer', 'buyers.id')
								->select('buyers.id')
								->get();
			
			$base->company = \App\ErpuserCompanyMapping::where('erp_user', $base->id)
								->join('company', 'erpuser_company_mapping.company', 'company.id')
								->select('company.id')->get();
			
			$base->roles = \App\ErpUserRolesMapping::where('erp_user', $base->id)
								->join('erp_roles', 'erpusers_roles_mapping.role', 'erp_roles.id')
								->select('erp_roles.id')
								->get();
			
			$user_template = \App\ErpusersTemplateMapping::where('erp_user', $base->id)
								->join('buyers', 'erpusers_template_mapping.buyer', 'buyers.id')
								->select('buyers.buyer_name', 'buyers.id', 'erpusers_template_mapping.templates')
								->get();
			$temp = array();
			foreach($base->buyer as $b){
				foreach($user_template as $t){
					if($b->id == $t->id){
						$temp[$t->buyer_name][] = $t->templates;
					}
				}
			}
			$base->user_template = $temp;
		}
		
		return $base_data;
    }
	
}
