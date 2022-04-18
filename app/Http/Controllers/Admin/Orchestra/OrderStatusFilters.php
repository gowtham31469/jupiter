<?php

namespace App\Http\Controllers\Admin\Orchestra;

use Illuminate\Http\Request;
use DB;
use App\Http\Controllers\Controller;

class OrderStatusFilters extends Controller
{
    public function orchestraOrdersFilter(Request $request)
    {
        $date_start = date("Y-m-d", strtotime(" -6 month"));
        $date_end = date("Y-m-d");

        $orchestra_hang_tag_orders = DB::table("orchestra_hang_tag_orders")
        ->whereBetween('orchestra_hang_tag_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
            ->select("season_code");

        $orchestra_special_tag_orders = DB::table("orchestra_special_tag_orders")
        ->whereBetween('orchestra_special_tag_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
            ->select("season_code");
        
        $orchestra_main_label_orders = DB::table("orchestra_main_label_orders")
        ->whereBetween('orchestra_main_label_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                ->select("season_code");

        $orchestra_size_label_orders = DB::table("orchestra_size_label_orders")
        ->whereBetween('orchestra_size_label_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                ->select("season_code");

        $orchestra_heat_transfer_orders = DB::table("orchestra_heat_transfer_orders")
        ->whereBetween('orchestra_heat_transfer_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                ->select("season_code");

        $orchestra_wash_care_label_orders = DB::table("orchestra_wash_care_label_orders")
        ->whereBetween('orchestra_wash_care_label_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                ->select("season_code");

        $orchestra_solid_carton_sticker_orders = DB::table("orchestra_solid_carton_sticker_orders")
        ->whereBetween('orchestra_solid_carton_sticker_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                ->select("season_code");

        $orchestra_solid_poly_bag_sticker_orders = DB::table("orchestra_solid_poly_bag_sticker_orders")
        ->whereBetween('orchestra_solid_poly_bag_sticker_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                ->select("season_code");

        $orchestra_standard_carton_sticker_orders = DB::table("orchestra_standard_carton_sticker_orders")
        ->whereBetween('orchestra_standard_carton_sticker_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                ->select("season_code");
            
        $data = DB::table("orchestra_standard_poly_bag_sticker_orders")
        ->whereBetween('orchestra_standard_poly_bag_sticker_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
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

        $orchestra_hang_tag_orders_style = DB::table("orchestra_hang_tag_orders")
        ->whereBetween('orchestra_hang_tag_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                ->select("uvc",DB::raw("'printed_boards' as \"type\""));

        $orchestra_special_tag_orders_style = DB::table("orchestra_special_tag_orders")
        ->whereBetween('orchestra_special_tag_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                ->select("uvc",DB::raw("'printed_boards' as \"type\""));
                
        $orchestra_main_label_orders_style = DB::table("orchestra_main_label_orders")
        ->whereBetween('orchestra_main_label_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                ->select("uvc",DB::raw("'labels' as \"type\""));

        $orchestra_size_label_orders_style = DB::table("orchestra_size_label_orders")
        ->whereBetween('orchestra_size_label_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                ->select("uvc",DB::raw("'labels' as \"type\""));
        
        $orchestra_heat_transfer_orders_style = DB::table("orchestra_heat_transfer_orders")
        ->whereBetween('orchestra_heat_transfer_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                ->select("uvc",DB::raw("'labels' as \"type\""));

        $orchestra_wash_care_label_orders_style = DB::table("orchestra_wash_care_label_orders")
        ->whereBetween('orchestra_wash_care_label_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                ->select("uvc",DB::raw("'labels' as \"type\""));
        
        $orchestra_solid_carton_sticker_orders_style = DB::table("orchestra_solid_carton_sticker_orders")
        ->whereBetween('orchestra_solid_carton_sticker_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                ->select("uvc",DB::raw("'stickers' as \"type\""));

        $orchestra_solid_poly_bag_sticker_orders_style = DB::table("orchestra_solid_poly_bag_sticker_orders")
        ->whereBetween('orchestra_solid_poly_bag_sticker_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                ->select("uvc",DB::raw("'stickers' as \"type\""));

        $orchestra_standard_carton_sticker_orders_style = DB::table("orchestra_standard_carton_sticker_orders")
        ->whereBetween('orchestra_standard_carton_sticker_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                ->select("uvc",DB::raw("'stickers' as \"type\""));

        $style_no = DB::table("orchestra_standard_poly_bag_sticker_orders")
                    ->whereBetween('orchestra_standard_poly_bag_sticker_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
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

        //$data = json_decode($data);
        $result = response()->json(['error'=> false,'message' =>'success','data'=>$data,'style_no'=>$style_no],200);
        return $result;
    }
}
