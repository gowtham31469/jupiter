<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrchestraAgeGroup extends Model
{
	 /**
      * The table associated with the model.
      *
      * @var string
      */
      protected $table = 'orchestra_age_group';

     /**
      * The attributes that are mass assignable.
      *
      * @var array
      */
      protected $fillable = ['age_group_name','color','is_enabled'];
}
