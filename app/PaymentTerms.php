<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentTerms extends Model
{
    /**
      * The table associated with the model.
      *
      * @var string
      */
      protected $table = 'payment_terms';

     /**
      * The attributes that are mass assignable.
      *
      * @var array
      */
      protected $fillable = ['payment_terms'];
}
