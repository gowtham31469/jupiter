<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\CustomerResetPasswordNotification;
use Illuminate\Notifications\Notifiable;

class CustomerProfile extends Authenticatable
{
     use Notifiable;
     /**
      * The table associated with the model.
      *
      * @var string
      */
      protected $table = 'customer_profile';

     /**
      * The attributes that are mass assignable.
      *
      * @var array
      */
      protected $fillable = ['title', 'name' ,'email' ,'password' , 'role_id' , 'remember_token' , 'created_at' , 'updated_at'];

      /**
       * [sendPasswordResetNotification to  send forgot password notfication]
       * @param  [type] $token [description]
       */
      public function sendPasswordResetNotification($token)
      {
          $this->notify(new CustomerResetPasswordNotification($token,$this->name));
      }
}
