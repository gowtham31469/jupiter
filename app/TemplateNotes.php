<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TemplateNotes extends Model
{
    /**
      * The table associated with the model.
      *
      * @var string
      */
      protected $table = 'template_notes';

     /**
      * The attributes that are mass assignable.
      *
      * @var array
      */
      protected $fillable = ['template_id','notes'];
}
