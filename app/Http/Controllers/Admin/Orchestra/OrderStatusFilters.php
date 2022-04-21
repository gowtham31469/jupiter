<?php

namespace App\Http\Controllers\Admin\Orchestra;

use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classes\OrchestraOrderFilter;


class OrderStatusFilters extends Controller
{
    protected function orchestraOrdersFilter(Request $request)
    {
        
        $filter_obj = new OrchestraOrderFilter($request);

        $data = $filter_obj->getOrdersSeasonCodes();

        $style_no = $filter_obj->getOrdersStyleNumbers();

        return response()->json(['error' => false, 'message' => 'success', 'data' => $data, 'style_no' => $style_no], 200);
    }

    protected function getOrchestraOrders(Request $request)
    {
        $orch_filter_artworkstatus = $request->filled('orch_filter_artworkstatus')?$request->orch_filter_artworkstatus:[];
        $orch_filter_orderstatus = $request->filled('orch_filter_orderstatus')?$request->orch_filter_orderstatus:[];
        $orch_filter_season = $request->filled('orch_filter_season')?$request->orch_filter_season:[];
        $orch_filter_style = $request->filled('orch_filter_style')?$request->orch_filter_style:[];
        $orch_filter_postatus = $request->filled('orch_filter_postatus')?$request->orch_filter_postatus:[];
        $orch_filter_pistatus = $request->filled('orch_filter_pistatus')?$request->orch_filter_pistatus:[];

        if (in_array("Approval Pending", $orch_filter_artworkstatus)) {
            array_splice($orch_filter_artworkstatus, array_search("Approval Pending", $orch_filter_artworkstatus), 1);
            array_push($orch_filter_artworkstatus, "Order Placed", "Saved", "Order Pending");
        }

        if ($request->start_date === null && $request->end_date === null) {
            $start_date = date("Y-m-d", strtotime(" -6 month"));
            $end_date = date("Y-m-d");
        } else {
            $start_date = $request->start_date;
            $end_date = $request->end_date;
        }
    
        $ddmmyy="DD-MM-YYYY";
        $actions='-';

        $data['orchestra_hang_tag_orders'] = DB::table('orchestra_hang_tag_orders')
                        ->leftjoin('customers','customers.id', 'orchestra_hang_tag_orders.customer_id')
                        ->join('orchestra_item_master','orchestra_item_master.id', 'orchestra_hang_tag_orders.template_front_id')
                        ->where('orchestra_hang_tag_orders.status','!=','Order Cancelled')
                        ->whereBetween('orchestra_hang_tag_orders.created_at', [$start_date.' 00:00:00', $end_date.' 23:59:59'])
                        ->WhereIn('season_code', $orch_filter_season)
                        ->WhereIn('uvc', $orch_filter_style)
                        ->WhereIn('po_status', $orch_filter_postatus)
                        ->WhereIn('pi_status', $orch_filter_pistatus)
                        ->where(function($query) use ($orch_filter_orderstatus, $orch_filter_artworkstatus){
                            if($orch_filter_orderstatus)
                            {
                                $query->orWhereIn('status', $orch_filter_orderstatus);
                            }
                            if($orch_filter_artworkstatus)
                            {
                                $query->orWhereIn('status', $orch_filter_artworkstatus);
                            }
                        })
                        ->select('orchestra_hang_tag_orders.color_code', 'orchestra_hang_tag_orders.uvc as UVC', DB::raw("orchestra_hang_tag_orders.season_code as \"Season\""), DB::raw("'-' as \"BuyerPONo\""), DB::raw("'Hang Tag' as \"ArtworkType\""), DB::raw("'Hang Tag' as \"TemplateName\""), 'orchestra_item_master.item_name as Reference No', 'customers.id as customer_id', 'customers.company_name as SupplierName', DB::raw("to_char(orchestra_hang_tag_orders.created_at, "."'".$ddmmyy."'".") as \"OrderDate\""), DB::raw("to_char(orchestra_hang_tag_orders.delivery_date, "."'".$ddmmyy."'".") as \"DeliveryDate\""), 'orchestra_hang_tag_orders.status as Artwork Status', 'orchestra_hang_tag_orders.pi_status as PI Status', 'orchestra_hang_tag_orders.po_status as PO Status', 'orchestra_hang_tag_orders.po_number as PO Number', DB::raw("'".$actions."'".' as "Actions"'), DB::raw("'orchestra_hang_tag_orders' as \"tableName\""), 'orchestra_hang_tag_orders.id  as tableID', 'orchestra_hang_tag_orders.po_file', 'customers.pi_required', 'customers.po_required', 'orchestra_hang_tag_orders.template_front_id as template_id', 'orchestra_hang_tag_orders.order_flag', 'customers.delivery_day_count', 'orchestra_hang_tag_orders.is_order_count_empty')
                        ->groupBy('orchestra_hang_tag_orders.uvc', 'customers.company_name', 'orchestra_hang_tag_orders.created_at', 'orchestra_hang_tag_orders.delivery_date', 'orchestra_hang_tag_orders.status', 'orchestra_hang_tag_orders.pi_status', 'orchestra_hang_tag_orders.status', 'orchestra_hang_tag_orders.po_status', 'orchestra_hang_tag_orders.po_number', 'customers.pi_required', 'customers.id', 'orchestra_hang_tag_orders.id', 'orchestra_item_master.item_name')
                        ->orderBy('orchestra_hang_tag_orders.id', 'asc')
                        // ->toSql();
                        ->get();
        $data['orchestra_special_tag_orders'] = DB::table('orchestra_special_tag_orders')
                        ->join('orchestra_item_master','orchestra_item_master.id', 'orchestra_special_tag_orders.template_id')
                        ->leftjoin('customers','customers.id', 'orchestra_special_tag_orders.customer_id')
                        ->where('orchestra_special_tag_orders.status','!=','Order Cancelled')
                        ->whereBetween('orchestra_special_tag_orders.created_at', [$start_date.' 00:00:00', $end_date.' 23:59:59'])
                        ->WhereIn('season_code', $orch_filter_season)
                        ->WhereIn('uvc', $orch_filter_style)
                        ->WhereIn('po_status', $orch_filter_postatus)
                        ->WhereIn('pi_status', $orch_filter_pistatus)
                        ->where(function($query) use ($orch_filter_orderstatus, $orch_filter_artworkstatus){
                            if($orch_filter_orderstatus)
                            {
                                $query->orWhereIn('status', $orch_filter_orderstatus);
                            }
                            if($orch_filter_artworkstatus)
                            {
                                $query->orWhereIn('status', $orch_filter_artworkstatus);
                            }
                        })
                        ->select('orchestra_special_tag_orders.color_code','orchestra_special_tag_orders.uvc as UVC',DB::raw("orchestra_special_tag_orders.season_code as \"Season\""),DB::raw("'-' as \"BuyerPONo\""),DB::raw("'Special Tag' as \"ArtworkType\""),DB::raw("'Special Tag' as \"TemplateName\""),'orchestra_item_master.item_name as Reference No','customers.id as customer_id','customers.company_name as SupplierName',DB::raw("to_char(orchestra_special_tag_orders.created_at,"."'".$ddmmyy."'".") as \"OrderDate\""),DB::raw("to_char(orchestra_special_tag_orders.delivery_date,"."'".$ddmmyy."'".") as \"DeliveryDate\""),'orchestra_special_tag_orders.status as Artwork Status','orchestra_special_tag_orders.pi_status as PI Status','orchestra_special_tag_orders.po_status as PO Status','orchestra_special_tag_orders.po_number as PO Number',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'orchestra_special_tag_orders' as \"tableName\""),'orchestra_special_tag_orders.id  as tableID','orchestra_special_tag_orders.po_file','customers.pi_required','customers.po_required','orchestra_special_tag_orders.template_id','orchestra_special_tag_orders.order_flag','customers.delivery_day_count','orchestra_special_tag_orders.is_order_count_empty')
                        ->groupBy('orchestra_special_tag_orders.uvc','customers.company_name','orchestra_special_tag_orders.created_at','orchestra_special_tag_orders.delivery_date','orchestra_special_tag_orders.status','orchestra_special_tag_orders.pi_status','orchestra_special_tag_orders.status','orchestra_special_tag_orders.po_status','orchestra_special_tag_orders.po_number','customers.pi_required','customers.id','orchestra_special_tag_orders.id','orchestra_item_master.item_name')
                        ->orderBy('orchestra_special_tag_orders.id','asc')
                        ->get();
            
