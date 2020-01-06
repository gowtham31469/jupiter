<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\OrchestraSolidPolySticker;
use App\OrchestraSolidPolyStickerQty;
use App\OrchestraSolidCartonSticker;
use App\OrchestraSolidCartonStickerQty;
use App\OrchestraParcelDetails;
use App\OrchestraStdCartonStickerQty;
use App\OrchestraStdCartonSticker;
use App\OrchestraStdPolyStickerQty;
use App\OrchestraStdPolySticker;
use App\OrchestraHangTagQty;
use App\OrchestraHangTag;
use App\OrchestraWashCare;
use App\OrchestraWashCareQty;
use App\OrchestraAgeGroup;
use App\Buyer;
use App\Customer;
use App\History;
use App\Action;
use App\EventHistory;
use App\ErpOrderDetails;
use App\ErpOrdersInvoice; 
use App\Invoices;
use App\InvoiceDetails;
use App\InvExtraCharges;
use App\Classes\Invoice;
use App\InvoiceAddress;
use App\TescoTaxCode;
use Carbon\Carbon;
use Response;
use View;
use Auth;
use DB;
use Session;
use App\ErpOrders as Erp;
use PDF;
use App\Classes\JobcardPDFHeaderFooter;
use App\Classes\FPDF_Merge;
use File;
use Config;
use Yajra\DataTables\Facades\DataTables;
use App\TescoTCL1Orders;
use App\TescoTCL1OrdersQuantity;
use App\TescoTCL2Orders;
use App\TescoTCL2OrdersQuantity;
use App\TescoTCL4Orders;
use App\TescoTCL4OrdersQuantity;
use App\TescoTHL1Orders;
use App\TescoTHL1OrdersQuantity;
use App\TescoTHL3Orders;
use App\TescoTHL3OrdersQuantity;
use App\TescoTSL1Orders;
use App\TescoTSL1OrdersQuantity;
use App\TescoTSL3Orders;
use App\TescoTSL3OrdersQuantity;


class OrderStatusAjaxCntrl extends Controller
{

  public function getOrderStatusesOrch(Request $request)
  {

    $ddmmyy="DD-MM-YYYY";
      $actions='-';
            $data1 = DB::table('orchestra_hang_tag_orders')->join('orchestra_parcel_details','orchestra_parcel_details.UVC','=','orchestra_hang_tag_orders.uvc')->join('orchestra_item_master','orchestra_item_master.id','=','orchestra_hang_tag_orders.template_front_id')->leftjoin('customers','customers.factory_code','=','orchestra_parcel_details.FactoryCode')->where('orchestra_hang_tag_orders.status','!=','Order Cancelled')->select('orchestra_hang_tag_orders.color_code','orchestra_parcel_details.UVC',DB::raw("orchestra_hang_tag_orders.season_code as \"Season\""),DB::raw("'-' as \"BuyerPONo\""),DB::raw("'Hang Tag' as \"ArtworkType\""),DB::raw("'Hang Tag' as \"TemplateName\""),'orchestra_item_master.item_name as Reference No','customers.id as customer_id','orchestra_parcel_details.SupplierName as SupplierName',DB::raw("to_char(orchestra_hang_tag_orders.created_at,"."'".$ddmmyy."'".") as \"OrderDate\""),DB::raw("to_char(orchestra_hang_tag_orders.delivery_date,"."'".$ddmmyy."'".") as \"DeliveryDate\""),'orchestra_hang_tag_orders.status as artworkStatus','orchestra_hang_tag_orders.pi_status as pIstatus','orchestra_hang_tag_orders.po_status as poStatus','orchestra_hang_tag_orders.po_number as PONumber',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'orchestra_hang_tag_orders' as \"tableName\""),'orchestra_hang_tag_orders.id  as tableID','orchestra_hang_tag_orders.po_file','customers.pi_required','customers.po_required','orchestra_hang_tag_orders.template_front_id as template_id','orchestra_hang_tag_orders.order_flag','customers.delivery_day_count','orchestra_hang_tag_orders.is_order_count_empty')->groupBy('orchestra_parcel_details.UVC','orchestra_parcel_details.SupplierName','orchestra_hang_tag_orders.created_at','orchestra_hang_tag_orders.delivery_date','orchestra_hang_tag_orders.status','orchestra_hang_tag_orders.pi_status','orchestra_hang_tag_orders.status','orchestra_hang_tag_orders.po_status','orchestra_hang_tag_orders.po_number','customers.pi_required','customers.id','orchestra_hang_tag_orders.id','orchestra_item_master.item_name')->orderBy('orchestra_hang_tag_orders.id','asc')
              ->where(function ($query) use ($request)
              {
              if($request->dateBegin && $request->dateEnd)
              {

              $query->whereBetween('orchestra_hang_tag_orders.created_at', [date('Y-m-d',strtotime($request->dateBegin)), date('Y-m-d',strtotime($request->dateEnd))]);
              }
              })
              ->get();

