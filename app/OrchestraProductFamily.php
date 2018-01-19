<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrchestraProductFamily extends Model
{
	/**
      * The table associated with the model.
      *
      * @var string
      */
      protected $table = 'orchestra_product_family';

     /**
      * The attributes that are mass assignable.
      *
      * @var array
      */
      protected $fillable = ['french','arabic','english','code'];
}
