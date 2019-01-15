<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvExtraCharges extends Model
{
    /**
      * The table associated with the model.
      *
      * @var string
      */
      protected $table = 'inv_extra_charges';

     /**
      * The attributes that are mass assignable.
      *
      * @var array
      */
      protected $fillable = ['id','ext_chrg_name','amount','invoice_id','created_at','updated_at'];
}
