<?php

namespace App\Classes;

use DB;
use Carbon\Carbon;

/**
 * Common class for orchestra order status page
 * @author [Gowtham] <[gowtham@wizroots.com]>
 */
class OrchestraOrderStatus
{

  private $current_date;
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    // get the current time
    $this->current_date = Carbon::now('Asia/Kolkata')->toDateTimeString();
  }

  public function getExistingLabelOrderStatusPo($uvc)
  {
        $data['orchestra_hang_tag_orders']=DB::table('orchestra_hang_tag_orders')->join('orchestra_item_master','orchestra_item_master.id','orchestra_hang_tag_orders.template_front_id')->where('orchestra_hang_tag_orders.status','!=','Order Cancelled')->select('orchestra_hang_tag_orders.*','orchestra_item_master.company_id', DB::raw("'Hang Tag' as \"TemplateName\""),DB::raw("'Hang Tag' as \"TemplateType\""),DB::raw("'orchestra_hang_tag_orders' as \"tableName\""))->where('uvc',$uvc)->get();
    
        $data['orchestra_special_tag_orders']=DB::table('orchestra_special_tag_orders')->join('orchestra_item_master','orchestra_item_master.id','orchestra_special_tag_orders.template_id')->where('orchestra_special_tag_orders.status','!=','Order Cancelled')->select('orchestra_special_tag_orders.*','orchestra_item_master.company_id', DB::raw("'Special Tag' as \"TemplateName\""),DB::raw("'Special Tag' as \"TemplateType\""),DB::raw("'orchestra_special_tag_orders' as \"tableName\""))->where('uvc',$uvc)->get();
    
        $data['orchestra_main_label_orders']=DB::table('orchestra_main_label_orders')->join('orchestra_item_master','orchestra_item_master.id','orchestra_main_label_orders.template_id')->where('orchestra_main_label_orders.status','!=','Order Cancelled')->select('orchestra_main_label_orders.*','orchestra_item_master.company_id',DB::raw("'Main Label' as \"TemplateName\""),DB::raw("'Main Label' as \"TemplateType\""),DB::raw("'orchestra_main_label_orders' as \"tableName\""))->where('uvc',$uvc)->get();
    
        $data['orchestra_size_label_orders']=DB::table('orchestra_size_label_orders')->join('orchestra_item_master','orchestra_item_master.id','orchestra_size_label_orders.template_id')->where('orchestra_size_label_orders.status','!=','Order Cancelled')->select('orchestra_size_label_orders.*','orchestra_item_master.company_id',DB::raw("'Size Label' as \"TemplateName\""),DB::raw("'Size Label' as \"TemplateType\""),DB::raw("'orchestra_size_label_orders' as \"tableName\""))->where('uvc',$uvc)->get();		
    
        $data['orchestra_heat_transfer_orders']=DB::table('orchestra_heat_transfer_orders')->join('orchestra_item_master','orchestra_item_master.id','orchestra_heat_transfer_orders.template_id')->where('orchestra_heat_transfer_orders.status','!=','Order Cancelled')->select('orchestra_heat_transfer_orders.*','orchestra_item_master.company_id',DB::raw("'Heat Transfer' as \"TemplateName\""),DB::raw("'Heat Transfer' as \"TemplateType\""),DB::raw("'orchestra_heat_transfer_orders' as \"tableName\""))->where('uvc',$uvc)->get();
    
        $data['orchestra_wash_care_label_orders']=DB::table('orchestra_wash_care_label_orders')->join('orchestra_item_master','orchestra_item_master.id','orchestra_wash_care_label_orders.template_id')->where('orchestra_wash_care_label_orders.status','!=','Order Cancelled')->select('orchestra_wash_care_label_orders.*','orchestra_item_master.company_id','orchestra_wash_care_label_orders.template_name as TemplateName',DB::raw("'Wash Care Labels' as \"TemplateType\""),DB::raw("'orchestra_wash_care_label_orders' as \"tableName\""))->where('uvc',$uvc)->get();
    
        $data['orchestra_solid_carton_sticker_orders']=DB::table('orchestra_solid_carton_sticker_orders')->join('orchestra_item_master','orchestra_item_master.id','orchestra_solid_carton_sticker_orders.template_id')->where('orchestra_solid_carton_sticker_orders.status','!=','Order Cancelled')->select('orchestra_solid_carton_sticker_orders.*','orchestra_item_master.company_id',DB::raw("'Solid Carton Stickers' as \"TemplateName\""),DB::raw("'Solid Carton Stickers' as \"TemplateType\""),DB::raw("'orchestra_solid_carton_sticker_orders' as \"tableName\""))->where('uvc',$uvc)->get();
    
        $data['orchestra_solid_poly_bag_sticker_orders']=DB::table('orchestra_solid_poly_bag_sticker_orders')->join('orchestra_item_master','orchestra_item_master.id','orchestra_solid_poly_bag_sticker_orders.template_id')->where('orchestra_solid_poly_bag_sticker_orders.status','!=','Order Cancelled')->select('orchestra_solid_poly_bag_sticker_orders.*','orchestra_item_master.company_id',DB::raw("'Solid Poly Bag Stickers' as \"TemplateName\""),DB::raw("'Solid Poly Bag Stickers' as \"TemplateType\""),DB::raw("'orchestra_solid_poly_bag_sticker_orders' as \"tableName\""))->where('uvc',$uvc)->get();
    
        $data['orchestra_standard_carton_sticker_orders']=DB::table('orchestra_standard_carton_sticker_orders')->join('orchestra_item_master','orchestra_item_master.id','orchestra_standard_carton_sticker_orders.template_id')->where('orchestra_standard_carton_sticker_orders.status','!=','Order Cancelled')->select('orchestra_standard_carton_sticker_orders.*','orchestra_item_master.company_id',DB::raw("'Standard Carton Stickers' as \"TemplateName\""),DB::raw("'Standard Carton Stickers' as \"TemplateType\""),DB::raw("'orchestra_standard_carton_sticker_orders' as \"tableName\""))->where('uvc',$uvc)->get();
    
        $data['orchestra_standard_poly_bag_sticker_orders']=DB::table('orchestra_standard_poly_bag_sticker_orders')->join('orchestra_item_master','orchestra_item_master.id','orchestra_standard_poly_bag_sticker_orders.template_id')->where('orchestra_standard_poly_bag_sticker_orders.status','!=','Order Cancelled')->select('orchestra_standard_poly_bag_sticker_orders.*','orchestra_item_master.company_id',DB::raw("'Standard Poly Bag Stickers' as \"TemplateName\""),DB::raw("'Standard Poly Bag Stickers' as \"TemplateType\""),DB::raw("'orchestra_standard_poly_bag_sticker_orders' as \"tableName\""))->where('uvc',$uvc)->get();
		
		return $data;
   } 

}