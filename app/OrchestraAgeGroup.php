<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrchestraAgeGroup extends Model
{
	 protected $table = 'orchestra_age_group';
     protected $fillable = [
      'age_group_name','color'
     ];
}
