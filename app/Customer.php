<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Customer extends Authenticatable
{
	 /**
      * The table associated with the model.
      *
      * @var string
      */
      protected $table = 'customers';

     /**
      * The attributes that are mass assignable.
      *
      * @var array
      */
      protected $fillable = ['company_name','factory_code','email', 'password','courier_choice','account_no','tin_number','pan_number','gstin_number','is_active','pi_required','account_holder','ifsc_code','bank','branch','payment_terms','place_of_supply','freight_type','nominated_forwarder','po_required','credit_limit','delivery_date_required','delivery_day_count'];
    

     /**
      * The attributes that should be hidden for arrays.
      *
      * @var array
      */
	   protected $hidden = ['password', 'remember_token'];
}
