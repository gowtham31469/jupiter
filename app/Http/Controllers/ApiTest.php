<?php

namespace App\Http\Controllers;

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


class ApiTest extends Controller
{

  public function getOrderStatusesOrch()
  {

    $ddmmyy="DD-MM-YYYY";
      $actions='-';
            $data1 = DB::table('orchestra_hang_tag_orders')->join('orchestra_parcel_details','orchestra_parcel_details.UVC','=','orchestra_hang_tag_orders.uvc')->join('orchestra_item_master','orchestra_item_master.id','=','orchestra_hang_tag_orders.template_front_id')->leftjoin('customers','customers.factory_code','=','orchestra_parcel_details.FactoryCode')->where('orchestra_hang_tag_orders.status','!=','Order Cancelled')->select('orchestra_hang_tag_orders.color_code','orchestra_parcel_details.UVC',DB::raw("orchestra_hang_tag_orders.season_code as \"Season\""),DB::raw("'-' as \"BuyerPONo\""),DB::raw("'Hang Tag' as \"ArtworkType\""),DB::raw("'Hang Tag' as \"TemplateName\""),'orchestra_item_master.item_name as Reference No','customers.id as customer_id','orchestra_parcel_details.SupplierName as SupplierName',DB::raw("to_char(orchestra_hang_tag_orders.created_at,"."'".$ddmmyy."'".") as \"OrderDate\""),DB::raw("to_char(orchestra_hang_tag_orders.delivery_date,"."'".$ddmmyy."'".") as \"DeliveryDate\""),'orchestra_hang_tag_orders.status as artworkStatus','orchestra_hang_tag_orders.pi_status as pIstatus','orchestra_hang_tag_orders.po_status as PO Status','orchestra_hang_tag_orders.po_number as PO Number',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'orchestra_hang_tag_orders' as \"tableName\""),'orchestra_hang_tag_orders.id  as tableID','orchestra_hang_tag_orders.po_file','customers.pi_required','customers.po_required','orchestra_hang_tag_orders.template_front_id as template_id','orchestra_hang_tag_orders.order_flag','customers.delivery_day_count','orchestra_hang_tag_orders.is_order_count_empty')->groupBy('orchestra_parcel_details.UVC','orchestra_parcel_details.SupplierName','orchestra_hang_tag_orders.created_at','orchestra_hang_tag_orders.delivery_date','orchestra_hang_tag_orders.status','orchestra_hang_tag_orders.pi_status','orchestra_hang_tag_orders.status','orchestra_hang_tag_orders.po_status','orchestra_hang_tag_orders.po_number','customers.pi_required','customers.id','orchestra_hang_tag_orders.id','orchestra_item_master.item_name')->orderBy('orchestra_hang_tag_orders.id','asc')->get();
    
