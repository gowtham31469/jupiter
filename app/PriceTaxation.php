<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PriceTaxation extends Model
{
    /**
      * The table associated with the model.
      *
      * @var string
      */
      protected $table = 'price_taxation';

     /**
      * The attributes that are mass assignable.
      *
      * @var array
      */
      protected $fillable = ['customer_id','buyer_id','template_id','custom_name','moq'];
}
