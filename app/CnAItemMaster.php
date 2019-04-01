<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CnAItemMaster extends Model
{
    /**
      * The table associated with the model.
      *
      * @var string
      */
      protected $table = 'c&a_item_master';

     /**
      * The attributes that are mass assignable.
      *
      * @var array
      */
      protected $fillable = ['company_id', 'parent_id', 'item_name', 'ref_no', 'tag_name', 'created_at', 'updated_at'];
}
