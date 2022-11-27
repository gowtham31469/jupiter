<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SpectrraJobcardMatchToTable extends Model
{
     /**
      * The table associated with the model.
      *
      * @var string
      */
      protected $table = 'spectrra_jobcard_match_to_tables';

     /**
      * The attributes that are mass assignable.
      *
      * @var array
      */
      protected $fillable = [
            'spectrra_jobcard_main_id',
            'color_match_text'
       ];
}
