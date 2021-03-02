<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TescoFabricLists extends Model
{
    /**
      * The table associated with the model.
      *
      * @var string
      */
      protected $table = 'tesco_fabriclists';

     /**
      * The attributes that are mass assignable.
      *
      * @var array
      */
      protected $fillable = ['english', 'polish', 'czech', 'slovak', 'hungarian', 'is_enabled'];
}
