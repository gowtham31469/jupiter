<?php
namespace App\Classes;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Response;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use DB;
use Auth;
/**
 * Class to check price availability for the tesco template orders
 * @author [Praveen] <[praveen@wizroots.com]>
 */
class PriceAvailabilityCheck
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
   * @param  [type] $templates,customer_id
   * @return price available count
   */
  public function checkPriceAvailability($templates,$customer_id)
  {
    try {
          $price_missing = 0;
          /** [ Check if price is available for this template ] */  
          foreach ($templates as $template) 
          {          
            $template_price_avail = DB::table('price_taxation')
                                   ->select('price_range.price')
                                   ->join('price_range','price_range.price_taxation_id','price_taxation.id')
                                   ->where('price_taxation.template_id','=',$template['id'])
                                   ->where('price_taxation.customer_id','=',$customer_id)
                                   ->first();
            $price_missing = $template_price_avail == ''?$price_missing+1:$price_missing;
          }
          return $price_missing;
            
        } catch (\Exception $e)
        {
             return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
  }

  
  
}