            $data['orchestra_main_label_orders'] = DB::table('orchestra_main_label_orders')
                        ->join('orchestra_item_master','orchestra_item_master.id', 'orchestra_main_label_orders.template_id')
                        ->leftjoin('customers','customers.id', 'orchestra_main_label_orders.customer_id')
                        ->where('orchestra_main_label_orders.status','!=','Order Cancelled')
                        ->whereBetween('orchestra_main_label_orders.created_at', [$start_date.' 00:00:00', $end_date.' 23:59:59'])
                        ->WhereIn('season_code', $orch_filter_season)
                        ->WhereIn('uvc', $orch_filter_style)
                        ->WhereIn('po_status', $orch_filter_postatus)
                        ->WhereIn('pi_status', $orch_filter_pistatus)
                        ->where(function($query) use ($orch_filter_orderstatus, $orch_filter_artworkstatus){
                            if($orch_filter_orderstatus)
                            {
                                $query->orWhereIn('status', $orch_filter_orderstatus);
                            }
                            if($orch_filter_artworkstatus)
                            {
                                $query->orWhereIn('status', $orch_filter_artworkstatus);
                            }
                        })
                        ->select('orchestra_main_label_orders.color_code','orchestra_main_label_orders.uvc as UVC',DB::raw("orchestra_main_label_orders.season_code as \"Season\""),DB::raw("'-' as \"BuyerPONo\""),DB::raw("'Main Label' as \"ArtworkType\""),DB::raw("'Main Label' as \"TemplateName\""),'orchestra_item_master.item_name as Reference No','customers.id as customer_id','customers.company_name as SupplierName',DB::raw("to_char(orchestra_main_label_orders.created_at,"."'".$ddmmyy."'".") as \"OrderDate\""),DB::raw("to_char(orchestra_main_label_orders.delivery_date,"."'".$ddmmyy."'".") as \"DeliveryDate\""),'orchestra_main_label_orders.status as Artwork Status','orchestra_main_label_orders.pi_status as PI Status','orchestra_main_label_orders.po_status as PO Status','orchestra_main_label_orders.po_number as PO Number',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'orchestra_main_label_orders' as \"tableName\""),'orchestra_main_label_orders.id  as tableID','orchestra_main_label_orders.po_file','customers.pi_required','customers.po_required','orchestra_main_label_orders.template_id','orchestra_main_label_orders.order_flag','customers.delivery_day_count','orchestra_main_label_orders.is_order_count_empty')
                        ->groupBy('orchestra_main_label_orders.uvc','customers.company_name','orchestra_main_label_orders.created_at','orchestra_main_label_orders.delivery_date','orchestra_main_label_orders.status','orchestra_main_label_orders.pi_status','orchestra_main_label_orders.status','orchestra_main_label_orders.po_status','orchestra_main_label_orders.po_number','customers.pi_required','customers.id','orchestra_main_label_orders.id','orchestra_item_master.item_name')->orderBy('orchestra_main_label_orders.id','asc')
                        ->get();
            
            $data['orchestra_size_label_orders'] = DB::table('orchestra_size_label_orders')
                        ->join('orchestra_item_master','orchestra_item_master.id', 'orchestra_size_label_orders.template_id')
                        ->leftjoin('customers','customers.id', 'orchestra_size_label_orders.customer_id')
                        ->where('orchestra_size_label_orders.status','!=','Order Cancelled')
                        ->whereBetween('orchestra_size_label_orders.created_at', [$start_date.' 00:00:00', $end_date.' 23:59:59'])
                        ->WhereIn('season_code', $orch_filter_season)
                        ->WhereIn('uvc', $orch_filter_style)
                        ->WhereIn('po_status', $orch_filter_postatus)
                        ->WhereIn('pi_status', $orch_filter_pistatus)
                        ->where(function($query) use ($orch_filter_orderstatus, $orch_filter_artworkstatus){
                            if($orch_filter_orderstatus)
                            {
                                $query->orWhereIn('status', $orch_filter_orderstatus);
                            }
                            if($orch_filter_artworkstatus)
                            {
                                $query->orWhereIn('status', $orch_filter_artworkstatus);
                            }
                        })
                        ->select('orchestra_size_label_orders.color_code','orchestra_size_label_orders.uvc as UVC',DB::raw("orchestra_size_label_orders.season_code as \"Season\""),DB::raw("'-' as \"BuyerPONo\""),DB::raw("'Size Label' as \"ArtworkType\""),DB::raw("'Size Label' as \"TemplateName\""),'orchestra_item_master.item_name as Reference No','customers.id as customer_id','customers.company_name as SupplierName',DB::raw("to_char(orchestra_size_label_orders.created_at,"."'".$ddmmyy."'".") as \"OrderDate\""),DB::raw("to_char(orchestra_size_label_orders.delivery_date,"."'".$ddmmyy."'".") as \"DeliveryDate\""),'orchestra_size_label_orders.status as Artwork Status','orchestra_size_label_orders.pi_status as PI Status','orchestra_size_label_orders.po_status as PO Status','orchestra_size_label_orders.po_number as PO Number',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'orchestra_size_label_orders' as \"tableName\""),'orchestra_size_label_orders.id  as tableID','orchestra_size_label_orders.po_file','customers.pi_required','customers.po_required','orchestra_size_label_orders.template_id','orchestra_size_label_orders.order_flag','customers.delivery_day_count','orchestra_size_label_orders.is_order_count_empty')
                        ->groupBy('orchestra_size_label_orders.uvc','customers.company_name','orchestra_size_label_orders.created_at','orchestra_size_label_orders.delivery_date','orchestra_size_label_orders.status','orchestra_size_label_orders.pi_status','orchestra_size_label_orders.status','orchestra_size_label_orders.po_status','orchestra_size_label_orders.po_number','customers.pi_required','customers.id','orchestra_size_label_orders.id','orchestra_item_master.item_name')->orderBy('orchestra_size_label_orders.id','asc')
                        ->get();
		
			$data['orchestra_heat_transfer_orders'] = DB::table('orchestra_heat_transfer_orders')
                        ->join('orchestra_item_master','orchestra_item_master.id', 'orchestra_heat_transfer_orders.template_id')
                        ->leftjoin('customers','customers.id', 'orchestra_heat_transfer_orders.customer_id')
                        ->where('orchestra_heat_transfer_orders.status','!=','Order Cancelled')
                        ->whereBetween('orchestra_heat_transfer_orders.created_at', [$start_date.' 00:00:00', $end_date.' 23:59:59'])
                        ->WhereIn('season_code', $orch_filter_season)
                        ->WhereIn('uvc', $orch_filter_style)
                        ->WhereIn('po_status', $orch_filter_postatus)
                        ->WhereIn('pi_status', $orch_filter_pistatus)
                        ->where(function($query) use ($orch_filter_orderstatus, $orch_filter_artworkstatus){
                            if($orch_filter_orderstatus)
                            {
                                $query->orWhereIn('status', $orch_filter_orderstatus);
                            }
                            if($orch_filter_artworkstatus)
                            {
                                $query->orWhereIn('status', $orch_filter_artworkstatus);
                            }
                        })
                        ->select('orchestra_heat_transfer_orders.color_code','orchestra_heat_transfer_orders.uvc as UVC',DB::raw("orchestra_heat_transfer_orders.season_code as \"Season\""),DB::raw("'-' as \"BuyerPONo\""),DB::raw("'Heat Transfer' as \"ArtworkType\""),DB::raw("'Heat Transfer' as \"TemplateName\""),'orchestra_item_master.item_name as Reference No','customers.id as customer_id','customers.company_name as SupplierName',DB::raw("to_char(orchestra_heat_transfer_orders.created_at,"."'".$ddmmyy."'".") as \"OrderDate\""),DB::raw("to_char(orchestra_heat_transfer_orders.delivery_date,"."'".$ddmmyy."'".") as \"DeliveryDate\""),'orchestra_heat_transfer_orders.status as Artwork Status','orchestra_heat_transfer_orders.pi_status as PI Status','orchestra_heat_transfer_orders.po_status as PO Status','orchestra_heat_transfer_orders.po_number as PO Number',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'orchestra_heat_transfer_orders' as \"tableName\""),'orchestra_heat_transfer_orders.id  as tableID','orchestra_heat_transfer_orders.po_file','customers.pi_required','customers.po_required','orchestra_heat_transfer_orders.template_id','orchestra_heat_transfer_orders.order_flag','customers.delivery_day_count','orchestra_heat_transfer_orders.is_order_count_empty')
                        ->groupBy('orchestra_heat_transfer_orders.uvc','customers.company_name','orchestra_heat_transfer_orders.created_at','orchestra_heat_transfer_orders.delivery_date','orchestra_heat_transfer_orders.status','orchestra_heat_transfer_orders.pi_status','orchestra_heat_transfer_orders.status','orchestra_heat_transfer_orders.po_status','orchestra_heat_transfer_orders.po_number','customers.pi_required','customers.id','orchestra_heat_transfer_orders.id','orchestra_item_master.item_name')
                        ->orderBy('orchestra_heat_transfer_orders.id','asc')
                        ->get();
		
			 $data['orchestra_wash_care_label_orders'] = DB::table('orchestra_wash_care_label_orders')
                        ->join('orchestra_item_master','orchestra_item_master.id', 'orchestra_wash_care_label_orders.template_id')
                        ->leftjoin('customers','customers.id', 'orchestra_wash_care_label_orders.customer_id')
                        ->where('orchestra_wash_care_label_orders.status','!=','Order Cancelled')
                        ->whereBetween('orchestra_wash_care_label_orders.created_at', [$start_date.' 00:00:00', $end_date.' 23:59:59'])
                        ->WhereIn('season_code', $orch_filter_season)
                        ->WhereIn('uvc', $orch_filter_style)
                        ->WhereIn('po_status', $orch_filter_postatus)
                        ->WhereIn('pi_status', $orch_filter_pistatus)
                        ->where(function($query) use ($orch_filter_orderstatus, $orch_filter_artworkstatus){
                            if($orch_filter_orderstatus)
                            {
                                $query->orWhereIn('status', $orch_filter_orderstatus);
                            }
                            if($orch_filter_artworkstatus)
                            {
                                $query->orWhereIn('status', $orch_filter_artworkstatus);
                            }
                        })
                        ->select('orchestra_wash_care_label_orders.color_code','orchestra_wash_care_label_orders.uvc as UVC',DB::raw("orchestra_wash_care_label_orders.season_code as \"Season\""),DB::raw("orchestra_wash_care_label_orders.po as \"BuyerPONo\""),DB::raw("'WashCareLabel' as \"ArtworkType\""),'orchestra_wash_care_label_orders.template_name as TemplateName','orchestra_item_master.item_name as Reference No','customers.id as customer_id','customers.company_name as SupplierName',DB::raw("to_char(orchestra_wash_care_label_orders.created_at,"."'".$ddmmyy."'".") as \"OrderDate\""),DB::raw("to_char(orchestra_wash_care_label_orders.delivery_date,"."'".$ddmmyy."'".") as \"DeliveryDate\""),'orchestra_wash_care_label_orders.status as Artwork Status','orchestra_wash_care_label_orders.pi_status as PI Status','orchestra_wash_care_label_orders.po_status as PO Status','orchestra_wash_care_label_orders.po_number as PO Number',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'orchestra_wash_care_label_orders' as \"tableName\""),'orchestra_wash_care_label_orders.id  as tableID','orchestra_wash_care_label_orders.po_file','customers.pi_required','customers.po_required','orchestra_wash_care_label_orders.template_id','orchestra_wash_care_label_orders.order_flag','customers.delivery_day_count','orchestra_wash_care_label_orders.is_order_count_empty')
                        ->groupBy('orchestra_wash_care_label_orders.uvc','customers.company_name','orchestra_wash_care_label_orders.created_at','orchestra_wash_care_label_orders.delivery_date','orchestra_wash_care_label_orders.status','orchestra_wash_care_label_orders.pi_status','orchestra_wash_care_label_orders.status','orchestra_wash_care_label_orders.po_status','orchestra_wash_care_label_orders.po_number','customers.pi_required','customers.id','orchestra_wash_care_label_orders.id','orchestra_item_master.item_name')->orderBy('orchestra_wash_care_label_orders.id','asc')
                        ->get();

             $data['orchestra_solid_carton_sticker_orders'] = DB::table('orchestra_solid_carton_sticker_orders')
                        ->join('orchestra_item_master','orchestra_item_master.id', 'orchestra_solid_carton_sticker_orders.template_id')
                        ->leftjoin('customers','customers.id', 'orchestra_solid_carton_sticker_orders.customer_id')
                        ->where('orchestra_solid_carton_sticker_orders.status','!=','Order Cancelled')
                        ->whereBetween('orchestra_solid_carton_sticker_orders.created_at', [$start_date.' 00:00:00', $end_date.' 23:59:59'])
                        ->WhereIn('season_code', $orch_filter_season)
                        ->WhereIn('uvc', $orch_filter_style)
                        ->WhereIn('po_status', $orch_filter_postatus)
                        ->WhereIn('pi_status', $orch_filter_pistatus)
                        ->where(function($query) use ($orch_filter_orderstatus, $orch_filter_artworkstatus){
                            if($orch_filter_orderstatus)
                            {
                                $query->orWhereIn('status', $orch_filter_orderstatus);
                            }
                            if($orch_filter_artworkstatus)
                            {
                                $query->orWhereIn('status', $orch_filter_artworkstatus);
                            }
                        })
                        ->select(DB::raw('distinct on (orchestra_solid_carton_sticker_orders.uvc,orchestra_solid_carton_sticker_orders.po) UVC as "UVC"'),DB::raw("orchestra_solid_carton_sticker_orders.season_code as \"Season\""),DB::raw("orchestra_solid_carton_sticker_orders.po as \"BuyerPONo\""),DB::raw("'Solid Carton Stickers' as \"ArtworkType\""),DB::raw("'Solid Carton Stickers' as \"TemplateName\""),DB::raw("'-' as \"Reference No\""),'customers.id as customer_id','customers.company_name as SupplierName',DB::raw("to_char(orchestra_solid_carton_sticker_orders.created_at,"."'".$ddmmyy."'".") as \"OrderDate\""),DB::raw("to_char(orchestra_solid_carton_sticker_orders.delivery_date,"."'".$ddmmyy."'".") as \"DeliveryDate\""),'orchestra_solid_carton_sticker_orders.color_code','orchestra_solid_carton_sticker_orders.status as Artwork Status','orchestra_solid_carton_sticker_orders.pi_status as PI Status','orchestra_solid_carton_sticker_orders.po_status as PO Status','orchestra_solid_carton_sticker_orders.po_number as PO Number',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'orchestra_solid_carton_sticker_orders' as \"tableName\""),'orchestra_solid_carton_sticker_orders.id  as tableID','orchestra_solid_carton_sticker_orders.po_file','customers.pi_required','customers.po_required','orchestra_solid_carton_sticker_orders.template_id','orchestra_solid_carton_sticker_orders.order_flag','customers.delivery_day_count','orchestra_solid_carton_sticker_orders.is_order_count_empty')
                        ->groupBy('orchestra_solid_carton_sticker_orders.uvc','orchestra_solid_carton_sticker_orders.po','customers.company_name','orchestra_solid_carton_sticker_orders.created_at','orchestra_solid_carton_sticker_orders.delivery_date','orchestra_solid_carton_sticker_orders.status','orchestra_solid_carton_sticker_orders.pi_status','orchestra_solid_carton_sticker_orders.status','orchestra_solid_carton_sticker_orders.po_status','orchestra_solid_carton_sticker_orders.po_number','customers.pi_required','customers.id','orchestra_solid_carton_sticker_orders.id')
                        ->get();

            $data['orchestra_solid_poly_bag_sticker_orders'] = DB::table('orchestra_solid_poly_bag_sticker_orders')
                        ->join('orchestra_item_master','orchestra_item_master.id', 'orchestra_solid_poly_bag_sticker_orders.template_id')
                        ->leftjoin('customers','customers.id', 'orchestra_solid_poly_bag_sticker_orders.customer_id')
                        ->where('orchestra_solid_poly_bag_sticker_orders.status','!=','Order Cancelled')
                        ->whereBetween('orchestra_solid_poly_bag_sticker_orders.created_at', [$start_date.' 00:00:00', $end_date.' 23:59:59'])
                        ->WhereIn('season_code', $orch_filter_season)
                        ->WhereIn('uvc', $orch_filter_style)
                        ->WhereIn('po_status', $orch_filter_postatus)
                        ->WhereIn('pi_status', $orch_filter_pistatus)
                        ->where(function($query) use ($orch_filter_orderstatus, $orch_filter_artworkstatus){
                            if($orch_filter_orderstatus)
                            {
                                $query->orWhereIn('status', $orch_filter_orderstatus);
                            }
                            if($orch_filter_artworkstatus)
                            {
                                $query->orWhereIn('status', $orch_filter_artworkstatus);
                            }
                        })
                        ->select(DB::raw('distinct on (orchestra_solid_poly_bag_sticker_orders.uvc,orchestra_solid_poly_bag_sticker_orders.po) UVC as "UVC"'),DB::raw("orchestra_solid_poly_bag_sticker_orders.season_code as \"Season\""),DB::raw("orchestra_solid_poly_bag_sticker_orders.po as \"BuyerPONo\""),DB::raw("'Solid Poly Bag Stickers' as \"ArtworkType\""),DB::raw("'Solid Poly Bag Stickers' as \"TemplateName\""),DB::raw("'-' as \"Reference No\""),'customers.id as customer_id','customers.company_name as SupplierName',DB::raw("to_char(orchestra_solid_poly_bag_sticker_orders.created_at,"."'".$ddmmyy."'".") as \"OrderDate\""),DB::raw("to_char(orchestra_solid_poly_bag_sticker_orders.delivery_date,"."'".$ddmmyy."'".") as \"DeliveryDate\""),'orchestra_solid_poly_bag_sticker_orders.status as Artwork Status','orchestra_solid_poly_bag_sticker_orders.pi_status as PI Status','orchestra_solid_poly_bag_sticker_orders.po_status as PO Status','orchestra_solid_poly_bag_sticker_orders.po_number as PO Number',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'orchestra_solid_poly_bag_sticker_orders' as \"tableName\""),'orchestra_solid_poly_bag_sticker_orders.color_code','orchestra_solid_poly_bag_sticker_orders.id  as tableID','orchestra_solid_poly_bag_sticker_orders.po_file','customers.pi_required','customers.po_required','orchestra_solid_poly_bag_sticker_orders.template_id','orchestra_solid_poly_bag_sticker_orders.order_flag','customers.delivery_day_count','orchestra_solid_poly_bag_sticker_orders.is_order_count_empty')
                        ->groupBy('orchestra_solid_poly_bag_sticker_orders.uvc','orchestra_solid_poly_bag_sticker_orders.po','customers.company_name','orchestra_solid_poly_bag_sticker_orders.created_at','orchestra_solid_poly_bag_sticker_orders.delivery_date','orchestra_solid_poly_bag_sticker_orders.status','orchestra_solid_poly_bag_sticker_orders.pi_status','orchestra_solid_poly_bag_sticker_orders.po_status','orchestra_solid_poly_bag_sticker_orders.po_number','orchestra_solid_poly_bag_sticker_orders.id','customers.pi_required','customers.id')
                        ->get();
              
            $data['orchestra_standard_carton_sticker_orders'] = DB::table('orchestra_standard_carton_sticker_orders')
                        ->join('orchestra_item_master','orchestra_item_master.id', 'orchestra_standard_carton_sticker_orders.template_id')
                        ->leftjoin('customers','customers.id', 'orchestra_standard_carton_sticker_orders.customer_id')
                        ->where('orchestra_standard_carton_sticker_orders.status','!=','Order Cancelled')
                        ->whereBetween('orchestra_standard_carton_sticker_orders.created_at', [$start_date.' 00:00:00', $end_date.' 23:59:59'])
                        ->WhereIn('season_code', $orch_filter_season)
                        ->WhereIn('uvc', $orch_filter_style)
                        ->WhereIn('po_status', $orch_filter_postatus)
                        ->WhereIn('pi_status', $orch_filter_pistatus)
                        ->where(function($query) use ($orch_filter_orderstatus, $orch_filter_artworkstatus){
                            if($orch_filter_orderstatus)
                            {
                                $query->orWhereIn('status', $orch_filter_orderstatus);
                            }
                            if($orch_filter_artworkstatus)
                            {
                                $query->orWhereIn('status', $orch_filter_artworkstatus);
                            }
                        })
                        ->select(DB::raw('distinct on (orchestra_standard_carton_sticker_orders.uvc,orchestra_standard_carton_sticker_orders.po) UVC as "UVC"'),DB::raw("orchestra_standard_carton_sticker_orders.season_code as \"Season\""),DB::raw("orchestra_standard_carton_sticker_orders.po as \"BuyerPONo\""),DB::raw("'Standard Carton Stickers' as \"ArtworkType\""),DB::raw("'Standard Carton Stickers' as \"TemplateName\""),DB::raw("'-' as \"Reference No\""),'customers.id as customer_id','customers.company_name as SupplierName',DB::raw("to_char(orchestra_standard_carton_sticker_orders.created_at,"."'".$ddmmyy."'".") as \"OrderDate\""),DB::raw("to_char(orchestra_standard_carton_sticker_orders.delivery_date,"."'".$ddmmyy."'".") as \"DeliveryDate\""),'orchestra_standard_carton_sticker_orders.status as Artwork Status','orchestra_standard_carton_sticker_orders.pi_status as PI Status','orchestra_standard_carton_sticker_orders.po_status as PO Status','orchestra_standard_carton_sticker_orders.po_number as PO Number',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'orchestra_standard_carton_sticker_orders' as \"tableName\""),'orchestra_standard_carton_sticker_orders.color_code','orchestra_standard_carton_sticker_orders.id  as tableID','orchestra_standard_carton_sticker_orders.po_file','customers.pi_required','customers.po_required','orchestra_standard_carton_sticker_orders.template_id','orchestra_standard_carton_sticker_orders.order_flag','customers.delivery_day_count','orchestra_standard_carton_sticker_orders.is_order_count_empty')
                        ->groupBy('orchestra_standard_carton_sticker_orders.uvc','orchestra_standard_carton_sticker_orders.po','customers.company_name','orchestra_standard_carton_sticker_orders.created_at','orchestra_standard_carton_sticker_orders.delivery_date','orchestra_standard_carton_sticker_orders.status','orchestra_standard_carton_sticker_orders.pi_status','orchestra_standard_carton_sticker_orders.po_status','orchestra_standard_carton_sticker_orders.po_number','orchestra_standard_carton_sticker_orders.id','customers.pi_required','customers.id')
                        ->get();
    
            $data['orchestra_standard_poly_bag_sticker_orders'] = DB::table('orchestra_standard_poly_bag_sticker_orders')
                        ->join('orchestra_item_master','orchestra_item_master.id', 'orchestra_standard_poly_bag_sticker_orders.template_id')
                        ->leftjoin('customers','customers.id', 'orchestra_standard_poly_bag_sticker_orders.customer_id')
                        ->where('orchestra_standard_poly_bag_sticker_orders.status','!=','Order Cancelled')
                        ->whereBetween('orchestra_standard_poly_bag_sticker_orders.created_at', [$start_date.' 00:00:00', $end_date.' 23:59:59'])
                        ->WhereIn('season_code', $orch_filter_season)
                        ->WhereIn('uvc', $orch_filter_style)
                        ->WhereIn('po_status', $orch_filter_postatus)
                        ->WhereIn('pi_status', $orch_filter_pistatus)
                        ->where(function($query) use ($orch_filter_orderstatus, $orch_filter_artworkstatus){
                            if($orch_filter_orderstatus)
                            {
                                $query->orWhereIn('status', $orch_filter_orderstatus);
                            }
                            if($orch_filter_artworkstatus)
                            {
                                $query->orWhereIn('status', $orch_filter_artworkstatus);
                            }
                        })
                        ->select(DB::raw('distinct on (orchestra_standard_poly_bag_sticker_orders.uvc,orchestra_standard_poly_bag_sticker_orders.po) UVC as "UVC"'),DB::raw("orchestra_standard_poly_bag_sticker_orders.season_code as \"Season\""),DB::raw("orchestra_standard_poly_bag_sticker_orders.po as \"BuyerPONo\""),DB::raw("'Standard Poly Bag Stickers' as \"ArtworkType\""),DB::raw("'Standard Poly Bag Stickers' as \"TemplateName\""),DB::raw("'-' as \"Reference No\""),'customers.id as customer_id','customers.company_name as SupplierName',DB::raw("to_char(orchestra_standard_poly_bag_sticker_orders.created_at,"."'".$ddmmyy."'".") as \"OrderDate\""),DB::raw("to_char(orchestra_standard_poly_bag_sticker_orders.delivery_date,"."'".$ddmmyy."'".") as \"DeliveryDate\""),'orchestra_standard_poly_bag_sticker_orders.status as Artwork Status','orchestra_standard_poly_bag_sticker_orders.pi_status as PI Status','orchestra_standard_poly_bag_sticker_orders.po_status as PO Status','orchestra_standard_poly_bag_sticker_orders.po_number as PO Number',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'orchestra_standard_poly_bag_sticker_orders' as \"tableName\""),'orchestra_standard_poly_bag_sticker_orders.color_code','orchestra_standard_poly_bag_sticker_orders.id  as tableID','orchestra_standard_poly_bag_sticker_orders.po_file','customers.pi_required','customers.po_required','orchestra_standard_poly_bag_sticker_orders.template_id','orchestra_standard_poly_bag_sticker_orders.order_flag','customers.delivery_day_count','orchestra_standard_poly_bag_sticker_orders.is_order_count_empty')
                        ->groupBy('orchestra_standard_poly_bag_sticker_orders.uvc','orchestra_standard_poly_bag_sticker_orders.po','customers.company_name','orchestra_standard_poly_bag_sticker_orders.created_at','orchestra_standard_poly_bag_sticker_orders.delivery_date','orchestra_standard_poly_bag_sticker_orders.status','orchestra_standard_poly_bag_sticker_orders.pi_status','orchestra_standard_poly_bag_sticker_orders.po_status','orchestra_standard_poly_bag_sticker_orders.po_number','orchestra_standard_poly_bag_sticker_orders.id','customers.pi_required','customers.id')
                        ->get();

            echo  json_encode($data);
    }
}