            $data2 = DB::table('orchestra_special_tag_orders')->join('orchestra_parcel_details','orchestra_parcel_details.UVC','=','orchestra_special_tag_orders.uvc')->join('orchestra_item_master','orchestra_item_master.id','=','orchestra_special_tag_orders.template_id')->leftjoin('customers','customers.factory_code','=','orchestra_parcel_details.FactoryCode')->where('orchestra_special_tag_orders.status','!=','Order Cancelled')->select('orchestra_special_tag_orders.color_code','orchestra_parcel_details.UVC',DB::raw("orchestra_special_tag_orders.season_code as \"Season\""),DB::raw("'-' as \"BuyerPONo\""),DB::raw("'Special Tag' as \"ArtworkType\""),DB::raw("'Special Tag' as \"TemplateName\""),'orchestra_item_master.item_name as Reference No','customers.id as customer_id','orchestra_parcel_details.SupplierName as SupplierName',DB::raw("to_char(orchestra_special_tag_orders.created_at,"."'".$ddmmyy."'".") as \"OrderDate\""),DB::raw("to_char(orchestra_special_tag_orders.delivery_date,"."'".$ddmmyy."'".") as \"DeliveryDate\""),'orchestra_special_tag_orders.status as artworkStatus','orchestra_special_tag_orders.pi_status as pIstatus','orchestra_special_tag_orders.po_status as PO Status','orchestra_special_tag_orders.po_number as PO Number',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'orchestra_special_tag_orders' as \"tableName\""),'orchestra_special_tag_orders.id  as tableID','orchestra_special_tag_orders.po_file','customers.pi_required','customers.po_required','orchestra_special_tag_orders.template_id','orchestra_special_tag_orders.order_flag','customers.delivery_day_count','orchestra_special_tag_orders.is_order_count_empty')->groupBy('orchestra_parcel_details.UVC','orchestra_parcel_details.SupplierName','orchestra_special_tag_orders.created_at','orchestra_special_tag_orders.delivery_date','orchestra_special_tag_orders.status','orchestra_special_tag_orders.pi_status','orchestra_special_tag_orders.status','orchestra_special_tag_orders.po_status','orchestra_special_tag_orders.po_number','customers.pi_required','customers.id','orchestra_special_tag_orders.id','orchestra_item_master.item_name')->orderBy('orchestra_special_tag_orders.id','asc')->get();
     
               
            $data3 = DB::table('orchestra_main_label_orders')->join('orchestra_parcel_details','orchestra_parcel_details.UVC','=','orchestra_main_label_orders.uvc')->join('orchestra_item_master','orchestra_item_master.id','=','orchestra_main_label_orders.template_id')->leftjoin('customers','customers.factory_code','=','orchestra_parcel_details.FactoryCode')->where('orchestra_main_label_orders.status','!=','Order Cancelled')->select('orchestra_main_label_orders.color_code','orchestra_parcel_details.UVC',DB::raw("orchestra_main_label_orders.season_code as \"Season\""),DB::raw("'-' as \"BuyerPONo\""),DB::raw("'Main Label' as \"ArtworkType\""),DB::raw("'Main Label' as \"TemplateName\""),'orchestra_item_master.item_name as Reference No','customers.id as customer_id','orchestra_parcel_details.SupplierName as SupplierName',DB::raw("to_char(orchestra_main_label_orders.created_at,"."'".$ddmmyy."'".") as \"OrderDate\""),DB::raw("to_char(orchestra_main_label_orders.delivery_date,"."'".$ddmmyy."'".") as \"DeliveryDate\""),'orchestra_main_label_orders.status as artworkStatus','orchestra_main_label_orders.pi_status as pIstatus','orchestra_main_label_orders.po_status as PO Status','orchestra_main_label_orders.po_number as PO Number',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'orchestra_main_label_orders' as \"tableName\""),'orchestra_main_label_orders.id  as tableID','orchestra_main_label_orders.po_file','customers.pi_required','customers.po_required','orchestra_main_label_orders.template_id','orchestra_main_label_orders.order_flag','customers.delivery_day_count','orchestra_main_label_orders.is_order_count_empty')->groupBy('orchestra_parcel_details.UVC','orchestra_parcel_details.SupplierName','orchestra_main_label_orders.created_at','orchestra_main_label_orders.delivery_date','orchestra_main_label_orders.status','orchestra_main_label_orders.pi_status','orchestra_main_label_orders.status','orchestra_main_label_orders.po_status','orchestra_main_label_orders.po_number','customers.pi_required','customers.id','orchestra_main_label_orders.id','orchestra_item_master.item_name')->orderBy('orchestra_main_label_orders.id','asc')->get();
    
      $data3 = DB::table('orchestra_size_label_orders')->join('orchestra_parcel_details','orchestra_parcel_details.UVC','=','orchestra_size_label_orders.uvc')->join('orchestra_item_master','orchestra_item_master.id','=','orchestra_size_label_orders.template_id')->leftjoin('customers','customers.factory_code','=','orchestra_parcel_details.FactoryCode')->where('orchestra_size_label_orders.status','!=','Order Cancelled')->select('orchestra_size_label_orders.color_code','orchestra_parcel_details.UVC',DB::raw("orchestra_size_label_orders.season_code as \"Season\""),DB::raw("'-' as \"BuyerPONo\""),DB::raw("'Size Label' as \"ArtworkType\""),DB::raw("'Size Label' as \"TemplateName\""),'orchestra_item_master.item_name as Reference No','customers.id as customer_id','orchestra_parcel_details.SupplierName as SupplierName',DB::raw("to_char(orchestra_size_label_orders.created_at,"."'".$ddmmyy."'".") as \"OrderDate\""),DB::raw("to_char(orchestra_size_label_orders.delivery_date,"."'".$ddmmyy."'".") as \"DeliveryDate\""),'orchestra_size_label_orders.status as artworkStatus','orchestra_size_label_orders.pi_status as pIstatus','orchestra_size_label_orders.po_status as PO Status','orchestra_size_label_orders.po_number as PO Number',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'orchestra_size_label_orders' as \"tableName\""),'orchestra_size_label_orders.id  as tableID','orchestra_size_label_orders.po_file','customers.pi_required','customers.po_required','orchestra_size_label_orders.template_id','orchestra_size_label_orders.order_flag','customers.delivery_day_count','orchestra_size_label_orders.is_order_count_empty')->groupBy('orchestra_parcel_details.UVC','orchestra_parcel_details.SupplierName','orchestra_size_label_orders.created_at','orchestra_size_label_orders.delivery_date','orchestra_size_label_orders.status','orchestra_size_label_orders.pi_status','orchestra_size_label_orders.status','orchestra_size_label_orders.po_status','orchestra_size_label_orders.po_number','customers.pi_required','customers.id','orchestra_size_label_orders.id','orchestra_item_master.item_name')->orderBy('orchestra_size_label_orders.id','asc')->get();
    
      $data4 = DB::table('orchestra_heat_transfer_orders')->join('orchestra_parcel_details','orchestra_parcel_details.UVC','=','orchestra_heat_transfer_orders.uvc')->join('orchestra_item_master','orchestra_item_master.id','=','orchestra_heat_transfer_orders.template_id')->leftjoin('customers','customers.factory_code','=','orchestra_parcel_details.FactoryCode')->where('orchestra_heat_transfer_orders.status','!=','Order Cancelled')->select('orchestra_heat_transfer_orders.color_code','orchestra_parcel_details.UVC',DB::raw("orchestra_heat_transfer_orders.season_code as \"Season\""),DB::raw("'-' as \"BuyerPONo\""),DB::raw("'Heat Transfer' as \"ArtworkType\""),DB::raw("'Heat Transfer' as \"TemplateName\""),'orchestra_item_master.item_name as Reference No','customers.id as customer_id','orchestra_parcel_details.SupplierName as SupplierName',DB::raw("to_char(orchestra_heat_transfer_orders.created_at,"."'".$ddmmyy."'".") as \"OrderDate\""),DB::raw("to_char(orchestra_heat_transfer_orders.delivery_date,"."'".$ddmmyy."'".") as \"DeliveryDate\""),'orchestra_heat_transfer_orders.status as artworkStatus','orchestra_heat_transfer_orders.pi_status as pIstatus','orchestra_heat_transfer_orders.po_status as PO Status','orchestra_heat_transfer_orders.po_number as PO Number',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'orchestra_heat_transfer_orders' as \"tableName\""),'orchestra_heat_transfer_orders.id  as tableID','orchestra_heat_transfer_orders.po_file','customers.pi_required','customers.po_required','orchestra_heat_transfer_orders.template_id','orchestra_heat_transfer_orders.order_flag','customers.delivery_day_count','orchestra_heat_transfer_orders.is_order_count_empty')->groupBy('orchestra_parcel_details.UVC','orchestra_parcel_details.SupplierName','orchestra_heat_transfer_orders.created_at','orchestra_heat_transfer_orders.delivery_date','orchestra_heat_transfer_orders.status','orchestra_heat_transfer_orders.pi_status','orchestra_heat_transfer_orders.status','orchestra_heat_transfer_orders.po_status','orchestra_heat_transfer_orders.po_number','customers.pi_required','customers.id','orchestra_heat_transfer_orders.id','orchestra_item_master.item_name')->orderBy('orchestra_heat_transfer_orders.id','asc')->get();

            $data5 = DB::table('orchestra_wash_care_label_orders')->join('orchestra_parcel_details','orchestra_parcel_details.UVC','=','orchestra_wash_care_label_orders.uvc')->join('orchestra_item_master','orchestra_item_master.id','=','orchestra_wash_care_label_orders.template_id')->leftjoin('customers','customers.factory_code','=','orchestra_parcel_details.FactoryCode')->where('orchestra_wash_care_label_orders.status','!=','Order Cancelled')->select('orchestra_wash_care_label_orders.color_code','orchestra_parcel_details.UVC',DB::raw("orchestra_wash_care_label_orders.season_code as \"Season\""),DB::raw("orchestra_wash_care_label_orders.po as \"BuyerPONo\""),DB::raw("'WashCareLabel' as \"ArtworkType\""),'orchestra_wash_care_label_orders.template_name as TemplateName','orchestra_item_master.item_name as Reference No','customers.id as customer_id','orchestra_parcel_details.SupplierName as SupplierName',DB::raw("to_char(orchestra_wash_care_label_orders.created_at,"."'".$ddmmyy."'".") as \"OrderDate\""),DB::raw("to_char(orchestra_wash_care_label_orders.delivery_date,"."'".$ddmmyy."'".") as \"DeliveryDate\""),'orchestra_wash_care_label_orders.status as artworkStatus','orchestra_wash_care_label_orders.pi_status as pIstatus','orchestra_wash_care_label_orders.po_status as PO Status','orchestra_wash_care_label_orders.po_number as PO Number',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'orchestra_wash_care_label_orders' as \"tableName\""),'orchestra_wash_care_label_orders.id  as tableID','orchestra_wash_care_label_orders.po_file','customers.pi_required','customers.po_required','orchestra_wash_care_label_orders.template_id','orchestra_wash_care_label_orders.order_flag','customers.delivery_day_count','orchestra_wash_care_label_orders.is_order_count_empty')->groupBy('orchestra_parcel_details.UVC','orchestra_parcel_details.SupplierName','orchestra_wash_care_label_orders.created_at','orchestra_wash_care_label_orders.delivery_date','orchestra_wash_care_label_orders.status','orchestra_wash_care_label_orders.pi_status','orchestra_wash_care_label_orders.status','orchestra_wash_care_label_orders.po_status','orchestra_wash_care_label_orders.po_number','customers.pi_required','customers.id','orchestra_wash_care_label_orders.id','orchestra_item_master.item_name')->orderBy('orchestra_wash_care_label_orders.id','asc')->get();

            $data6 = DB::table('orchestra_solid_carton_sticker_orders')->join('orchestra_parcel_details','orchestra_parcel_details.UVC','=','orchestra_solid_carton_sticker_orders.uvc')->join('orchestra_item_master','orchestra_item_master.id','=','orchestra_solid_carton_sticker_orders.template_id')->leftjoin('customers','customers.factory_code','=','orchestra_parcel_details.FactoryCode')->where('orchestra_solid_carton_sticker_orders.status','!=','Order Cancelled')->select(DB::raw('distinct on (orchestra_solid_carton_sticker_orders.uvc,orchestra_solid_carton_sticker_orders.po) "UVC"'),DB::raw("orchestra_solid_carton_sticker_orders.season_code as \"Season\""),DB::raw("orchestra_solid_carton_sticker_orders.po as \"BuyerPONo\""),DB::raw("'Solid Carton Stickers' as \"ArtworkType\""),DB::raw("'Solid Carton Stickers' as \"TemplateName\""),'customers.id as customer_id','orchestra_parcel_details.SupplierName as SupplierName',DB::raw("to_char(orchestra_solid_carton_sticker_orders.created_at,"."'".$ddmmyy."'".") as \"OrderDate\""),DB::raw("to_char(orchestra_solid_carton_sticker_orders.delivery_date,"."'".$ddmmyy."'".") as \"DeliveryDate\""),'orchestra_solid_carton_sticker_orders.color_code','orchestra_solid_carton_sticker_orders.status as artworkStatus','orchestra_solid_carton_sticker_orders.pi_status as pIstatus','orchestra_solid_carton_sticker_orders.po_status as PO Status','orchestra_solid_carton_sticker_orders.po_number as PO Number',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'orchestra_solid_carton_sticker_orders' as \"tableName\""),'orchestra_solid_carton_sticker_orders.id  as tableID','orchestra_solid_carton_sticker_orders.po_file','customers.pi_required','customers.po_required','orchestra_solid_carton_sticker_orders.template_id','orchestra_solid_carton_sticker_orders.order_flag','customers.delivery_day_count','orchestra_solid_carton_sticker_orders.is_order_count_empty')->where('orchestra_parcel_details.IsSTD',0)->groupBy('orchestra_solid_carton_sticker_orders.uvc','orchestra_parcel_details.UVC','orchestra_solid_carton_sticker_orders.po','orchestra_parcel_details.SupplierName','orchestra_solid_carton_sticker_orders.created_at','orchestra_solid_carton_sticker_orders.delivery_date','orchestra_solid_carton_sticker_orders.status','orchestra_solid_carton_sticker_orders.pi_status','orchestra_solid_carton_sticker_orders.status','orchestra_solid_carton_sticker_orders.po_status','orchestra_solid_carton_sticker_orders.po_number','customers.pi_required','customers.id','orchestra_solid_carton_sticker_orders.id')->get();
              
            $data7 = DB::table('orchestra_solid_poly_bag_sticker_orders')->join('orchestra_parcel_details','orchestra_parcel_details.UVC','=','orchestra_solid_poly_bag_sticker_orders.uvc')->join('orchestra_item_master','orchestra_item_master.id','=','orchestra_solid_poly_bag_sticker_orders.template_id')->leftjoin('customers','customers.factory_code','=','orchestra_parcel_details.FactoryCode')->where('orchestra_solid_poly_bag_sticker_orders.status','!=','Order Cancelled')->select(DB::raw('distinct on (orchestra_solid_poly_bag_sticker_orders.uvc,orchestra_solid_poly_bag_sticker_orders.po) "UVC"'),DB::raw("orchestra_solid_poly_bag_sticker_orders.season_code as \"Season\""),DB::raw("orchestra_solid_poly_bag_sticker_orders.po as \"BuyerPONo\""),DB::raw("'Solid Poly Bag Stickers' as \"ArtworkType\""),DB::raw("'Solid Poly Bag Stickers' as \"TemplateName\""),'customers.id as customer_id','orchestra_parcel_details.SupplierName as SupplierName',DB::raw("to_char(orchestra_solid_poly_bag_sticker_orders.created_at,"."'".$ddmmyy."'".") as \"OrderDate\""),DB::raw("to_char(orchestra_solid_poly_bag_sticker_orders.delivery_date,"."'".$ddmmyy."'".") as \"DeliveryDate\""),'orchestra_solid_poly_bag_sticker_orders.status as artworkStatus','orchestra_solid_poly_bag_sticker_orders.pi_status as pIstatus','orchestra_solid_poly_bag_sticker_orders.po_status as PO Status','orchestra_solid_poly_bag_sticker_orders.po_number as PO Number',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'orchestra_solid_poly_bag_sticker_orders' as \"tableName\""),'orchestra_solid_poly_bag_sticker_orders.color_code','orchestra_solid_poly_bag_sticker_orders.id  as tableID','orchestra_solid_poly_bag_sticker_orders.po_file','customers.pi_required','customers.po_required','orchestra_solid_poly_bag_sticker_orders.template_id','orchestra_solid_poly_bag_sticker_orders.order_flag','customers.delivery_day_count','orchestra_solid_poly_bag_sticker_orders.is_order_count_empty')->where('orchestra_parcel_details.IsSTD',0)->groupBy('orchestra_solid_poly_bag_sticker_orders.uvc','orchestra_parcel_details.UVC','orchestra_solid_poly_bag_sticker_orders.po','orchestra_parcel_details.SupplierName','orchestra_solid_poly_bag_sticker_orders.created_at','orchestra_solid_poly_bag_sticker_orders.delivery_date','orchestra_solid_poly_bag_sticker_orders.status','orchestra_solid_poly_bag_sticker_orders.pi_status','orchestra_solid_poly_bag_sticker_orders.po_status','orchestra_solid_poly_bag_sticker_orders.po_number','orchestra_solid_poly_bag_sticker_orders.id','customers.pi_required','customers.id')->get();
    
            $data8 = DB::table('orchestra_standard_carton_sticker_orders')->join('orchestra_parcel_details','orchestra_parcel_details.UVC','=','orchestra_standard_carton_sticker_orders.uvc')->join('orchestra_item_master','orchestra_item_master.id','=','orchestra_standard_carton_sticker_orders.template_id')->leftjoin('customers','customers.factory_code','=','orchestra_parcel_details.FactoryCode')->where('orchestra_standard_carton_sticker_orders.status','!=','Order Cancelled')->select(DB::raw('distinct on (orchestra_standard_carton_sticker_orders.uvc,orchestra_standard_carton_sticker_orders.po) "UVC"'),DB::raw("orchestra_standard_carton_sticker_orders.season_code as \"Season\""),DB::raw("orchestra_standard_carton_sticker_orders.po as \"BuyerPONo\""),DB::raw("'Standard Carton Stickers' as \"ArtworkType\""),DB::raw("'Standard Carton Stickers' as \"TemplateName\""),'customers.id as customer_id','orchestra_parcel_details.SupplierName as SupplierName',DB::raw("to_char(orchestra_standard_carton_sticker_orders.created_at,"."'".$ddmmyy."'".") as \"OrderDate\""),DB::raw("to_char(orchestra_standard_carton_sticker_orders.delivery_date,"."'".$ddmmyy."'".") as \"DeliveryDate\""),'orchestra_standard_carton_sticker_orders.status as artworkStatus','orchestra_standard_carton_sticker_orders.pi_status as pIstatus','orchestra_standard_carton_sticker_orders.po_status as PO Status','orchestra_standard_carton_sticker_orders.po_number as PO Number',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'orchestra_standard_carton_sticker_orders' as \"tableName\""),'orchestra_standard_carton_sticker_orders.color_code','orchestra_standard_carton_sticker_orders.id  as tableID','orchestra_standard_carton_sticker_orders.po_file','customers.pi_required','customers.po_required','orchestra_standard_carton_sticker_orders.template_id','orchestra_standard_carton_sticker_orders.order_flag','customers.delivery_day_count','orchestra_standard_carton_sticker_orders.is_order_count_empty')->where('orchestra_parcel_details.IsSTD',1)->groupBy('orchestra_standard_carton_sticker_orders.uvc','orchestra_parcel_details.UVC','orchestra_standard_carton_sticker_orders.po','orchestra_parcel_details.SupplierName','orchestra_standard_carton_sticker_orders.created_at','orchestra_standard_carton_sticker_orders.delivery_date','orchestra_standard_carton_sticker_orders.status','orchestra_standard_carton_sticker_orders.pi_status','orchestra_standard_carton_sticker_orders.po_status','orchestra_standard_carton_sticker_orders.po_number','orchestra_standard_carton_sticker_orders.id','customers.pi_required','customers.id')->get();

            $data9 = DB::table('orchestra_standard_poly_bag_sticker_orders')->join('orchestra_parcel_details','orchestra_parcel_details.UVC','=','orchestra_standard_poly_bag_sticker_orders.uvc')->join('orchestra_item_master','orchestra_item_master.id','=','orchestra_standard_poly_bag_sticker_orders.template_id')->leftjoin('customers','customers.factory_code','=','orchestra_parcel_details.FactoryCode')->where('orchestra_standard_poly_bag_sticker_orders.status','!=','Order Cancelled')->select(DB::raw('distinct on (orchestra_standard_poly_bag_sticker_orders.uvc,orchestra_standard_poly_bag_sticker_orders.po) "UVC"'),DB::raw("orchestra_standard_poly_bag_sticker_orders.season_code as \"Season\""),DB::raw("orchestra_standard_poly_bag_sticker_orders.po as \"BuyerPONo\""),DB::raw("'Standard Poly Bag Stickers' as \"ArtworkType\""),DB::raw("'Standard Poly Bag Stickers' as \"TemplateName\""),'customers.id as customer_id','orchestra_parcel_details.SupplierName as SupplierName',DB::raw("to_char(orchestra_standard_poly_bag_sticker_orders.created_at,"."'".$ddmmyy."'".") as \"OrderDate\""),DB::raw("to_char(orchestra_standard_poly_bag_sticker_orders.delivery_date,"."'".$ddmmyy."'".") as \"DeliveryDate\""),'orchestra_standard_poly_bag_sticker_orders.status as artworkStatus','orchestra_standard_poly_bag_sticker_orders.pi_status as pIstatus','orchestra_standard_poly_bag_sticker_orders.po_status as PO Status','orchestra_standard_poly_bag_sticker_orders.po_number as PO Number',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'orchestra_standard_poly_bag_sticker_orders' as \"tableName\""),'orchestra_standard_poly_bag_sticker_orders.color_code','orchestra_standard_poly_bag_sticker_orders.id  as tableID','orchestra_standard_poly_bag_sticker_orders.po_file','customers.pi_required','customers.po_required','orchestra_standard_poly_bag_sticker_orders.template_id','orchestra_standard_poly_bag_sticker_orders.order_flag','customers.delivery_day_count','orchestra_standard_poly_bag_sticker_orders.is_order_count_empty')->where('orchestra_parcel_details.IsSTD',1)->groupBy('orchestra_standard_poly_bag_sticker_orders.uvc','orchestra_parcel_details.UVC','orchestra_standard_poly_bag_sticker_orders.po','orchestra_parcel_details.SupplierName','orchestra_standard_poly_bag_sticker_orders.created_at','orchestra_standard_poly_bag_sticker_orders.delivery_date','orchestra_standard_poly_bag_sticker_orders.status','orchestra_standard_poly_bag_sticker_orders.pi_status','orchestra_standard_poly_bag_sticker_orders.po_status','orchestra_standard_poly_bag_sticker_orders.po_number','orchestra_standard_poly_bag_sticker_orders.id','customers.pi_required','customers.id')->get();


            $data = $data1->merge($data2)->merge($data3)->merge($data4)->merge($data5)->merge($data6)->merge($data7)->merge($data8)->merge($data9);

            // return $data;
          return Datatables::of($data)
              
              ->addColumn('artworkStatus', function ($data) {
                  return $data->artworkStatus =="Order Approved" ? '<span class="label label-OrderApproved">'.$data->artworkStatus.'</span>':'<span class="label label-OrderPlaced">'.$data->artworkStatus.'</span>';
              })

              ->addColumn('deliveryDate', function ($data) {
                  return '<span style="display:none;">2019-12-19 06:08:58</span><input value="31-12-2019" class="form-control input-sm datepicker_recurring_start" data-id="7" style="width: 107px;" placeholder="Delivery Date" onchange="updateDeliveryDate(this,&quot;276&quot;,&quot;tesco_tcl1_orders&quot;)" readonly="">';
              })

              ->addColumn('actions', function ($activities) {
                  // return '';

                  return'<a class="pi_generation" href="javascript:void(0)" onclick="genarateTescoPI(&quot;276&quot;,&quot;tesco_tcl1_orders&quot;,&quot;false&quot;,&quot;tst12QR&quot;,&quot;19&quot;,&quot;tst12QR&quot;,&quot;TCL1&quot;,&quot;newOrder&quot;)"><i class="fa fa-file-pdf-o fa-button pi-view filterbtn options" data-placement="top" title="" data-original-title="Proforma Invoice"></i></a><a href="javascript:void(0)" onclick="tescoPOUpload(this,&quot;276&quot;,&quot;/storage/app/public/po-files/TESCO_PO_1576735749_po_460-92592_LINKUP_RATIO_A.pdf&quot;,&quot;tesco_tcl1_orders&quot;)" class="po-style"><i class="fa fa-file-powerpoint-o fa-button po-view filterbtn options" data-placement="top" title="" style="border-color:#FFFFFF" data-original-title="Purchase Order"></i></a>';
                  })

              ->editColumn('inputPo', function($data)
              {
                  return '<div class="form-group has-feedback"><div class="input-group"><input type="text" onkeypress="return AvoidSpace(event)" name="tesco-po" id="tesco-po" class="form-control input-sm tesco-po" placeholder="Enter PO" maxlength="11" value="tst12QR"><span class="input-group-btn po-span"><button type="submit" class="btn btn-sm btn-default tesco-po-submit" onclick="tescoSetPO(this,&quot;276&quot;,&quot;tesco_tcl1_orders&quot;,&quot;/storage/app/public/po-files/TESCO_PO_1576735749_po_460-92592_LINKUP_RATIO_A.pdf&quot;)"><span class="fa fa-check"></span></button></span></div></div>';
              })  

          ->rawColumns(['inputPo','artworkStatus','actions','deliveryDate'])
          ->make(true);
  }

  public function getOrderStatusesTesco()
  {


      $tcl1 = "TCL 1";
      $tesco_tcl1_orders = "tesco_tcl1_orders";
      $actions="-";
      $ddmmyy="DD-MM-YYYY";
            $data1 = DB::table('tesco_tcl1_orders')
                             ->join('customers','tesco_tcl1_orders.tesco_supplier_id','=','customers.id')
                            ->whereNotIn('tesco_tcl1_orders.order_status',['Saved','Order Cancelled'])
                            ->select(DB::raw("'".$actions."'".' as "StyleNo"'),'tesco_tcl1_orders.uk_style_ref','tesco_tcl1_orders.ce_style_ref','tesco_tcl1_orders.po_number as BuyerPO','tesco_tcl1_orders.zone as Zone',DB::raw("'".$tcl1."'".' as "TemplateName"'),'tesco_tcl1_orders.template_type as Template Type','customers.id as customer_id','customers.company_name as Company','tesco_tcl1_orders.created_at as OrderDate',DB::raw('to_char(tesco_tcl1_orders.delivery_date,'."'".$ddmmyy."'".') as "DeliveryDate"'),'tesco_tcl1_orders.order_status as artworkStatus','tesco_tcl1_orders.pi_status as pIstatus','tesco_tcl1_orders.po_status as PO Status','tesco_tcl1_orders.purchase_order_no as PO Number',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'".$tesco_tcl1_orders."'".' as "tableName"'),'tesco_tcl1_orders.id as tableID','tesco_tcl1_orders.po_file','customers.pi_required','customers.po_required','tesco_tcl1_orders.template_id','tesco_tcl1_orders.order_flag','customers.delivery_day_count','is_order_count_empty')
                            ->get();
      $tcl2 = "TCL 2";
      $tesco_tcl2_orders = "tesco_tcl2_orders";
            $data2 = DB::table('tesco_tcl2_orders')
                            ->join('customers','tesco_tcl2_orders.tesco_supplier_id','=','customers.id')
                            // ->where('tesco_tcl2_orders.is_order_count_empty',0)
                            ->whereNotIn('tesco_tcl2_orders.order_status',['Saved','Order Cancelled'])
                            ->select(DB::raw("'".$actions."'".' as "StyleNo"'),'tesco_tcl2_orders.uk_style_ref','tesco_tcl2_orders.ce_style_ref','tesco_tcl2_orders.po_number as BuyerPO','tesco_tcl2_orders.zone as Zone',DB::raw("'".$tcl2."'".' as "TemplateName"'),'tesco_tcl2_orders.template_type as Template Type','customers.id as customer_id','customers.company_name as Company','tesco_tcl2_orders.created_at as OrderDate',DB::raw('to_char(tesco_tcl2_orders.delivery_date,'."'".$ddmmyy."'".') as "DeliveryDate"'),'tesco_tcl2_orders.order_status as artworkStatus','tesco_tcl2_orders.pi_status as pIstatus','tesco_tcl2_orders.po_status as PO Status','tesco_tcl2_orders.purchase_order_no as PO Number',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'".$tesco_tcl2_orders."'".' as "tableName"'),'tesco_tcl2_orders.id as tableID','tesco_tcl2_orders.po_file','customers.pi_required','customers.po_required','tesco_tcl2_orders.template_id','tesco_tcl2_orders.order_flag','customers.delivery_day_count','is_order_count_empty')
                            ->get();
      $tcl4 = "TCL 4";
      $tesco_tcl4_orders = "tesco_tcl4_orders";
            $data3 = DB::table('tesco_tcl4_orders')
                            ->join('customers','tesco_tcl4_orders.tesco_supplier_id','=','customers.id')
                            // ->where('tesco_tcl4_orders.is_order_count_empty',0)
                            // ->where('tesco_tcl4_orders.order_status', '!=', 'Saved')
                            ->whereNotIn('tesco_tcl4_orders.order_status',['Saved','Order Cancelled'])
                            ->select(DB::raw("'".$actions."'".' as "StyleNo"'),'tesco_tcl4_orders.uk_style_ref','tesco_tcl4_orders.ce_style_ref','tesco_tcl4_orders.po_number as BuyerPO','tesco_tcl4_orders.zone as Zone',DB::raw("'".$tcl4."'".' as "TemplateName"'),'tesco_tcl4_orders.template_type as Template Type','customers.id as customer_id','customers.company_name as Company','tesco_tcl4_orders.created_at as OrderDate',DB::raw('to_char(tesco_tcl4_orders.delivery_date,'."'".$ddmmyy."'".') as "DeliveryDate"'),'tesco_tcl4_orders.order_status as artworkStatus','tesco_tcl4_orders.pi_status as pIstatus','tesco_tcl4_orders.po_status as PO Status','tesco_tcl4_orders.purchase_order_no as PO Number',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'".$tesco_tcl4_orders."'".' as "tableName"'),'tesco_tcl4_orders.id as tableID','tesco_tcl4_orders.po_file','customers.pi_required','customers.po_required','tesco_tcl4_orders.template_id','tesco_tcl4_orders.order_flag','customers.delivery_day_count','is_order_count_empty')
                            ->get();
      $thl1 = "THL 1";
      $tesco_thl1_orders = "tesco_thl1_orders";
            $data4 = DB::table('tesco_thl1_orders')
                            ->join('customers','tesco_thl1_orders.tesco_supplier_id','=','customers.id')
                            ->where('tesco_thl1_orders.is_order_count_empty',0)
                            // ->where('tesco_thl1_orders.order_status', '!=', 'Saved')
                            ->whereNotIn('tesco_thl1_orders.order_status',['Saved','Order Cancelled'])
                            ->select(DB::raw("'".$actions."'".' as "StyleNo"'),'tesco_thl1_orders.uk_style_ref','tesco_thl1_orders.ce_style_ref','tesco_thl1_orders.po_number as BuyerPO','tesco_thl1_orders.zone as Zone',DB::raw("'".$thl1."'".' as "TemplateName"'),'tesco_thl1_orders.template_type as Template Type','customers.id as customer_id','customers.company_name as Company','tesco_thl1_orders.created_at as OrderDate',DB::raw('to_char(tesco_thl1_orders.delivery_date,'."'".$ddmmyy."'".') as "DeliveryDate"'),'tesco_thl1_orders.order_status as artworkStatus','tesco_thl1_orders.pi_status as pIstatus','tesco_thl1_orders.po_status as PO Status','tesco_thl1_orders.purchase_order_no as PO Number',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'".$tesco_thl1_orders."'".' as "tableName"'),'tesco_thl1_orders.id as tableID','tesco_thl1_orders.po_file','customers.pi_required','customers.po_required','tesco_thl1_orders.template_id','tesco_thl1_orders.order_flag','customers.delivery_day_count','is_order_count_empty')
                                ->get();
      $thl3 = "THL 3";
      $tesco_thl3_orders = "tesco_thl3_orders";
            $data5 = DB::table('tesco_thl3_orders')
                            ->join('customers','tesco_thl3_orders.tesco_supplier_id','=','customers.id')
                            // ->where('tesco_thl3_orders.is_order_count_empty',0)
                            // ->where('tesco_thl3_orders.order_status', '!=', 'Saved')
                            ->whereNotIn('tesco_thl3_orders.order_status',['Saved','Order Cancelled'])
                            ->select(DB::raw("'".$actions."'".' as "StyleNo"'),'tesco_thl3_orders.uk_style_ref','tesco_thl3_orders.ce_style_ref','tesco_thl3_orders.po_number as BuyerPO','tesco_thl3_orders.zone as Zone',DB::raw("'".$thl3."'".' as "TemplateName"'),'tesco_thl3_orders.template_type as Template Type','customers.id as customer_id','customers.company_name as Company','tesco_thl3_orders.created_at as OrderDate',DB::raw('to_char(tesco_thl3_orders.delivery_date,'."'".$ddmmyy."'".') as "DeliveryDate"'),'tesco_thl3_orders.order_status as artworkStatus','tesco_thl3_orders.pi_status as pIstatus','tesco_thl3_orders.po_status as PO Status','tesco_thl3_orders.purchase_order_no as PO Number',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'".$tesco_thl3_orders."'".' as "tableName"'),'tesco_thl3_orders.id as tableID','tesco_thl3_orders.po_file','customers.pi_required','customers.po_required','tesco_thl3_orders.template_id','tesco_thl3_orders.order_flag','customers.delivery_day_count','is_order_count_empty')
                            ->get();
      $tsl1 = "TSL 1";
      $tesco_tsl1_orders = "tesco_tsl1_orders";
            $data6 = DB::table('tesco_tsl1_orders')
                            ->join('customers','tesco_tsl1_orders.tesco_supplier_id','=','customers.id')
                            // ->where('tesco_tsl1_orders.is_order_count_empty',0)
                            // ->where('tesco_tsl1_orders.order_status', '!=', 'Saved')
                            ->whereNotIn('tesco_tsl1_orders.order_status',['Saved','Order Cancelled'])
                            ->select(DB::raw("'".$actions."'".' as "StyleNo"'),'tesco_tsl1_orders.uk_style_ref','tesco_tsl1_orders.ce_style_ref','tesco_tsl1_orders.po_number as BuyerPO','tesco_tsl1_orders.zone as Zone',DB::raw("'".$tsl1."'".' as "TemplateName"'),'tesco_tsl1_orders.template_type as Template Type','customers.id as customer_id','customers.company_name as Company','tesco_tsl1_orders.created_at as OrderDate',DB::raw('to_char(tesco_tsl1_orders.delivery_date,'."'".$ddmmyy."'".') as "DeliveryDate"'),'tesco_tsl1_orders.order_status as artworkStatus','tesco_tsl1_orders.pi_status as pIstatus','tesco_tsl1_orders.po_status as PO Status','tesco_tsl1_orders.purchase_order_no as PO Number',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'".$tesco_tsl1_orders."'".' as "tableName"'),'tesco_tsl1_orders.id as tableID','tesco_tsl1_orders.po_file','customers.pi_required','customers.po_required','tesco_tsl1_orders.template_id','tesco_tsl1_orders.order_flag','customers.delivery_day_count','is_order_count_empty')
                            ->get();
      $tsl3 = "TSL 3";
      $tesco_tsl3_orders = "tesco_tsl3_orders";
            $data7 = DB::table('tesco_tsl3_orders')
                            ->join('customers','tesco_tsl3_orders.tesco_supplier_id','=','customers.id')
                            // ->where('tesco_tsl3_orders.is_order_count_empty',0)
                            // ->where('tesco_tsl3_orders.order_status', '!=', 'Saved')
                            ->whereNotIn('tesco_tsl3_orders.order_status',['Saved','Order Cancelled'])
                            ->select(DB::raw("'".$actions."'".' as "StyleNo"'),'tesco_tsl3_orders.uk_style_ref','tesco_tsl3_orders.ce_style_ref','tesco_tsl3_orders.po_number as BuyerPO','tesco_tsl3_orders.zone as Zone',DB::raw("'".$tsl3."'".' as "TemplateName"'),'tesco_tsl3_orders.template_type as Template Type','customers.id as customer_id','customers.company_name as Company','tesco_tsl3_orders.created_at as OrderDate',DB::raw('to_char(tesco_tsl3_orders.delivery_date,'."'".$ddmmyy."'".') as "DeliveryDate"'),'tesco_tsl3_orders.order_status as artworkStatus','tesco_tsl3_orders.pi_status as pIstatus','tesco_tsl3_orders.po_status as PO Status','tesco_tsl3_orders.purchase_order_no as PO Number',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'".$tesco_tsl3_orders."'".' as "tableName"'),'tesco_tsl3_orders.id as tableID','tesco_tsl3_orders.po_file','customers.pi_required','customers.po_required','tesco_tsl3_orders.template_id','tesco_tsl3_orders.order_flag','customers.delivery_day_count','is_order_count_empty')
                            ->get();


          $data = $data1->merge($data2)->merge($data3)->merge($data4)->merge($data5)->merge($data6)->merge($data7);


          return Datatables::of($data)
              
                ->addColumn('artworkStatus', function ($data) {
                    return $data->artworkStatus =="Order Approved" ? '<span class="label label-OrderApproved">'.$data->artworkStatus.'</span>':'<span class="label label-OrderPlaced">'.$data->artworkStatus.'</span>';
                })

              ->addColumn('deliveryDate', function ($data) {
                  return '<span style="display:none;">2019-12-19 06:08:58</span><input value="31-12-2019" class="form-control input-sm datepicker_recurring_start" data-id="7" style="width: 107px;" placeholder="Delivery Date" onchange="updateDeliveryDate(this,&quot;276&quot;,&quot;tesco_tcl1_orders&quot;)" readonly="">';
              })

              ->addColumn('actions', function ($activities) {
                  // return '';

                  return'<a class="pi_generation" href="javascript:void(0)" onclick="genarateTescoPI(&quot;276&quot;,&quot;tesco_tcl1_orders&quot;,&quot;false&quot;,&quot;tst12QR&quot;,&quot;19&quot;,&quot;tst12QR&quot;,&quot;TCL1&quot;,&quot;newOrder&quot;)"><i class="fa fa-file-pdf-o fa-button pi-view filterbtn options" data-placement="top" title="" data-original-title="Proforma Invoice"></i></a><a href="javascript:void(0)" onclick="tescoPOUpload(this,&quot;276&quot;,&quot;/storage/app/public/po-files/TESCO_PO_1576735749_po_460-92592_LINKUP_RATIO_A.pdf&quot;,&quot;tesco_tcl1_orders&quot;)" class="po-style"><i class="fa fa-file-powerpoint-o fa-button po-view filterbtn options" data-placement="top" title="" style="border-color:#FFFFFF" data-original-title="Purchase Order"></i></a>';
                  })

              ->editColumn('inputPo', function($data)
              {
                  return '<div class="form-group has-feedback"><div class="input-group"><input type="text" onkeypress="return AvoidSpace(event)" name="tesco-po" id="tesco-po" class="form-control input-sm tesco-po" placeholder="Enter PO" maxlength="11" value="tst12QR"><span class="input-group-btn po-span"><button type="submit" class="btn btn-sm btn-default tesco-po-submit" onclick="tescoSetPO(this,&quot;276&quot;,&quot;tesco_tcl1_orders&quot;,&quot;/storage/app/public/po-files/TESCO_PO_1576735749_po_460-92592_LINKUP_RATIO_A.pdf&quot;)"><span class="fa fa-check"></span></button></span></div></div>';
              })  

          ->rawColumns(['inputPo','artworkStatus','actions','deliveryDate'])
          ->make(true);
  }

}