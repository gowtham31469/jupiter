<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventHistory extends Model
{
   protected $table ='event_history'; 
   protected $fillable = ['id','history_id','action_id','reason','created_by'];
}
