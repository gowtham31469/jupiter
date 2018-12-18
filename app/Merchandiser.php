<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\MerchandiserResetPasswordNotification;
use Illuminate\Notifications\Notifiable;

class Merchandiser extends Authenticatable
{
    use Notifiable;
	  /**
      * The table associated with the model.
      *
      * @var string
      */
      protected $table = 'merchandisers';

     /**
      * The attributes that are mass assignable.
      *
      * @var array
      */
      protected $fillable = ['email', 'password','buyer_id','customer_id','merchandiser_name','merchandiser_address','merchandiser_phone','is_active','buyer_id'];
     /**
      * The attributes that should be hidden for arrays.
      *
      * @var array
      */
	   protected $hidden = ['password', 'remember_token'];

     /**
      * [sendPasswordResetNotification to  send forgot password notfication]
      * @param  [type] $token [description]
      */
     public function sendPasswordResetNotification($token)
     {
         $this->notify(new MerchandiserResetPasswordNotification($token,$this->merchandiser_name));
     }
}
