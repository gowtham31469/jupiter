<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerContactPersons extends Model
{
	 /**
      * The table associated with the model.
      *
      * @var string
      */
      protected $table = 'customer_contact_persons';

     /**
      * The attributes that are mass assignable.
      *
      * @var array
      */
      protected $fillable = ['customer_id', 'contact_person_name', 'contact_person_email','contact_person_phone','default_contact_person'];
}
