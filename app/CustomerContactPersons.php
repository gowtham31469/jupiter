<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerContactPersons extends Model
{
  protected $table = 'customer_contact_persons';
  protected $fillable = [
      'customer_id', 'contact_person_name', 'contact_person_email','contact_person_phone','default_contact_person'
  ];
}
