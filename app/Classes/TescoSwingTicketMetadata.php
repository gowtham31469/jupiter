<?php

namespace App\Classes;

use DB;

/**
 * Class to get tesco swing tickets metadata
 * @author [Praveen] <[praveen@wizroots.com]>
 */
class TescoSwingTicketMetadata
{
  /**
   * method to get tesco swing tickets metadata
   * @return array 
   */
  public function getProductBenefits($template)
  {
    return DB::table('tesco_product_benefits')
      ->where('is_enabled', 1)
      ->where('template_id', $template)
      ->orderBy('id')
      ->pluck('english', 'id')
      ->all();
  }

  public function getMMProductBenefits($template)
  {
    return DB::table('tesco_mm_product_benefits')
      ->where('is_enabled', 1)
      ->where('template_id', $template)
      ->orderBy('id')
      ->pluck('english', 'id')
      ->all();
  }

  public function getProductDescription()
  {
    return DB::table('tesco_product_descriptions')
      ->where('is_enabled', 1)
      ->orderBy('id')
      ->pluck('english', 'id')
      ->all();
  }

  public function getWeeSymbols()
  {
    return DB::table('tesco_wee_symbols')
      ->select('id', 'wee_symbol', 'wee_symbol_text')
      ->where('is_enabled', 1)
      ->orderBy('id')
      ->get()
      ->toArray();
  }

  public function getwoolmarkLogos()
  {
    return DB::table('tesco_woolmark_logos')
      ->select('id', 'woolmark_logo', 'woolmark_logo_text')
      ->where('is_enabled', 1)
      ->orderBy('id')
      ->get()
      ->toArray();
  }

  public function getSeasonCodes()
  {
    return DB::table('tesco_season_codes')
      ->where('is_enabled', 1)
      ->orderBy('id')
      ->pluck('english', 'id')
      ->all();
  }
}
