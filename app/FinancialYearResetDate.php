<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FinancialYearResetDate extends Model
{
    /**
      * The table associated with the model.
      *
      * @var string
      */
      protected $table = 'financial_year_reset_date';

      protected $primaryKey = 'id';

      /**
      * The attributes that are mass assignable.
      *
      * @var array
      */
      protected $fillable = ['financial_year_date', 'updated_at', 'deleted_at'];
}
