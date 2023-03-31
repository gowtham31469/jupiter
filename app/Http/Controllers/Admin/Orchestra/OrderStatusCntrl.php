<?php

namespace App\Http\Controllers\Admin\Orchestra;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;   
use App\Classes\Modules;
use App\Classes\ProformaInvoice;
use Response;
use App\HangTagOrders;
use App\ProfomaInvoice as PI;
use App\ProfomaInvoiceItemDetails;
use App\HangTagOrdersQty;
use App\SpecialTagOrders;
use App\SpecialTagOrdersQty;
use App\MainLabelOrders;
use App\MainLabelOrdersQty;
use App\WashLabelOrders;
use App\WashLabelOrdersQty;
use App\WashCareComposition;
use App\SolidCartonStickerOrders;
use App\SolidCartonStickerOrdersQty;
use App\SolidPolyStickerOrders;
use App\SolidPolyStickerOrdersQty;
use App\StandardCartonStickers;
use App\StandardPolyStickers;
use App\StandardCartonStickersQty;
use App\StandardPolyStickersQty;
use App\HeatTransferOrders;
use App\HeatTransferOrdersQty;
use View;
use DB;
use App\Classes\ERPOrderStatusCheck;
use App\Classes\ErpOrders;
use Carbon\Carbon;
use App\OrchestraParcelDetails;
use App\OrchestraParcelQuantityDetails;
use App\Classes\OrchestraTreeStructure;
use Auth;

