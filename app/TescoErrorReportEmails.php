<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TescoErrorReportEmails extends Model
{
    /**
      * The table associated with the model.
      *
      * @var string
      */
      protected $table = 'tesco_error_report_emails';

     /**
      * The attributes that are mass assignable.
      *
      * @var array
      */
      protected $fillable = ['company_id','error_case','contact_person','email_address','is_enabled'];
}
