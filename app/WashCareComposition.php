<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class WashCareComposition extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'orchestra_wash_care_composition';
    protected $fillable =['owcl_id','parts_id','composition_id','percentage','page_id'];
}