            $data2 = DB::table('orchestra_special_tag_orders')->join('orchestra_parcel_details','orchestra_parcel_details.UVC','=','orchestra_special_tag_orders.uvc')->join('orchestra_item_master','orchestra_item_master.id','=','orchestra_special_tag_orders.template_id')->leftjoin('customers','customers.factory_code','=','orchestra_parcel_details.FactoryCode')->where('orchestra_special_tag_orders.status','!=','Order Cancelled')->select('orchestra_special_tag_orders.color_code','orchestra_parcel_details.UVC',DB::raw("orchestra_special_tag_orders.season_code as \"Season\""),DB::raw("'-' as \"BuyerPONo\""),DB::raw("'Special Tag' as \"ArtworkType\""),DB::raw("'Special Tag' as \"TemplateName\""),'orchestra_item_master.item_name as Reference No','customers.id as customer_id','orchestra_parcel_details.SupplierName as SupplierName',DB::raw("to_char(orchestra_special_tag_orders.created_at,"."'".$ddmmyy."'".") as \"OrderDate\""),DB::raw("to_char(orchestra_special_tag_orders.delivery_date,"."'".$ddmmyy."'".") as \"DeliveryDate\""),'orchestra_special_tag_orders.status as artworkStatus','orchestra_special_tag_orders.pi_status as pIstatus','orchestra_special_tag_orders.po_status as poStatus','orchestra_special_tag_orders.po_number as PONumber',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'orchestra_special_tag_orders' as \"tableName\""),'orchestra_special_tag_orders.id  as tableID','orchestra_special_tag_orders.po_file','customers.pi_required','customers.po_required','orchestra_special_tag_orders.template_id','orchestra_special_tag_orders.order_flag','customers.delivery_day_count','orchestra_special_tag_orders.is_order_count_empty')->groupBy('orchestra_parcel_details.UVC','orchestra_parcel_details.SupplierName','orchestra_special_tag_orders.created_at','orchestra_special_tag_orders.delivery_date','orchestra_special_tag_orders.status','orchestra_special_tag_orders.pi_status','orchestra_special_tag_orders.status','orchestra_special_tag_orders.po_status','orchestra_special_tag_orders.po_number','customers.pi_required','customers.id','orchestra_special_tag_orders.id','orchestra_item_master.item_name')->orderBy('orchestra_special_tag_orders.id','asc')
              ->where(function ($query) use ($request)
                {
                if($request->dateBegin && $request->dateEnd)
                {

                $query->whereBetween('orchestra_special_tag_orders.created_at', [date('Y-m-d',strtotime($request->dateBegin)), date('Y-m-d',strtotime($request->dateEnd))]);
                }
                })->get();
     
               
            $data3 = DB::table('orchestra_main_label_orders')->join('orchestra_parcel_details','orchestra_parcel_details.UVC','=','orchestra_main_label_orders.uvc')->join('orchestra_item_master','orchestra_item_master.id','=','orchestra_main_label_orders.template_id')->leftjoin('customers','customers.factory_code','=','orchestra_parcel_details.FactoryCode')->where('orchestra_main_label_orders.status','!=','Order Cancelled')->select('orchestra_main_label_orders.color_code','orchestra_parcel_details.UVC',DB::raw("orchestra_main_label_orders.season_code as \"Season\""),DB::raw("'-' as \"BuyerPONo\""),DB::raw("'Main Label' as \"ArtworkType\""),DB::raw("'Main Label' as \"TemplateName\""),'orchestra_item_master.item_name as Reference No','customers.id as customer_id','orchestra_parcel_details.SupplierName as SupplierName',DB::raw("to_char(orchestra_main_label_orders.created_at,"."'".$ddmmyy."'".") as \"OrderDate\""),DB::raw("to_char(orchestra_main_label_orders.delivery_date,"."'".$ddmmyy."'".") as \"DeliveryDate\""),'orchestra_main_label_orders.status as artworkStatus','orchestra_main_label_orders.pi_status as pIstatus','orchestra_main_label_orders.po_status as poStatus','orchestra_main_label_orders.po_number as PONumber',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'orchestra_main_label_orders' as \"tableName\""),'orchestra_main_label_orders.id  as tableID','orchestra_main_label_orders.po_file','customers.pi_required','customers.po_required','orchestra_main_label_orders.template_id','orchestra_main_label_orders.order_flag','customers.delivery_day_count','orchestra_main_label_orders.is_order_count_empty')->groupBy('orchestra_parcel_details.UVC','orchestra_parcel_details.SupplierName','orchestra_main_label_orders.created_at','orchestra_main_label_orders.delivery_date','orchestra_main_label_orders.status','orchestra_main_label_orders.pi_status','orchestra_main_label_orders.status','orchestra_main_label_orders.po_status','orchestra_main_label_orders.po_number','customers.pi_required','customers.id','orchestra_main_label_orders.id','orchestra_item_master.item_name')->orderBy('orchestra_main_label_orders.id','asc')
              ->where(function ($query) use ($request)
                  {
                  if($request->dateBegin && $request->dateEnd)
                  {

                  $query->whereBetween('orchestra_main_label_orders.created_at', [date('Y-m-d',strtotime($request->dateBegin)), date('Y-m-d',strtotime($request->dateEnd))]);
                  }
                  })->get();
    
      $data3 = DB::table('orchestra_size_label_orders')->join('orchestra_parcel_details','orchestra_parcel_details.UVC','=','orchestra_size_label_orders.uvc')->join('orchestra_item_master','orchestra_item_master.id','=','orchestra_size_label_orders.template_id')->leftjoin('customers','customers.factory_code','=','orchestra_parcel_details.FactoryCode')->where('orchestra_size_label_orders.status','!=','Order Cancelled')->select('orchestra_size_label_orders.color_code','orchestra_parcel_details.UVC',DB::raw("orchestra_size_label_orders.season_code as \"Season\""),DB::raw("'-' as \"BuyerPONo\""),DB::raw("'Size Label' as \"ArtworkType\""),DB::raw("'Size Label' as \"TemplateName\""),'orchestra_item_master.item_name as Reference No','customers.id as customer_id','orchestra_parcel_details.SupplierName as SupplierName',DB::raw("to_char(orchestra_size_label_orders.created_at,"."'".$ddmmyy."'".") as \"OrderDate\""),DB::raw("to_char(orchestra_size_label_orders.delivery_date,"."'".$ddmmyy."'".") as \"DeliveryDate\""),'orchestra_size_label_orders.status as artworkStatus','orchestra_size_label_orders.pi_status as pIstatus','orchestra_size_label_orders.po_status as poStatus','orchestra_size_label_orders.po_number as PONumber',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'orchestra_size_label_orders' as \"tableName\""),'orchestra_size_label_orders.id  as tableID','orchestra_size_label_orders.po_file','customers.pi_required','customers.po_required','orchestra_size_label_orders.template_id','orchestra_size_label_orders.order_flag','customers.delivery_day_count','orchestra_size_label_orders.is_order_count_empty')->groupBy('orchestra_parcel_details.UVC','orchestra_parcel_details.SupplierName','orchestra_size_label_orders.created_at','orchestra_size_label_orders.delivery_date','orchestra_size_label_orders.status','orchestra_size_label_orders.pi_status','orchestra_size_label_orders.status','orchestra_size_label_orders.po_status','orchestra_size_label_orders.po_number','customers.pi_required','customers.id','orchestra_size_label_orders.id','orchestra_item_master.item_name')->orderBy('orchestra_size_label_orders.id','asc')
        ->where(function ($query) use ($request)
                  {
                  if($request->dateBegin && $request->dateEnd)
                  {

                  $query->whereBetween('orchestra_size_label_orders.created_at', [date('Y-m-d',strtotime($request->dateBegin)), date('Y-m-d',strtotime($request->dateEnd))]);
                  }
                  })->get();
    
      $data4 = DB::table('orchestra_heat_transfer_orders')->join('orchestra_parcel_details','orchestra_parcel_details.UVC','=','orchestra_heat_transfer_orders.uvc')->join('orchestra_item_master','orchestra_item_master.id','=','orchestra_heat_transfer_orders.template_id')->leftjoin('customers','customers.factory_code','=','orchestra_parcel_details.FactoryCode')->where('orchestra_heat_transfer_orders.status','!=','Order Cancelled')->select('orchestra_heat_transfer_orders.color_code','orchestra_parcel_details.UVC',DB::raw("orchestra_heat_transfer_orders.season_code as \"Season\""),DB::raw("'-' as \"BuyerPONo\""),DB::raw("'Heat Transfer' as \"ArtworkType\""),DB::raw("'Heat Transfer' as \"TemplateName\""),'orchestra_item_master.item_name as Reference No','customers.id as customer_id','orchestra_parcel_details.SupplierName as SupplierName',DB::raw("to_char(orchestra_heat_transfer_orders.created_at,"."'".$ddmmyy."'".") as \"OrderDate\""),DB::raw("to_char(orchestra_heat_transfer_orders.delivery_date,"."'".$ddmmyy."'".") as \"DeliveryDate\""),'orchestra_heat_transfer_orders.status as artworkStatus','orchestra_heat_transfer_orders.pi_status as pIstatus','orchestra_heat_transfer_orders.po_status as poStatus','orchestra_heat_transfer_orders.po_number as PONumber',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'orchestra_heat_transfer_orders' as \"tableName\""),'orchestra_heat_transfer_orders.id  as tableID','orchestra_heat_transfer_orders.po_file','customers.pi_required','customers.po_required','orchestra_heat_transfer_orders.template_id','orchestra_heat_transfer_orders.order_flag','customers.delivery_day_count','orchestra_heat_transfer_orders.is_order_count_empty')->groupBy('orchestra_parcel_details.UVC','orchestra_parcel_details.SupplierName','orchestra_heat_transfer_orders.created_at','orchestra_heat_transfer_orders.delivery_date','orchestra_heat_transfer_orders.status','orchestra_heat_transfer_orders.pi_status','orchestra_heat_transfer_orders.status','orchestra_heat_transfer_orders.po_status','orchestra_heat_transfer_orders.po_number','customers.pi_required','customers.id','orchestra_heat_transfer_orders.id','orchestra_item_master.item_name')->orderBy('orchestra_heat_transfer_orders.id','asc')
        ->where(function ($query) use ($request)
                  {
                  if($request->dateBegin && $request->dateEnd)
                  {

                  $query->whereBetween('orchestra_heat_transfer_orders.created_at', [date('Y-m-d',strtotime($request->dateBegin)), date('Y-m-d',strtotime($request->dateEnd))]);
                  }
                  })->get();

            $data5 = DB::table('orchestra_wash_care_label_orders')->join('orchestra_parcel_details','orchestra_parcel_details.UVC','=','orchestra_wash_care_label_orders.uvc')->join('orchestra_item_master','orchestra_item_master.id','=','orchestra_wash_care_label_orders.template_id')->leftjoin('customers','customers.factory_code','=','orchestra_parcel_details.FactoryCode')->where('orchestra_wash_care_label_orders.status','!=','Order Cancelled')->select('orchestra_wash_care_label_orders.color_code','orchestra_parcel_details.UVC',DB::raw("orchestra_wash_care_label_orders.season_code as \"Season\""),DB::raw("orchestra_wash_care_label_orders.po as \"BuyerPONo\""),DB::raw("'WashCareLabel' as \"ArtworkType\""),'orchestra_wash_care_label_orders.template_name as TemplateName','orchestra_item_master.item_name as Reference No','customers.id as customer_id','orchestra_parcel_details.SupplierName as SupplierName',DB::raw("to_char(orchestra_wash_care_label_orders.created_at,"."'".$ddmmyy."'".") as \"OrderDate\""),DB::raw("to_char(orchestra_wash_care_label_orders.delivery_date,"."'".$ddmmyy."'".") as \"DeliveryDate\""),'orchestra_wash_care_label_orders.status as artworkStatus','orchestra_wash_care_label_orders.pi_status as pIstatus','orchestra_wash_care_label_orders.po_status as poStatus','orchestra_wash_care_label_orders.po_number as PONumber',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'orchestra_wash_care_label_orders' as \"tableName\""),'orchestra_wash_care_label_orders.id  as tableID','orchestra_wash_care_label_orders.po_file','customers.pi_required','customers.po_required','orchestra_wash_care_label_orders.template_id','orchestra_wash_care_label_orders.order_flag','customers.delivery_day_count','orchestra_wash_care_label_orders.is_order_count_empty')->groupBy('orchestra_parcel_details.UVC','orchestra_parcel_details.SupplierName','orchestra_wash_care_label_orders.created_at','orchestra_wash_care_label_orders.delivery_date','orchestra_wash_care_label_orders.status','orchestra_wash_care_label_orders.pi_status','orchestra_wash_care_label_orders.status','orchestra_wash_care_label_orders.po_status','orchestra_wash_care_label_orders.po_number','customers.pi_required','customers.id','orchestra_wash_care_label_orders.id','orchestra_item_master.item_name')->orderBy('orchestra_wash_care_label_orders.id','asc')
              ->where(function ($query) use ($request)
                  {
                  if($request->dateBegin && $request->dateEnd)
                  {

                  $query->whereBetween('orchestra_wash_care_label_orders.created_at', [date('Y-m-d',strtotime($request->dateBegin)), date('Y-m-d',strtotime($request->dateEnd))]);
                  }
                  })->get();

            $data6 = DB::table('orchestra_solid_carton_sticker_orders')->join('orchestra_parcel_details','orchestra_parcel_details.UVC','=','orchestra_solid_carton_sticker_orders.uvc')->join('orchestra_item_master','orchestra_item_master.id','=','orchestra_solid_carton_sticker_orders.template_id')->leftjoin('customers','customers.factory_code','=','orchestra_parcel_details.FactoryCode')->where('orchestra_solid_carton_sticker_orders.status','!=','Order Cancelled')->select(DB::raw('distinct on (orchestra_solid_carton_sticker_orders.uvc,orchestra_solid_carton_sticker_orders.po) "UVC"'),DB::raw("orchestra_solid_carton_sticker_orders.season_code as \"Season\""),DB::raw("orchestra_solid_carton_sticker_orders.po as \"BuyerPONo\""),DB::raw("'Solid Carton Stickers' as \"ArtworkType\""),DB::raw("'Solid Carton Stickers' as \"TemplateName\""),'customers.id as customer_id','orchestra_parcel_details.SupplierName as SupplierName',DB::raw("to_char(orchestra_solid_carton_sticker_orders.created_at,"."'".$ddmmyy."'".") as \"OrderDate\""),DB::raw("to_char(orchestra_solid_carton_sticker_orders.delivery_date,"."'".$ddmmyy."'".") as \"DeliveryDate\""),'orchestra_solid_carton_sticker_orders.color_code','orchestra_solid_carton_sticker_orders.status as artworkStatus','orchestra_solid_carton_sticker_orders.pi_status as pIstatus','orchestra_solid_carton_sticker_orders.po_status as poStatus','orchestra_solid_carton_sticker_orders.po_number as PONumber',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'orchestra_solid_carton_sticker_orders' as \"tableName\""),'orchestra_solid_carton_sticker_orders.id  as tableID','orchestra_solid_carton_sticker_orders.po_file','customers.pi_required','customers.po_required','orchestra_solid_carton_sticker_orders.template_id','orchestra_solid_carton_sticker_orders.order_flag','customers.delivery_day_count','orchestra_solid_carton_sticker_orders.is_order_count_empty')->where('orchestra_parcel_details.IsSTD',0)->groupBy('orchestra_solid_carton_sticker_orders.uvc','orchestra_parcel_details.UVC','orchestra_solid_carton_sticker_orders.po','orchestra_parcel_details.SupplierName','orchestra_solid_carton_sticker_orders.created_at','orchestra_solid_carton_sticker_orders.delivery_date','orchestra_solid_carton_sticker_orders.status','orchestra_solid_carton_sticker_orders.pi_status','orchestra_solid_carton_sticker_orders.status','orchestra_solid_carton_sticker_orders.po_status','orchestra_solid_carton_sticker_orders.po_number','customers.pi_required','customers.id','orchestra_solid_carton_sticker_orders.id')->where(function ($query) use ($request)
                  {
                  if($request->dateBegin && $request->dateEnd)
                  {

                  $query->whereBetween('orchestra_solid_carton_sticker_orders.created_at', [date('Y-m-d',strtotime($request->dateBegin)), date('Y-m-d',strtotime($request->dateEnd))]);
                  }
                  })->get();
              
            $data7 = DB::table('orchestra_solid_poly_bag_sticker_orders')->join('orchestra_parcel_details','orchestra_parcel_details.UVC','=','orchestra_solid_poly_bag_sticker_orders.uvc')->join('orchestra_item_master','orchestra_item_master.id','=','orchestra_solid_poly_bag_sticker_orders.template_id')->leftjoin('customers','customers.factory_code','=','orchestra_parcel_details.FactoryCode')->where('orchestra_solid_poly_bag_sticker_orders.status','!=','Order Cancelled')->select(DB::raw('distinct on (orchestra_solid_poly_bag_sticker_orders.uvc,orchestra_solid_poly_bag_sticker_orders.po) "UVC"'),DB::raw("orchestra_solid_poly_bag_sticker_orders.season_code as \"Season\""),DB::raw("orchestra_solid_poly_bag_sticker_orders.po as \"BuyerPONo\""),DB::raw("'Solid Poly Bag Stickers' as \"ArtworkType\""),DB::raw("'Solid Poly Bag Stickers' as \"TemplateName\""),'customers.id as customer_id','orchestra_parcel_details.SupplierName as SupplierName',DB::raw("to_char(orchestra_solid_poly_bag_sticker_orders.created_at,"."'".$ddmmyy."'".") as \"OrderDate\""),DB::raw("to_char(orchestra_solid_poly_bag_sticker_orders.delivery_date,"."'".$ddmmyy."'".") as \"DeliveryDate\""),'orchestra_solid_poly_bag_sticker_orders.status as artworkStatus','orchestra_solid_poly_bag_sticker_orders.pi_status as pIstatus','orchestra_solid_poly_bag_sticker_orders.po_status as poStatus','orchestra_solid_poly_bag_sticker_orders.po_number as PONumber',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'orchestra_solid_poly_bag_sticker_orders' as \"tableName\""),'orchestra_solid_poly_bag_sticker_orders.color_code','orchestra_solid_poly_bag_sticker_orders.id  as tableID','orchestra_solid_poly_bag_sticker_orders.po_file','customers.pi_required','customers.po_required','orchestra_solid_poly_bag_sticker_orders.template_id','orchestra_solid_poly_bag_sticker_orders.order_flag','customers.delivery_day_count','orchestra_solid_poly_bag_sticker_orders.is_order_count_empty')->where('orchestra_parcel_details.IsSTD',0)->groupBy('orchestra_solid_poly_bag_sticker_orders.uvc','orchestra_parcel_details.UVC','orchestra_solid_poly_bag_sticker_orders.po','orchestra_parcel_details.SupplierName','orchestra_solid_poly_bag_sticker_orders.created_at','orchestra_solid_poly_bag_sticker_orders.delivery_date','orchestra_solid_poly_bag_sticker_orders.status','orchestra_solid_poly_bag_sticker_orders.pi_status','orchestra_solid_poly_bag_sticker_orders.po_status','orchestra_solid_poly_bag_sticker_orders.po_number','orchestra_solid_poly_bag_sticker_orders.id','customers.pi_required','customers.id')
              ->where(function ($query) use ($request)
                  {
                  if($request->dateBegin && $request->dateEnd)
                  {

                  $query->whereBetween('orchestra_solid_poly_bag_sticker_orders.created_at', [date('Y-m-d',strtotime($request->dateBegin)), date('Y-m-d',strtotime($request->dateEnd))]);
                  }
                  })->get();
    
            $data8 = DB::table('orchestra_standard_carton_sticker_orders')->join('orchestra_parcel_details','orchestra_parcel_details.UVC','=','orchestra_standard_carton_sticker_orders.uvc')->join('orchestra_item_master','orchestra_item_master.id','=','orchestra_standard_carton_sticker_orders.template_id')->leftjoin('customers','customers.factory_code','=','orchestra_parcel_details.FactoryCode')->where('orchestra_standard_carton_sticker_orders.status','!=','Order Cancelled')->select(DB::raw('distinct on (orchestra_standard_carton_sticker_orders.uvc,orchestra_standard_carton_sticker_orders.po) "UVC"'),DB::raw("orchestra_standard_carton_sticker_orders.season_code as \"Season\""),DB::raw("orchestra_standard_carton_sticker_orders.po as \"BuyerPONo\""),DB::raw("'Standard Carton Stickers' as \"ArtworkType\""),DB::raw("'Standard Carton Stickers' as \"TemplateName\""),'customers.id as customer_id','orchestra_parcel_details.SupplierName as SupplierName',DB::raw("to_char(orchestra_standard_carton_sticker_orders.created_at,"."'".$ddmmyy."'".") as \"OrderDate\""),DB::raw("to_char(orchestra_standard_carton_sticker_orders.delivery_date,"."'".$ddmmyy."'".") as \"DeliveryDate\""),'orchestra_standard_carton_sticker_orders.status as artworkStatus','orchestra_standard_carton_sticker_orders.pi_status as pIstatus','orchestra_standard_carton_sticker_orders.po_status as poStatus','orchestra_standard_carton_sticker_orders.po_number as PONumber',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'orchestra_standard_carton_sticker_orders' as \"tableName\""),'orchestra_standard_carton_sticker_orders.color_code','orchestra_standard_carton_sticker_orders.id  as tableID','orchestra_standard_carton_sticker_orders.po_file','customers.pi_required','customers.po_required','orchestra_standard_carton_sticker_orders.template_id','orchestra_standard_carton_sticker_orders.order_flag','customers.delivery_day_count','orchestra_standard_carton_sticker_orders.is_order_count_empty')->where('orchestra_parcel_details.IsSTD',1)->groupBy('orchestra_standard_carton_sticker_orders.uvc','orchestra_parcel_details.UVC','orchestra_standard_carton_sticker_orders.po','orchestra_parcel_details.SupplierName','orchestra_standard_carton_sticker_orders.created_at','orchestra_standard_carton_sticker_orders.delivery_date','orchestra_standard_carton_sticker_orders.status','orchestra_standard_carton_sticker_orders.pi_status','orchestra_standard_carton_sticker_orders.po_status','orchestra_standard_carton_sticker_orders.po_number','orchestra_standard_carton_sticker_orders.id','customers.pi_required','customers.id')
              ->where(function ($query) use ($request)
                  {
                  if($request->dateBegin && $request->dateEnd)
                  {

                  $query->whereBetween('orchestra_standard_carton_sticker_orders.created_at', [date('Y-m-d',strtotime($request->dateBegin)), date('Y-m-d',strtotime($request->dateEnd))]);
                  }
                  })->get();

            $data9 = DB::table('orchestra_standard_poly_bag_sticker_orders')->join('orchestra_parcel_details','orchestra_parcel_details.UVC','=','orchestra_standard_poly_bag_sticker_orders.uvc')->join('orchestra_item_master','orchestra_item_master.id','=','orchestra_standard_poly_bag_sticker_orders.template_id')->leftjoin('customers','customers.factory_code','=','orchestra_parcel_details.FactoryCode')->where('orchestra_standard_poly_bag_sticker_orders.status','!=','Order Cancelled')->select(DB::raw('distinct on (orchestra_standard_poly_bag_sticker_orders.uvc,orchestra_standard_poly_bag_sticker_orders.po) "UVC"'),DB::raw("orchestra_standard_poly_bag_sticker_orders.season_code as \"Season\""),DB::raw("orchestra_standard_poly_bag_sticker_orders.po as \"BuyerPONo\""),DB::raw("'Standard Poly Bag Stickers' as \"ArtworkType\""),DB::raw("'Standard Poly Bag Stickers' as \"TemplateName\""),'customers.id as customer_id','orchestra_parcel_details.SupplierName as SupplierName',DB::raw("to_char(orchestra_standard_poly_bag_sticker_orders.created_at,"."'".$ddmmyy."'".") as \"OrderDate\""),DB::raw("to_char(orchestra_standard_poly_bag_sticker_orders.delivery_date,"."'".$ddmmyy."'".") as \"DeliveryDate\""),'orchestra_standard_poly_bag_sticker_orders.status as artworkStatus','orchestra_standard_poly_bag_sticker_orders.pi_status as pIstatus','orchestra_standard_poly_bag_sticker_orders.po_status as poStatus','orchestra_standard_poly_bag_sticker_orders.po_number as PONumber',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'orchestra_standard_poly_bag_sticker_orders' as \"tableName\""),'orchestra_standard_poly_bag_sticker_orders.color_code','orchestra_standard_poly_bag_sticker_orders.id  as tableID','orchestra_standard_poly_bag_sticker_orders.po_file','customers.pi_required','customers.po_required','orchestra_standard_poly_bag_sticker_orders.template_id','orchestra_standard_poly_bag_sticker_orders.order_flag','customers.delivery_day_count','orchestra_standard_poly_bag_sticker_orders.is_order_count_empty')->where('orchestra_parcel_details.IsSTD',1)->groupBy('orchestra_standard_poly_bag_sticker_orders.uvc','orchestra_parcel_details.UVC','orchestra_standard_poly_bag_sticker_orders.po','orchestra_parcel_details.SupplierName','orchestra_standard_poly_bag_sticker_orders.created_at','orchestra_standard_poly_bag_sticker_orders.delivery_date','orchestra_standard_poly_bag_sticker_orders.status','orchestra_standard_poly_bag_sticker_orders.pi_status','orchestra_standard_poly_bag_sticker_orders.po_status','orchestra_standard_poly_bag_sticker_orders.po_number','orchestra_standard_poly_bag_sticker_orders.id','customers.pi_required','customers.id')
              ->where(function ($query) use ($request)
                  {
                  if($request->dateBegin && $request->dateEnd)
                  {

                  $query->whereBetween('orchestra_standard_poly_bag_sticker_orders.created_at', [date('Y-m-d',strtotime($request->dateBegin)), date('Y-m-d',strtotime($request->dateEnd))]);
                  }
                  })->get();


            $collection = $data1->merge($data2)->merge($data3)->merge($data4)->merge($data5)->merge($data6)->merge($data7)->merge($data8)->merge($data9);
            
            // return $data;


            if($request->template)
          {
            $collection = $collection->filter(function($value, $key) use($request) {
              if ($value->TemplateName == $request->template) 
              {
                return true;
              }
            });
          }

          if($request->customer)
          {
            $collection = $collection->filter(function($value, $key) use($request) {
              if ($value->SupplierName == $request->customer) 
              {
                return true;
              }
            });
          }

          if($request->style)
          {
            $collection = $collection->filter(function($value, $key) use($request) {
              if ($value->UVC == $request->style) 
              {
                return true;
              }
            });
          }
            return Datatables::of($collection)



            ->setRowId(function($data) {
              return $data->tableName."_".$data->tableID;
            })

            ->addColumn('artworkStatus', function ($data) {

              if($data->is_order_count_empty == 0) 
              {
                $status = str_replace(' ', '', $data->artworkStatus);
                return '<span class="label label-' . $status . '">' .$data->artworkStatus. '</span>';
              }
              else
              {
                return '<span class="label label-CreateOrder">Create Order</span>';
              }
            })

            ->addColumn('templateName', function ($data) {

                if($data->color_code != "" && $data->TemplateName != null)
                {
                  return $data->color_code.' '.$data->TemplateName;
                }
                else
                {
                  return $data->TemplateName;
                }
            })

            ->addColumn('uvc', function ($data) {

        
                   $order_flag = $data->order_flag;
                   $span_style = '';
                    if ($data->order_flag == "reOrder") {
                        $span_style = '<span title="Additional Quantity" class="batch-additional">A</span>';
                    }
                    if ($data->order_flag == "repeatOrder") {
                        $span_style = '<span title="Repeat Order" class="batch-reorder">R</span>';
                    }

                    return '<a class="" href="javascript:void(0)" onclick=updateArtworkOrc(this)>'.$span_style.'' . $data->UVC. '</a>';
               
            })

            ->addColumn('poStatus', function ($data) {
              if($data->po_required == 1 && $data->is_order_count_empty == 0) 
              {

                if($data->poStatus != null &&  $data->poStatus != " ")
                {
                  return '<span class="po_class label label-POPending">'.$data->poStatus.'</span>';
                }
                else{
                  return '<span class="po_class label label-POPending">PO Pending</span>';
                }

              }
              else{
                return "-";
              }
            })


            ->addColumn('pIstatus', function ($data) {
              if($data->pIstatus != null && $data->pIstatus != "" && $data->is_order_count_empty == 0) 
              {
                if ($data->pIstatus == "PI Approved") {
                  $action_status = true;
                }

                $status = str_replace(' ', '', $data->pIstatus);
                return '<span class="pi_class label label-' . $status . '">' . $data->poStatus . '</span>';
              }
              else{
                return "-";
              }
            })

            ->addColumn('deliveryDate', function ($data) {
              if ($data->is_order_count_empty == 0) {
                if ($data->DeliveryDate == null) {
                  return '<span style="display:none;">"' . $data->OrderDate . '"</span><input class="form-control input-sm datepicker_recurring_start" data-id="' . $data->delivery_day_count . '" style="width: 107px; border-color: rgb(244, 67, 54);" placeholder="Delivery Date" onchange=updateDeliveryDate(this,"' .$data->tableID . '","'.$data->tableName . '")  readonly>';
                } else {

                  return '<span style="display:none;">"' . $data->OrderDate . '"</span><input value="'.$data->DeliveryDate.'" class="form-control input-sm datepicker_recurring_start" data-id="' .$data->delivery_day_count . '" style="width: 107px;" placeholder="Delivery Date" onchange=updateDeliveryDate(this,"' .$data->tableID . '","' .$data->tableName. '") readonly></td>';
                }
              } else {
                return '<span style=display:none;>' . $data->OrderDate . '</span> - </td>';
              }
            })

            ->addColumn('actions', function ($data){

              // $zone = $data->Zone;
              // $style_no = "";
              // if ($zone == "CE") {
              // $style_no = $data->ce_style_ref;
              // } else {
              // $style_no = $data->uk_style_ref;
              // }

              $po_file = $data->po_file;
              if ($po_file != null) {
              $style_po = "style='border-color:#FFFFFF'";
              } else {
              $style_po = "style='border-color:#FF0000'";
              }
              $pi_required = $data->pi_required;
              $po_required = $data->po_required;

              if ($data->pIstatus == "PI Approved") {
              $action_status = "true";
              }
              else{
              $action_status = "false";
              }

              if ($data->artworkStatus != "Order Cancelled" && $data->is_order_count_empty == 0) {
              if ($pi_required == 1 && $po_required == 1) {
              return '<a class="pi_generation" href="javascript:void(0)" onclick=genarateOrchetraPI("' .$data->tableID . '","' .$data->tableName . '","' .$data->UVC. '","' . $action_status . '","'.$data->BuyerPONo.'")><i class="fa fa-file-pdf-o fa-button pi-view filterbtn options" data-placement="top" title="Proforma Invoice"></i></a>

              <a href="javascript:void(0)" onclick=orchestraPOUpload(this,"' .$data->tableID . '","'  .$po_file.'","' .$data->tableName . '") class="po-style" ><i class="fa fa-file-powerpoint-o fa-button po-view filterbtn options" data-placement="top" title="Purchase Order" ' . $style_po . '></i></a>';

              } else if ($po_required == 1 && $pi_required == 0) {

              return '<a href="javascript:void(0)" class="po-style" onclick=orchestraPOUpload(this,"' .$data->tableID . '","' . $po_file . '","' .$data->tableName . '") ><i class="fa fa-file-powerpoint-o fa-button po-view filterbtn options" data-placement="top" title="Purchase Order" ' . $style_po . '></i></a>';

              } else if ($pi_required == 1 && $po_required == 0) {

              return '<a class="pi_generation" href="javascript:void(0)" onclick=genarateOrchetraPI("' .$data->tableID . '","' .$data->tableName . '","' .$data->UVC . '","' . $action_status . '","'.$data->BuyerPONo.'")><i class="fa fa-file-pdf-o fa-button pi-view filterbtn options" data-placement="top" title="Proforma Invoice"></i></a>';
              }
              } else {
              return "-";
              }

              })

            ->editColumn('PONumber', function($data)
            {

               if($data->po_required == 1 && $data->is_order_count_empty == 0) {
                    
                    $customer_po_number = $data->PONumber === null?'':$data->PONumber;

                    return '<div class="form-group has-feedback"><div class="input-group"><input type="text" onkeypress="return AvoidSpace(event)" name="orchestra-po" id="orchestra-po-' . $data->tableID . '" class="form-control input-sm orchestra-po" placeholder="Enter PO" maxlength="11" value="' . $customer_po_number . '"><span class="input-group-btn po-span"><button type="submit" class="btn btn-sm btn-default orchestra-po-submit" onclick=orchestraSetPO(this,"'  .$data->tableID . '","' .$data->tableName . '","' . $data->po_file . '")><span class="fa fa-check"></span></button></span></div></div>';
                }

              else {
                 return '-';
              }

            })  

            ->addColumn('OrderDate', function ($data) {
              return date("d-m-Y", strtotime($data->OrderDate));
            })

            ->rawColumns(['inputPo','artworkStatus','actions','deliveryDate','pIstatus','poStatus','PONumber','uvc'])
            ->make(true);
  }

  public function getOrderStatusesTesco(Request $request)
  {

      // return($request->dRange);
          $tcl1 = "TCL 1";
          $tesco_tcl1_orders = "tesco_tcl1_orders";
          $actions="-";
          $ddmmyy="DD-MM-YYYY";
                $data1 = DB::table('tesco_tcl1_orders')
                                 ->join('customers','tesco_tcl1_orders.tesco_supplier_id','=','customers.id')
                                ->whereNotIn('tesco_tcl1_orders.order_status',['Saved','Order Cancelled'])
                                ->select(DB::raw("'".$actions."'".' as "StyleNo"'),'tesco_tcl1_orders.uk_style_ref','tesco_tcl1_orders.ce_style_ref','tesco_tcl1_orders.po_number as BuyerPO','tesco_tcl1_orders.zone as Zone',DB::raw("'".$tcl1."'".' as "TemplateName"'),'tesco_tcl1_orders.template_type as Template Type','customers.id as customer_id','customers.company_name as Company','tesco_tcl1_orders.created_at as OrderDate',DB::raw('to_char(tesco_tcl1_orders.delivery_date,'."'".$ddmmyy."'".') as "DeliveryDate"'),'tesco_tcl1_orders.order_status as artworkStatus','tesco_tcl1_orders.pi_status as pIstatus','tesco_tcl1_orders.po_status as poStatus','tesco_tcl1_orders.purchase_order_no as PONumber',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'".$tesco_tcl1_orders."'".' as "tableName"'),'tesco_tcl1_orders.id as tableID','tesco_tcl1_orders.po_file','customers.pi_required','customers.po_required','tesco_tcl1_orders.template_id','tesco_tcl1_orders.order_flag','customers.delivery_day_count','is_order_count_empty')

                                  ->where(function ($query) use ($request)
                                   {
                                    if($request->dateBegin && $request->dateEnd)
                                    {

                                      $query->whereBetween('tesco_tcl1_orders.created_at', [date('Y-m-d',strtotime($request->dateBegin)), date('Y-m-d',strtotime($request->dateEnd))]);
                                    }
                                   })

                                  // ->where(function ($query) use ($request)
                                  //  {
                                  //   if($request->customer)
                                  //   {

                                  //     $query->where('customers.company_name',$request->customer);
                                  //   }
                                  //  })

                                  // ->where(function ($query) use ($request)
                                  //  {
                                  //   if($request->template)
                                  //   {

                                  //     $query->where('TemplateName',$request->template);
                                  //   }
                                  //  })

                                ->get();
          $tcl2 = "TCL 2";
          $tesco_tcl2_orders = "tesco_tcl2_orders";
                $data2 = DB::table('tesco_tcl2_orders')
                                ->join('customers','tesco_tcl2_orders.tesco_supplier_id','=','customers.id')
                                // ->where('tesco_tcl2_orders.is_order_count_empty',0)
                                ->whereNotIn('tesco_tcl2_orders.order_status',['Saved','Order Cancelled'])
                                ->select(DB::raw("'".$actions."'".' as "StyleNo"'),'tesco_tcl2_orders.uk_style_ref','tesco_tcl2_orders.ce_style_ref','tesco_tcl2_orders.po_number as BuyerPO','tesco_tcl2_orders.zone as Zone',DB::raw("'".$tcl2."'".' as "TemplateName"'),'tesco_tcl2_orders.template_type as Template Type','customers.id as customer_id','customers.company_name as Company','tesco_tcl2_orders.created_at as OrderDate',DB::raw('to_char(tesco_tcl2_orders.delivery_date,'."'".$ddmmyy."'".') as "DeliveryDate"'),'tesco_tcl2_orders.order_status as artworkStatus','tesco_tcl2_orders.pi_status as pIstatus','tesco_tcl2_orders.po_status as poStatus','tesco_tcl2_orders.purchase_order_no as PONumber',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'".$tesco_tcl2_orders."'".' as "tableName"'),'tesco_tcl2_orders.id as tableID','tesco_tcl2_orders.po_file','customers.pi_required','customers.po_required','tesco_tcl2_orders.template_id','tesco_tcl2_orders.order_flag','customers.delivery_day_count','is_order_count_empty')

                                ->where(function ($query) use ($request)
                                   {
                                    if($request->dateBegin && $request->dateEnd)
                                    {

                                      $query->whereBetween('tesco_tcl2_orders.created_at', [date('Y-m-d',strtotime($request->dateBegin)), date('Y-m-d',strtotime($request->dateEnd))]);
                                    }
                                   })
                                ->get();
          $tcl4 = "TCL 4";
          $tesco_tcl4_orders = "tesco_tcl4_orders";
                $data3 = DB::table('tesco_tcl4_orders')
                                ->join('customers','tesco_tcl4_orders.tesco_supplier_id','=','customers.id')
                                // ->where('tesco_tcl4_orders.is_order_count_empty',0)
                                // ->where('tesco_tcl4_orders.order_status', '!=', 'Saved')
                                ->whereNotIn('tesco_tcl4_orders.order_status',['Saved','Order Cancelled'])
                                ->select(DB::raw("'".$actions."'".' as "StyleNo"'),'tesco_tcl4_orders.uk_style_ref','tesco_tcl4_orders.ce_style_ref','tesco_tcl4_orders.po_number as BuyerPO','tesco_tcl4_orders.zone as Zone',DB::raw("'".$tcl4."'".' as "TemplateName"'),'tesco_tcl4_orders.template_type as Template Type','customers.id as customer_id','customers.company_name as Company','tesco_tcl4_orders.created_at as OrderDate',DB::raw('to_char(tesco_tcl4_orders.delivery_date,'."'".$ddmmyy."'".') as "DeliveryDate"'),'tesco_tcl4_orders.order_status as artworkStatus','tesco_tcl4_orders.pi_status as pIstatus','tesco_tcl4_orders.po_status as poStatus','tesco_tcl4_orders.purchase_order_no as PONumber',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'".$tesco_tcl4_orders."'".' as "tableName"'),'tesco_tcl4_orders.id as tableID','tesco_tcl4_orders.po_file','customers.pi_required','customers.po_required','tesco_tcl4_orders.template_id','tesco_tcl4_orders.order_flag','customers.delivery_day_count','is_order_count_empty')

                                ->where(function ($query) use ($request)
                                   {
                                    if($request->dateBegin && $request->dateEnd)
                                    {

                                      $query->whereBetween('tesco_tcl4_orders.created_at', [date('Y-m-d',strtotime($request->dateBegin)), date('Y-m-d',strtotime($request->dateEnd))]);
                                    }
                                   })

                                ->get();
          $thl1 = "THL 1";
          $tesco_thl1_orders = "tesco_thl1_orders";
                $data4 = DB::table('tesco_thl1_orders')
                                ->join('customers','tesco_thl1_orders.tesco_supplier_id','=','customers.id')
                                ->where('tesco_thl1_orders.is_order_count_empty',0)
                                // ->where('tesco_thl1_orders.order_status', '!=', 'Saved')
                                ->whereNotIn('tesco_thl1_orders.order_status',['Saved','Order Cancelled'])
                                ->select(DB::raw("'".$actions."'".' as "StyleNo"'),'tesco_thl1_orders.uk_style_ref','tesco_thl1_orders.ce_style_ref','tesco_thl1_orders.po_number as BuyerPO','tesco_thl1_orders.zone as Zone',DB::raw("'".$thl1."'".' as "TemplateName"'),'tesco_thl1_orders.template_type as Template Type','customers.id as customer_id','customers.company_name as Company','tesco_thl1_orders.created_at as OrderDate',DB::raw('to_char(tesco_thl1_orders.delivery_date,'."'".$ddmmyy."'".') as "DeliveryDate"'),'tesco_thl1_orders.order_status as artworkStatus','tesco_thl1_orders.pi_status as pIstatus','tesco_thl1_orders.po_status as poStatus','tesco_thl1_orders.purchase_order_no as PONumber',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'".$tesco_thl1_orders."'".' as "tableName"'),'tesco_thl1_orders.id as tableID','tesco_thl1_orders.po_file','customers.pi_required','customers.po_required','tesco_thl1_orders.template_id','tesco_thl1_orders.order_flag','customers.delivery_day_count','is_order_count_empty')

                                ->where(function ($query) use ($request)
                                   {
                                    if($request->dateBegin && $request->dateEnd)
                                    {

                                      $query->whereBetween('tesco_thl1_orders.created_at', [date('Y-m-d',strtotime($request->dateBegin)), date('Y-m-d',strtotime($request->dateEnd))]);
                                    }
                                   })

                                    ->get();
          $thl3 = "THL 3";
          $tesco_thl3_orders = "tesco_thl3_orders";
                $data5 = DB::table('tesco_thl3_orders')
                                ->join('customers','tesco_thl3_orders.tesco_supplier_id','=','customers.id')
                                // ->where('tesco_thl3_orders.is_order_count_empty',0)
                                // ->where('tesco_thl3_orders.order_status', '!=', 'Saved')
                                ->whereNotIn('tesco_thl3_orders.order_status',['Saved','Order Cancelled'])
                                ->select(DB::raw("'".$actions."'".' as "StyleNo"'),'tesco_thl3_orders.uk_style_ref','tesco_thl3_orders.ce_style_ref','tesco_thl3_orders.po_number as BuyerPO','tesco_thl3_orders.zone as Zone',DB::raw("'".$thl3."'".' as "TemplateName"'),'tesco_thl3_orders.template_type as Template Type','customers.id as customer_id','customers.company_name as Company','tesco_thl3_orders.created_at as OrderDate',DB::raw('to_char(tesco_thl3_orders.delivery_date,'."'".$ddmmyy."'".') as "DeliveryDate"'),'tesco_thl3_orders.order_status as artworkStatus','tesco_thl3_orders.pi_status as pIstatus','tesco_thl3_orders.po_status as poStatus','tesco_thl3_orders.purchase_order_no as PONumber',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'".$tesco_thl3_orders."'".' as "tableName"'),'tesco_thl3_orders.id as tableID','tesco_thl3_orders.po_file','customers.pi_required','customers.po_required','tesco_thl3_orders.template_id','tesco_thl3_orders.order_flag','customers.delivery_day_count','is_order_count_empty')

                                ->where(function ($query) use ($request)
                                   {
                                    if($request->dateBegin && $request->dateEnd)
                                    {

                                      $query->whereBetween('tesco_thl3_orders.created_at', [date('Y-m-d',strtotime($request->dateBegin)), date('Y-m-d',strtotime($request->dateEnd))]);
                                    }
                                   })

                                ->get();
          $tsl1 = "TSL 1";
          $tesco_tsl1_orders = "tesco_tsl1_orders";
                $data6 = DB::table('tesco_tsl1_orders')
                                ->join('customers','tesco_tsl1_orders.tesco_supplier_id','=','customers.id')
                                // ->where('tesco_tsl1_orders.is_order_count_empty',0)
                                // ->where('tesco_tsl1_orders.order_status', '!=', 'Saved')
                                ->whereNotIn('tesco_tsl1_orders.order_status',['Saved','Order Cancelled'])
                                ->select(DB::raw("'".$actions."'".' as "StyleNo"'),'tesco_tsl1_orders.uk_style_ref','tesco_tsl1_orders.ce_style_ref','tesco_tsl1_orders.po_number as BuyerPO','tesco_tsl1_orders.zone as Zone',DB::raw("'".$tsl1."'".' as "TemplateName"'),'tesco_tsl1_orders.template_type as Template Type','customers.id as customer_id','customers.company_name as Company','tesco_tsl1_orders.created_at as OrderDate',DB::raw('to_char(tesco_tsl1_orders.delivery_date,'."'".$ddmmyy."'".') as "DeliveryDate"'),'tesco_tsl1_orders.order_status as artworkStatus','tesco_tsl1_orders.pi_status as pIstatus','tesco_tsl1_orders.po_status as poStatus','tesco_tsl1_orders.purchase_order_no as PONumber',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'".$tesco_tsl1_orders."'".' as "tableName"'),'tesco_tsl1_orders.id as tableID','tesco_tsl1_orders.po_file','customers.pi_required','customers.po_required','tesco_tsl1_orders.template_id','tesco_tsl1_orders.order_flag','customers.delivery_day_count','is_order_count_empty')

                                 ->where(function ($query) use ($request)
                                   {
                                    if($request->dateBegin && $request->dateEnd)
                                    {

                                      $query->whereBetween('tesco_tsl1_orders.created_at', [date('Y-m-d',strtotime($request->dateBegin)), date('Y-m-d',strtotime($request->dateEnd))]);
                                    }
                                   })

                                ->get();
          $tsl3 = "TSL 3";
          $tesco_tsl3_orders = "tesco_tsl3_orders";
                $data7 = DB::table('tesco_tsl3_orders')
                                ->join('customers','tesco_tsl3_orders.tesco_supplier_id','=','customers.id')
                                // ->where('tesco_tsl3_orders.is_order_count_empty',0)
                                // ->where('tesco_tsl3_orders.order_status', '!=', 'Saved')
                                ->whereNotIn('tesco_tsl3_orders.order_status',['Saved','Order Cancelled'])
                                ->select(DB::raw("'".$actions."'".' as "StyleNo"'),'tesco_tsl3_orders.uk_style_ref','tesco_tsl3_orders.ce_style_ref','tesco_tsl3_orders.po_number as BuyerPO','tesco_tsl3_orders.zone as Zone',DB::raw("'".$tsl3."'".' as "TemplateName"'),'tesco_tsl3_orders.template_type as Template Type','customers.id as customer_id','customers.company_name as Company','tesco_tsl3_orders.created_at as OrderDate',DB::raw('to_char(tesco_tsl3_orders.delivery_date,'."'".$ddmmyy."'".') as "DeliveryDate"'),'tesco_tsl3_orders.order_status as artworkStatus','tesco_tsl3_orders.pi_status as pIstatus','tesco_tsl3_orders.po_status as poStatus','tesco_tsl3_orders.purchase_order_no as PONumber',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'".$tesco_tsl3_orders."'".' as "tableName"'),'tesco_tsl3_orders.id as tableID','tesco_tsl3_orders.po_file','customers.pi_required','customers.po_required','tesco_tsl3_orders.template_id','tesco_tsl3_orders.order_flag','customers.delivery_day_count','is_order_count_empty')

                                 ->where(function ($query) use ($request)
                                   {
                                    if($request->dateBegin && $request->dateEnd)
                                    {

                                      $query->whereBetween('tesco_tsl3_orders.created_at', [date('Y-m-d',strtotime($request->dateBegin)), date('Y-m-d',strtotime($request->dateEnd))]);
                                    }
                                   })

                                ->get();


          $collection = $data1->merge($data2)->merge($data3)->merge($data4)->merge($data5)->merge($data6)->merge($data7);

          $collection->map(function ($collection,$key) 
          {
            $collection->Zone == "CE" ? $collection->StNo = $collection->ce_style_ref : $collection->StNo =$collection->uk_style_ref;
          });

          if($request->template)
          {
            $collection = $collection->filter(function($value, $key) use($request) {
              if ($value->TemplateName == $request->template) 
              {
                return true;
              }
            });
          }

          if($request->customer)
          {
            $collection = $collection->filter(function($value, $key) use($request) {
              if ($value->Company == $request->customer) 
              {
                return true;
              }
            });
          }

          if($request->style)
          {
            $collection = $collection->filter(function($value, $key) use($request) {
              if ($value->StNo == $request->style) 
              {
                return true;
              }
            });
          }


          $action_status = false;

          return Datatables::of($collection)
                
              ->setRowId(function($data) {
              return $data->tableName."_".$data->tableID;
              })

              ->addColumn('artworkStatus', function ($data) {

              if($data->is_order_count_empty == 0) 
              {
              $status = str_replace(' ', '', $data->artworkStatus);
              return '<span class="label label-' . $status . '">' .$data->artworkStatus. '</span>';
              }
              else
              {
              return '<span class="label label-CreateOrder">Create Order</span>';
              }
              })

              ->addColumn('StyleNo', function ($data) {

              $order_flag = $data->order_flag;
              $span_style = '';
              if ($order_flag == "reOrder") {
              $span_style = '<span title="Additional Quantity" class="status-icon batch-additional">A</span>';
              }
              if ($order_flag == "repeatOrder") {
              $span_style = '<span title="Repeat Order" class="status-icon batch-reorder">R</span>';
              }
              // $zone = $data->Zone;
              // $style_no = "";
              // if ($zone == "CE") {
              // $style_no = $data->ce_style_ref;
              // } else {
              // $style_no = $data->uk_style_ref;
              // }
              return '<a class="" href="javascript:void(0)" onclick=updateArtwork(this)>'.$span_style.'' . $data->StNo. '</a>';

              // return'<a class="" href="javascript:void(0)" onclick="updateArtwork(this)"><span title="Additional Quantity" class="batch-additional">A</span>43242423</a>;
              })

              ->addColumn('poStatus', function ($data) {
              if($data->po_required == 1 && $data->is_order_count_empty == 0) 
              {

              if($data->poStatus != null &&  $data->poStatus != " ")
              {
				  $status = str_replace(' ', '', $data->poStatus);
              	  return '<span class="po_class label label-'.$status.'">'.$data->poStatus.'</span>';
              }
              else{
              return '<span class="po_class label label-POPending">PO Pending</span>';
              }

              }
              else{
              return "-";
              }
              })

              ->addColumn('pIstatus', function ($data) {
              if($data->pIstatus != null && $data->pIstatus != "" && $data->is_order_count_empty == 0) 
              {
              // dd($action_status);
              if ($data->pIstatus == "PI Approved") {
              $action_status = true;
              }

              $status = str_replace(' ', '', $data->pIstatus);
              return '<span class="pi_class label label-' . $status . '">' . $data->pIstatus . '</span>';
              }
              else{
              return "-";
              }
              })

              ->addColumn('deliveryDate', function ($data) {
              if ($data->is_order_count_empty == 0) {
              if ($data->DeliveryDate == null) {
              return '<span style="display:none;">"' . $data->OrderDate . '"</span><input class="form-control input-sm datepicker_recurring_start" data-id="' . $data->delivery_day_count . '" style="width: 107px; border-color: rgb(244, 67, 54);" placeholder="Delivery Date" onchange=updateDeliveryDate(this,"' .$data->tableID . '","'.$data->tableName . '")  readonly>';
              } else {

              return '<span style="display:none;">"' . $data->OrderDate . '"</span><input value="'.$data->DeliveryDate.'" class="form-control input-sm datepicker_recurring_start" data-id="' .$data->delivery_day_count . '" style="width: 107px;" placeholder="Delivery Date" onchange=updateDeliveryDate(this,"' .$data->tableID . '","' .$data->tableName. '") readonly></td>';
              }
              } else {
              return '<span style=display:none;>' . $data->OrderDate . '</span> - </td>';
              }
              })

              ->addColumn('actions', function ($data){

              $zone = $data->Zone;
              $style_no = "";
              if ($zone == "CE") {
              $style_no = $data->ce_style_ref;
              } else {
              $style_no = $data->uk_style_ref;
              }
              $po_file = $data->po_file;
              if ($po_file != null) {
              $style_po = "style='border-color:#FFFFFF'";
              } else {
              $style_po = "style='border-color:#FF0000'";
              }
              $pi_required = $data->pi_required;
              $po_required = $data->po_required;

              if ($data->pIstatus == "PI Approved") {
              $action_status = "true";
              }
              else{
              $action_status = "false";
              }

              if ($data->artworkStatus != "Order Cancelled" && $data->is_order_count_empty == 0) {
              if ($pi_required == 1 && $po_required == 1) {
              return '<a class="pi_generation" id="111" href="javascript:void(0)" onclick=genarateTescoPI("' . $data->tableID . '","' . $data->tableName . '","'.$action_status.'","' .$style_no . '","' . $data->template_id . '","' .$data->BuyerPO . '","' . str_replace(' ', '', $data->TemplateName). '","' . $data->order_flag . '")>
              <i class="fa fa-file-pdf-o fa-button pi-view filterbtn options" data-placement="top" title="Proforma Invoice"></i></a>




              <a href="javascript:void(0)" onclick=tescoPOUpload(this,"' . $data->tableID . '","' . $data->po_file . '","'  .$data->tableName . '") class="po-style" ><i class="fa fa-file-powerpoint-o fa-button po-view filterbtn options" data-placement="top" title="Purchase Order" ' . $style_po . '></i></a>';

              } else if ($po_required == 1 && $pi_required == 0) {

              return '<a href="javascript:void(0)" id="222" class="po-style" onclick=tescoPOUpload(this,"' .$data->tableID . '","' .$data->po_file . '","' .$data->tableName . '") ><i class="fa fa-file-powerpoint-o fa-button po-view filterbtn options" data-placement="top" title="Purchase Order" ' .$style_po . '></i></a>';

              } else if ($pi_required == 1 && $po_required == 0) {

              return '<a id="333" class="pi_generation" href="javascript:void(0)" onclick=genarateTescoPI("' .$data->tableID . '","' .$data->tableName . '","' .$action_status. '","' .$style_no. '","' .$data->template_id . '","' .$data->BuyerPO . '","' . str_replace(' ', '', $data->TemplateName). '","' . $data->order_flag . '")><i class="fa fa-file-pdf-o fa-button pi-view filterbtn options" data-placement="top" title="Proforma Invoice"></i></a>';
              }
              } else {
              return "-";
              }

              })

              ->editColumn('PONumber', function($data)
              {

              if($data->po_required == 1 && $data->is_order_count_empty == 0)
              {
              $po_file = $data->po_file;
              $customer_po_number = $data->PONumber === null?'':$data->PONumber;
              return '<div class="form-group has-feedback"><div class="input-group"><input type="text" onkeypress="return AvoidSpace(event)" name="tesco-po" id="tesco-po" class="form-control input-sm tesco-po" placeholder="Enter PO" maxlength="11" value="'.$customer_po_number.'"><span class="input-group-btn po-span"><button type="submit" class="btn btn-sm btn-default tesco-po-submit" onclick=tescoSetPO(this,"' .$data->tableID . '","' .$data->tableName . '","' . $po_file .'")><span class="fa fa-check"></span></button></span></div></div>';
              }
              else {
              return '-';
              }

              })  
              ->addColumn('OrderDate', function ($data) {
              return date("d-m-Y", strtotime($data->OrderDate));
              })

          ->rawColumns(['PONumber','artworkStatus','actions','deliveryDate','poStatus','pIstatus','StyleNo'])
          ->make(true);
  }

}
