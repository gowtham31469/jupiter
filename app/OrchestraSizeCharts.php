<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrchestraSizeCharts extends Model
{
    protected $table = 'orchestra_size_chart';
     protected $fillable = [
      'age','cm','in'
     ];
}
