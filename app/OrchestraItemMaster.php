<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrchestraItemMaster extends Model
{
    protected $table = 'orchestra_item_master';
    protected $fillable = ['company_id', 'parent_id', 'item_name', 'ref_no', 'tag_name', 'created_at', 'updated_at'];
    
}
