<?php

namespace App\Classes;

use DB;

/**
 * Class to get tesco care labels metadata
 * @author [Praveen] <[praveen@wizroots.com]>
 */
class TescoCareMetadata
{
  public function getCareLabelSymbols()
  {
    return DB::table('tesco_care_symbols')
      ->select('tesco_care_symbols.id', 'tesco_care_symbols.iso_symbols', 'tesco_care_symbols.degree', 'tesco_care_category.category_name')
      ->join('tesco_care_category', 'tesco_care_category.id', 'tesco_care_symbols.category_id')
      ->where('is_enabled', 1)
      ->orderBy('id')
      ->get()
      ->groupBy('category_name')
      ->toArray();
  }

  public function getCountryOrigin()
  {
    return DB::table('tesco_countries')
      ->where('is_enabled', 1)
      ->orderBy('id')
      ->pluck('english', 'id')
      ->all();
  }

  public function getKidsWarning()
  {
    return DB::table('tesco_kids_warning_statements')
      ->where('is_enabled', 1)
      ->orderBy('id')
      ->pluck('english', 'id')
      ->all();
  }

  public function getCareLabelComponents()
  {
    return DB::table('tesco_components')
      ->where('is_enabled', 1)
      ->orderBy('id')
      ->pluck('english', 'id')
      ->all();
  }

  public function getCareLabelFabrics()
  {
    return DB::table('tesco_fabriclists')
      ->where('is_enabled', 1)
      ->orderBy('id')
      ->pluck('english', 'id')
      ->all();
  }

  public function getCareLabelInstructions()
  {
    return DB::table('tesco_care_additional_instructions')
      ->where('is_enabled', 1)
      ->orderBy('id')
      ->pluck('english', 'id')
      ->all();
  }

  public function getTescoSuppliers()
  {
    return DB::table('tesco_suppliers')
      ->join('customer_metadata', 'customer_metadata.customer_metadata_id', 'tesco_suppliers.id')
      ->join('customer_profile', 'customer_profile.company_id', 'customer_metadata.customer_id')
      ->where('is_enabled', 1)
      ->orderBy('tesco_suppliers.supplier_name')
      ->pluck('tesco_suppliers.supplier_name', 'tesco_suppliers.id')
      ->all();
  }

  public function getPartOfTwoPieceSet()
  {
    return DB::table('part_of_two_piece_set')
      ->orderBy('id')
      ->pluck('name', 'id')
      ->all();
  }
}
