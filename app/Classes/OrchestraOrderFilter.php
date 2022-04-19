<?php
namespace App\Classes;
use DB;

/**
 * Class to fetch orchestra orders data for filter
 * @author [Praveen] <[praveen@wizroots.com]>
 */
class OrchestraOrderFilter
{
    private $start_date;
    private $end_date;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct($date)
    {
        
        if ($date->start_date === null && $date->end_date === null) {
            $this->start_date = date("Y-m-d", strtotime(" -6 month"));
            $this->end_date = date("Y-m-d");
        } else {
            $this->start_date = $date->start_date;
            $this->end_date = $date->end_date;
        }
    }
  
  /**
   * method to all the orchestra orders season code for filter selectbox
   * @param  [type] $request
   * @return collection season_codes
   */
  public function getOrdersSeasonCodes()
  {
    
    $orchestra_hang_tag_orders = DB::table("orchestra_hang_tag_orders")
    ->whereBetween('orchestra_hang_tag_orders.created_at', [$this->start_date.' 00:00:00', $this->end_date.' 23:59:59'])
        ->select("season_code");

    $orchestra_special_tag_orders = DB::table("orchestra_special_tag_orders")
    ->whereBetween('orchestra_special_tag_orders.created_at', [$this->start_date.' 00:00:00', $this->end_date.' 23:59:59'])
        ->select("season_code");

    $orchestra_main_label_orders = DB::table("orchestra_main_label_orders")
    ->whereBetween('orchestra_main_label_orders.created_at', [$this->start_date.' 00:00:00', $this->end_date.' 23:59:59'])
            ->select("season_code");

    $orchestra_size_label_orders = DB::table("orchestra_size_label_orders")
    ->whereBetween('orchestra_size_label_orders.created_at', [$this->start_date.' 00:00:00', $this->end_date.' 23:59:59'])
            ->select("season_code");

    $orchestra_heat_transfer_orders = DB::table("orchestra_heat_transfer_orders")
    ->whereBetween('orchestra_heat_transfer_orders.created_at', [$this->start_date.' 00:00:00', $this->end_date.' 23:59:59'])
            ->select("season_code");

    $orchestra_wash_care_label_orders = DB::table("orchestra_wash_care_label_orders")
    ->whereBetween('orchestra_wash_care_label_orders.created_at', [$this->start_date.' 00:00:00', $this->end_date.' 23:59:59'])
            ->select("season_code");

    $orchestra_solid_carton_sticker_orders = DB::table("orchestra_solid_carton_sticker_orders")
    ->whereBetween('orchestra_solid_carton_sticker_orders.created_at', [$this->start_date.' 00:00:00', $this->end_date.' 23:59:59'])
            ->select("season_code");

    $orchestra_solid_poly_bag_sticker_orders = DB::table("orchestra_solid_poly_bag_sticker_orders")
    ->whereBetween('orchestra_solid_poly_bag_sticker_orders.created_at', [$this->start_date.' 00:00:00', $this->end_date.' 23:59:59'])
            ->select("season_code");

    $orchestra_standard_carton_sticker_orders = DB::table("orchestra_standard_carton_sticker_orders")
    ->whereBetween('orchestra_standard_carton_sticker_orders.created_at', [$this->start_date.' 00:00:00', $this->end_date.' 23:59:59'])
            ->select("season_code");
        
    return DB::table("orchestra_standard_poly_bag_sticker_orders")
    ->whereBetween('orchestra_standard_poly_bag_sticker_orders.created_at', [$this->start_date.' 00:00:00', $this->end_date.' 23:59:59'])
        ->select("season_code")
        ->groupBy('season_code')
        ->union($orchestra_hang_tag_orders)
        ->union($orchestra_special_tag_orders)
        ->union($orchestra_main_label_orders)
        ->union($orchestra_size_label_orders)
        ->union($orchestra_heat_transfer_orders)
        ->union($orchestra_wash_care_label_orders)
        ->union($orchestra_solid_carton_sticker_orders)
        ->union($orchestra_solid_poly_bag_sticker_orders)
        ->union($orchestra_standard_carton_sticker_orders)
        ->get();
  }

