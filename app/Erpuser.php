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
        $base_data = Erpuser::all();
		$companies = array();
		foreach($base_data as $base){
			$base->company = ErpuserCompanyMapping::where('erp_user', $base->id)
								->join('company', 'erpuser_company_mapping.company', 'company.id')
								->select('company.name', 'company.id')->get();
			
			$base->buyer = ErpuserBuyerMapping::where('erp_user', $base->id)
								->join('buyers', 'erpuser_buyer.buyer', 'buyers.id')
								->select('buyers.buyer_name as name', 'buyers.id')
								->get();
		}
		 
		return $base_data;
    }
}
