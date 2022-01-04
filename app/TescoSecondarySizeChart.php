<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TescoSecondarySizeChart extends Model
{
    /**
     * The table associated with the model.
    *
    * @var string
    */
    protected $table = 'tesco_secondary_size_chart';

     /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at',
    ];
    
    /**
     * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = ['tesco_primary_size_id', 'tesco_secondary_size_category_id', 'secondary_size'];

    public function primarySizeChart(){
        return $this->belongsTo(TescoPrimarySize::class);
    }
    
    
}
