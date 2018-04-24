<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrchestraSpecialInstructions extends Model
{
	/**
      * The table associated with the model.
      *
      * @var string
      */
      protected $table = 'orchestra_care_instruction';

     /**
      * The attributes that are mass assignable.
      *
      * @var array
      */
      protected $fillable = ['french','spanish','portuguese','italian','dutch','german','russian','chinese','arabic','greek','turkish','english','is_enabled'];
}
