<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrchestraCountries extends Model
{
    protected $table = 'orchestra_countries';
     protected $fillable = [
      'french','spanish','portuguese','italian','dutch','german','russian','chinese','arabic','greek','turkish','english'
     ];
}
