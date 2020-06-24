<?php
namespace App\Classes;
/**
 * Class to Remove Special Characters from String
 * @author [Praveen] <[praveen@wizroots.com]>
 */
class CleanString
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
   * method to check price availability
   * @param  [type] $string
   * @return string 
   */
  public function cleanStr($string){
    // Replaces all spaces with hyphens.
    $string = str_replace(' ', '-', $string);

    // Removes special chars.
    $string = preg_replace('/[^A-Za-z0-9\-]/', '-', $string);

    // Replaces multiple hyphens with single one.
    $string = preg_replace('/-+/', '-', $string);
    
    return str_random(6).'-'.$string;
}

  
  
}