  /**
   * method to all the orchestra orders style number for filter selectbox
   * @param  [type] $request
   * @return collection style numbers
   */
  public function getOrdersStyleNumbers()
  {
    $orchestra_hang_tag_orders_style = DB::table("orchestra_hang_tag_orders")
    ->whereBetween('orchestra_hang_tag_orders.created_at', [$this->start_date.' 00:00:00', $this->end_date.' 23:59:59'])
            ->select("uvc",DB::raw("'printed_boards' as \"type\""));

    $orchestra_special_tag_orders_style = DB::table("orchestra_special_tag_orders")
    ->whereBetween('orchestra_special_tag_orders.created_at', [$this->start_date.' 00:00:00', $this->end_date.' 23:59:59'])
            ->select("uvc",DB::raw("'printed_boards' as \"type\""));
            
    $orchestra_main_label_orders_style = DB::table("orchestra_main_label_orders")
    ->whereBetween('orchestra_main_label_orders.created_at', [$this->start_date.' 00:00:00', $this->end_date.' 23:59:59'])
            ->select("uvc",DB::raw("'labels' as \"type\""));

    $orchestra_size_label_orders_style = DB::table("orchestra_size_label_orders")
    ->whereBetween('orchestra_size_label_orders.created_at', [$this->start_date.' 00:00:00', $this->end_date.' 23:59:59'])
            ->select("uvc",DB::raw("'labels' as \"type\""));
    
    $orchestra_heat_transfer_orders_style = DB::table("orchestra_heat_transfer_orders")
    ->whereBetween('orchestra_heat_transfer_orders.created_at', [$this->start_date.' 00:00:00', $this->end_date.' 23:59:59'])
            ->select("uvc",DB::raw("'labels' as \"type\""));

    $orchestra_wash_care_label_orders_style = DB::table("orchestra_wash_care_label_orders")
    ->whereBetween('orchestra_wash_care_label_orders.created_at', [$this->start_date.' 00:00:00', $this->end_date.' 23:59:59'])
            ->select("uvc",DB::raw("'labels' as \"type\""));
    
    $orchestra_solid_carton_sticker_orders_style = DB::table("orchestra_solid_carton_sticker_orders")
    ->whereBetween('orchestra_solid_carton_sticker_orders.created_at', [$this->start_date.' 00:00:00', $this->end_date.' 23:59:59'])
            ->select("uvc",DB::raw("'stickers' as \"type\""));

    $orchestra_solid_poly_bag_sticker_orders_style = DB::table("orchestra_solid_poly_bag_sticker_orders")
    ->whereBetween('orchestra_solid_poly_bag_sticker_orders.created_at', [$this->start_date.' 00:00:00', $this->end_date.' 23:59:59'])
            ->select("uvc",DB::raw("'stickers' as \"type\""));

    $orchestra_standard_carton_sticker_orders_style = DB::table("orchestra_standard_carton_sticker_orders")
    ->whereBetween('orchestra_standard_carton_sticker_orders.created_at', [$this->start_date.' 00:00:00', $this->end_date.' 23:59:59'])
            ->select("uvc",DB::raw("'stickers' as \"type\""));

    return DB::table("orchestra_standard_poly_bag_sticker_orders")
                ->whereBetween('orchestra_standard_poly_bag_sticker_orders.created_at', [$this->start_date.' 00:00:00', $this->end_date.' 23:59:59'])
                ->select("uvc",DB::raw("'stickers' as \"type\""))
                ->union($orchestra_hang_tag_orders_style)
                ->union($orchestra_special_tag_orders_style)
                ->union($orchestra_main_label_orders_style)
                ->union($orchestra_size_label_orders_style)
                ->union($orchestra_heat_transfer_orders_style)
                ->union($orchestra_wash_care_label_orders_style)
                ->union($orchestra_solid_carton_sticker_orders_style)
                ->union($orchestra_solid_poly_bag_sticker_orders_style)
                ->union($orchestra_standard_carton_sticker_orders_style)
                ->get();
  }
  
}
