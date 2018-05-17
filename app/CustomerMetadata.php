<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerMetadata extends Model
{
    /**
      * The table associated with the model.
      *
      * @var string
      */
      protected $table = 'customer_metadata';

     /**
      * The attributes that are mass assignable.
      *
      * @var array
      */
      protected $fillable = ['customer_id', 'customer_metadata_id'];
}
