<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Buyer extends Model
{
	/**
      * The table associated with the model.
      *
      * @var string
      */
      protected $table = 'buyers';

      protected $primaryKey = 'id';

     /**
      * The attributes that are mass assignable.
      *
      * @var array
      */
      protected $fillable = ['buyer_name','buyer_address','buyer_email','buyer_phone','is_active','logo'];
}
