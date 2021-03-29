<?php
namespace App\Classes;
use DB;
/**
 * Class to get tesco care labels metadata
 * @author [Praveen] <[praveen@wizroots.com]>
 */
class TescoCareMetadata
{

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
      
  }

  /**
   * method to get tesco care labels metadata
   * @return array 
   */
  public function getCareMetadata() {
    $metadata_array = [];

    $metadata_array['symbols'] = DB::table('tesco_care_symbols')
            ->select('tesco_care_symbols.id', 'tesco_care_symbols.iso_symbols', 'tesco_care_symbols.degree', 'tesco_care_category.category_name')
            ->join('tesco_care_category', 'tesco_care_category.id', 'tesco_care_symbols.category_id')
            ->get()->groupBy('category_name')->toArray();

    $metadata_array['coo'] = DB::table('tesco_countries')->where('is_enabled','=',1)->pluck('english','id')->all();
    $metadata_array['kids_warning'] = DB::table('tesco_kids_warning_statements')->where('is_enabled','=',1)->pluck('english','id')->all();
    $metadata_array['components'] = DB::table('tesco_components')->where('is_enabled','=',1)->pluck('english','id')->all();
    $metadata_array['fabriclists'] = DB::table('tesco_fabriclists')->where('is_enabled','=',1)->pluck('english','id')->all();
    
    $metadata_array['tesco_suppliers'] = DB::table('tesco_suppliers')
                        ->join('customer_metadata','customer_metadata.customer_metadata_id','=','tesco_suppliers.id')
                        ->join('customer_profile','customer_profile.company_id','=','customer_metadata.customer_id')
                        ->where('is_enabled','=',1)
                        ->orderBy('tesco_suppliers.supplier_name')
                        ->pluck('tesco_suppliers.supplier_name','tesco_suppliers.id')
                        ->all();

    return $metadata_array;
}

  
  
}