class OrderStatusCntrl extends Controller
{
    private $modules_permission;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->orc_tree_structure    = new OrchestraTreeStructure();
    }
	/**
     * method to get the details about a particular artwork
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function editArtwork(Request $request)
    {
        try
        {
            $row_id     =   $request->id;
            $table_name =   $request->table_name;
            $uvc		=   $request->uvc;
            $season_code 	=   $request->season;
            $color_code =   $request->color_code;
            $po 		=   $request->buyer;
			if($table_name == "orchestra_solid_carton_sticker_orders" || $table_name == "orchestra_solid_poly_bag_sticker_orders" || $table_name == "orchestra_standard_carton_sticker_orders" || $table_name == "orchestra_standard_poly_bag_sticker_orders" )
			{
				$data['order_details'] = DB::table('orchestra_parcel_details')
                                        ->select('orchestra_parcel_details.*','orchestra_parcel_details.Size_Range_Label as Age','orchestra_parcel_details.Start_Print_Date	 as HangTag_Print_Date','orchestra_size_chart.cm',DB::raw("sum(orchestra_parcel_details.\"Quantity\") as \"Sum\""), DB::raw("'null' as \"PrixEurope\""), DB::raw("'null' as \"PrixClubEurope\""), DB::raw("'null' as \"Prix_Vente_Arabie_Saoudite\""), DB::raw("'null' as \"Prix_Club_Arabie_Saoudite\""), DB::raw("'null' as \"Prix_Vente_Canada\""), DB::raw("'null' as \"Prix_Club_Canada\""), DB::raw("'null' as \"Prix_Vente_Suisse\""), DB::raw("'null' as \"Prix_Club_Suisse\""), DB::raw("'null' as \"Prix_Vente_Maroc\""), DB::raw("'null' as \"Prix_Club_Maroc\""), DB::raw("'null' as \"Prix_Vente_Ile_Maurice\""), DB::raw("'null' as \"Prix_Club_Ile_Maurice\""), DB::raw("'null' as \"Prix_Vente_ALGERIE\""), DB::raw("'null' as \"Prix_Club_ALGERIE\""), DB::raw("'null' as \"Prix_Vente_USA\""), DB::raw("'null' as \"Prix_Club_USA\""))
                                        ->leftJoin('orchestra_size_chart','orchestra_parcel_details.Size_Label','=','orchestra_size_chart.age')
                                        ->where('orchestra_parcel_details.UVC',$uvc)
                                        ->where('orchestra_parcel_details.Season_Code',$season_code)
                                        ->where('orchestra_parcel_details.Color_Code',$color_code)
                                        ->where('orchestra_parcel_details.Po_Status_Code_Description','!=','Cancelled.')
                                        ->groupBy('orchestra_parcel_details.id','Size_Label','orchestra_size_chart.cm')
                                        ->orderBy('orchestra_parcel_details.id')
                                        ->get();
				
				$data['orchestra_age_group']= DB::table('orchestra_age_group')->where('is_enabled',1)->select('*')->get();   
				
				$data['orchestra_parcel_quantity_details'] = DB::table('orchestra_parcel_quantity_details')->select(DB::raw('distinct on ("UVC_TCO") "UVC_TCO"'),'UVC','Size','BarcodeForPricesTags','Quantity','Color')->where('BuyerPONo',$po)->where('orchestra_parcel_quantity_details.UVC',$uvc)->get();

				$data['orchestra_parcel_details']= DB::table('orchestra_parcel_details as alias')->leftJoin('orchestra_color_code','orchestra_color_code.code','alias.Color_Code')->select(DB::raw('distinct on (alias."Size") "Size"'),DB::raw("(SELECT SUM(\"Quantity\") FROM orchestra_parcel_details WHERE \"Size\" = alias.\"Size\" and \"BuyerPONo\"='".$po."') as \"Sum\""),'alias.*','Standard_Code as ParcelCode','orchestra_color_code.english as colour_Code')->where('BuyerPONo',$po)->groupBy('alias.id','orchestra_color_code.id','BuyerPONo')->get();
				
				//print_r($data['orchestra_parcel_details']);
				
				if($data['orchestra_parcel_details']{0}->IsSTD == 1)
				{
					$orchestra_template = DB::table('orchestra_template')->select('*')->where('item_id','101')->get();
					$data['carton_template']=$orchestra_template{0}->template_file;
					$data['carton_template_id']=101;

					$factory_code = $data['orchestra_parcel_details']{0}->FactoryCode;
					$customer_id = DB::table('customers')->select('*')->where('factory_code',$factory_code)->get();
					if(count($customer_id) > 0)
					{
						$carton_moq = DB::table('price_taxation')->select('*')->where('customer_id',$customer_id{0}->id)->where('buyer_id',1)->where('template_id',101)->get();

						if(count($carton_moq) > 0)
						{
							$data['carton_moq']=$carton_moq{0}->moq;
						}
						else
						{
							$data['carton_moq']=1;
						}
					}
					else
					{
						$data['carton_moq']=1;
					}

					$orchestra_template = DB::table('orchestra_template')->select('*')->where('item_id','102')->get();
					$data['polybag_template']=$orchestra_template{0}->template_file;
					$data['template_name']="Standard";
					$data['polybag_template_id']=102;
					if(count($customer_id) > 0)
					{
						$polybag_moq = DB::table('price_taxation')->select('*')->where('customer_id',$customer_id{0}->id)->where('buyer_id',1)->where('template_id',102)->get();

						if(count($polybag_moq) > 0)
						{
							$data['polybag_moq']=$polybag_moq{0}->moq;
						}
						else
						{
							$data['polybag_moq']=1;
						}
					}
					else
					{
						$data['polybag_moq']=1;
					}
				}
				else
				{
					$orchestra_template = DB::table('orchestra_template')->select('*')->where('item_id','99')->get();
					$data['carton_template']=$orchestra_template{0}->template_file;
					$data['carton_template_id']=99;

					$factory_code = $data['orchestra_parcel_details']{0}->FactoryCode;
					$customer_id = DB::table('customers')->select('*')->where('factory_code',$factory_code)->get();

					if(count($customer_id) > 0)
					{
						$carton_moq = DB::table('price_taxation')->select('*')->where('customer_id',$customer_id{0}->id)->where('buyer_id',1)->where('template_id',99)->get();

						if(count($carton_moq) > 0)
						{
							$data['carton_moq']=$carton_moq{0}->moq;
						}
						else
						{
							$data['carton_moq']=1;
						}


					}
					else
					{
						$data['carton_moq']=1;
					}


					$orchestra_template = DB::table('orchestra_template')->select('*')->where('item_id','100')->get();
					$data['polybag_template']=$orchestra_template{0}->template_file;
					$data['template_name']="Solid";
					$data['polybag_template_id']=100;

					if(count($customer_id) > 0)
					{
						$polybag_moq = DB::table('price_taxation')->select('*')->where('customer_id',$customer_id{0}->id)->where('buyer_id',1)->where('template_id',100)->get();

						if(count($polybag_moq) > 0)
						{
							$data['polybag_moq']=$polybag_moq{0}->moq;
						}
						else
						{
							$data['polybag_moq']=1;
						}
					}
					else
					{
						$data['polybag_moq']=1;
					}


				}

                $factory = $data['order_details']{0}->FactoryCode;
                $data['customers']=DB::table('customers')->join('customer_buyers','customer_buyers.customer_id','=','customers.id')->where('customers.factory_code',$factory)->where('customer_buyers.buyer_id',1)->select('*')->get();

                if($table_name == "orchestra_solid_carton_sticker_orders")
                {
                    $data['orchestra_solid_carton_sticker_orders']=DB::table('orchestra_solid_carton_sticker_orders')->select('*',DB::raw("'Solid Carton Stickers' as \"TemplateName\""))->where('id',$row_id)->where('status','!=','Order Cancelled')->get();
                    if(count($data['orchestra_solid_carton_sticker_orders']) > 0)
                    {
                        $data['orchestra_solid_carton_sticker_orders_qty']=DB::table('orchestra_solid_carton_sticker_orders_qty')->select('*')->where('oscs_id',$data['orchestra_solid_carton_sticker_orders']{0}->id)->orderBy('orchestra_solid_carton_sticker_orders_qty.id','asc')->get();
                        
                        if(count($data['orchestra_solid_carton_sticker_orders_qty']) > 0)
                        {
                            $data['solid_carton_erp_details']= DB::table('erp_order_details')->where('order_qnty_table_name','ilike','orchestra_solid_carton_sticker_orders_qty')->join('erp_status', 'erp_status.id', 'erp_order_details.status')->where('order_qnty_table_id','=', $data['orchestra_solid_carton_sticker_orders_qty']{0}->id)->select('erp_order_details.id','erp_status.status_name')->first();
                        }
                        else
                        {
                            $data['orchestra_solid_carton_sticker_orders_qty']="";
                        }
                        
                        
                    }
                    else
                    {
                        $data['orchestra_solid_carton_sticker_orders_qty']="";
                    }
                }
                else if($table_name == "orchestra_solid_poly_bag_sticker_orders")
                {
                    $data['orchestra_solid_poly_bag_sticker_orders']=DB::table('orchestra_solid_poly_bag_sticker_orders')->select('*',DB::raw("'Solid Poly Bag Stickers' as \"TemplateName\""))->where('id',$row_id)->where('status','!=','Order Cancelled')->get();
                    if(count($data['orchestra_solid_poly_bag_sticker_orders']) > 0)
                    {
                        $data['orchestra_solid_poly_bag_sticker_orders_qty']=DB::table('orchestra_solid_poly_bag_sticker_orders_qty')->select('*')->where('ospbs_id',$data['orchestra_solid_poly_bag_sticker_orders']{0}->id)->orderBy('orchestra_solid_poly_bag_sticker_orders_qty.id','asc')->get();
                        
                        if(count($data['orchestra_solid_poly_bag_sticker_orders_qty']) > 0)
                        {
                            $data['solid_poly_erp_details']= DB::table('erp_order_details')->where('order_qnty_table_name','ilike','orchestra_solid_poly_bag_sticker_orders_qty')->join('erp_status', 'erp_status.id', 'erp_order_details.status')->where('order_qnty_table_id','=', $data['orchestra_solid_poly_bag_sticker_orders_qty']{0}->id)->select('erp_order_details.id','erp_status.status_name')->first();
                        }
                        else
                        {
                            $data['orchestra_solid_poly_bag_sticker_orders_qty']="";
                        }
                        
                        
                    }
                    else
                    {
                        $data['orchestra_solid_poly_bag_sticker_orders_qty']="";
                    }
                }
                else if($table_name == "orchestra_standard_carton_sticker_orders")
                {
                    $data['orchestra_standard_carton_sticker_orders']=DB::table('orchestra_standard_carton_sticker_orders')->select('*',DB::raw("'Standard Carton Stickers' as \"TemplateName\""))->where('id',$row_id)->where('status','!=','Order Cancelled')->get();
                    if(count($data['orchestra_standard_carton_sticker_orders']) > 0)
                    {
                        $data['orchestra_standard_carton_sticker_orders_qty']=DB::table('orchestra_standard_carton_sticker_orders_qty')->select('*')->where('oscs_id',$data['orchestra_standard_carton_sticker_orders']{0}->id)->orderBy('orchestra_standard_carton_sticker_orders_qty.id','asc')->get();
                        
                        if(count($data['orchestra_standard_carton_sticker_orders_qty']) > 0)
                        {
                            $data['standard_carton_erp_details']= DB::table('erp_order_details')->where('order_qnty_table_name','ilike','orchestra_standard_carton_sticker_orders_qty')->join('erp_status', 'erp_status.id', 'erp_order_details.status')->where('order_qnty_table_id','=', $data['orchestra_standard_carton_sticker_orders_qty']{0}->id)->select('erp_order_details.id','erp_status.status_name')->first();
                        }
                        else
                        {
                            $data['orchestra_standard_carton_sticker_orders_qty']="";
                        }
                        
                    }
                    else
                    {
                        $data['orchestra_standard_carton_sticker_orders_qty']="";
                    }
                }
                else if($table_name == "orchestra_standard_poly_bag_sticker_orders")
                {
                    $data['orchestra_standard_poly_bag_sticker_orders']=DB::table('orchestra_standard_poly_bag_sticker_orders')->select('*',DB::raw("'Standard Poly Bag Stickers' as \"TemplateName\""))->where('id',$row_id)->where('status','!=','Order Cancelled')->get();
                    if(count($data['orchestra_standard_poly_bag_sticker_orders']) > 0)
                    {
                        $data['orchestra_standard_poly_bag_sticker_orders_qty']=DB::table('orchestra_standard_poly_bag_sticker_orders_qty')->select('*')->where('ospbs_id',$data['orchestra_standard_poly_bag_sticker_orders']{0}->id)->orderBy('orchestra_standard_poly_bag_sticker_orders_qty.id','asc')->get();
                        
                        if(count($data['orchestra_standard_poly_bag_sticker_orders_qty']) > 0)
                        {
                            $data['standard_poly_erp_details']= DB::table('erp_order_details')->where('order_qnty_table_name','ilike','orchestra_standard_poly_bag_sticker_orders_qty')->join('erp_status', 'erp_status.id', 'erp_order_details.status')->where('order_qnty_table_id','=', $data['orchestra_standard_poly_bag_sticker_orders_qty']{0}->id)->select('erp_order_details.id','erp_status.status_name')->first();
                        }
                        else
                        {
                            $data['orchestra_standard_poly_bag_sticker_orders_qty']="";
                        }
                    }
                    else
                    {
                        $data['orchestra_standard_poly_bag_sticker_orders_qty']="";
                    }	
                }                
				
			}
			else
			{
                $data['order_details'] = DB::table('orchestra_parcel_details as opd')
                            ->leftJoin('orchestra_size_chart','opd.Size_Label','=','orchestra_size_chart.age')
                            ->select(DB::raw('SUM("Quantity") as "Sum"'),DB::raw('Min(opd.id) as id'),DB::raw('Min(opd."Start_Print_Date") as Start_Print_Date'),"orchestra_size_chart.cm","opd.Size_Range_Label","opd.Season_Code","opd.UVC" ,"opd.Size","opd.UVC_TCO","opd.Color_Code","opd.BarcodeForPricesTags","opd.SKU_Code","opd.FactoryCode","opd.Family_Code","opd.Activity_Code","opd.Activity_Label","opd.Licence_Code","opd.Theme")
                            ->where('UVC',$uvc)
                            ->where('Season_Code',$season_code)
                            ->where('Color_Code',$color_code)
                            ->where('Po_Status_Code_Description','!=','Cancelled.')
                            ->groupBy("Size_Label","orchestra_size_chart.cm","opd.Size_Range_Label","opd.SKU_Code","opd.Size","opd.UVC_TCO","opd.BarcodeForPricesTags","opd.FactoryCode","opd.Family_Code","opd.Color_Code","opd.Season_Code","opd.UVC","opd.Activity_Code","opd.Activity_Label","opd.Licence_Code","opd.Theme")
                            ->orderBy(DB::raw('MIN(opd.id)'),'asc')
                            ->get();

                if(count($data['order_details']) > 0)
                {

                    if($table_name == "orchestra_hang_tag_orders")
                    {
                        if($data['order_details']{0}->Licence_Code === 'DI')
                        {
                            $treeviewData= DB::table("orchestra_item_master")->select('id',DB::raw("(CASE WHEN ref_no = '' THEN item_name ELSE CONCAT(item_name,' - ',ref_no) END) as text"),'parent_id')->orWhere('id','103')->orWhere('tag_name','disney')->orderBy('item_name', 'asc')->get()->toArray();
    
                            if($treeviewData)
                            {
                                $childs = array();
                                foreach($treeviewData as &$item) 
                                {
                                $childs[$item->parent_id][] = &$item;
                                unset($item);
                                }
                                foreach($treeviewData as &$item) 
                                {
                                    if (isset($childs[$item->id]))
                                    {
                                        $item->children = $childs[$item->id];
                                        unset($item);
                                    }
                                }
                                $tree = $childs[0];
                                $data['treefinalData']=$tree;
    
                            } 
                        }
                        else
                        {   
                            $treeviewData= DB::table("orchestra_item_master")->select('id',DB::raw("(CASE WHEN ref_no = '' THEN item_name ELSE CONCAT(item_name,' - ',ref_no) END) as text"),'parent_id')->orWhere('id','103')->orWhere('tag_name','hang')->orWhere('id','104')->orWhere('id','105')->orderBy('item_name', 'asc')->get()->toArray();
    
                            if($treeviewData)
                            {
                                $childs = array();
                                foreach($treeviewData as &$item) 
                                {
                                $childs[$item->parent_id][] = &$item;
                                unset($item);
                                }
                                foreach($treeviewData as &$item) 
                                {
                                    if (isset($childs[$item->id]))
                                    {
                                        $item->children = $childs[$item->id];
                                        unset($item);
                                    }
                                }
                                $tree = $childs[0];
                                $data['treefinalData']=$tree;
    
                            } 
                        }
    
                        $data['orchestra_hang_tag_orders']=DB::table('orchestra_hang_tag_orders')->join('orchestra_hang_tag_orders_qty','orchestra_hang_tag_orders.id','orchestra_hang_tag_orders_qty.oht_id')->select('orchestra_hang_tag_orders.*','orchestra_hang_tag_orders_qty.quantity',DB::raw("'Hang Tag' as \"TemplateName\""))->where('uvc',$uvc)
                        ->where('season_code',$season_code)->where('orchestra_hang_tag_orders.id',$row_id)->where('status','!=','Order Cancelled')->get();
                    }
                    else if($table_name == "orchestra_heat_transfer_orders")
                    {
                        $data['orchestra_heat_transfer_orders']=DB::table('orchestra_heat_transfer_orders')->join('orchestra_heat_transfer_orders_qty','orchestra_heat_transfer_orders.id','orchestra_heat_transfer_orders_qty.oht_id')->select('orchestra_heat_transfer_orders.*','orchestra_heat_transfer_orders_qty.quantity',DB::raw("'Heat Transfer' as \"TemplateName\""))->where('uvc',$uvc)
                        ->where('season_code',$season_code)->where('orchestra_heat_transfer_orders.id',$row_id)->where('status','!=','Order Cancelled')->get();

                        $data['treeviewheat_transfer'] = $this->orc_tree_structure->treeViewHT();
                    }
                    else if($table_name == "orchestra_special_tag_orders")
                    {
                        $data['orchestra_special_tag_orders']=DB::table('orchestra_special_tag_orders')->join('orchestra_special_tag_orders_qty','orchestra_special_tag_orders.id','orchestra_special_tag_orders_qty.ost_id')->select('orchestra_special_tag_orders.*','orchestra_special_tag_orders_qty.quantity',DB::raw("'Special Tag' as \"TemplateName\""))->where('uvc',$uvc)
                        ->where('season_code',$season_code)->where('orchestra_special_tag_orders.id',$row_id)->where('status','!=','Order Cancelled')->get();

                        $data['treeviewspecial'] = $this->orc_tree_structure->treeViewSpecial();
                    }
                    else if($table_name == "orchestra_main_label_orders")
                    {
                        $data['orchestra_main_label_orders']=DB::table('orchestra_main_label_orders')->join('orchestra_main_label_orders_qty','orchestra_main_label_orders.id','orchestra_main_label_orders_qty.oml_id')->select('orchestra_main_label_orders.*','orchestra_main_label_orders_qty.quantity',DB::raw("'Main Label' as \"TemplateName\""))->where('uvc',$uvc)
                        ->where('season_code',$season_code)->where('orchestra_main_label_orders.id',$row_id)->where('status','!=','Order Cancelled')->get();

                        $data['treeviewmain_label'] = $this->orc_tree_structure->treeViewMain();
                    }
                    else if($table_name == "orchestra_size_label_orders")
                    {
                        $data['orchestra_size_label_orders']=DB::table('orchestra_size_label_orders')->join('orchestra_size_label_orders_qty','orchestra_size_label_orders.id','orchestra_size_label_orders_qty.osl_id')->select('orchestra_size_label_orders.*','orchestra_size_label_orders_qty.quantity',DB::raw("'Size Label' as \"TemplateName\""))->where('uvc',$uvc)
                        ->where('season_code',$season_code)->where('orchestra_size_label_orders.id',$row_id)->where('status','!=','Order Cancelled')->get();

                        $data['treeviewsize_label'] = $this->orc_tree_structure->treeViewSize();
                    }
                    else if($table_name == "orchestra_wash_care_label_orders")
                    {
                        $data['orchestra_wash_care_label_orders']=DB::table('orchestra_wash_care_label_orders')->select('*','orchestra_wash_care_label_orders.template_name as TemplateName')->where('uvc',$uvc)->where('season_code',$season_code)->where('orchestra_wash_care_label_orders.id',$row_id)->where('status','!=','Order Cancelled')->orderBy('orchestra_wash_care_label_orders.po','asc')->get();


                        if($data['order_details']{0}->Licence_Code === 'DI')
                        {
                            if($data['order_details']{0}->Activity_Label != "PUERICULTURE")
                            {
                                $treeviewwash_label= DB::table("orchestra_item_master")->select('id',DB::raw("(CASE WHEN ref_no = '' THEN item_name ELSE CONCAT(item_name,' - ',ref_no) END) as text"),'parent_id')->orWhere('parent_id','3')->where('id','!=','8')->where('id','!=','202')->where('id','!=','203')->where('id','!=','9')->where('id','!=','4')->orderBy('item_name', 'asc')->get();
                                $data['treeviewwash_label'] = $treeviewwash_label;
                            }
                            else
                            {
                                $treeviewwash_label= DB::table("orchestra_item_master")->select('id',DB::raw("(CASE WHEN ref_no = '' THEN item_name ELSE CONCAT(item_name,' - ',ref_no) END) as text"),'parent_id')->where('parent_id','3')->where('id','!=','202')->where('tag_name','wcl_pmm')->orderBy('item_name', 'asc')->get();
                                $data['treeviewwash_label'] = $treeviewwash_label;
                            }
                            
                        }
                        else
                        {
                            if($data['order_details']{0}->Activity_Label != "PUERICULTURE")
                            {
                                $orc_template_name= DB::table("orchestra_item_master")->select('item_name')->orWhere('id','4')->get();

                                $data['treeviewwash_label'] = array(["id" => "4", "text" => $orc_template_name{0}->item_name, "parent_id" => "null"]);
                            }
                            else
                            {
                                $orc_template_name= DB::table("orchestra_item_master")->select('item_name')->orWhere('id','202')->get();

                                $data['treeviewwash_label'] = array(["id" => "202", "text" => $orc_template_name{0}->item_name, "parent_id" => "null"]);
                            }
                        }

                        foreach($data['orchestra_wash_care_label_orders'] as $value1)
                        {
                            $orchestra_wash_label_orders_qty=DB::table('orchestra_wash_care_label_orders_qty')->select('*')->where('owcl_id',$value1->id)->get();
                            $value1->qty=$orchestra_wash_label_orders_qty;

                            $orchestra_wash_care_composition=DB::table('orchestra_wash_care_composition')->leftJoin('orchestra_compositions','orchestra_compositions.id','=','orchestra_wash_care_composition.composition_id')->leftJoin('orchestra_parts','orchestra_parts.id','=','orchestra_wash_care_composition.parts_id')->select('orchestra_wash_care_composition.*','orchestra_compositions.french','orchestra_compositions.english','orchestra_compositions.spanish','orchestra_compositions.portuguese','orchestra_compositions.italian','orchestra_compositions.dutch','orchestra_compositions.german','orchestra_compositions.russian','orchestra_compositions.chinese','orchestra_compositions.arabic','orchestra_compositions.greek','orchestra_compositions.turkish','orchestra_parts.french as parts_french','orchestra_parts.english as parts_english','orchestra_parts.spanish as parts_spanish','orchestra_parts.portuguese as parts_portuguese','orchestra_parts.italian as parts_italian','orchestra_parts.dutch as parts_dutch','orchestra_parts.german as parts_german','orchestra_parts.russian as parts_russian','orchestra_parts.chinese as parts_chinese','orchestra_parts.arabic as parts_arabic','orchestra_parts.greek as parts_greek','orchestra_parts.turkish as parts_turkish')->orderBy('orchestra_wash_care_composition.id','asc')->where('owcl_id',$value1->id)->get();
                            $value1->composition=$orchestra_wash_care_composition;
                            $care_instructions=array();
                            foreach(explode(',',$value1->care_instruction_id) as $value2)
                            {
                                array_push($care_instructions,$value2);
                            }
                            $value1->care_instruction=$care_instructions;
                        }

                        $data['orchestra_care_symbols'] = DB::table('orchestra_care_symbols')->where('is_enabled',1)->select('*')->get();

                        $data['orchestra_composition'] = DB::table('orchestra_compositions')->where('is_enabled',1)->select('*')->get();

                        $data['orchestra_countries'] = DB::table('orchestra_countries')->where('is_enabled',1)->select('*')->get();

                        $data['orchestra_care_instruction'] = DB::table('orchestra_care_instruction')->where('is_enabled',1)->select('*')->get(); 
                        
                        $data['orchestra_parts'] = DB::table('orchestra_parts')->where('is_enabled',1)->select('*')->get();
                    }
                    
                    $factory = $data['order_details']{0}->FactoryCode;
                    $data['customers']=DB::table('customers')->join('customer_buyers','customer_buyers.customer_id','=','customers.id')->where('customers.factory_code',$factory)->where('customer_buyers.buyer_id',1)->select('*')->get();
                    
                    if(count($data['customers']) > 0)
                    {
                        $cust_id = $data['customers']{0}->id;
                        $data['merchandisers']=DB::table('merchandisers')->where('buyer_id',1)->select('*')->get();
                    }
                    else
                    {
                        $data['merchandisers']="";
                    }

                }
            }
		    echo json_encode($data);
        }
        catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
			return $result;
        }
        
    }

    public function getOrderDetails(Request $request)
	{
        $table_name        =   $request->table_name;
        $table_id          =   $request->table_id;
        
        if($table_name == "orchestra_solid_carton_sticker_orders")
        {
            $data[$table_name]=DB::table($table_name)->join($table_name.'_qty',$table_name.'_qty.oscs_id',$table_name.'.id')->join('orchestra_age_group','orchestra_age_group.id',$table_name.'.group_id')->join('orchestra_color_code','orchestra_color_code.code',$table_name.'.color_code')->where($table_name.'.status','!=','Order Cancelled')->select($table_name.'.*',$table_name.'_qty.size',$table_name.'_qty.barcode',$table_name.'_qty.uvc_tco','orchestra_age_group.color as age_color','orchestra_color_code.french as french_color','orchestra_color_code.english as english_color')->where($table_name.'.id',$table_id)->get();
        }
        else if($table_name == "orchestra_solid_poly_bag_sticker_orders")
        {
            $data[$table_name]=DB::table($table_name)->join($table_name.'_qty',$table_name.'_qty.ospbs_id',$table_name.'.id')->join('orchestra_age_group','orchestra_age_group.id',$table_name.'.group_id')->join('orchestra_color_code','orchestra_color_code.code',$table_name.'.color_code')->where($table_name.'.status','!=','Order Cancelled')->select($table_name.'.*',$table_name.'_qty.size',$table_name.'_qty.barcode',$table_name.'_qty.uvc_tco','orchestra_age_group.color as age_color','orchestra_color_code.french as french_color','orchestra_color_code.english as english_color')->where($table_name.'.id',$table_id)->get();
        }
        else if($table_name == "orchestra_standard_carton_sticker_orders")
        {
            $data[$table_name]=DB::table($table_name)->join($table_name.'_qty',$table_name.'_qty.oscs_id',$table_name.'.id')->join('orchestra_age_group','orchestra_age_group.id',$table_name.'.group_id')->join('orchestra_color_code','orchestra_color_code.code',$table_name.'.color_code')->where($table_name.'.status','!=','Order Cancelled')->select($table_name.'.*',$table_name.'_qty.size',$table_name.'_qty.barcode',$table_name.'_qty.uvc_tco','orchestra_age_group.color as age_color','orchestra_color_code.french as french_color','orchestra_color_code.english as english_color')->where($table_name.'.id',$table_id)->get();
        }
        else if($table_name == "orchestra_standard_poly_bag_sticker_orders")
        {
            $data[$table_name]=DB::table($table_name)->join($table_name.'_qty',$table_name.'_qty.ospbs_id',$table_name.'.id')->join('orchestra_age_group','orchestra_age_group.id',$table_name.'.group_id')->join('orchestra_color_code','orchestra_color_code.code',$table_name.'.color_code')->where($table_name.'.status','!=','Order Cancelled')->select($table_name.'.*',$table_name.'_qty.size',$table_name.'_qty.barcode',$table_name.'_qty.uvc_tco','orchestra_age_group.color as age_color','orchestra_color_code.french as french_color','orchestra_color_code.english as english_color')->where($table_name.'.id',$table_id)->get();

            
            $parcel_details = DB::table('orchestra_parcel_details')->where('UVC',$data[$table_name][0]->uvc)->where('BuyerPONo',$data[$table_name][0]->po)->where('Color_Code',$data[$table_name][0]->color_code)->select('*')->orderBy('orchestra_parcel_details.id')->get();

            $data[$table_name][0]->size_chart = $parcel_details;
        }
        else if($table_name == "orchestra_hang_tag_orders")
        {
            $data[$table_name]=DB::table($table_name)->join($table_name.'_qty',$table_name.'_qty.oht_id',$table_name.'.id')->join('orchestra_template',$table_name.'.template_front_id','orchestra_template.id')->join('orchestra_parcel_details',$table_name.'.orchestra_parcel_details_id','orchestra_parcel_details.id')->where($table_name.'.status','!=','Order Cancelled')->select($table_name.'.*',$table_name.'_qty.size',$table_name.'_qty.barcode',$table_name.'_qty.uvc_tco','orchestra_template.template_name','orchestra_parcel_details.Licence_Code')->where($table_name.'.id',$table_id)->get();

            $parcel_details = DB::table('orchestra_parcel_details')->where('UVC',$data[$table_name][0]->uvc)->where('Season_Code',$data[$table_name][0]->season_code)->where('Color_Code',$data[$table_name][0]->color_code)->select('*')->get();

            $data[$table_name][0]->size_chart = $parcel_details;
        }
        else if($table_name == "orchestra_wash_care_label_orders")
        {
            $data[$table_name]=DB::table($table_name)->join($table_name.'_qty',$table_name.'_qty.owcl_id',$table_name.'.id')->join('orchestra_color_code','orchestra_color_code.code',$table_name.'.color_code')->where($table_name.'.status','!=','Order Cancelled')->select($table_name.'.*',$table_name.'_qty.size',$table_name.'_qty.barcode',$table_name.'_qty.uvc_tco','orchestra_color_code.french as french_color','orchestra_color_code.english as english_color')->where($table_name.'.id',$table_id)->get();

            
            $parcel_details = DB::table('orchestra_parcel_details')->where('id',$data[$table_name][0]->orchestra_parcel_details_id)->select('*')->get();

            $orchestra_wash_care_composition = DB::table('orchestra_wash_care_composition')->join('orchestra_compositions','orchestra_compositions.id','orchestra_wash_care_composition.composition_id')->where('owcl_id',$table_id)->select('orchestra_wash_care_composition.*','orchestra_compositions.english')->orderBy('orchestra_wash_care_composition.id')->get();

            $orchestra_parts_comp = DB::table('orchestra_wash_care_composition')->where('owcl_id',$table_id)->select('orchestra_wash_care_composition.parts_id')->groupBy('orchestra_wash_care_composition.parts_id')->get();

            $data['product_family'] = DB::table('orchestra_product_family')->select('*')->where('code',$parcel_details{0}->Family_Code)->get();
            
            $data[$table_name][0]->activity_label = $parcel_details{0}->Activity_Label;
            
            $data[$table_name][0]->care_composition = $orchestra_wash_care_composition;
            $data[$table_name][0]->parts_comp = $orchestra_parts_comp;
            $data[$table_name][0]->product_family = $data['product_family'];
        }
        
        echo json_encode($data);
    }
    /**
     * This function is used to toggle the artwork edit permission for customer
     * 
     * @return boolean values
     */
    public function toggleArtworkEditPermission(Request $request)
    {
       try {
           $tablename = $request->table_name;
           $rowid     = $request->rowid;

           $permission = DB::table($tablename)->where('id',$rowid)->value('edit_permission') == 1?0:1;

           DB::table($tablename)->where('id',$rowid)
            ->update([
                'edit_permission' => $permission
            ]);            

           $result = response()->json(['error'=> false,'message' =>'success', 'permission' => $permission],200);
       }
       catch (\Exception $e)
       {
           $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
       }
       return $result;
   }  



}