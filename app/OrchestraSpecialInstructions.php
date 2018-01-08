<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrchestraSpecialInstructions extends Model
{
    protected $table = 'orchestra_care_instruction';
     protected $fillable = [
      'french','spanish','portuguese','italian','dutch','german','russian','chinese','arabic','greek','turkish','english'
     ];
}
