<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrchestraProductFamily extends Model
{
    protected $table = 'orchestra_product_family';
     protected $fillable = [
      'french','arabic','english','code'
     ];
}
