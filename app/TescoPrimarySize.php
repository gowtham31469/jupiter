<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TescoPrimarySize extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tesco_primary_size';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['tesco_size_department_id', 'department_title', 'uk_size', 'ce_size', 'is_flag'];


<<<<<<< HEAD
      /**
       * Get secondary size chart relationship
       *
       * @return eloquent object
       */ 
      public function secSizeChart()
      {
          // return $this->hasMany(TescoSecondarySizeChart::class)->join('tesco_secondary_size_category as tssc', 'tssc.id', '=', 'tesco_secondary_size_chart.tesco_secondary_size_category_id');

             return $this->hasMany(TescoSecondarySizeChart::class);
      }
=======
    /**
     * Get secondary size chart relationship
     *
     * @return eloquent object
     */
    public function secSizeChart()
    {
        return $this->hasMany(TescoSecondarySizeChart::class)->join('tesco_secondary_size_category as tssc', 'tssc.id', '=', 'tesco_secondary_size_chart.tesco_secondary_size_category_id');
    }
>>>>>>> 4bd6a953b15c3c288bdc912306f1b39086e76dad
}
