<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NominatedForwarder extends Model
{
    /**
      * The table associated with the model.
      *
      * @var string
      */
      protected $table = 'nominated_forwarders';

     /**
      * The attributes that are mass assignable.
      *
      * @var array
      */
      protected $fillable = [,'forwarder'];
}
