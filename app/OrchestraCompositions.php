<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrchestraCompositions extends Model
{
    protected $table = 'orchestra_compositions';
     protected $fillable = [
      'french','spanish','portuguese','italian','dutch','german','russian','chinese','arabic','greek','turkish','english'
     ];
}
