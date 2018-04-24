<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrchestraCareSymbols extends Model
{
     /**
      * The table associated with the model.
      *
      * @var string
      */
      protected $table = 'orchestra_care_symbols';

     /**
      * The attributes that are mass assignable.
      *
      * @var array
      */
      protected $fillable = [' 	category_id','cofreet_symbols','astms_symbol','care_instruction','is_enabled'];
}
