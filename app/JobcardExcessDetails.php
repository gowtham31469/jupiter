<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobcardExcessDetails extends Model
{
    protected $table = 'jobcard_excess_details';

    protected $fillable = [
        'jobcard_main_table_id',
        'ref_name',
        'mtrs_for_stickers',
        'actual_excess',
        'settings',
        'total_mtrs',
        'created_at',
    ];
}
