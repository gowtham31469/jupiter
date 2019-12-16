<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    /**
      * The table associated with the model.
      *
	  * author <gubera>
	  *
      * @var string
      */
      protected $table = 'company';

     /**
      * The attributes that are mass assignable.
      *
      * @var array
      */
      protected $fillable = ['name', 'address', 'email', 'gstin', 'account_no', 'account_holder', 'ifsc_code', 'bank', 'branch', 'account_type', 'logo','phone','office_phone', 'created_at', 'updated_at'];

}
