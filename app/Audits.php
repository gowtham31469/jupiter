<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Audits extends Model
{
    /**
      * The table associated with the model.
      *
      * @var string
      */
      protected $table = 'audits';

     /**
      * The attributes that are mass assignable.
      *
      * @var array
      */
      protected $fillable = ['user_id','module','ip_address','user_agent','event','old_values','new_values','url','created_at','updated_at'];
}
