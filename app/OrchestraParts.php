<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrchestraParts extends Model
{
    protected $table = 'orchestra_parts';
     protected $fillable = [
      'french','spanish','portuguese','italian','dutch','german','russian','chinese','arabic','greek','turkish','english'
     ];
}
