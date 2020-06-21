<?php

namespace App\Http\Controllers\Admin\Orchestra;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;   
use App\Classes\Modules;
use App\Classes\ProformaInvoice;
use Response;
use Excel;
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
            $season 	=   $request->season;
            $color_code =   $request->color_code;
            $po 		=   $request->buyer;
			if($table_name == "orchestra_solid_carton_sticker_orders" || $table_name == "orchestra_solid_poly_bag_sticker_orders" || $table_name == "orchestra_standard_carton_sticker_orders" || $table_name == "orchestra_standard_poly_bag_sticker_orders" )
			{
				$data['order_details'] = DB::table('orchestra_parcel_details')
                                        ->select('orchestra_parcel_details.*','orchestra_parcel_details.Size_Range_Label as Age','orchestra_parcel_details.Start_Print_Date	 as HangTag_Print_Date','orchestra_size_chart.cm',DB::raw("sum(orchestra_parcel_details.\"Quantity\") as \"Sum\""), DB::raw("'null' as \"PrixEurope\""), DB::raw("'null' as \"PrixClubEurope\""), DB::raw("'null' as \"Prix_Vente_Arabie_Saoudite\""), DB::raw("'null' as \"Prix_Club_Arabie_Saoudite\""), DB::raw("'null' as \"Prix_Vente_Canada\""), DB::raw("'null' as \"Prix_Club_Canada\""), DB::raw("'null' as \"Prix_Vente_Suisse\""), DB::raw("'null' as \"Prix_Club_Suisse\""), DB::raw("'null' as \"Prix_Vente_Maroc\""), DB::raw("'null' as \"Prix_Club_Maroc\""), DB::raw("'null' as \"Prix_Vente_Ile_Maurice\""), DB::raw("'null' as \"Prix_Club_Ile_Maurice\""), DB::raw("'null' as \"Prix_Vente_ALGERIE\""), DB::raw("'null' as \"Prix_Club_ALGERIE\""), DB::raw("'null' as \"Prix_Vente_USA\""), DB::raw("'null' as \"Prix_Club_USA\""))
                                        ->leftJoin('orchestra_size_chart','orchestra_parcel_details.Size_Label','=','orchestra_size_chart.age')
                                        ->where('orchestra_parcel_details.UVC',$uvc)->where('orchestra_parcel_details.Season_Code',$season)
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
				
			}
			else
			{
				if($color_code != null && $color_code != "null" && $color_code != "")
				{
					$data['order_details'] = DB::table('orchestra_parcel_details')
                                        ->select('orchestra_parcel_details.*','orchestra_parcel_details.Size_Range_Label as Age','orchestra_parcel_details.Start_Print_Date	 as HangTag_Print_Date','orchestra_size_chart.cm',DB::raw("sum(orchestra_parcel_details.\"Quantity\") as \"Sum\""), DB::raw("'null' as \"PrixEurope\""), DB::raw("'null' as \"PrixClubEurope\""), DB::raw("'null' as \"Prix_Vente_Arabie_Saoudite\""), DB::raw("'null' as \"Prix_Club_Arabie_Saoudite\""), DB::raw("'null' as \"Prix_Vente_Canada\""), DB::raw("'null' as \"Prix_Club_Canada\""), DB::raw("'null' as \"Prix_Vente_Suisse\""), DB::raw("'null' as \"Prix_Club_Suisse\""), DB::raw("'null' as \"Prix_Vente_Maroc\""), DB::raw("'null' as \"Prix_Club_Maroc\""), DB::raw("'null' as \"Prix_Vente_Ile_Maurice\""), DB::raw("'null' as \"Prix_Club_Ile_Maurice\""), DB::raw("'null' as \"Prix_Vente_ALGERIE\""), DB::raw("'null' as \"Prix_Club_ALGERIE\""), DB::raw("'null' as \"Prix_Vente_USA\""), DB::raw("'null' as \"Prix_Club_USA\""))
                                        ->leftJoin('orchestra_size_chart','orchestra_parcel_details.Size_Label','=','orchestra_size_chart.age')
                                        ->where('orchestra_parcel_details.UVC',$uvc)
                                        ->where('orchestra_parcel_details.Color_Code',$color_code)->where('orchestra_parcel_details.Season_Code',$season)
                                        ->groupBy('orchestra_parcel_details.id','Size_Label','orchestra_size_chart.cm')
                                        ->orderBy('orchestra_parcel_details.id')
                                        ->get();
				}
				else
				{
					$data['order_details'] = DB::table('orchestra_parcel_details')
                                        ->select('orchestra_parcel_details.*','orchestra_parcel_details.Size_Range_Label as Age','orchestra_parcel_details.Start_Print_Date	 as HangTag_Print_Date','orchestra_size_chart.cm',DB::raw("sum(orchestra_parcel_details.\"Quantity\") as \"Sum\""), DB::raw("'null' as \"PrixEurope\""), DB::raw("'null' as \"PrixClubEurope\""), DB::raw("'null' as \"Prix_Vente_Arabie_Saoudite\""), DB::raw("'null' as \"Prix_Club_Arabie_Saoudite\""), DB::raw("'null' as \"Prix_Vente_Canada\""), DB::raw("'null' as \"Prix_Club_Canada\""), DB::raw("'null' as \"Prix_Vente_Suisse\""), DB::raw("'null' as \"Prix_Club_Suisse\""), DB::raw("'null' as \"Prix_Vente_Maroc\""), DB::raw("'null' as \"Prix_Club_Maroc\""), DB::raw("'null' as \"Prix_Vente_Ile_Maurice\""), DB::raw("'null' as \"Prix_Club_Ile_Maurice\""), DB::raw("'null' as \"Prix_Vente_ALGERIE\""), DB::raw("'null' as \"Prix_Club_ALGERIE\""), DB::raw("'null' as \"Prix_Vente_USA\""), DB::raw("'null' as \"Prix_Club_USA\""))
                                        ->leftJoin('orchestra_size_chart','orchestra_parcel_details.Size_Label','=','orchestra_size_chart.age')
                                        ->where('orchestra_parcel_details.UVC',$uvc)->where('orchestra_parcel_details.Season_Code',$season)
                                        ->groupBy('orchestra_parcel_details.id','Size_Label','orchestra_size_chart.cm')
                                        ->orderBy('orchestra_parcel_details.id')
                                        ->get();
				}
				
				
				//print_r($data['order_details']);
			}
			
            // get price details for po data
            foreach($data['order_details'] as $key => $value)
            {
                $price_data = DB::table('orchestra_parcel_quantity_details')
                                ->select('orchestra_parcel_quantity_details.*')
                                ->where('orchestra_parcel_quantity_details.UVC_TCO', $value->SKU_Code)
                                ->get();
                
                foreach ($price_data as $key1 => $value2) 
                {
                    $currency = $value2->Currency;

                    switch ($currency) {
                        case "EUR":
                            $value->PrixEurope  = $value2->Price;
                            $value->PrixClubEurope = $value2->Club_Price;
                            break;
                        case "SAR":
                            $value->Prix_Vente_Arabie_Saoudite  = $value2->Price;
                            $value->Prix_Club_Arabie_Saoudite = $value2->Club_Price;
                            break;
                        case "CAD":
                            $value->Prix_Vente_Canada  = $value2->Price;
                            $value->Prix_Club_Canada = $value2->Club_Price;
                            break;
                        case "CHF":
                            $value->Prix_Vente_Suisse  = $value2->Price;
                            $value->Prix_Club_Suisse = $value2->Club_Price;
                            break;
                        case "MAD":
                            $value->Prix_Vente_Maroc  = $value2->Price;
                            $value->Prix_Club_Maroc = $value2->Club_Price;
                            break;
                        case "MUR":
                            $value->Prix_Vente_Ile_Maurice  = $value2->Price;
                            $value->Prix_Club_Ile_Maurice = $value2->Club_Price;
                            break;
                        case "DZD":
                            $value->Prix_Vente_ALGERIE  = $value2->Price;
                            $value->Prix_Club_ALGERIE = $value2->Club_Price;
                            break;
                        case "USD":
                            $value->Prix_Vente_USA  = $value2->Price;
                            $value->Prix_Club_USA = $value2->Club_Price;
                            break;
                        default:
                            echo json_encode("Invalid Currency");
                    }
                }
            }

            $data['barcode'] = DB::table('orchestra_parcel_details')->select('*')->where('UVC',$uvc)->where('Season_Code',$season)->groupBy('id','BuyerPONo')->get();
        
           $total_qty = DB::table('orchestra_parcel_details')->select('*',DB::raw("sum(\"Quantity\") as total_qty"))->where('UVC',$uvc)->where('Season_Code',$season)->groupBy('id','UVC')->get();
            $data['total_qty']=$total_qty{0}->total_qty;
            // Licence_Code = DI for disney
            if($total_qty{0}->Licence_Code === 'DI')
            {
                $orchestra_template = DB::table('orchestra_template')->select('*')->where('id','5')->get();
                $data['orchestra_template']=$orchestra_template{0}->template_file;
                $data['orchestra_template_id']=5;
                
                $data['orchestra_wash_template_front']="/storage/app/template/Orchestra/WCL/sticker1-disney.svg";
                $data['orchestra_wash_template_back']="/storage/app/template/Orchestra/WCL/sticker2.svg";
                $data['orchestra_wash_template_middle']="/storage/app/template/Orchestra/WCL/sticker3.svg";
				
                $data['orchestra_wash_template_id']=5;
				
				$factory_code = $data['order_details']{0}->FactoryCode;
				$customer_id = DB::table('customers')->select('*')->where('factory_code',$factory_code)->get();

				if(count($customer_id) > 0)
				{
					$carton_moq = DB::table('price_taxation')->select('*')->where('customer_id',$customer_id{0}->id)->where('buyer_id',1)->where('template_id',5)->get();

					if(count($carton_moq) > 0)
					{
						$data['wash_moq']=$carton_moq{0}->moq;
					}
					else
					{
						$data['wash_moq']=1;
					}


				}
				else
				{
					$data['wash_moq']=1;
				}
				
				
                
                $orchestra_template_front = DB::table('orchestra_template')->select('*')->where('item_id','2')->where('disney',1)->get();
                $data['orchestra_template_front']=$orchestra_template_front;
				
				 $treeviewData= DB::table("orchestra_item_master")->select('id',DB::raw("(CASE WHEN ref_no = '' THEN item_name ELSE CONCAT(item_name,' - ',ref_no) END) as text"),'parent_id')->orWhere('id','103')->orWhere('tag_name','disney')->orderBy('item_name', 'asc')->get()->toArray();
            if($treeviewData){
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
                $orchestra_template = DB::table('orchestra_template')->select('*')->where('id','4')->get();
                $data['orchestra_template']=$orchestra_template{0}->template_file;
                $data['orchestra_template_id']=4;
                
                $data['orchestra_wash_template_front']="/storage/app/template/Orchestra/WCL/sticker1.svg";
                $data['orchestra_wash_template_back']="/storage/app/template/Orchestra/WCL/sticker2.svg";
                $data['orchestra_wash_template_middle']="/storage/app/template/Orchestra/WCL/sticker3.svg";
                $data['orchestra_wash_template_id']=4;
				
				$factory_code = $data['order_details']{0}->FactoryCode;
				$customer_id = DB::table('customers')->select('*')->where('factory_code',$factory_code)->get();

				if(count($customer_id) > 0)
				{
					$carton_moq = DB::table('price_taxation')->select('*')->where('customer_id',$customer_id{0}->id)->where('buyer_id',1)->where('template_id',4)->get();

					if(count($carton_moq) > 0)
					{
						$data['wash_moq']=$carton_moq{0}->moq;
					}
					else
					{
						$data['wash_moq']=1;
					}


				}
				else
				{
					$data['wash_moq']=1;
				}
                
                $orchestra_template_front = DB::table('orchestra_template')->select('*')->where('item_id','2')->where('disney', 0)->get();
                $data['orchestra_template_front']=$orchestra_template_front;
				
				
				 $treeviewData= DB::table("orchestra_item_master")->select('id',DB::raw("(CASE WHEN ref_no = '' THEN item_name ELSE CONCAT(item_name,' - ',ref_no) END) as text"),'parent_id')->orWhere('id','103')->orWhere('tag_name','hang')->orWhere('id','104')->orWhere('id','105')->orderBy('item_name', 'asc')->get()->toArray();
            if($treeviewData){
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
			
			$treeviewspecial= DB::table("orchestra_item_master")->select('id',DB::raw("(CASE WHEN ref_no = '' THEN item_name ELSE CONCAT(item_name,' - ',ref_no) END) as text"),'parent_id')->orWhere('id','103')->orWhere('id','104')->orWhere('id','105')->orWhere('tag_name','special')->orderBy('item_name', 'asc')->get()->toArray();
            if($treeviewspecial){
                        $childs = array();
                        foreach($treeviewspecial as &$item) 
                        {
                        $childs[$item->parent_id][] = &$item;
                        unset($item);
                        }
                        foreach($treeviewspecial as &$item) 
                        {
                            if (isset($childs[$item->id]))
                            {
                                $item->children = $childs[$item->id];
                                unset($item);
                            }
                        }
                        $tree = $childs[0];
                        $data['treeviewspecial']=$tree;

               }
		
		 $treeviewheat_transfer= DB::table("orchestra_item_master")->select('id',DB::raw("(CASE WHEN ref_no = '' THEN item_name ELSE CONCAT(item_name,' - ',ref_no) END) as text"),'parent_id')->orWhere('id','1')->orWhere('id','62')->orWhere('id','63')->orWhere('tag_name','heat_transfer')->orderBy('item_name', 'asc')->get()->toArray();
            if($treeviewheat_transfer){
                        $childs = array();
                        foreach($treeviewheat_transfer as &$item) 
                        {
                        $childs[$item->parent_id][] = &$item;
                        unset($item);
                        }
                        foreach($treeviewheat_transfer as &$item) 
                        {
                            if (isset($childs[$item->id]))
                            {
                                $item->children = $childs[$item->id];
                                unset($item);
                            }
                        }
                        $tree = $childs[0];
                        $data['treeviewheat_transfer']=$tree;

               }
		
		$treeviewmain_label= DB::table("orchestra_item_master")->select('id',DB::raw("(CASE WHEN ref_no = '' THEN item_name ELSE CONCAT(item_name,' - ',ref_no) END) as text"),'parent_id')->orWhere('id','1')->orWhere('id','2')->orWhere('id','10')->orWhere('id','13')->orWhere('tag_name','main')->orWhere('tag_name','MS')->orWhere('tag_name','main_size')->orderBy('item_name', 'asc')->get()->toArray();
            if($treeviewmain_label){
                        $childs = array();
                        foreach($treeviewmain_label as &$item) 
                        {
                        $childs[$item->parent_id][] = &$item;
                        unset($item);
                        }
                        foreach($treeviewmain_label as &$item) 
                        {
                            if (isset($childs[$item->id]))
                            {
                                $item->children = $childs[$item->id];
                                unset($item);
                            }
                        }
                        $tree = $childs[0];
                        $data['treeviewmain_label']=$tree;

               }
		
		$treeviewsize_label= DB::table("orchestra_item_master")->select('id',DB::raw("(CASE WHEN ref_no = '' THEN item_name ELSE CONCAT(item_name,' - ',ref_no) END) as text"),'parent_id')->orWhere('id','1')->orWhere('id','2')->orWhere('id','10')->orWhere('id','13')->orWhere('tag_name','size')->orWhere('tag_name','MS')->orWhere('tag_name','main_size')->orderBy('item_name', 'asc')->get()->toArray();
            if($treeviewsize_label){
                        $childs = array();
                        foreach($treeviewsize_label as &$item) 
                        {
                        $childs[$item->parent_id][] = &$item;
                        unset($item);
                        }
                        foreach($treeviewsize_label as &$item) 
                        {
                            if (isset($childs[$item->id]))
                            {
                                $item->children = $childs[$item->id];
                                unset($item);
                            }
                        }
                        $tree = $childs[0];
                        $data['treeviewsize_label']=$tree;

               }
		$code=$data['order_details']{0}->Family_Code;
		$data['orchestra_hang_tag_orders']=DB::table('orchestra_hang_tag_orders')->select('*',DB::raw("'Hang Tag' as \"TemplateName\""))->where('id',$row_id)->get();
        if(count($data['orchestra_hang_tag_orders']) > 0)
        {
            $data['orchestra_hang_tag_orders_qty']=DB::table('orchestra_hang_tag_orders_qty')->select('*')->where('oht_id',$data['orchestra_hang_tag_orders']{0}->id)->orderBy('orchestra_hang_tag_orders_qty.parcel_id','asc')->get();
			
			if(count($data['orchestra_hang_tag_orders']) > 0)
        	{
				$data['hang_erp_details']= DB::table('erp_order_details')->where('order_qnty_table_name','ilike','orchestra_hang_tag_orders_qty')->join('erp_status', 'erp_status.id', 'erp_order_details.status')->where('order_qnty_table_id','=', $data['orchestra_hang_tag_orders_qty']{0}->id)->select('erp_order_details.id','erp_status.status_name')->first();
			}
			else
			{
				$data['orchestra_hang_tag_orders_qty']="";
			}
			
			 
        }
        else
        {
            $data['orchestra_hang_tag_orders_qty']="";
        }
         $data['orchestra_special_tag_orders']=DB::table('orchestra_special_tag_orders')->select('*',DB::raw("'Special Tag' as \"TemplateName\""))->where('id',$row_id)->get();
        if(count($data['orchestra_special_tag_orders']) > 0)
        {
            $data['orchestra_special_tag_orders_qty']=DB::table('orchestra_special_tag_orders_qty')->select('*')->where('ost_id',$data['orchestra_special_tag_orders']{0}->id)->orderBy('orchestra_special_tag_orders_qty.id','asc')->get();
			
			if(count($data['orchestra_special_tag_orders_qty']) > 0)
        	{
				 $data['special_erp_details']= DB::table('erp_order_details')->where('order_qnty_table_name','ilike','orchestra_special_tag_orders_qty')->join('erp_status', 'erp_status.id', 'erp_order_details.status')->where('order_qnty_table_id','=', $data['orchestra_special_tag_orders_qty']{0}->id)->select('erp_order_details.id','erp_status.status_name')->first();
			}
			else
			{
				$data['orchestra_special_tag_orders_qty']="";
			}
			
			
        }
        else
        {
            $data['orchestra_special_tag_orders_qty']="";
        }     
		
		$data['orchestra_heat_transfer_orders']=DB::table('orchestra_heat_transfer_orders')->select('*',DB::raw("'Heat Transfer' as \"TemplateName\""))->where('id',$row_id)->get();
        if(count($data['orchestra_heat_transfer_orders']) > 0)
        {
            $data['orchestra_heat_transfer_orders_qty']=DB::table('orchestra_heat_transfer_orders_qty')->select('*')->where('oht_id',$data['orchestra_heat_transfer_orders']{0}->id)->orderBy('orchestra_heat_transfer_orders_qty.parcel_id','asc')->get();
			
			if(count($data['orchestra_heat_transfer_orders_qty']) > 0)
        	{
				$data['heat_erp_details']= DB::table('erp_order_details')->where('order_qnty_table_name','ilike','orchestra_heat_transfer_orders_qty')->join('erp_status', 'erp_status.id', 'erp_order_details.status')->where('order_qnty_table_id','=', $data['orchestra_heat_transfer_orders_qty']{0}->id)->select('erp_order_details.id','erp_status.status_name')->first();
			}
			else
			{
				 $data['orchestra_heat_transfer_orders_qty']="";
			}
			
			
        }
        else
        {
            $data['orchestra_heat_transfer_orders_qty']="";
        }
		
		$data['orchestra_main_label_orders']=DB::table('orchestra_main_label_orders')->select('*',DB::raw("'Main Label' as \"TemplateName\""))->where('id',$row_id)->get();
        if(count($data['orchestra_main_label_orders']) > 0)
        {
            $data['orchestra_main_label_orders_qty']=DB::table('orchestra_main_label_orders_qty')->select('*')->where('oml_id',$data['orchestra_main_label_orders']{0}->id)->orderBy('orchestra_main_label_orders_qty.id','asc')->get();
			
			if(count($data['orchestra_main_label_orders_qty']) > 0)
        	{
				$data['main_erp_details']= DB::table('erp_order_details')->where('order_qnty_table_name','ilike','orchestra_main_label_orders_qty')->join('erp_status', 'erp_status.id', 'erp_order_details.status')->where('order_qnty_table_id','=', $data['orchestra_main_label_orders_qty']{0}->id)->select('erp_order_details.id','erp_status.status_name')->first();
			}
			else
			{
				$data['orchestra_main_label_orders_qty']="";
			}
			
			 
        }
        else
        {
            $data['orchestra_main_label_orders_qty']="";
        }
        
		$data['orchestra_size_label_orders']=DB::table('orchestra_size_label_orders')->select('*',DB::raw("'Size Label' as \"TemplateName\""))->where('id',$row_id)->get();
        if(count($data['orchestra_size_label_orders']) > 0)
        {
            $data['orchestra_size_label_orders_qty']=DB::table('orchestra_size_label_orders_qty')->select('*')->where('osl_id',$data['orchestra_size_label_orders']{0}->id)->orderBy('orchestra_size_label_orders_qty.parcel_id','asc')->get();
			
			if(count($data['orchestra_size_label_orders_qty']) > 0)
        	{
				$data['size_erp_details']= DB::table('erp_order_details')->where('order_qnty_table_name','ilike','orchestra_size_label_orders_qty')->join('erp_status', 'erp_status.id', 'erp_order_details.status')->where('order_qnty_table_id','=', $data['orchestra_size_label_orders_qty']{0}->id)->select('erp_order_details.id','erp_status.status_name')->first();
			}
			else
			{
				$data['orchestra_size_label_orders_qty']="";
			}
			
			
        }
        else
        {
            $data['orchestra_size_label_orders_qty']="";
        }
			
		$data['orchestra_solid_carton_sticker_orders']=DB::table('orchestra_solid_carton_sticker_orders')->select('*',DB::raw("'Solid Carton Stickers' as \"TemplateName\""))->where('id',$row_id)->get();
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
			
		$data['orchestra_solid_poly_bag_sticker_orders']=DB::table('orchestra_solid_poly_bag_sticker_orders')->select('*',DB::raw("'Solid Poly Bag Stickers' as \"TemplateName\""))->where('id',$row_id)->get();
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
		
		$data['orchestra_standard_carton_sticker_orders']=DB::table('orchestra_standard_carton_sticker_orders')->select('*',DB::raw("'Standard Carton Stickers' as \"TemplateName\""))->where('id',$row_id)->get();
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
        
        $data['orchestra_standard_poly_bag_sticker_orders']=DB::table('orchestra_standard_poly_bag_sticker_orders')->select('*',DB::raw("'Standard Poly Bag Stickers' as \"TemplateName\""))->where('id',$row_id)->get();
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
		$data['orchestra_wash_care_label_orders']=DB::table('orchestra_wash_care_label_orders')->select('*','orchestra_wash_care_label_orders.template_name as TemplateName')->where('id',$row_id)->get();
        foreach($data['orchestra_wash_care_label_orders'] as $value1)
        {
            $orchestra_wash_label_orders_qty=DB::table('orchestra_wash_care_label_orders_qty')->select('*')->where('owcl_id',$value1->id)->orderBy('orchestra_wash_care_label_orders_qty.id','asc')->get();
            $value1->qty=$orchestra_wash_label_orders_qty;
			
			if(count($orchestra_wash_label_orders_qty) > 0)
        	{
				$data['wash_erp_details']= DB::table('erp_order_details')->where('order_qnty_table_name','ilike','orchestra_wash_care_label_orders_qty')->join('erp_status', 'erp_status.id', 'erp_order_details.status')->where('order_qnty_table_id','=', $orchestra_wash_label_orders_qty{0}->id)->select('erp_order_details.id','erp_status.status_name')->first();
			}
			else
			{
				 $data['orchestra_wash_care_label_orders_qty']="";
			}
			
			
            
             $orchestra_wash_care_composition=DB::table('orchestra_wash_care_composition')->leftJoin('orchestra_compositions','orchestra_compositions.id','=','orchestra_wash_care_composition.composition_id')->leftJoin('orchestra_parts','orchestra_parts.id','=','orchestra_wash_care_composition.parts_id')->select('orchestra_wash_care_composition.*','orchestra_compositions.french','orchestra_compositions.english','orchestra_compositions.spanish','orchestra_compositions.portuguese','orchestra_compositions.italian','orchestra_compositions.dutch','orchestra_compositions.german','orchestra_compositions.russian','orchestra_compositions.chinese','orchestra_compositions.arabic','orchestra_compositions.greek','orchestra_compositions.turkish','orchestra_parts.french as parts_french','orchestra_parts.english as parts_english','orchestra_parts.spanish as parts_spanish','orchestra_parts.portuguese as parts_portuguese','orchestra_parts.italian as parts_italian','orchestra_parts.dutch as parts_dutch','orchestra_parts.german as parts_german','orchestra_parts.russian as parts_russian','orchestra_parts.chinese as parts_chinese','orchestra_parts.arabic as parts_arabic','orchestra_parts.greek as parts_greek','orchestra_parts.turkish as parts_turkish')->orderBy('orchestra_wash_care_composition.id','asc')->where('owcl_id',$value1->id)->get();
             $value1->composition=$orchestra_wash_care_composition;
            $care_instructions=array();
            foreach(explode(',',$value1->care_instruction_id) as $value2)
            {
                array_push($care_instructions,$value2);
            }
            $value1->care_instruction=$care_instructions;
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

		$data['orchestra_care_symbols'] = DB::table('orchestra_care_symbols')->where('is_enabled',1)->select('*')->get();
		$data['orchestra_composition'] = DB::table('orchestra_compositions')->where('is_enabled',1)->select('*')->get();
		$data['orchestra_countries'] = DB::table('orchestra_countries')->where('is_enabled',1)->select('*')->get();
		$data['orchestra_care_instruction'] = DB::table('orchestra_care_instruction')->where('is_enabled',1)->select('*')->get(); 
		$data['orchestra_parts'] = DB::table('orchestra_parts')->where('is_enabled',1)->select('*')->get();
		$pro_family = DB::table('orchestra_product_family')->where('is_enabled',1)->select('*')->where('code',$code)->get();
		if(count($pro_family) > 0)
		{
			$data['product_family'] = DB::table('orchestra_product_family')->select('*')->where('code',$code)->get();
		}
		else
		{
			$data['product_family'] ="";
		}
		echo json_encode($data);
        }
        catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
			return $result;
        }
        
    }
}