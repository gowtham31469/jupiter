<?php
namespace App\Http\Controllers\ErpUser\Jobmanager;

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
use App\OrchestraAgeGroup;
use App\OrchestraWashCare;
use App\OrchestraWashCareQty;
use Response;
use View;
use Auth;
use DB;
use Session;
use App\ErpOrders as Erp;
use App\ErpOrderDetails as ErpOrderDetails;
use Carbon\Carbon;
use PDF;
use App\Classes\JobcardPDFHeaderFooter;
use App\Classes\FPDF_Merge;
use File;
use Config;
use App\History;
use App\Buyer;
use App\FinancialYearResetDate;
use App\Classes\TescoMaterialQuery;
use App\Classes\JobcardIDGenerator;

class JobManagerController extends Controller
{
	private $current_date;
	private $erp_jc_status_id;
	private $order_ap_status_id;
	private $tesco_material_obj;
	private $jobcard_id_gen_obj;

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('erpuser');
		// get the current time
		$this->current_date = Carbon::now('Asia/Kolkata')->format('Y-m-d');

		// Get Erp status table id for 'Moved To JC Queue' status.
		$this->erp_jc_status_id = DB::table('erp_status')->where('status_name', 'Moved To JC Queue')->value('id');
		 
		// Get order status table id for 'Order Approved' status.
		$this->order_ap_status_id = DB::table('orders_status')->where('status_name', 'Order Approved')->value('id');

		$this->tesco_material_obj = new TescoMaterialQuery();
		$this->jobcard_id_gen_obj = new JobcardIDGenerator();
	}
		
	/**
	 * Show the Jobcard  Manager List View.
	 * @author [Gowtham] <[gowtham@wizroots.com]>
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		try {
			$erp_user_id = Auth::guard('erpuser')->id();
			
			$companies   = 	DB::table('erpuser_company_mapping')
									->join('company','erpuser_company_mapping.company','company.id')
									->where('erpuser_company_mapping.erp_user',$erp_user_id)
									->select('erpuser_company_mapping.*','company.name', 'company.logo')
									->get();

			if($companies)
			{
				$company_id   = DB::table('company')
								->select('company.*')
								->where('company.id', $companies[0]->company)
								->first();
			}
			else
			{
				return view('erp.home');
			}

			if(session()->get('company') == "" && session()->get('company') == null)
			{
				session()->put('company', $company_id->id);
				session()->put('company_name', strtolower( str_replace(' ','',$company_id->name)) );
			}

			// full access to erp admin
			if($erp_user_id != 1)
			{
				$templates   = 		DB::table('erpusers_template_mapping')
									->join('buyers','erpusers_template_mapping.buyer','buyers.id')
									->where('erpusers_template_mapping.company',session()->get('company'))
									->where('erpusers_template_mapping.erp_user',$erp_user_id)
									->select('erpusers_template_mapping.buyer as buyer_id', 'erpusers_template_mapping.templates', 'buyers.template_table','buyers.item_master_table')
									->distinct('erpusers_template_mapping.templates')
									->get();
			}
			else
			{
				$templates   = 		DB::table('erpusers_template_mapping')
									->join('buyers','erpusers_template_mapping.buyer','buyers.id')
									->where('erpusers_template_mapping.company',session()->get('company'))
									->select('erpusers_template_mapping.buyer as buyer_id', 'erpusers_template_mapping.templates', 'buyers.template_table','buyers.item_master_table')
									->distinct('erpusers_template_mapping.templates')
									->get();
			}
			
			$new_orders = array();
			$i=1;
			foreach($templates as $value)
			{
				$arr_template =  DB::table($value->template_table)
								->where('hierarchy_id',$value->templates)
								//->where('flag',1)
								->distinct('hierarchy_id')
								->pluck('item_id');
				if($arr_template)
				{
					$new_orders[$value->buyer_id."-".$i] = $arr_template;
				}
				$i++;
			}
			
			$orders_erp = array();
			foreach($new_orders as $key => $values)
			{
				$split_key = explode('-', $key);
				$buyer_id = $split_key[0];
				$this->buyer_name = strtolower(DB::table('buyers')->where('id', $buyer_id)->value('buyer_name'));
				
				foreach($values as $valuess)
				{
					$erp_order_details = Erp::join('erp_order_details', 'erp_order_details.erp_order_id', 'erp_orders.id')
											->join('customers', 'erp_orders.customer_id', 'customers.id')
											->join('buyers', 'erp_orders.buyer_id', 'buyers.id')
											->join('erp_status', 'erp_orders.status', 'erp_status.id')
											->join($this->buyer_name.'_template', $this->buyer_name.'_template.item_id', 'erp_order_details.template_id')
											->where('erp_order_details.template_id', '=', $valuess)
											// ->where('erp_orders.status', '=', $erp_status_id)
											->where('erp_order_details.deleted_at', null)
											->where('erp_order_details.status', '=', $this->erp_jc_status_id)
											->where('erp_order_details.order_status_id', '=', $this->order_ap_status_id)
											->where('buyers.id', $buyer_id)
											->orderBy('erp_order_details.erp_order_id', 'asc')
											->groupBy('erp_order_details.template_id', 'erp_orders.id', 'customers.company_name', 'erp_status.status_name', 'buyers.id', 'erp_order_details.reference_name','erp_order_details.order_qnty_table_name','erp_order_details.order_qnty_table_id', 'erp_order_details.style_no', 'erp_order_details.buyer_po_number', 'erp_order_details.delivery_date', 'erp_order_details.erp_order_id',$this->buyer_name.'_template.template_file', 'erp_order_details.order_type', 'erp_order_details.created_at','erp_order_details.uuid', 'erp_order_details.zone')
											->select('erp_orders.id', 'erp_order_details.template_id','erp_orders.customer_id', 'erp_orders.buyer_id', 'erp_orders.po_no', 'erp_orders.po_file', 'erp_orders.status', 'erp_orders.erp_id','erp_order_details.reference_name','erp_order_details.order_qnty_table_name','erp_order_details.order_qnty_table_id', 'erp_order_details.style_no', 'erp_order_details.buyer_po_number', 'erp_order_details.delivery_date', 'customers.company_name', 'erp_status.status_name', 'buyers.buyer_name', 'buyers.id as buyer_id',$this->buyer_name.'_template.template_file', 'erp_order_details.order_type', 'erp_order_details.created_at', 'erp_order_details.uuid', 'erp_order_details.zone')
											->get()->toArray();
					
					if(count($erp_order_details) > 0)
					{
						foreach($erp_order_details as $erp_order_det)
						{
							// format delivery date to dd-mm-yyyy
							if($erp_order_det["delivery_date"] != null)
							{
                                 $erp_order_det["delivery_date"] = Carbon::parse($erp_order_det["delivery_date"])->format('d-m-Y');
							}
							
							if($erp_order_det["order_qnty_table_name"] == "orchestra_hang_tag_orders_qty")
							{
								$fkey = DB::table('orchestra_hang_tag_orders_qty')
								->where('id',$erp_order_det["order_qnty_table_id"])
								->first()
								->oht_id;

								$row = DB::table('orchestra_hang_tag_orders')
								->where('id',$fkey)->first();
								
								$disney = DB::table('orchestra_parcel_details')
								->where('id',$row->orchestra_parcel_details_id)->first();
								
								$erp_order_det["Disney"] = $disney->Licence_Code;
								
								$erp_order_det["frontPath"] = DB::table('orchestra_template')
								->where('item_id',$row->template_front_id)
								->where('flag',1)
								->first()
								->template_file;

								$erp_order_det["backPath"] = DB::table('orchestra_template')
								->where('item_id',$row->template_front_id)
								->where('flag',0)
								->first()
								->template_file;
								
							}
							
							if($erp_order_det["order_qnty_table_name"] == "orchestra_wash_care_label_orders_qty")
							{
								$fkey = DB::table('orchestra_wash_care_label_orders_qty')
								->where('id',$erp_order_det["order_qnty_table_id"])
								->first()
								->owcl_id;

								$row = DB::table('orchestra_wash_care_label_orders')
								->where('id',$fkey)->first();
								
								$disney = DB::table('orchestra_parcel_details')
								->where('id',$row->orchestra_parcel_details_id)->first();
								
								$erp_order_det["Disney"] = $disney->Licence_Code;
								
								$erp_order_det["frontPath"] = DB::table('orchestra_template')
								->where('item_id',$row->template_id)
								->where('flag',1)
								->first()
								->template_file;

								$erp_order_det["backPath"]='/storage/app/template/Orchestra/WCL/sticker2.svg';
								$erp_order_det["middlePath"]='/storage/app/template/Orchestra/WCL/sticker3.svg';
							}

							$orders_erp[$erp_order_det['uuid']] = $erp_order_det;
						}
						
					}
				}
				
			}

			$erp_dispatch_mode = DB::table('erp_dispatch_mode')
			->where('is_enabled', true)
			->pluck('disp_mode', 'id')
			->all();
			$erp_finish_type = DB::table('erp_finish_type')
			->where('is_enabled', true)
			->pluck('finish_type', 'id')
			->all();
			$erp_machines = DB::table('erp_machines')
			->where('is_enabled', true)
			->pluck('machine', 'id')
			->all();
			$erp_teeth_no = DB::table('erp_teeth_no')
			->where('is_enabled', true)
			->pluck('teeth_no', 'id')
			->all();

			asort($orders_erp);

			return view(
				'erp.Jobmanager.jobmanager',
				[
					'orders' => $orders_erp,
					'companies' => $companies,
					'disp_modes' => $erp_dispatch_mode,
					'finish_types' => $erp_finish_type,
					'machines' => $erp_machines,
					'teeth_nos' => $erp_teeth_no
				]
			);
		}
		catch (\Exception $e)
		{
		return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
		}
	}
	/**
	 * Get order deatils.
	 * @param order details id
	 * @author Gowtham <gowtham@wizroots.com>
	 * @return \Illuminate\Http\Response
	 */
	public function getOrderDetails(Request $request)
	{

		$ref_name = $request['ref_name'];
		$style_no = $request['style_no'];
		$buyer_no = $request['buyer_no'];
		$id = $request['id'];
		$order_type = $request['order_type'];
		$created_at = $request['created_at'];
		$table_name_print = $request['table_name_print'];

		if($request->buyer=="orchestra")
		{
			$order_details  = 	DB::table('erp_order_details')
							->where('erp_order_details.style_no',$style_no)
							->where('erp_order_details.erp_order_id',$id)
							->where('erp_order_details.reference_name',$ref_name)

							->where(function($query) use($buyer_no) 
							{
							$query->when($buyer_no!="null", function ($q) use($buyer_no) 
							{
							return $q->where('erp_order_details.buyer_po_number',$buyer_no);
							});
							})

							->where('erp_order_details.status','!=',4)
							->where('erp_order_details.order_status_id','!=',3)
							->where('erp_order_details.deleted_at', null)
							->orderBy('erp_order_details.id', 'desc')
							->select('*')
							->get();

			$order_Qty_table = $order_details[0]->order_qnty_table_name;
			$ordertable = substr($order_Qty_table,0,strrpos($order_Qty_table,"_"));
				
			$stringArr=explode("_", $order_Qty_table);
			$target_id='';

			for($i=0; $i <sizeof($stringArr)-2 ; $i++) { 
			$target_id.=$stringArr[$i][0];
			}
			$target_id=$target_id."_id";

			$parentTable_id = DB::table($order_Qty_table)
							->where('id',$order_details[0]->order_qnty_table_id)
							->first()->$target_id;
			switch ($ordertable) 
			{	

				case 'orchestra_wash_care_label_orders' :

						$res = OrchestraWashCare::with(['washcarelabelQty' => function ($query) {
						$query->leftJoin('orchestra_size_chart as osc', 'osc.age', 'orchestra_wash_care_label_orders_qty.size')->select(DB::raw('distinct on ("orchestra_wash_care_label_orders_qty".uvc_tco) "uvc_tco"'),'orchestra_wash_care_label_orders_qty.*','osc.*');
						}])->where('orchestra_wash_care_label_orders.id',$parentTable_id)->get();
					
					$res->map(function ($res) use($parentTable_id) {
						$res->reference_name = "Orchestra WashCare";
						$res->parentId = $parentTable_id;

						$res->cofreet_symbol1 = DB::table('orchestra_care_symbols')->where('id',$res->washing_id)->first()->cofreet_symbols;
						$res->astm_symbol1 = DB::table('orchestra_care_symbols')->where('id',$res->washing_id)->first()->astms_symbol;

						$res->cofreet_symbol2 = DB::table('orchestra_care_symbols')->where('id',$res->whitening_id)->first()->cofreet_symbols;
						$res->astm_symbol2 = DB::table('orchestra_care_symbols')->where('id',$res->whitening_id)->first()->astms_symbol;

						$res->cofreet_symbol3 = DB::table('orchestra_care_symbols')->where('id',$res->dryer_id)->first()->cofreet_symbols;
						$res->astm_symbol3 = DB::table('orchestra_care_symbols')->where('id',$res->dryer_id)->first()->astms_symbol;

						$res->cofreet_symbol4 = DB::table('orchestra_care_symbols')->where('id',$res->ironing_id)->first()->cofreet_symbols;
						$res->astm_symbol4 = DB::table('orchestra_care_symbols')->where('id',$res->ironing_id)->first()->astms_symbol;

						$res->cofreet_symbol5 = DB::table('orchestra_care_symbols')->where('id',$res->cleaning_id)->first()->cofreet_symbols;
						$res->astm_symbol5 = DB::table('orchestra_care_symbols')->where('id',$res->cleaning_id)->first()->astms_symbol;

						});

				break;

				case 'orchestra_hang_tag_orders':

					$res = OrchestraHangTag::with(array('hangtagqty' => function($query) {
								$query->leftJoin('orchestra_size_chart as osc', 'osc.age', 'orchestra_hang_tag_orders_qty.size')
								->leftJoin('orchestra_parcel_details as opd', 'opd.SKU_Code', 'orchestra_hang_tag_orders_qty.uvc_tco')
								->select(DB::raw('distinct on ("orchestra_hang_tag_orders_qty".uvc_tco) "uvc_tco"'),'orchestra_hang_tag_orders_qty.*','osc.*','Size_Range_Label');
								}))
								->where('id', '=', $parentTable_id)
								->get();


					$res->map(function ($res) use($parentTable_id) {
					foreach($res->hangtagqty as $key => $value) 
					{
					$CountryCodes =array(0=>'DZD',1=>'CAD',2=>'CHF',3=>'MAD',4=>'MUR',5=>'USD',6=>'EUR');
						
					$countries = ['DZD','CAD','CHF','MAD','MUR','USD','EUR'];
					$price = ['algeria_price','canada_price','swiss_price','morocco_price'
							,'morris_price','us_price','eur_price'];
					$clubPrice = ['algeria_club_price','canada_club_price','swiss_club_price','morroco_club_price'
							,'morris_club_price','us_club_price','eur_club_price'];
					$PriceArr = array();

					foreach($CountryCodes as $key => $country)
					{ 
					$q= OrchestraHangTag::leftJoin('orchestra_parcel_quantity_details', function ($join) use($value,$country,$parentTable_id)
					{
					$join->on('orchestra_hang_tag_orders.uvc', '=', 'orchestra_parcel_quantity_details.UVC')
					->select('Price','Club_Price')
					->where('Currency',$country)
					->where('Size', $value->size);
					})->where('orchestra_hang_tag_orders.id', '=', $parentTable_id)
					->select('Price','Club_Price')
					->get();

					array_push($PriceArr, array("Currency"=>$countries[$key],"Price"=>$q->first()->Price, "Club_Price" => $q->first()->Club_Price));
					}

					$value->prices =  $PriceArr;
					$PriceArr = array();
					}
					});

					$res['reference_name'] = "Orchestra HangTag";
					$res['theme'] = OrchestraParcelDetails::where('id',$res[0]->orchestra_parcel_details_id)->first()->Theme;

				break;
			
				case 'orchestra_solid_poly_bag_sticker_orders':

					$res = OrchestraSolidPolySticker::with(['solidpolystickerqty' => 
						function($query)
						{  
							$query->groupBy('size')
								  ->groupBy('orchestra_solid_poly_bag_sticker_orders_qty.id');
						}])
						->where('orchestra_solid_poly_bag_sticker_orders.id', '=', $parentTable_id)
						->get();

						$res->map(function ($res) {
						$res->reference_name = "Solid Polybag Stickers";
						$res->age_group = OrchestraAgeGroup::where('id',$res->group_id)->first()->color;
							$color_code = DB::table('orchestra_color_code')->select('*')->where('code',$res->color_code)->first();
							if($color_code)
							{
								$res->color_code =$color_code->french;
							}
							else
							{
								$res->color_code = "";
							}
							 
						});
				break;


				case 'orchestra_solid_carton_sticker_orders':

					$res = OrchestraSolidCartonSticker::with(['solidcartonstickerqty' => 
						function($query)
						{  
							$query->groupBy('size')
								  ->groupBy('orchestra_solid_carton_sticker_orders_qty.id');
						}])
					->where('orchestra_solid_carton_sticker_orders.id', '=', $parentTable_id)
					->get();
					
						$res->map(function ($res) {
						$res->theme = OrchestraParcelDetails::where('id',$res->orchestra_parcel_details_id)->first()->Theme;
						$res->reference_name = "Solid Carton Stickers";
						$res->age_group = OrchestraAgeGroup::where('id',$res->group_id)->first()->color;
							$color_code = DB::table('orchestra_color_code')->select('*')->where('code',$res->color_code)->first();
							if($color_code)
							{
								$res->color_code =$color_code->french;
							}
							else
							{
								$res->color_code = "";
							}
						});
						
				break;

				case 'orchestra_standard_carton_sticker_orders':

					$res = OrchestraStdCartonSticker::with(['stdcartonstickerqty' => 
						function($query)
						{  
							$query->groupBy('size')
								  ->groupBy('orchestra_standard_carton_sticker_orders_qty.id');
						}])
						->where('orchestra_standard_carton_sticker_orders.id', '=', $parentTable_id)
						->get();
					
						$res->map(function ($res) {
						$res->theme = OrchestraParcelDetails::where('id',$res->orchestra_parcel_details_id)->first()->Theme;
						$res->reference_name = "Std Carton Stickers";
						$res->age_group = OrchestraAgeGroup::where('id',$res->group_id)->first()->color;
						$res->parcelCode = OrchestraParcelDetails::where('id',$res->orchestra_parcel_details_id)->first()->Standard_Code;
							
							$color_code = DB::table('orchestra_color_code')->select('*')->where('code',$res->color_code)->first();
							if($color_code)
							{
								$res->color_code =$color_code->french;
							}
							else
							{
								$res->color_code = "";
							}
						});
				break;

				case 'orchestra_standard_poly_bag_sticker_orders':

					$res = OrchestraStdPolySticker::with(['stdpolystickerqty' => 
						function($query)
						{  
							$query->groupBy('size')
								  ->groupBy('orchestra_standard_poly_bag_sticker_orders_qty.id');
						}])
					    ->where('orchestra_standard_poly_bag_sticker_orders.id', '=', $parentTable_id)
						->get();
						
				
						$sizeArr=OrchestraParcelDetails::where('UVC',$res[0]->uvc)
												->where('Season_Code',$res[0]->season_code)
												->select('Quantity','Size','Size_Breakdown')
												->orderBy('id','asc')
												->get()->groupBy('Size')
												->toArray();
								$qtyAr=[];				
								$sizAr=[];
								$sizBrkAr=[];
							foreach ($sizeArr as $key => $value)
							 {
									$qty=0;				
									foreach ($value as $key1 => $value1) {
											$qty+=$value1['Quantity'];		
												}	
												array_push($qtyAr, $qty);
												array_push($sizAr, $key);
								if(count($value) > 0 )
								{
									array_push($sizBrkAr, $value[0]['Size_Breakdown']);
								}
												
							 }					


						$res->map(function ($res) use($sizeArr,$qtyAr,$sizAr,$sizBrkAr) {
						$res->theme = OrchestraParcelDetails::where('id',$res->orchestra_parcel_details_id)->first()->Theme;
						$res->parcelCode = OrchestraParcelDetails::where('id',$res->orchestra_parcel_details_id)->first()->Standard_Code;
						$res->reference_name = "Std Poly Stickers";
						$res->age_group = OrchestraAgeGroup::where('id',$res->group_id)->first()->color;
						$res->sizeArr = $sizAr;
						$res->qtyArr = $qtyAr;
						$res->sizBrkAr = $sizBrkAr;
							$color_code = DB::table('orchestra_color_code')->select('*')->where('code',$res->color_code)->first();
							if($color_code)
							{
								$res->color_code =$color_code->french;
							}
							else
							{
								$res->color_code = "";
							}
						});
						
				break;

				default:
				# code...
				break;
			}
	
		return response()->json(['error'=> false,'order_details' =>$res],200);
		}


		else{
			if($table_name_print == "tesco_washcare_orders")
			{
				$order_details  = 	DB::table('erp_order_details')
									->where('erp_order_details.style_no',$style_no)
									->where('erp_order_details.erp_order_id',$id)
									->where('erp_order_details.reference_name',$ref_name)
									->where('erp_order_details.buyer_po_number',$buyer_no)
									->where('erp_order_details.order_type',$order_type)
									->where('erp_order_details.status','!=',4)
									->where('erp_order_details.order_status_id','!=',3)
									->where('erp_order_details.deleted_at', null)
									->orderBy('erp_order_details.order_qnty_table_id', 'asc')
									->select('*')
									->get();
									if(sizeof($order_details) > 0)
									{
										$tesco_item_master=DB::table('tesco_item_master')->where('id',$order_details[0]->template_id)->select('*')->get();

										$supplier_id =DB::table('erp_orders')->where('id',$order_details[0]->erp_order_id)->select('*')->get();
										$customer_name=DB::table('customers')->where('id',$supplier_id[0]->customer_id)->select('*')->get();
			
										foreach($order_details as $value)
										{
											$order_details_omp  = 	DB::table($value->order_qnty_table_name)
																	->join('tesco_primary_size','tesco_washcare_orders_quanty.size_id', 'tesco_primary_size.id')
																	->where($value->order_qnty_table_name.'.id',$value->order_qnty_table_id)
																	->select('*')
																	->get();
											
											$order_table = str_replace("_quanty","",$value->order_qnty_table_name);
											$order_id  = "washcare_order_id";


											$component  = 	DB::table('tesco_washcare_orders_component_mapping')
																					->where('washcare_order_id',$order_details_omp{0}->washcare_order_id)
																					->select('*')
																					->get();
											foreach($component as $comp_val)
											{
													$fibre =  DB::table('tesco_washcare_orders_component_fibre_mapping')
																					->where('washcare_component_id',$comp_val->id)
																					->select('*')
																					->get();
													$comp_val->fibre=$fibre;
													
											}

											$degree_text  = DB::table('tesco_care_additional_instructions')
																			->where('english','UNLESS IT’S DIRTY WASH AT 30º')
																			->select('*')
																			->get();



								
											$order_omp  = 	DB::table($order_table)
																->join('tesco_suppliers',$order_table.'.tesco_metadata_supplier_id', 'tesco_suppliers.id')
																->join('tesco_care_symbols as washing',$order_table.'.wash_symbol_id', 'washing.id')
                                ->join('tesco_care_symbols as whitening',$order_table.'.bleach_symbol_id', 'whitening.id')
                                ->join('tesco_care_symbols as dry',$order_table.'.dry_symbol_id', 'dry.id')
                                ->join('tesco_care_symbols as iron',$order_table.'.ironing_symbol_id', 'iron.id')
                                ->join('tesco_care_symbols as cleaning',$order_table.'.dryclean_symbol_id', 'cleaning.id')
																	->where($order_table.'.id',$order_details_omp[0]->$order_id)
																	->select($order_table.'.*','tesco_suppliers.supplier_name','washing.iso_symbols as wash_iso','washing.astms_symbol as wash_astm','washing.degree','whitening.iso_symbols as whit_iso','whitening.astms_symbol as whit_astm','dry.iso_symbols as dry_iso','dry.astms_symbol as dry_astm','iron.iso_symbols as iron_iso','iron.astms_symbol as iron_astm','cleaning.iso_symbols as clean_iso','cleaning.astms_symbol as clean_astm')
																	->get();

																	$value->washcare_order_id=$order_details_omp{0}->washcare_order_id;
																	$value->size_id=$order_details_omp{0}->size_id;
																	$value->uk_ean=$order_details_omp{0}->uk_ean;
																	$value->ce_ean=$order_details_omp{0}->ce_ean;
																	$value->quantity=$order_details_omp{0}->quantity;
																	$value->selected_secondary_sizes=$order_details_omp{0}->selected_secondary_sizes;
																	$value->uk_size=$order_details_omp{0}->uk_size;
																	$value->ce_size=$order_details_omp{0}->ce_size;

											
																	$value->po_number=$order_omp{0}->po_number;
																	$value->uk_style_ref=$order_omp{0}->uk_style_ref;
																	$value->ce_style_ref=$order_omp{0}->ce_style_ref;
																	$value->eqos_site_code=$order_omp{0}->eqos_site_code;
																	$value->fabric_batch_code=$order_omp{0}->fabric_batch_code;
																	$value->leather_suede_code=$order_omp{0}->leather_suede_code;
																	$value->tog=$order_omp{0}->tog;
																	$value->potps_id=$order_omp{0}->potps_id;

																	if($order_omp{0}->potps_id != null)
																	{
																			$part_name  = 	DB::table('part_of_two_piece_set')
																											->where('id',$order_omp{0}->potps_id)
																											->select('*')
																											->get();
																			$value->part_name = $part_name{0}->name;
																	}
																	else
																	{
																			$value->part_name = "";
																	}
																	
																	$value->baby_sleeping_bag_symbol=$order_omp{0}->baby_sleeping_bag_symbol;
																	$value->bs_en_declaration=$order_omp{0}->bs_en_declaration;
																	$value->internal_reference=$order_omp{0}->internal_reference;
																	$value->customer_name=$customer_name{0}->short_name;
																	$value->country_of_origin_text=$order_omp{0}->country_of_origin_text;
																	$value->wash_iso=$order_omp{0}->wash_iso;
																	$value->wash_astm=$order_omp{0}->wash_astm;
																	$value->whit_iso=$order_omp{0}->whit_iso;
																	$value->whit_astm=$order_omp{0}->whit_astm;
																	$value->dry_iso=$order_omp{0}->dry_iso;
																	$value->dry_astm=$order_omp{0}->dry_astm;
																	$value->iron_iso=$order_omp{0}->iron_iso;
																	$value->iron_astm=$order_omp{0}->iron_astm;
																	$value->clean_iso=$order_omp{0}->clean_iso;
																	$value->clean_astm=$order_omp{0}->clean_astm;
																	$value->degree=$order_omp{0}->degree;
																	$value->additional_care_text_1=$order_omp{0}->additional_care_text_1;
																	$value->additional_care_text_2=$order_omp{0}->additional_care_text_2;
																	$value->additional_care_text_3=$order_omp{0}->additional_care_text_3;
																	$value->kids_warning_text=$order_omp{0}->kids_warning_text;
																	$value->exclusive_trims_text=$order_omp{0}->exclusive_trims_text;
																	$value->contains_non_textile_part_text=$order_omp{0}->contains_non_textile_part_text;
																	$value->part_of_set_text=$order_omp{0}->part_of_set_text;
											
										}
									}
									else
									{
										$tesco_item_master = array();
									}

									$tesco_tiny  = 	DB::table('tesco_translation')
																		->where('id',1)
																		->select('*')
																		->get();
									$tesco_nb  = 	DB::table('tesco_translation')
																		->where('id',2)
																		->select('*')
																		->get();
									$tesco_up1  = 	DB::table('tesco_translation')
																		->where('id',3)
																		->select('*')
																		->get();
									$tesco_up3  = 	DB::table('tesco_translation')
																		->where('id',4)
																		->select('*')
																		->get();
									$tesco_onesize  = 	DB::table('tesco_translation')
																		->where('id',5)
																		->select('*')
																		->get();
																		
									return response()->json(['error'=> true,'order_details' =>$order_details,'tesco_item_master' =>$tesco_item_master,'component'=>$component,'degree_text'=>$degree_text,'tesco_tiny'=>$tesco_tiny,'tesco_nb'=>$tesco_nb,'tesco_up1'=>$tesco_up1,'tesco_up3'=>$tesco_up3,'tesco_onesize'=>$tesco_onesize],200);
			}
			else
			{
				$order_details  = 	DB::table('erp_order_details')
									->where('erp_order_details.style_no',$style_no)
									->where('erp_order_details.erp_order_id',$id)
									->where('erp_order_details.reference_name',$ref_name)
									->where('erp_order_details.buyer_po_number',$buyer_no)
									->where('erp_order_details.order_type',$order_type)
									->where('erp_order_details.status','!=',4)
									->where('erp_order_details.order_status_id','!=',3)
									->where('erp_order_details.deleted_at', null)
									->orderBy('erp_order_details.id', 'desc')
									->select('*')
									->get();
									if(sizeof($order_details) > 0)
									{
										$tesco_item_master=DB::table('tesco_item_master')->where('id',$order_details[0]->template_id)->select('*')->get();
			
										foreach($order_details as $value)
										{
											$order_details_omp  = 	DB::table($value->order_qnty_table_name)
																	->where('id',$value->order_qnty_table_id)
																	->select('*')
																	->get();
											
											$order_table = str_replace("_quanty","",$value->order_qnty_table_name);
											$order_id  = strtolower(str_replace(" ","",$ref_name).'_order_id');
								
											$order_omp  = 	DB::table($order_table)
												->join('tesco_department',$order_table.'.tesco_department_id', 'tesco_department.id')
												->join('tesco_brand',$order_table.'.tesco_brand_id', 'tesco_brand.id')
												->join('tesco_suppliers',$order_table.'.tesco_metadata_supplier_id', 'tesco_suppliers.id')
																	->where($order_table.'.id',$order_details_omp[0]->$order_id)
																	->select($order_table.'.*','tesco_department.department','tesco_brand.brand','tesco_suppliers.supplier_name')
																	->get();
											
											$value->tpnd              = $order_details_omp[0]->tpnd;
											$value->itf_barcode       = $order_details_omp[0]->itf_barcode;
											$value->qr_code       	  = $order_details_omp[0]->qr_code;
											$value->units_per_carton  = $order_details_omp[0]->units_per_carton;
											$value->cases_per_carton  = $order_details_omp[0]->cases_per_carton;
											$value->ean_barcode       = $order_details_omp[0]->ean_barcode;
											$value->size              = $order_details_omp[0]->size;
											$value->case_size         = $order_details_omp[0]->case_size;
											$value->orms_tpn          = $order_details_omp[0]->orms_tpn;
											$value->zone              = $order_omp[0]->zone;
											$value->po_number         = $order_omp[0]->po_number;
											$value->uk_style_ref      = $order_omp[0]->uk_style_ref;
											$value->ce_style_ref      = $order_omp[0]->ce_style_ref;
											$value->season_code       = $order_omp[0]->season_code;
											$value->short_description = $order_omp[0]->short_description;
											$value->eqos_site_code    = $order_omp[0]->eqos_site_code;
											$value->manufacture_date  = $order_omp[0]->manufacture_date;
											$value->department        = $order_omp[0]->department;
											$value->brand             = $order_omp[0]->brand;
											$value->supplier_name     = $order_omp[0]->supplier_name;
											$value->rfid_compliant    = $order_omp[0]->rfid_compliant;
											$value->tag_at_source     = $order_omp[0]->tag_at_source;
											
										}
									}
									else
									{
										$tesco_item_master = array();
									}
									return response()->json(['error'=> true,'order_details' =>$order_details,'tesco_item_master' =>$tesco_item_master],200);
			}
		}
	
		
		
	}
	/**
	 * Get order details for job card template creation by selected ids.
	 * @param order details id
	 * @author Praveen <praveen@wizroots.com>
	 * @return \Illuminate\Http\Response
	 */
	public function getOrderDetailsByIds(Request $request)
	{
	  try {
			if(session()->get('company_name') === "jupitergarmentsaccessories"){
				$erp_orders_ids     = explode(',', $request['orders']);
				$selected_ref_arrays= explode(',', $request['ref_array']);
				$buyer_pos          = explode(',', $request['buyer_po_array']);
				$order_type         = explode(',', $request['order_type_array']);
				$uuid               = explode(',', $request['uuid_array']);
				$order_dt_array     = array();
				$delivery_dt_array  = array();
				$style_array        = array();
				$ordertype_array    = array();
				$temp_id_array      = array();
				$template_data      = array();
				$ratio_templates    = array();
				$single_templates   = array();
				$print_color_array  = array();
				$material_array     = array();
				$article_array      = array();
				$length_array       = array();
				$width_array        = array();
				$distinct_ref_array = array();
				$single_temp_data   = array();
				$common_data_chunk  = array();
				$size_chunk         = array();
				$final_chunk        = array();
				$order_detail_ids   = array();
				$erp_id_array       = array();
				$customer_po_array  = array();
				$color_code         = array();
				$tint_color         = array();

				/** Get erp order details table ids from erp orders table id */
				foreach ($erp_orders_ids as $row => $erp_orders_id) {
					// buyer po number is not mandatory for orchestra.
					// So splitting query using conditional statements.
					$order_detail_id_array = ErpOrderDetails::where([
						'erp_order_details.erp_order_id' => $erp_orders_id,
						'erp_order_details.order_type' => $order_type[$row],
						'erp_order_details.uuid' => $uuid[$row],
						'erp_order_details.status' => $this->erp_jc_status_id,
						'erp_order_details.order_status_id' => $this->order_ap_status_id,
						'erp_order_details.reference_name' => $selected_ref_arrays[$row]
					]);
					if ($buyer_pos[$row] != "") {
						$order_detail_id_array = $order_detail_id_array->where('erp_order_details.buyer_po_number', $buyer_pos[$row])
							->pluck('erp_order_details.id')->all();
					} else {
						$order_detail_id_array = $order_detail_id_array->pluck('erp_order_details.id')->all();
					}

					$order_detail_ids = array_unique(array_merge($order_detail_ids, $order_detail_id_array));
				}
				sort($order_detail_ids);
				
				$order_details_collection = Erp::select('erp_orders.*', 'erp_order_details.*')
				->join('erp_order_details', 'erp_order_details.erp_order_id', 'erp_orders.id')
				->whereIn('erp_order_details.id', $order_detail_ids)
				->get();

				/** Same buyer or not validation */
				$buyer_id_list = $order_details_collection->pluck('buyer_id')->toArray();

				if (count(array_unique($buyer_id_list)) === 1) {
					$buyer = DB::table('buyers')
					->select('buyer_name', 'id as buyer_id', 'template_table', 'item_master_table')
					->where('id', $buyer_id_list[0])
						->first();

					$this->buyer_name = $buyer->buyer_name;
				} else {
					return response()->json(['error' => true, 'message' => 'Different Buyers Not Acceptable'], 406);
				}

				/** Same customer or not validation */
				$customer_id_list = $order_details_collection->pluck('customer_id')->toArray();

				if (count(array_unique($customer_id_list)) === 1) {
					$customer = DB::table('customers')
					->select('short_name as customer_name', 'id as customer_id')
					->where('id', $customer_id_list[0])
						->first();
				} else {
					return response()->json(['error' => true, 'message' => 'Different Customers Not Acceptable'], 406);
				}

				/** Same company or not validation */
				$company_id_list = $order_details_collection->pluck('company_id')->toArray();

				if (count(array_unique($company_id_list)) === 1) {
					$company = DB::table('company')
					->select('name as company', 'id as company_id', 'address as company_address', 'logo as company_logo')
					->where('id', $company_id_list[0])
						->first();
				} else {
					return response()->json(['error' => true, 'message' => 'Different Companies Not Acceptable'], 406);
				}

				foreach ($order_details_collection as $key => $order_details) {

					!in_array($order_details->created_at, $order_dt_array, true) ? array_push($order_dt_array, $order_details->created_at) : $order_dt_array;

					!in_array(Carbon::parse($order_details->delivery_date)->format('d-m-Y'), $delivery_dt_array, true) ? array_push($delivery_dt_array, Carbon::parse($order_details->delivery_date)->format('d-m-Y')) : $delivery_dt_array;

					!in_array($order_details->style_no, $style_array, true) ? array_push($style_array, $order_details->style_no) : $style_array;

					!in_array($order_details->order_type, $ordertype_array, true) ? array_push($ordertype_array, $order_details->order_type) : $ordertype_array;

					!in_array($order_details->template_id, $temp_id_array, true) ? array_push($temp_id_array, $order_details->template_id) : $temp_id_array;

					!in_array($order_details->reference_name, $distinct_ref_array, true) ? array_push($distinct_ref_array, $order_details->reference_name) : $distinct_ref_array;

					!in_array($order_details->po_no, $customer_po_array, true) ? array_push($customer_po_array, $order_details->po_no) : $customer_po_array;

					/** Create separate ratio/single templates array  */
					if ($order_details->template_type == "ratio") {
						if ($this->buyer_name === "Tesco") {
							$order_main_table_data = DB::table($order_details->order_qnty_table_name)
							->select($order_details->order_qnty_table_name . '.id as qty_table_id', $order_details->order_qnty_table_name . '.ratio_pack_type')
							->first();

							$order_details->ratio_pack_type = $order_main_table_data->ratio_pack_type;
						} else {
							$order_main_table_data = DB::table($order_details->order_qnty_table_name)
							->select($order_details->order_qnty_table_name . '.id as qty_table_id')
							->first();
						}

						$order_details->qty_table_id = $order_main_table_data->qty_table_id;

						$ratio_templates[$order_details->reference_name][] = $order_details;
					} else {
						// to differenciate reorder and repeatorder having same values
						$unique_id = $order_details->uuid;

						$erp_id_array[$order_details->reference_name][] = $order_details->erp_id . '-' . $order_details->buyer_po_number . '-' . $order_details->order_type . '-' . $unique_id;

						$single_templates[$order_details->reference_name]['order_data'][$order_details->erp_id . '-' . $order_details->buyer_po_number . '-' . $order_details->order_type . '-' . $unique_id]['ocn_number'] = $order_details->ocn_number;
						$single_templates[$order_details->reference_name]['order_data'][$order_details->erp_id . '-' . $order_details->buyer_po_number . '-' . $order_details->order_type . '-' . $unique_id]['erp_id'] = $order_details->erp_id;
						$single_templates[$order_details->reference_name]['order_data'][$order_details->erp_id . '-' . $order_details->buyer_po_number . '-' . $order_details->order_type . '-' . $unique_id]['buyer_po_number'] = $order_details->buyer_po_number;
						$single_templates[$order_details->reference_name]['order_data'][$order_details->erp_id . '-' . $order_details->buyer_po_number . '-' . $order_details->order_type . '-' . $unique_id]['template_id'] = $order_details->template_id;
						$single_templates[$order_details->reference_name]['size'][$order_details->size][$order_details->erp_id . '-' . $order_details->buyer_po_number . '-' . $order_details->order_type . '-' . $unique_id] = $order_details->order_quantity;
					}

					// Add color code and age group pantone color as tint color for orchestra
					if ($this->buyer_name === "Orchestra") {
						// parse orchestra orders table and table id from quantity table name
						$main_table     = str_replace("_qty", "", $order_details->order_qnty_table_name);
						$result_str     = str_replace("_", " ", str_replace("_orders", "", $main_table));
						$qnty_id        = '';
						foreach (explode(' ', $result_str) as $word) {
							$qnty_id .= strtolower($word[0]);
						}

						$orchestra_color_details = DB::table($order_details->order_qnty_table_name)
						->select($main_table . '.color_code', 'orchestra_age_group.pantone_color')
						->join($main_table, $main_table . '.id', $order_details->order_qnty_table_name . '.' . $qnty_id . '_id')
						->join('orchestra_age_group',  'orchestra_age_group.id', $main_table . '.group_id')
						->where($order_details->order_qnty_table_name . '.id', $order_details->order_qnty_table_id)
						->first();

						!in_array($orchestra_color_details->color_code, $color_code, true) ? array_push($color_code, $orchestra_color_details->color_code) : $color_code;
						!in_array($orchestra_color_details->pantone_color, $tint_color, true) ? array_push($tint_color, $orchestra_color_details->pantone_color) : $tint_color;
					}
				}

				/** Get templates metadata details */
				if (!empty($temp_id_array)) {
					$template_details = DB::table($buyer->template_table)
						->select($buyer->template_table . '.color', $buyer->template_table . '.dimension_height as length', $buyer->template_table . '.dimension_width as width', $buyer->item_master_table . '.item_name as article', 'raw_material_master.material_name_employee as material',  $buyer->template_table . '.material_id')
						->join($buyer->item_master_table, $buyer->item_master_table . '.id', $buyer->template_table . '.hierarchy_id')
						->join('raw_material_master', 'raw_material_master.id', $buyer->template_table . '.material_id')
						->whereIn($buyer->template_table . '.item_id', $temp_id_array)
						->orderBy($buyer->template_table . '.id')
						->get();

					foreach ($template_details as $template_detail) {
						!in_array($template_detail->color, $print_color_array, true) ? array_push($print_color_array, $template_detail->color) : $print_color_array;

						!in_array($template_detail->material, $material_array, true) ? array_push($material_array, $template_detail->material) : $material_array;

						!in_array($template_detail->article, $article_array, true) ? array_push($article_array, $template_detail->article) : $article_array;

						!in_array($template_detail->length, $length_array, true) ? array_push($length_array, $template_detail->length) : $length_array;

						!in_array($template_detail->width, $width_array, true) ? array_push($width_array, $template_detail->width) : $width_array;
					}
				} else {
					return response()->json(['error' => true, 'message' => 'Template details are missing, contact your admin.'], 404);
				}
				
				/** Different size breakup value handling */
				foreach ($single_templates as $template => $template_item) {
					ksort($template_item['order_data']);
					$po_count = sizeof($template_item['order_data']);

					foreach ($template_item['size'] as $size => $qty) {
						$size_count = sizeof($qty);

						if ($size_count < $po_count) {
							/** push 0 qty value for missing/additional size-breakup  */
							for ($i = 0; $i < sizeof($erp_id_array[$template]); $i++) {
								$qty = !array_key_exists($erp_id_array[$template][$i], $qty) ? array_merge($qty, [$erp_id_array[$template][$i] => 0]) : $qty;
							}
						}
						ksort($qty);

						$template_item['size'][$size] = $qty;
					}
					$single_templates[$template] = $template_item;

					/** Split template data array into small chunks (3 elements in each chunks) */
					/** common data chunk */
					$common_data_chunk = array_chunk($template_item['order_data'], 3);
					foreach ($common_data_chunk as $key => $data) {
						$final_chunk[$template . '_' . $key]['order_data'] = $data;
					}

					foreach ($template_item['size'] as $size => $qty) {
						/** size-quantity chunk */
						$size_chunk =  array_chunk($qty, 3, true);
						foreach ($size_chunk as $key2 => $value2) {
							/** check if all the size values are 0 or not
							 * remove following if condition if client wants zero qty size in final table.
							 */
							$is_size_qty_empty = array_filter($value2) != NULL ? 0 : 1;
							if ($is_size_qty_empty != 1) {
								$final_chunk[$template . '_' . $key2]['size'][$size . '_' . $key2] = $value2;
							}
						}
					}
				}

				/** Unset unwanted values from buyers array */
				unset($buyer->template_table);
				unset($buyer->item_master_table);

				// jobcard id format "Itemname(2)FinancialYearERPID(last 5)0001" where  and for old order "JGA-JC-1"
				$workorder_id = $this->jobcard_id_gen_obj->generateJobcardID($article_array, $erp_orders_ids);

				/** Push all array values into template_data array */
				$template_data['order_date']       = Carbon::parse(min($order_dt_array))->format('d-m-Y');
				$template_data['cus_del_date']     = $delivery_dt_array;
				$template_data['today_date']       = Carbon::parse($this->current_date)->format('d-m-Y');
				$template_data['workorder_id']     = $workorder_id;
				$template_data['company']          = $company;
				$template_data['customer']         = $customer;
				$template_data['buyer']            = $buyer;
				$template_data['ordertype_array']  = $ordertype_array;
				$template_data['style_array']      = $style_array;
				$template_data['refnum_array']     = $distinct_ref_array;
				$template_data['color']            = $print_color_array;
				$template_data['material']		   = $material_array;
				$template_data['article']          = $article_array;
				$template_data['length']   		   = $length_array;
				$template_data['width']            = $width_array;
				$template_data['ratio']            = $ratio_templates;
				$template_data['single']           = $final_chunk;
				$template_data['order_detail_ids'] = $order_detail_ids;
				$template_data['customer_po_array'] = $customer_po_array;
				$template_data['color_code']       = $color_code;
				$template_data['tint_color']       = $tint_color;
			} else {
				return response()->json(['error'=> true,'message' =>'Jobcard for Spectrra Printers currently not available !!'],406);
			}

			return response()->json(['error'=> false,'message' =>'Success', 'template_data' => $template_data],200);
  
		  } catch (\Exception $e)
		  {
			return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
		  }
	}
	
	/**
	 * To store Jobcard data.
	 * @author [Praveen] <[praveen@wizroots.com]>
	 * @return \Illuminate\Http\Response , pdf file path
	 */
	public function storeJobcardData(Request $request)
	{
		try {
				$company_id      = $request['company_id'];
				$jobcard_id      = $request['workorder_id'];
				$created_date    = $request['created_date'] == ''?NULL:Carbon::parse($request['created_date'],'Asia/Kolkata')->toDateString();
				$article         = $request['article'];
				$material        = $request['material'];
				$customer_id     = $request['customer_id'];
				$style_no        = $request['style_no'];
				$order_type      = $request['order_type'];
				$buyer_id        = $request['buyer_id'];
				$reference       = $request['reference'];
				$disp_mode       = $request['disp_mode'];
				$order_date      = $request['order_date'] == ''?NULL:Carbon::parse($request['order_date'],'Asia/Kolkata')->toDateString();
				$length          = $request['length'];
				$repeat_gap      = $request['repeat_gap'];
				$machine         = $request['machine'];
				$cus_del_date    = $request['cus_del_date'] == ''?NULL:Carbon::parse($request['cus_del_date'],'Asia/Kolkata')->toDateString();
				$roll_width      = $request['roll_width'];
				$width           = $request['width'];
				$film_no         = $request['film_no'];
				$invoice_date    = $request['invoice_date'] == ''?NULL:Carbon::parse($request['invoice_date'],'Asia/Kolkata')->toDateString();
				$length_side     = $request['length_side'];
				$excess          = $request['excess'];
				$teeth_no        = $request['teeth_no'];
				$inv_no          = $request['inv_no'];
				$no_sticker      = $request['no_sticker'];
				$finish_type     = $request['finish_type'];
				$print_color     = $request['print_color'];
				$compiled_by     = $request['compiled_by'];
				$barcode_read    = $request['barcode_read'];
				$operator        = $request['operator'];
				$checker         = $request['checker'];
				$authorized_by   = $request['authorized_by'];
				$axicon_verifier = $request['axicon_verifier'];
				$approved_by     = $request['approved_by'];
				$packer          = $request['packer'];
				$approval_date   = $request['approval_date'] == ''?NULL:Carbon::parse($request['approval_date'],'Asia/Kolkata')->toDateString();
				$roll_issued_by  = $request['roll_issued_by'];
				$cutter          = $request['cutter'];
				$cartons         = $request['cartons'];
				$final_json_obj  = $request['final_json_obj'];
				$tint_color      = $request['tint_color'];
				$customer_po_num = $request['customer_po'];
				$erp_orders_ids  = explode(',', $request['erp_orders_ids']);
				$selected_ref    = explode(',', $request['selected_ref_array']);
				$buyer_pos       = explode(',', $request['selected_buyer_po_array']);
				$order_types     = explode(',', $request['order_type_array']);
				$uuid            = explode(',', $request['uuid_array']);

				$current_timestamp = Carbon::now('Asia/Kolkata')->toDateTimeString();

				if(isset($company_id) && isset($jobcard_id) && isset($created_date) && isset($article) && isset($material) && isset($customer_id) && isset($buyer_id) && isset($disp_mode) && isset($order_date) && isset($machine) && isset($cus_del_date)  && isset($finish_type)  && isset($compiled_by) && isset($final_json_obj) && isset($material))
				{
					$buyer    = DB::table('buyers')->select('buyer_name')->where('id', $buyer_id)->first();
					$customer = DB::table('customers')->select('short_name as customer_name', 'pi_required', 'po_required')->where('id', $customer_id)->first();

					$article_array = explode(', ', $article);
					
					// jobcard id format "Itemname(2)FinancialYearERPID(last 5)0001" where  and for old order "JGA-JC-1"
					$workorder_id = $this->jobcard_id_gen_obj->generateJobcardID($article_array, $erp_orders_ids);

					// Get Erp status table id for 'Jobcard Created' status.
					$erp_status_id = DB::table('erp_status')->where('status_name', 'Jobcard Created')->value('id');
					$store_jobcard_id = DB::table('jobcard_main_table')->insertGetId([
						'work_order_id'          => $workorder_id,
						'company_id'             => $company_id,
						'customer_id'            => $customer_id,
						'buyer_id'               => $buyer_id,
						'created_date'           => $created_date,
						'material'               => $material,
						'article'                => $article,
						'style_number'           => $style_no,
						'reference'              => $reference,
						'order_type'             => $order_type,
						'dispatch_mode'          => $disp_mode,
						'order_date'             => $order_date,
						'length'                 => $length,
						'repeat_gap'             => $repeat_gap,
						'machine'                => $machine,
						'customer_del_date'      => $cus_del_date,
						'width'                  => $width,
						'roll_width'             => $roll_width,
						'film_no'                => $film_no,
						'invoice_date'           => $invoice_date,
						'length_side'            => $length_side,
						'excess_percentage'      => $excess,
						'teeth_no'               => $teeth_no,
						'invoice_num'            => $inv_no,
						'no_of_stickers'         => $no_sticker,
						'finish_type'            => $finish_type,
						'print_color'            => $print_color,
						'compiled_by'            => $compiled_by,
						'barcode_read'           => $barcode_read,
						'operator'               => $operator,
						'checker'                => $checker,
						'authorized_by'          => $authorized_by,
						'axicon_verifier'        => $axicon_verifier,
						'approved_by'            => $approved_by,
						'packer'                 => $packer,
						'approval_date'          => $approval_date,
						'roll_issued_by'         => $roll_issued_by,
						'cutter'                 => $cutter,
						'cartons'                => $cartons,
						'template_quantity_json' => $final_json_obj,
						'created_at'             => $current_timestamp,
						'updated_at'             => $current_timestamp,
						'tint_color'             => $tint_color,
						'customer_po_num'        => $customer_po_num
					  ]);
					
					/** Store jobcard settings and excess details**/
					if($store_jobcard_id != '')
					{
						if (!empty($request['single_ref'])) 
						{
							
							$single_ref      = explode(',', $request['single_ref']);
							$act_excess 	 = explode(',', $request['act_excess']);
							$sticker_mtr 	 = explode(',', $request['sticker_mtr']);
							$settings        = explode(',', $request['settings']);
							$total_mtrs 	 = explode(',', $request['total_mtrs']);

							$i = 0;
							foreach($single_ref as $value)
							{
								$store_jobcard_details = DB::table('jobcard_excess_details')->insert([
									'jobcard_main_table_id'   => $store_jobcard_id,
									'ref_name'                => $single_ref[$i],
									'mtrs_for_stickers'       => $sticker_mtr[$i],
									'actual_excess'           => $act_excess[$i],
									'settings'                => $settings[$i],
									'total_mtrs'              => $total_mtrs[$i],
									'created_at'              => $current_timestamp,
									'updated_at'              => $current_timestamp
								]);
								$i++;
							}
						}
						else
						{
							$single_ref      = explode(',', $request['single_ref']);
							$act_excess 	 = explode(',', $request['act_excess']);
							$sticker_mtr 	 = explode(',', $request['sticker_mtr']);
							$settings        = explode(',', $request['settings']);
							$total_mtrs 	 = explode(',', $request['total_mtrs']);
						}

						/** Update jobcard created status erp order details table */
						foreach($erp_orders_ids as $row => $erp_orders_id)
						{
							/** Get updated data and update in history table */
							// buyer po is optional for orchestra orders 
							$update_query = ErpOrderDetails::where('erp_order_details.erp_order_id', $erp_orders_id)
							->where('erp_order_details.order_type', $order_types[$row])
							->where('erp_order_details.uuid', $uuid[$row])
							->where('erp_order_details.reference_name', $selected_ref[$row]);

							if($buyer_pos[$row] != "") {
								$update_query->where('erp_order_details.buyer_po_number', $buyer_pos[$row])
								->update([
									'status'  => $erp_status_id,
									'jc_number' => $workorder_id,
									'jc_created_by' => Auth::guard('erpuser')->id(),
									'jc_created_date' => $current_timestamp
								]);

								$jobcard_data = $update_query->where('erp_order_details.buyer_po_number', $buyer_pos[$row])->get();
							} else {
								$update_query->update([
									'status'  => $erp_status_id,
									'jc_number' => $workorder_id,
									'jc_created_by' => Auth::guard('erpuser')->id(),
									'jc_created_date' => $current_timestamp
								]);

								$jobcard_data = $update_query->get();
							}

							$template       = 'Available';
							$action_id  = DB::table('actions')->where('action', 'JC Created')->value('id');
							
							foreach($jobcard_data as $key => $data)
							{
								$qnty_table     = $data->order_qnty_table_name;
								$qty_row_id     = $data->order_qnty_table_id;
								if($buyer->buyer_name === 'Tesco'){

									$main_table     = str_replace("_quanty","",$qnty_table);
									$qnty_id        = str_replace("_orders_quanty","_order_id",str_replace("tesco_","",$qnty_table));
									$order_details  = DB::table($qnty_table)
														->select($main_table.'.*')
														->where($qnty_table.'.id', $qty_row_id)
														->join($main_table, $main_table.'.id', $qnty_table.'.'.$qnty_id)
														->first();
									$season         = $order_details->season_code;
									$country        = $order_details->zone;
									$style_zone     = strtolower($country).'_style_ref';
									$template_id    = $order_details->template_id;
									$order_table_array = explode("_", $main_table);
									$tesco_label_flag = $order_table_array[1] !=  'washcare'?false:true;
									$template_level = $this->tesco_material_obj->tescoMaterialDetails($template_id, $tesco_label_flag);
									
									$material_name  = $template_level->material;
									$material_sub   = $template_level->material_sub;
									$material_ref   = $template_level->ref;
									
									if ($customer->pi_required == 1 && $customer->po_required== 0) {
										$customer_po  = $order_details->pi_id;
									}
									else if($customer->po_required== 1){
										$customer_po  = $order_details->purchase_order_no;
									}

									$buyer_po       = $order_details->po_number;
									$style          = $order_details->zone === "joint" ? $order_details->uk_style_ref . '/' . $order_details->ce_style_ref : $order_details->$style_zone;
									$ocn_number     = $order_details->ocn_number;
									

								} else if($buyer->buyer_name === 'Orchestra'){

									$main_table     = str_replace("_qty","",$qnty_table);
									$result_str     = str_replace("_"," ",str_replace("_orders","",$main_table));
									$qnty_id        = '';
									foreach (explode(' ', $result_str) as $word)
									{
									$qnty_id .= strtolower($word[0]);
									}
									
									$order_details  = DB::table($qnty_table)->select($main_table.'.*')->where($qnty_table.'.id', $qty_row_id)->join($main_table, $main_table.'.id', $qnty_table.'.'.$qnty_id.'_id')
													->first();
									
									// template front and back id storing in orchestra_hang_tag_orders table
									$template_id = $main_table === 'orchestra_hang_tag_orders'?$order_details->template_front_id:$order_details->template_id;

									$template_data = DB::table('orchestra_item_master')
													->where('orchestra_item_master.id', $template_id)
													->select('orchestra_item_master.material_id', 'orchestra_item_master.item_name', 'orchestra_item_master.sub_material_id')->first();
									$material_name  = DB::table('orchestra_item_master')->where('id', $template_data->material_id)->value('item_name');
									$material_sub  = DB::table('orchestra_item_master')->where('id', $template_data->sub_material_id)->value('item_name');
									
									$material_ref   = $template_data->item_name;

									if ($customer->pi_required == 1 && $customer->po_required== 0) {
										$customer_po  = $order_details->pi_id;
									}
									else if($customer->po_required== 1){
										$customer_po  = $order_details->po_number;
									}
									
									$buyer_po       = $order_details->po;
									$style          = $order_details->uvc;
									$ocn_number     = null;
									$season         = $order_details->season_code;
									$country        = null;

								} else {

									$template       = 'Not Available';

								}

								// it will insert if erp_order_details_id i snot exists, otherwise it will update 
								$history_id = History::updateOrCreate(['erp_order_details_id' => $data->id], [
									'buyer_id'             => $buyer_id,
									'template'             => $template,
									'season'               => $season,
									'country'              => $country,
									'customer_id'          => $customer_id,
									'material'             => $material_name,
									'mat_sub'              => $material_sub,
									'reference'            => $material_ref,
									'style'                => $style,
									'po'                   => $customer_po,
									'buyer_po'             => $buyer_po,
									'ocn'                  => $ocn_number,
									'created_at'           => $current_timestamp,
									'updated_at'           => $current_timestamp,
									'disp_mode_id'         => $disp_mode
								]);

								DB::table('event_history')
								->insert([
									'history_id'  => $history_id->id,
									'action_id'   => $action_id,
									'created_by'  => Auth::guard('erpuser')->id(),
									'created_at'  => $current_timestamp,
									'updated_at'  => $current_timestamp,
								]);
								
							}
							
						}

						/** Update jobcard created status erp orders table(iff, all the orders in the order details table have same status. ie, after creating jobcard for all orders) */
						foreach($erp_orders_ids as $row => $erp_orders_id)
						{
							$erp_order_table_status = DB::table('erp_order_details')
								->where('erp_order_details.erp_order_id', $erp_orders_id)
								->pluck('status')->all();
							
							if(count(array_unique($erp_order_table_status)) === 1)
							{
								DB::table('erp_orders')
									->where('erp_orders.id', $erp_orders_id)
									->update([
										'status'  => $erp_order_table_status[0]
									]);
							}
						}

						$company  = DB::table('company')->select('name as company', 'address as company_address', 'logo as company_logo')->where('id', $company_id)->first();

						$erp_dispatch_mode = DB::table('erp_dispatch_mode')->where('id', $disp_mode)->value('disp_mode');
						$erp_finish_type   = DB::table('erp_finish_type')->where('id', $finish_type)->value('finish_type');
						$erp_machine       = DB::table('erp_machines')->where('id', $machine)->value('machine');
						$erp_teeth_no 	   = DB::table('erp_teeth_no')->where('id', $teeth_no)->value('teeth_no');

						/** [ Creating new object for JobcardPDFHeaderFooter() class ] */
						$format_approval_date  = $approval_date == ''?NULL:Carbon::parse($approval_date)->format('d-m-Y');
						$formated_inv_date     = $invoice_date == ''?NULL:Carbon::parse($invoice_date)->format('d-m-Y');
						$formated_created_date = $created_date == ''?NULL:Carbon::parse($created_date)->format('d-m-Y');
						$formated_order_date   = $order_date == ''?NULL:Carbon::parse($order_date)->format('d-m-Y');
						$formated_cus_del_date = $cus_del_date == ''?NULL:Carbon::parse($cus_del_date)->format('d-m-Y');
						
						$pdf = new JobcardPDFHeaderFooter(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
						$pdf->setData($company, $workorder_id, $formated_created_date, $article, $material, $compiled_by, $barcode_read, $operator, $checker, $authorized_by, $axicon_verifier, $approved_by, $packer, $format_approval_date, $roll_issued_by, $cutter, $cartons, $single_ref, $act_excess, $sticker_mtr, $settings, $total_mtrs, $tint_color, $customer_po_num);		

						$pdf->SetPrintHeader(true);
						$pdf->SetPrintFooter(true);  

						//margins
						$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
						$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
						$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
						
						// set header and footer fonts
						$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
						$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
						
						// /** Handle Page break */
						// if ($pdf->last_page_flag)
						// {
								// set auto page breaks
							$pdf->SetAutoPageBreak(TRUE, 40);
		
						// }
						// else 
						// {
						// 	  $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
						// }
						

						// set image scale factor
						$pdf->setImageScale(1.8);

						// set font
						$pdf->SetFont('helvetica', '', 8);

						// add a page
						$pdf->AddPage('P', 'A4');

						// set some text to print
						$view = View::make('erp/Jobmanager/jobcard-pdf-template')
								->with([
										'buyer' 		   => $buyer,
										'customer' 		   => $customer,
										'company'		   => $company,
										'workorder_id'     => $workorder_id,
										'created_date'     => $formated_created_date,
										'article'	       => $article,
										'material'		   => $material,
										'style_no'		   => $style_no,
										'order_type'       => $order_type,
										'reference'		   => $reference,
										'disp_mode'	       => $erp_dispatch_mode,
										'order_date'	   => $formated_order_date,
										'length'		   => $length,
										'repeat_gap'	   => $repeat_gap,
										'machine'		   => $erp_machine,
										'cus_del_date' 	   => $formated_cus_del_date,
										'roll_width'	   => $roll_width,
										'width'	  		   => $width,
										'film_no'		   => $film_no, 
										'invoice_date'     => $formated_inv_date,
										'length_side'	   => $length_side,
										'excess'	       => $excess,
										'teeth_no'		   => $erp_teeth_no,
										'inv_no'	       => $inv_no,
										'no_sticker'	   => $no_sticker,
										'finish_type'	   => $erp_finish_type,
										'print_color'      => $print_color,
										'compiled_by'      => $compiled_by,
										'barcode_read'     => $barcode_read,
										'operator'         => $operator,
										'checker'          => $checker,
										'authorized_by'    => $authorized_by,
										'axicon_verifier'  => $axicon_verifier,
										'approved_by'	   => $approved_by,
										'packer'		   => $packer,
										'approval_date'    => $format_approval_date,
										'roll_issued_by'   => $roll_issued_by,
										'cutter'		   => $cutter,
										'cartons' 		   => $cartons,
										'final_json_obj'   => json_decode($final_json_obj, true),
										'single_ref'	   => $single_ref,
										'act_excess'	   => $act_excess,
										'sticker_mtr'	   => $sticker_mtr,
										'settings'		   => $settings,
										'total_mtrs' 	   => $total_mtrs,
										'tint_color'       => $tint_color,
										'customer_po'      => $customer_po_num
								]);

						//append content to header
						$pdf->SetXY(13.4,26.3);
						$pdf->writeHTML($view->render());

						//Close and output PDF document
						ob_end_clean();
						$file_path =  "/storage/app/public/jobcards/".$workorder_id."."."pdf";	
						$pdf->IncludeJS("print();");
						$pdf->Output( public_path($file_path), 'F');

						DB::table('jobcard_main_table')->where('id', $store_jobcard_id)
						->update([
							'file_path'   => $file_path,
							'updated_at'  => $current_timestamp
						]);
						return response()->json(['error'=> false,'message' =>'success', 'file_path' => $file_path, 'buyer_name' => $buyer->buyer_name],200);	
					}
					else
					{
						return response()->json(['error'=> true,'message' =>'Error'],400);
					}
					
				}
				else
				{
					return response()->json(['error'=> true,'message' =>'Parameter is Missing'],400);
				}
		}
		catch (\Exception $e)
		{
			return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
		}
	}
	/**
   * Print Preview For Stickers
   *
   * @return pdf
   */
  
	public function printDivTesco(Request $request)
	{
		 $company_id = $request->id;
		 $customer = DB::table("erp_orders")
						->join('customers', 'erp_orders.customer_id', 'customers.id')
						->select('customers.short_name')
						->where('erp_orders.id',$company_id)
						->get();


						$order_details = DB::table("erp_order_details")
						->select('*')
						->where('erp_order_id',$company_id)
						->where('buyer_po_number',$request->tesco_po_number)
						->where('order_qnty_table_name','tesco_tcl4_orders_quanty')
						->get();

		 $customer_name = $customer[0]->short_name;
		 $this->customer_name = $customer_name;
		
		 $this->created_date = $request->created_at;
		
		 $this->template_name = $request->name;
		 $this->template_zone = $request->zone;
		  if($request->name == "TCL1-sticker")
		  {
			  $this->template_desc = "RATIO STICKER";
		  }
		  else if($request->name == "TCL2-sticker")
		  {
			   $this->template_desc = "RATIO BD STICKER"; 
		  }
		  else if($request->name == "TCL4-sticker")
		  {
			   $this->template_desc =  "SINGLES BD STICKER";
		  }
			else if($request->name == "TCL5-sticker")
			{
					 $this->template_desc =  "SINGLES BD STICKER";
			}
		  else if($request->name == "THL1-sticker")
		  {
				$this->template_desc = "RATIO STICKER";
		  }
		  else if($request->name == "THL3-sticker")
		  {
			   $this->template_desc = "SINGLES RATIO BD STICKER";
		  }
		  $this->tesco_po_number = $request->tesco_po_number;
		  $this->style = $request->style;
		  
			// Custom Header
		  PDF::setHeaderCallback(function($pdf) {
			$pdf->SetY(0);
		  $name=str_replace("-sticker", "", $this->template_name);
		  $zone=$this->template_zone;
			$tesco_po_number=$this->tesco_po_number;
			$style=$this->style;
			$tesco_description=$this->template_desc;
			$date=date_create($this->created_date);
			$created_date =  date_format($date,"d.m.Y");
			$image_file= K_PATH_IMAGES.'logo_example.jpg';
			$svg='<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="188.244mm" height="50.1333mm" viewBox="0 0 1024 264" version="1.2" baseProfile="tiny">
					<title>Qt Svg Document</title>
					<desc>Generated with Qt</desc>
					<defs>
					</defs>
					<g fill="none" stroke="black" stroke-width="1" fill-rule="evenodd" stroke-linecap="square" stroke-linejoin="bevel">
					<g fill="#ffffff" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1024,0 L1024,264 L0,264 L0,0"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1024,0 L1024,264 L0,264 L0,0"/>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal" style="&#10;    /* border-radius: 27px; */&#10;">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,65 L666,65 L666,66 L499,66 L499,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,65 L500,65 L500,144 L499,144 L499,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,65 L821,65 L821,66 L665,66 L665,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,65 L969,65 L969,66 L820,66 L820,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M968,65 L969,65 L969,105 L968,105 L968,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M968,104 L969,104 L969,144 L968,144 L968,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,182 L821,182 L821,183 L499,183 L499,182"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,143 L500,143 L500,183 L499,183 L499,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,182 L969,182 L969,183 L820,183 L820,182"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M968,143 L969,143 L969,183 L968,183 L968,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,143 L666,143 L666,144 L499,144 L499,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,65 L666,65 L666,144 L665,144 L665,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,104 L821,104 L821,105 L665,105 L665,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,65 L666,65 L666,105 L665,105 L665,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,65 L821,65 L821,105 L820,105 L820,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L969,104 L969,105 L820,105 L820,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,65 L821,65 L821,105 L820,105 L820,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,104 L821,104 L821,105 L665,105 L665,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,104 L666,104 L666,143 L665,143 L665,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L821,104 L821,143 L820,143 L820,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L969,104 L969,105 L820,105 L820,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L969,143 L969,144 L820,144 L820,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L821,104 L821,144 L820,144 L820,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,143 L821,143 L821,144 L499,144 L499,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L821,143 L821,183 L820,183 L820,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L969,143 L969,144 L820,144 L820,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L821,143 L821,183 L820,183 L820,143"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,212 L1016,212 L1016,213 L8,213 L8,212"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,213 L1016,213 L1016,214 L8,214 L8,213"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,212 L8,214 L9,213 L9,212 L8,212"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1015,213 L1015,214 L1016,214 L1016,212 L1015,213"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,216 L1016,216 L1016,217 L8,217 L8,216"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,217 L1016,217 L1016,218 L8,218 L8,217"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,216 L8,218 L9,217 L9,216 L8,216"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1015,217 L1015,218 L1016,218 L1016,216 L1015,217"/>
					</g>
					<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="56cm" height="37cm" version="1.1">
							<image xlink:href="'.public_path('/images/jupiter.jpg').'" x="174" y="66" height="65px" width="116px" style=""/>
					</svg>
					<g xmlns="http://www.w3.org/2000/svg" fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="35" font-style="normal" font-weight="700">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="50" y="176" font-family="Times New Roman" font-size="30" font-weight="700" font-style="normal">Jupiter Garments Accessories</text>

					</g>
					<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="507" y="127" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">PO NO : '.$tesco_po_number.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="671" y="90" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">STYLE : '.$style.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="90" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">REF : '.$name.' - '.$zone.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="671" y="129" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">DATE : '.$created_date.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="129" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">Size: 127x80mm</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="505" y="168" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">DESCRIPTION : '.$tesco_description.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="168" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">REV.NO :</text>
					</g>
					</g>
					</svg>';
					$pdf->ImageSVG('@' . $svg, '', '', '', '', $link='', $align='', $palign='', $border=0, $fitonpage=false);
			});
							
					// Custom Footer
			PDF::setFooterCallback(function($pdf) {
					$tesco_po_number=$this->tesco_po_number;
					$customer_name=$this->customer_name;
					// Position at 15 mm from bottom
					$pdf->SetY(-50);
					$pdf->SetX(9.5);
					// Set font
					//$pdf->SetFont('frankgothcnbt');
					// Page number
					$svg='<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="188mm" height="65.2667mm" viewBox="0 0 1360 168" version="1.2" baseProfile="tiny">
					<title>Qt Svg Document</title>
					<desc>Generated with Qt</desc>
					<defs>
					</defs>
					<g fill="none" stroke="black" stroke-width="1" fill-rule="evenodd" stroke-linecap="square" stroke-linejoin="bevel">
					<g fill="#ffffff" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1360,0 L1360,168 L0,168 L0,0"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1360,0 L1360,168 L0,168 L0,0"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,52 L1352,52 L1352,53 L8,53 L8,52"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,53 L1352,53 L1352,54 L8,54 L8,53"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,52 L8,54 L9,53 L9,52 L8,52"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1351,53 L1351,54 L1352,54 L1352,52 L1351,53"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,56 L1352,56 L1352,57 L8,57 L8,56"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,57 L1352,57 L1352,58 L8,58 L8,57"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,56 L8,58 L9,57 L9,56 L8,56"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1351,57 L1351,58 L1352,58 L1352,56 L1351,57"/>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M385,79 L386,79 L386,157 L385,157 L385,79"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M764,79 L765,79 L765,157 L764,157 L764,79"/>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="41" y="100" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">CUSTOMER : '.$customer_name.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="41" y="152" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">PO No : '.$tesco_po_number.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="405" y="100" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">APPROVED BY : </text>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="700" font-style="normal">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="405" y="152" font-family="Times New Roman" font-size="23" font-weight="700" font-style="normal">PREPARED BY:</text>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="784" y="103" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">COMMENTS IF ANY :</text>
					</g>
					</g>
					</svg>';
					
					 $pdf->ImageSVG('@' . $svg, '', '', '', '', $link='', $align='', $palign='', $border=0, $fitonpage=false);
		
			});
		
		
				$svgarr = explode(',_',$request->svg);
				$name=$request->name;
		        $svg=urldecode($request->svg);
				PDF::setImageScale(1);
				PDF::SetFont('frankgothcnbt');
				PDF::AddPage();
				$i=0;
				$yy=60;
				$qr_y=67.5;
				$j=0;
				$qr_count=0;
				PDF::SetXY(10, 50);
		
		
				$qr_flag=$request->qr_flag;
				$id=$request->id;
				$qr_code=explode(',_',$request->qr_code_fin);
				$qr_code_check_fin=explode(',_',$request->qr_code_check_fin);
		
				foreach($svgarr as $value)
				{
					$svg=urldecode($value);
					PDF::SetFillColor(255,255,255);
					if($i == 2)
					{
						$yy=60;
						$qr_y=67.5;
						PDF::AddPage();
						PDF::RoundedRect(15, 60, 127, 80, 0, '0000');
						$i = 0;
					}
					else if($i == 0)
					{
						PDF::RoundedRect(15, 60, 127, 80, 0, '0000');
						$yy=60;
						$qr_y=67.5;
					}
					else
					{
						PDF::RoundedRect(15, 160, 127, 80, 0, '0000');
						if($name == "TCL4-sticker")
						{
							//print_r( $order_details{0}->template_id);
							if($order_details{0}->template_id == 28 || $order_details{0}->template_id == 31)
							{
								$yy += 77;
							}
							else
							{
								$yy += 100; 
							}
						}
						else if($name == "TCL5-sticker")
						{
							$yy += 77;
						}
						else if($name == "TCL1-sticker")
					  {
							$yy += 77;
						}
						else if($name == "TCL2-sticker")
					  {
							$yy += 77;
					  }
						else
						{
							$yy += 100;  
						}
						$qr_y+=100;
					}
					
			
					
					$svg=urldecode($value);
					file_put_contents(public_path('/storage/app/template/big_sticker'.$j.'.svg'), $svg);
					
					$html = '<img style="width:480px;height:302.36220472px;" src="'.public_path('/storage/app/template/big_sticker'.$j.'.svg').'">';
					PDF::writeHTMLCell(127, 80, 15, $yy+1, $html, '', 0, 0, false, 'L', false);
				
					if($qr_flag == 1)
			{
				// new style
				$style = array(
					'border' => false,
					'padding' => 0,
					'bgcolor' => false
				);
				if($name == "TCL1-sticker")
				{
					
					$qr_code_check=explode(',',$qr_code_check_fin[$qr_count]);

					$lasttpn = substr($qr_code_check[3] , -3);
					$qr_check = $qr_code_check[0].",".$qr_code_check[2]."/".$lasttpn.",".$qr_code_check[3];
          if($qr_check == $qr_code[$qr_count])
          {
						PDF::write2DBarcode($qr_code[$qr_count], 'QRCODE,Q', 88, 65, 17, 17, $style, 'N');
					}
				}
				else if($name == "TCL2-sticker")
				{
					$qr_code_check=explode(',',$qr_code_check_fin[$qr_count]);

					$lasttpn = substr($qr_code_check[3] , -3);
					$qr_check = $qr_code_check[0].",".$qr_code_check[2]."/".$lasttpn.",".$qr_code_check[3];
          if($qr_check == $qr_code[$qr_count])
          {
						PDF::write2DBarcode($qr_code[$qr_count], 'QRCODE,Q', 89.5, 69, 17, 17, $style, 'N');
					}
				}
				else if($name == "TCL4-sticker")
				{
					$qr_code_check=explode(',',$qr_code_check_fin[$qr_count]);

					$lasttpn = substr($qr_code_check[3] , -3);
					$qr_check = $qr_code_check[0].",".$qr_code_check[1]."/".$lasttpn.",".$qr_code_check[3];
          if($qr_check == $qr_code[$qr_count])
          {
						PDF::write2DBarcode($qr_code[$qr_count], 'QRCODE,Q', 89, $qr_y+1.5, 17, 17, $style, 'N');
					}
				}
				else if($name == "TCL5-sticker")
				{
					$qr_code_check=explode(',',$qr_code_check_fin[$qr_count]);

					$lasttpn = substr($qr_code_check[3] , -3);
					$qr_check = $qr_code_check[0].",".$qr_code_check[2]."/".$lasttpn.",".$qr_code_check[3];
					if($qr_check == $qr_code[$qr_count])
					{
						PDF::write2DBarcode($qr_code[$qr_count], 'QRCODE,Q', 89, $qr_y-1.5, 17, 17, $style, 'N');
					}
				}
				else if($name == "THL1-sticker")
				{
					$qr_code_check=explode(',',$qr_code_check_fin[$qr_count]);

					$lasttpn = substr($qr_code_check[3] , -3);
					$qr_check = $qr_code_check[0].",".$qr_code_check[2]."/".$lasttpn.",".$qr_code_check[3];
          if($qr_check == $qr_code[$qr_count])
          {
						PDF::write2DBarcode($qr_code[$qr_count], 'QRCODE,Q', 89, 66, 17, 17, $style, 'N');
					}
				}
				else if($name == "THL3-sticker")
				{
					$qr_code_check=explode(',',$qr_code_check_fin[$qr_count]);

					$lasttpn = substr($qr_code_check[3] , -3);
					$qr_check = $qr_code_check[0].",".$qr_code_check[1]."/".$lasttpn.",".$qr_code_check[3];
          if($qr_check == $qr_code[$qr_count])
          {
						PDF::write2DBarcode($qr_code[$qr_count], 'QRCODE,Q', 90, $qr_y-2, 17, 17, $style, 'N');
					}
				}
				
			}
					
					$i++;
					$j++;
					$qr_count++;
					
				}
		
				$k=0;
				if(count($svgarr) == $j)
				{
					foreach($svgarr as $value)
					{
						unlink(public_path('/storage/app/template/big_sticker'.$k.'.svg'));
						$k++;
					}
				}
				$random = number_format(Carbon::now('Asia/Kolkata')->timestamp * rand(),0,'','');
				PDF::Output( public_path('/storage/app/template/'.$random.'-'.$name.'.pdf'), 'F');
				$file_path =  "/storage/app/template/".$random.'-'.$name."."."pdf";	
				
				return response()->json(['error'=> false,'message' =>'success', 'file_path' => $file_path,'url'=>url('/storage/app/template/'.$random.'-'.$name.'.pdf')],200);	
	} 
	 /**
   * Print Preview For Stickers
   *
   * @return pdf
   */
  
	public function printDivTescoSmall(Request $request)
	{
		 $company_id = $request->id;
		 $customer = DB::table("erp_orders")
						->join('customers', 'erp_orders.customer_id', 'customers.id')
						->select('customers.short_name')
						->where('erp_orders.id',$company_id)
						->get();
		 $customer_name = $customer[0]->short_name;
		 $this->customer_name = $customer_name;
		
		 $this->created_date = $request->created_at;
		
		 $this->template_name = $request->name;
		 $this->template_zone = $request->zone;
		
		  if($request->name == "TSL1-sticker")
		  {
			  $this->template_desc = "RATIO POLYBAG STICKER";
		  }
		  else if($request->name == "TSL3-sticker")
		  {
			   $this->template_desc = "SINGLE POLYBAG STICKER "; 
		  }
			else if($request->name == "TSL2-sticker")
			{
					 $this->template_desc = "SINGLE POLYBAG STICKER "; 
			}
		  $this->tesco_po_number = $request->tesco_po_number;
		  $this->style = $request->style;
		  $this->tesco_description = $request->tesco_description;
		  
			// Custom Header
		  PDF::setHeaderCallback(function($pdf) {
		  $name=str_replace("-sticker", "", $this->template_name);
		  $zone=$this->template_zone;
			$tesco_po_number=$this->tesco_po_number;
			$style=$this->style;
			$tesco_description=$this->template_desc;
			$date=date_create($this->created_date);
			$created_date =  date_format($date,"d.m.Y");
			$image_file= K_PATH_IMAGES.'logo_example.jpg';
			$svg='<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="188.244mm" height="50.1333mm" viewBox="0 0 1024 264" version="1.2" baseProfile="tiny">
					<title>Qt Svg Document</title>
					<desc>Generated with Qt</desc>
					<defs>
					</defs>
					<g fill="none" stroke="black" stroke-width="1" fill-rule="evenodd" stroke-linecap="square" stroke-linejoin="bevel">
					<g fill="#ffffff" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1024,0 L1024,264 L0,264 L0,0"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1024,0 L1024,264 L0,264 L0,0"/>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal" style="&#10;    /* border-radius: 27px; */&#10;">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,65 L666,65 L666,66 L499,66 L499,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,65 L500,65 L500,144 L499,144 L499,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,65 L821,65 L821,66 L665,66 L665,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,65 L969,65 L969,66 L820,66 L820,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M968,65 L969,65 L969,105 L968,105 L968,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M968,104 L969,104 L969,144 L968,144 L968,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,182 L821,182 L821,183 L499,183 L499,182"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,143 L500,143 L500,183 L499,183 L499,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,182 L969,182 L969,183 L820,183 L820,182"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M968,143 L969,143 L969,183 L968,183 L968,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,143 L666,143 L666,144 L499,144 L499,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,65 L666,65 L666,144 L665,144 L665,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,104 L821,104 L821,105 L665,105 L665,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,65 L666,65 L666,105 L665,105 L665,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,65 L821,65 L821,105 L820,105 L820,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L969,104 L969,105 L820,105 L820,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,65 L821,65 L821,105 L820,105 L820,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,104 L821,104 L821,105 L665,105 L665,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,104 L666,104 L666,143 L665,143 L665,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L821,104 L821,143 L820,143 L820,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L969,104 L969,105 L820,105 L820,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L969,143 L969,144 L820,144 L820,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L821,104 L821,144 L820,144 L820,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,143 L821,143 L821,144 L499,144 L499,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L821,143 L821,183 L820,183 L820,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L969,143 L969,144 L820,144 L820,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L821,143 L821,183 L820,183 L820,143"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,212 L1016,212 L1016,213 L8,213 L8,212"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,213 L1016,213 L1016,214 L8,214 L8,213"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,212 L8,214 L9,213 L9,212 L8,212"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1015,213 L1015,214 L1016,214 L1016,212 L1015,213"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,216 L1016,216 L1016,217 L8,217 L8,216"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,217 L1016,217 L1016,218 L8,218 L8,217"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,216 L8,218 L9,217 L9,216 L8,216"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1015,217 L1015,218 L1016,218 L1016,216 L1015,217"/>
					</g>
					<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="56cm" height="37cm" version="1.1">
							<image xlink:href="'.public_path('/images/jupiter.jpg').'" x="174" y="66" height="65px" width="116px" style=""/>
					</svg>
					<g xmlns="http://www.w3.org/2000/svg" fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="35" font-style="normal" font-weight="700">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="50" y="176" font-family="Times New Roman" font-size="30" font-weight="700" font-style="normal">Jupiter Garments Accessories</text>

					</g>
					<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="507" y="127" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">PO NO : '.$tesco_po_number.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="671" y="90" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">STYLE : '.$style.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="90" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">REF : '.$name.' - '.$zone.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="671" y="129" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">DATE : '.$created_date.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="129" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">SIZE: 40x30mm</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="505" y="168" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">DESCRIPTION : '.$tesco_description.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="168" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">REV.NO:</text>
					</g>
					</g>
					</svg>';
					$pdf->ImageSVG('@' . $svg, '', '', '', '', $link='', $align='', $palign='', $border=0, $fitonpage=false);
			});
							
			// Custom Footer
			PDF::setFooterCallback(function($pdf) {
			$tesco_po_number=$this->tesco_po_number;
			$customer_name=$this->customer_name;
			// Position at 15 mm from bottom
			$pdf->SetY(-50);
			$pdf->SetX(9.5);
			// Set font
			//$pdf->SetFont('frankgothcnbt');
			// Page number
			$svg='<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="188mm" height="65.2667mm" viewBox="0 0 1360 168" version="1.2" baseProfile="tiny">
					<title>Qt Svg Document</title>
					<desc>Generated with Qt</desc>
					<defs>
					</defs>
					<g fill="none" stroke="black" stroke-width="1" fill-rule="evenodd" stroke-linecap="square" stroke-linejoin="bevel">
					<g fill="#ffffff" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1360,0 L1360,168 L0,168 L0,0"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1360,0 L1360,168 L0,168 L0,0"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,52 L1352,52 L1352,53 L8,53 L8,52"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,53 L1352,53 L1352,54 L8,54 L8,53"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,52 L8,54 L9,53 L9,52 L8,52"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1351,53 L1351,54 L1352,54 L1352,52 L1351,53"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,56 L1352,56 L1352,57 L8,57 L8,56"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,57 L1352,57 L1352,58 L8,58 L8,57"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,56 L8,58 L9,57 L9,56 L8,56"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1351,57 L1351,58 L1352,58 L1352,56 L1351,57"/>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M385,79 L386,79 L386,157 L385,157 L385,79"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M764,79 L765,79 L765,157 L764,157 L764,79"/>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="41" y="100" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">CUSTOMER : '.$customer_name.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="41" y="152" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">PO NO : '.$tesco_po_number.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="405" y="100" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">APPROVED BY : </text>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="700" font-style="normal">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="405" y="152" font-family="Times New Roman" font-size="23" font-weight="700" font-style="normal">PREPARED BY:</text>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="784" y="103" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">COMMENTS IF ANY :</text>
					</g>
					</g>
					</svg>';
					
					 $pdf->ImageSVG('@' . $svg, '', '', '', '', $link='', $align='', $palign='', $border=0, $fitonpage=false);
		
		});
		$svgarr = explode(',_',$request->svg);
		$name=$request->name;
	//$svg=urldecode($request->svg);
	PDF::setImageScale(1);
	PDF::SetFont('frankgothcnbt');	
		PDF::AddPage();
		$i=0;
		$yy=0;
		$j=1;
		PDF::SetXY(10, 50);
		foreach($svgarr as $value)
		{
			//$svg=urldecode($value);
			PDF::SetFillColor(255,255,255);
			if($i == 3 || $i == 6 || $i == 9 || $i == 15 || $i == 18)
			{
				$xx=15;
				$yy+=50;
			}
			else if($i == 0)
			{
				$yy=50;
				$xx=15;
			}
			else if($i == 12)
			{
				PDF::AddPage();
				$xx=15;
				$yy=50;
			}
			else 
			{
				$xx+=70;
			}
			$j++;
			
			
			file_put_contents(public_path('/storage/app/template/demo_'.$j.'.svg'), $value);
			$html = '<img style="width:151.18110236px;height:113.38582677px;border:1px solid #333" src="'.public_path('/storage/app/template/demo_'.$j.'.svg').'">';
			PDF::writeHTMLCell(42, 32, $xx, $yy, $html, '', 0, 0, false, 'L', false);
			$i++;

		}
		$random = number_format(Carbon::now('Asia/Kolkata')->timestamp * rand(),0,'','');
		PDF::Output( public_path('/storage/app/template/'.$random.'-'.$name.'.pdf'), 'F');
		$file_path =  "/storage/app/template/".$random.'-'.$name."."."pdf";	
		
		return response()->json(['error'=> false,'message' =>'success', 'file_path' => $file_path,'url'=>url('/storage/app/template/'.$random.'-'.$name.'.pdf')],200);	
				
		} 
		/**
	 * Function to Merge Jobcard pdf and artwork
	 * @author <Praveen>
	 * @param array PDF files
	 * @return filepath
	 */
		public function pdfMerge(Request $request)
		{
			try {
						$path = explode(',',$request['path']);
						$merge = new FPDF_Merge();
						foreach($path as $value)
						{
							if($value != null && $value != "")
							{
								$merge->add(public_path($value));
							}
							
						}
						$file_path =  $path[0];	

						$merge->output(public_path($file_path) , 'F');
						
						return response()->json(['error'=> false,'message' =>'success', 'file_path' => $file_path],200);	
				} catch (\Exception $e) {
					return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
			}
				
		}
	/**
	 * Function to get tesco tsl template UK , CE size chart based on size entered.
	 * @author <Praveen>
	 * @param [string] $[size] [<Size entered>], [string] $[zone] [<zone selected>]
	 * @return equivalent uk,ce size for size entered
	 */
	public function getTescoTSLSizechart(Request $request)
	{
	   try {
			 $zone = $request['zone'];
			 $size = $request['size'];

		   if($zone == 'UK')
		   {
			   $size = DB::table('tesco_size_chart')->select('uk as size')->where('po_size', 'ilike', $size)->where('is_enabled','=',1)->get();
		   }
		   else
		   {
			   $size = DB::table('tesco_size_chart')->select('ce as size')->where('po_size', 'ilike', $size)->where('is_enabled','=',1)->get();
		   }
		   
		   return response()->json(['size_chart' => $size]);
	   } catch (\Exception $e) {
		   return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
	   }
	}

	/**
	 * Function to get all jobcards list.
	 * @author <Praveen>
	 * @return equivalent list of jobcards
	 */
	public function getJobcards()
	{
		try {
				$customer_id = Auth::guard('erpuser')->id();
				
				$companies   = 	DB::table('erpuser_company_mapping')
										->join('company','erpuser_company_mapping.company','company.id')
										->where('erpuser_company_mapping.erp_user',$customer_id)
										->select('erpuser_company_mapping.*','company.name', 'company.logo','company.id')
										->get();
				if($companies)
				{
					$company_id   = DB::table('company')
									->select('company.*')
									->where('company.id', '=', $companies[0]->company)
									->first();
				}
				else
				{
					return view('erp.home');
				}
				
				
				if(session()->get('company') == "" && session()->get('company') == null)
				{
					session()->put('company', $company_id->id);
					session()->put('company_name', strtolower( str_replace(' ','',$company_id->name)) );
				}
				/**
				 * Dropdown values
				 */
				$buyers  = 	DB::table('buyers')
								->join('jobcard_main_table', 'jobcard_main_table.buyer_id', 'buyers.id')
								->where('jobcard_main_table.company_id', '=', session()->get('company'))
								->pluck('buyers.buyer_name','buyers.id')
								->all();
				
				$erp_dispatch_mode = DB::table('erp_dispatch_mode')->where('is_enabled','=',true)->pluck('disp_mode','id')->all();
				$erp_finish_type   = DB::table('erp_finish_type')->where('is_enabled','=',true)->pluck('finish_type','id')->all();
				$erp_machines      = DB::table('erp_machines')->where('is_enabled','=',true)->pluck('machine','id')->all();
				$erp_teeth_no      = DB::table('erp_teeth_no')->where('is_enabled','=',true)->pluck('teeth_no','id')->all();
				return view('erp.Jobmanager.jobcards-list', ['buyers' => $buyers, 'companies' => $companies, 'disp_modes' => $erp_dispatch_mode, 'finish_types' => $erp_finish_type, 'machines' => $erp_machines, 'teeth_nos' => $erp_teeth_no]);	
			} catch (\Exctesteption $e) {
				return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
			}
	}
	
	/**
	 * Function to get jobcard details by id.
	 * @author <Praveen>
	 * @param  $id
	 * @return equivalent jobcard details
	 */  
	public function getJobcardDetailsById(Request $request)
	{
		try {
				$id = $request['id'];

				$customer_id = Auth::guard('erpuser')->id();

				if(session()->get('company') == "" || session()->get('company') == null || $customer_id == null)
				{
					return view('erp.home');
				}
				else
				{
					$jobcard_data  = DB::table('jobcard_main_table')
										->select('jobcard_main_table.*', 'customers.id as customer_id','customers.short_name as customer', 'buyers.buyer_name', 'buyers.id as buyer_id', 'company.name as company', 'company.id as company_id', 'company.address as company_address','company.logo as company_logo')
										->join('customers', 'jobcard_main_table.customer_id', 'customers.id')
										->join('company', 'company.id', 'jobcard_main_table.company_id')
										->join('buyers', 'jobcard_main_table.buyer_id', 'buyers.id')
										->where('jobcard_main_table.id', '=', $id)
										->first();
					$jobcard_data->created_date      =	$jobcard_data->created_date == ''?NULL:Carbon::parse($jobcard_data->created_date)->format('d-m-Y');
					$jobcard_data->order_date        =	$jobcard_data->order_date == ''?NULL:Carbon::parse($jobcard_data->order_date)->format('d-m-Y');
					$jobcard_data->customer_del_date =	$jobcard_data->customer_del_date == ''?NULL:Carbon::parse($jobcard_data->customer_del_date)->format('d-m-Y');
					$jobcard_data->invoice_date      =	$jobcard_data->invoice_date == ''?NULL:Carbon::parse($jobcard_data->invoice_date)->format('d-m-Y');
					$jobcard_data->approval_date     =	$jobcard_data->approval_date == ''?NULL:Carbon::parse($jobcard_data->approval_date)->format('d-m-Y');

					$jobcard_details  = DB::table('jobcard_excess_details')
										->select('jobcard_excess_details.ref_name', 'jobcard_excess_details.mtrs_for_stickers', 'jobcard_excess_details.actual_excess', 'jobcard_excess_details.settings', 'jobcard_excess_details.total_mtrs')
										->where('jobcard_excess_details.jobcard_main_table_id', '=', $jobcard_data->id)
										->get();
					$jobcard_data->additional_details = $jobcard_details;					
					return response()->json(['error'=> false,'message' =>'Success', 'jobcard_data' => $jobcard_data], 200);
				}
	
			} catch (\Exctesteption $e) {
				return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
			}
	}
	/**
	 * Function to update jobcard details by id.
	 * @author <Praveen>
	 * @param  $json_data
	 * @return equivalent pdf file path
	 */
	public function updateJobcardById(Request $request)
	{
		try {
				$company_id      = $request['company_id'];
				$workorder_id    = $request['workorder_id'];
				$created_date    = $request['created_date'] == ''?NULL:Carbon::parse($request['created_date'],'Asia/Kolkata')->toDateString();
				$article         = $request['article'];
				$material        = $request['material'];
				$customer_id     = $request['customer_id'];
				$style_no        = $request['style_no'];
				$order_type      = $request['order_type'];
				$buyer_id        = $request['buyer_id'];
				$reference       = $request['reference'];
				$disp_mode       = $request['disp_mode'];
				$order_date      = $request['order_date'] == ''?NULL:Carbon::parse($request['order_date'],'Asia/Kolkata')->toDateString();
				$length          = $request['length'];
				$repeat_gap      = $request['repeat_gap'];
				$machine         = $request['machine'];
				$cus_del_date    = $request['cus_del_date'] == ''?NULL:Carbon::parse($request['cus_del_date'],'Asia/Kolkata')->toDateString();
				$roll_width      = $request['roll_width'];
				$width           = $request['width'];
				$film_no         = $request['film_no'];
				$invoice_date    = $request['invoice_date'] == ''?NULL:Carbon::parse($request['invoice_date'],'Asia/Kolkata')->toDateString();
				$length_side     = $request['length_side'];
				$excess          = $request['excess'];
				$teeth_no        = $request['teeth_no'];
				$inv_no          = $request['inv_no'];
				$no_sticker      = $request['no_sticker'];
				$finish_type     = $request['finish_type'];
				$print_color     = $request['print_color'];
				$compiled_by     = $request['compiled_by'];
				$barcode_read    = $request['barcode_read'];
				$operator        = $request['operator'];
				$checker         = $request['checker'];
				$authorized_by   = $request['authorized_by'];
				$axicon_verifier = $request['axicon_verifier'];
				$approved_by     = $request['approved_by'];
				$packer          = $request['packer'];
				$approval_date   = $request['approval_date'] == ''?NULL:Carbon::parse($request['approval_date'],'Asia/Kolkata')->toDateString();
				$roll_issued_by  = $request['roll_issued_by'];
				$cutter          = $request['cutter'];
				$cartons         = $request['cartons'];
				$final_json_obj  = $request['final_json_obj'];
				$jobcard_id 	 = $request['jobcard_id'];
				$tint_color      = $request['tint_color'];
				$customer_po_num = $request['customer_po'];
				
				$current_timestamp = Carbon::now('Asia/Kolkata')->toDateTimeString();

				if(isset($company_id) && isset($workorder_id) && isset($created_date) && isset($article) && isset($material) && isset($customer_id) && isset($buyer_id) && isset($disp_mode) && isset($order_date) && isset($machine) && isset($cus_del_date)  && isset($finish_type)  && isset($compiled_by) && isset($final_json_obj) && isset($jobcard_id) && isset($material))
				{
					DB::table('jobcard_main_table')->where('id', $jobcard_id)
					->update([
						'work_order_id'          => $workorder_id,
						'company_id'             => $company_id,
						'customer_id'            => $customer_id,
						'buyer_id'               => $buyer_id,
						'created_date'           => $created_date,
						'material'               => $material,
						'article'                => $article,
						'style_number'           => $style_no,
						'reference'              => $reference,
						'order_type'             => $order_type,
						'dispatch_mode'          => $disp_mode,
						'order_date'             => $order_date,
						'length'                 => $length,
						'repeat_gap'             => $repeat_gap,
						'machine'                => $machine,
						'customer_del_date'      => $cus_del_date,
						'width'                  => $width,
						'roll_width'             => $roll_width,
						'film_no'                => $film_no,
						'invoice_date'           => $invoice_date,
						'length_side'            => $length_side,
						'excess_percentage'      => $excess,
						'teeth_no'               => $teeth_no,
						'invoice_num'            => $inv_no,
						'no_of_stickers'         => $no_sticker,
						'finish_type'            => $finish_type,
						'print_color'            => $print_color,
						'compiled_by'            => $compiled_by,
						'barcode_read'           => $barcode_read,
						'operator'               => $operator,
						'checker'                => $checker,
						'authorized_by'          => $authorized_by,
						'axicon_verifier'        => $axicon_verifier,
						'approved_by'            => $approved_by,
						'packer'                 => $packer,
						'approval_date'          => $approval_date,
						'roll_issued_by'         => $roll_issued_by,
						'cutter'                 => $cutter,
						'cartons'                => $cartons,
						'template_quantity_json' => $final_json_obj,
						'updated_at'             => $current_timestamp,
						'tint_color'             => $tint_color,
						'customer_po_num'        => $customer_po_num
					]);
					
					if (!empty($request['single_ref'])) 
					{
						
						$single_ref      = explode(',', $request['single_ref']);
						$act_excess 	 = explode(',', $request['act_excess']);
						$sticker_mtr 	 = explode(',', $request['sticker_mtr']);
						$settings        = explode(',', $request['settings']);
						$total_mtrs 	 = explode(',', $request['total_mtrs']);

						// delete data before insertion.
						DB::table('jobcard_excess_details')->where('jobcard_main_table_id', '=', $jobcard_id)
							->delete();
							
						$i = 0;
						foreach($single_ref as $value)
						{
							$store_jobcard_details = DB::table('jobcard_excess_details')->insert([
								'jobcard_main_table_id'   => $jobcard_id,
								'ref_name'                => $single_ref[$i],
								'mtrs_for_stickers'       => $sticker_mtr[$i],
								'actual_excess'           => $act_excess[$i],
								'settings'                => $settings[$i],
								'total_mtrs'              => $total_mtrs[$i],
								'created_at'              => $current_timestamp,
								'updated_at'              => $current_timestamp
							]);
							$i++;
						}
					}
					else
					{
						$single_ref      = explode(',', $request['single_ref']);
						$act_excess 	 = explode(',', $request['act_excess']);
						$sticker_mtr 	 = explode(',', $request['sticker_mtr']);
						$settings        = explode(',', $request['settings']);
						$total_mtrs 	 = explode(',', $request['total_mtrs']);
					}

					$buyer    = DB::table('buyers')->select('buyer_name')->where('id', '=', $buyer_id)->first();
					$customer = DB::table('customers')->select('short_name as customer_name')->where('id', '=', $customer_id)->first();
					$company  = DB::table('company')->select('name as company', 'address as company_address', 'logo as company_logo')->where('id', '=', $company_id)->first();

					$erp_dispatch_mode = DB::table('erp_dispatch_mode')->where('id','=', $disp_mode)->value('disp_mode');
					$erp_finish_type   = DB::table('erp_finish_type')->where('id','=', $finish_type)->value('finish_type');
					$erp_machine       = DB::table('erp_machines')->where('id','=', $machine)->value('machine');
					$erp_teeth_no      = DB::table('erp_teeth_no')->where('id','=', $teeth_no)->value('teeth_no');

					/** [ Creating new object for JobcardPDFHeaderFooter() class ] */
					$format_approval_date  = $approval_date == ''?NULL:Carbon::parse($approval_date)->format('d-m-Y');
					$formated_inv_date     = $invoice_date == ''?NULL:Carbon::parse($invoice_date)->format('d-m-Y');
					$formated_created_date = $created_date == ''?NULL:Carbon::parse($created_date)->format('d-m-Y');
					$formated_order_date   = $order_date == ''?NULL:Carbon::parse($order_date)->format('d-m-Y');
					$formated_cus_del_date = $cus_del_date == ''?NULL:Carbon::parse($cus_del_date)->format('d-m-Y');
					
					$pdf = new JobcardPDFHeaderFooter(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
					$pdf->setData($company, $workorder_id, $formated_created_date, $article, $material, $compiled_by, $barcode_read, $operator, $checker, $authorized_by, $axicon_verifier, $approved_by, $packer, $format_approval_date, $roll_issued_by, $cutter, $cartons, $single_ref, $act_excess, $sticker_mtr, $settings, $total_mtrs, $tint_color, $customer_po_num);	

					$pdf->SetPrintHeader(true);
					$pdf->SetPrintFooter(true);  

					//margins
					$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
					$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
					$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
					
					// set header and footer fonts
					$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
					$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
					
					// /** Handle Page break */
					// if ($pdf->last_page_flag)
					// {
							// set auto page breaks
							$pdf->SetAutoPageBreak(TRUE, 40);
	
					// }
					// else 
					// {
					// 		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
					// }

					// set image scale factor
					$pdf->setImageScale(1.8);

					// set font
					$pdf->SetFont('helvetica', '', 8);

					// add a page
					$pdf->AddPage('P', 'A4');

					// set some text to print
					$view = View::make('erp/Jobmanager/jobcard-pdf-template')
								->with([
										'buyer' 		   => $buyer,
										'customer' 		   => $customer,
										'company'		   => $company,
										'workorder_id'     => $workorder_id,
										'created_date'     => $formated_created_date,
										'article'	       => $article,
										'material'		   => $material,
										'style_no'		   => $style_no,
										'order_type'       => $order_type,
										'reference'		   => $reference,
										'disp_mode'	       => $erp_dispatch_mode,
										'order_date'	   => $formated_order_date,
										'length'		   => $length,
										'repeat_gap'	   => $repeat_gap,
										'machine'		   => $erp_machine,
										'cus_del_date' 	   => $formated_cus_del_date,
										'roll_width'	   => $roll_width,
										'width'	  		   => $width,
										'film_no'		   => $film_no, 
										'invoice_date'     => $formated_inv_date,
										'length_side'	   => $length_side,
										'excess'	       => $excess,
										'teeth_no'		   => $erp_teeth_no,
										'inv_no'	       => $inv_no,
										'no_sticker'	   => $no_sticker,
										'finish_type'	   => $erp_finish_type,
										'print_color'      => $print_color,
										'compiled_by'      => $compiled_by,
										'barcode_read'     => $barcode_read,
										'operator'         => $operator,
										'checker'          => $checker,
										'authorized_by'    => $authorized_by,
										'axicon_verifier'  => $axicon_verifier,
										'approved_by'	   => $approved_by,
										'packer'		   => $packer,
										'approval_date'    => $format_approval_date,
										'roll_issued_by'   => $roll_issued_by,
										'cutter'		   => $cutter,
										'cartons' 		   => $cartons,
										'final_json_obj'   => json_decode($final_json_obj, true),
										'single_ref'	   => $single_ref,
										'act_excess'	   => $act_excess,
										'sticker_mtr'	   => $sticker_mtr,
										'settings'		   => $settings,
										'total_mtrs' 	   => $total_mtrs,
										'tint_color'       => $tint_color,
										'customer_po'      => $customer_po_num
								]);

					//append content to header
					$pdf->SetXY(13.4,25.5);
					$pdf->writeHTML($view->render());

					//Close and output PDF document
					ob_end_clean();
					$file_path =  "/storage/app/public/jobcards/".$workorder_id."."."pdf";

					/** delete already existing file from folder */	
					File::delete($file_path);
					
					$pdf->IncludeJS("print();");
					$pdf->Output( public_path($file_path), 'F');

					DB::table('jobcard_main_table')->where('id', $jobcard_id)
					->update([
						'file_path'   => $file_path,
						'updated_at'  => $current_timestamp
					]);
					return response()->json(['error'=> false,'message' =>'success', 'file_path' => $file_path, 'buyer_name' => $buyer->buyer_name],200);	
					
				}
				else
				{
					return response()->json(['error'=> true,'message' =>'Parameter is Missing'],400);
				}
		}
		catch (\Exception $e)
		{
			return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
		}
	}


	/**
	 * Function to get customers list by  buyer id.
	 * @author <Praveen>
	 * @param  $buyer_id
	 * @return equivalent customer list
	 */  
	public function getCustomersListByBuyerId(Request $request)
	{
		try {
				$buyer_id = $request['selected_buyer_id'];

				if(session()->get('company') == "" || session()->get('company') == null)
				{
					return view('erp.home');
				}

				if($buyer_id == null)
				{
					return response()->json(['error'=> true,'message' =>'Parameter Missing'], 400);
				}
				else
				{
					$customers_list  = 	DB::table('customers')
										->join('jobcard_main_table', 'jobcard_main_table.customer_id', 'customers.id')
										->where('jobcard_main_table.company_id', '=', session()->get('company'))
										->where('jobcard_main_table.buyer_id', '=', $buyer_id)
										->pluck('customers.company_name','customers.id')
										->all();
					return response()->json(['error'=> false,'message' =>'Success', 'customers_list' => $customers_list], 200);
				}
					
	
			} catch (\Exctesteption $e) {
				return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
			}
	}

	/**
	 * Function to get jobcard id list by  buyer id and customer id.
	 * @author <Praveen>
	 * @param  $buyer_id, $customer_id
	 * @return equivalent jobcard ids list
	 */  
	public function getJobcardIdListByBuyerCustomerId(Request $request)
	{
		try {
				$buyer_id    = $request['selected_buyer_id'];
				$customer_id = $request['selected_customer_id'];

				if(session()->get('company') == "" || session()->get('company') == null)
				{
					return view('erp.home');
				}

				if($buyer_id == null || $customer_id == null)
				{
					return response()->json(['error'=> true,'message' =>'Parameter Missing'], 400);
				}
				else
				{
					$jobcard_id_list  = DB::table('jobcard_main_table')
											->where('jobcard_main_table.company_id', '=', session()->get('company'))
											->where('jobcard_main_table.buyer_id', '=', $buyer_id)
											->where('jobcard_main_table.customer_id', '=', $customer_id)
											->pluck('jobcard_main_table.work_order_id','jobcard_main_table.id')
											->all();
					return response()->json(['error'=> false,'message' =>'Success', 'jobcard_id_list' => $jobcard_id_list], 200);
				}
					
	
			} catch (\Exctesteption $e) {
				return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
			}
	}

	/**
	 * Function to get jobcards list by  buyer id, customer id and jobcard id.
	 * @author <Praveen>
	 * @param  $buyer_id, $customer_id, $jobcard_id
	 * @return equivalent jobcard list
	 */  
	public function getJobcardsListByBuyerCustomerJobcardId(Request $request)
	{
		try {
				$buyer_id    = $request['selected_buyer_id'];
				$customer_id = $request['selected_customer_id'];
				$jobcard_id  = $request['selected_jobcard_id'];

				if(session()->get('company') == "" || session()->get('company') == null)
				{
					return view('erp.home');
				}

				if($buyer_id == null || $customer_id == null || $jobcard_id == null)
				{
					return response()->json(['error'=> true,'message' =>'Parameter Missing'], 400);
				}
				else
				{
					$jobcards_list  = DB::table('jobcard_main_table')
											->join('customers', 'jobcard_main_table.customer_id', 'customers.id')
											->join('buyers', 'jobcard_main_table.buyer_id', 'buyers.id')
											->where('jobcard_main_table.company_id', '=', session()->get('company'))
											->where('jobcard_main_table.buyer_id', '=', $buyer_id)
											->where('jobcard_main_table.customer_id', '=', $customer_id)
											->where('jobcard_main_table.id', '=', $jobcard_id)
											->select('jobcard_main_table.id as jobcard_id', 'jobcard_main_table.work_order_id', 'customers.company_name', 'buyers.buyer_name')
											->first();
					return response()->json(['error'=> false,'message' =>'Success', 'jobcards_list' => $jobcards_list], 200);
				}
					
	
			} catch (\Exctesteption $e) {
				return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
			}
	}
	 public function generateDivTesco(Request $request)
	{
			$svgarr = explode(',_',$request->svg);
		$name=$request->name;
		$svg=urldecode($request->svg);
		define ('PDF_FONT_NAME_MAIN', 'frankgothcnbt');
		define ('PDF_FONT_NAME_DATA', 'frankgothcnbt');
		PDF::setPrintHeader(false);
		PDF::setPrintFooter(false);
		PDF::SetFont('frankgothcnbt');
		PDF::SetFont('franklingothicdemicond');
		PDF::SetAutoPageBreak(TRUE);
		$i=0;
		 
		$qr_flag=$request->qr_flag;
		$qr_code=explode(',_',$request->qr_code_fin);
		$qr_code_check_fin=explode(',_',$request->qr_code_check_fin);
		 
		foreach($svgarr as $value)
		{
			 PDF::SetMargins(0, 0, 0, false);
			 PDF::AddPage('L', array(80,127));
			 $svg=urldecode($value);
			 PDF::setImageScale(1.66);
			 PDF::ImageSVG('@' . $svg, $x=0, $y=0, $w=127, $h=80, $link='', $align='', $palign='', $border=0, $fitonpage=true);
			if($name != "TCL2-sticker" && $name != "TCL4-sticker")
            {
                 if($name != "THL3-sticker")
                 {
                     if($qr_flag == 1)
											{
												if($name == "THL1-sticker")
																{
													PDF::Image(public_path('/storage/app/template/barcode_'.$i.'.png'), 6, 54, 50, 16, 'PNG', '', '', true, 300, '', false, false, 0, false, false, false);
												}
												else if($name == "TCL5-sticker")
												{
													PDF::Image(public_path('/storage/app/template/barcode_'.$i.'.png'), 10, 53, 50, 16, 'PNG', '', '', true, 300, '', false, false, 0, false, false, false);
												}
												else
												{
													PDF::Image(public_path('/storage/app/template/barcode_'.$i.'.png'), 6, 54, 50, 16, 'PNG', '', '', true, 300, '', false, false, 0, false, false, false);
												}
												
											}
											else
											{
												PDF::Image(public_path('/storage/app/template/barcode_'.$i.'.png'), 12, 52, 50, 16, 'PNG', '', '', true, 300, '', false, false, 0, false, false, false);
											}
                 }
                else
                {
                    if($qr_flag == 1)
										{
											PDF::Image(public_path('/storage/app/template/barcode_'.$i.'.png'),-4, 53.5, 75, 16, 'PNG', '', '', true, 300, '', false, false, 0, false, false, false);
										}
										else
										{
											PDF::Image(public_path('/storage/app/template/barcode_'.$i.'.png'), -2, 52, 75, 16, 'PNG', '', '', true, 300, '', false, false, 0, false, false, false);
										}
                }
            }
			
			if($qr_flag == 1)
			{
				// new style
				$style = array(
					'border' => false,
					'padding' => 0,
					'bgcolor' => false
				);
				if($name == "TCL1-sticker")
				{
					$qr_code_check=explode(',',$qr_code_check_fin[$i]);

					$lasttpn = substr($qr_code_check[3] , -3);
					$qr_check = $qr_code_check[0].",".$qr_code_check[2]."/".$lasttpn.",".$qr_code_check[3];
          if($qr_check == $qr_code[$i])
          {
						PDF::write2DBarcode($qr_code[$i], 'QRCODE,Q', 73.5, 5, 17, 17, $style, 'N');
					}
				}
				else if($name == "TCL2-sticker")
				{
					$qr_code_check=explode(',',$qr_code_check_fin[$i]);

					$lasttpn = substr($qr_code_check[3] , -3);
					$qr_check = $qr_code_check[0].",".$qr_code_check[2]."/".$lasttpn.",".$qr_code_check[3];
          if($qr_check == $qr_code[$i])
          {
						PDF::write2DBarcode($qr_code[$i], 'QRCODE,Q', 74.5, 8.5, 17, 17, $style, 'N');
					}
				}
				else if($name == "TCL4-sticker")
				{
					$qr_code_check=explode(',',$qr_code_check_fin[$i]);

					$lasttpn = substr($qr_code_check[3] , -3);
					$qr_check = $qr_code_check[0].",".$qr_code_check[1]."/".$lasttpn.",".$qr_code_check[3];
          if($qr_check == $qr_code[$i])
          {
						PDF::write2DBarcode($qr_code[$i], 'QRCODE,Q', 74, 8.5, 17, 17, $style, 'N');
					}
				}
				else if($name == "TCL5-sticker")
				{
					 $qr_code_check=explode(',',$qr_code_check_fin[$i]);

					 $lasttpn = substr($qr_code_check[3] , -3);
					 $qr_check = $qr_code_check[0].",".$qr_code_check[2]."/".$lasttpn.",".$qr_code_check[3];
					 if($qr_check == $qr_code[$i])
					 {
						PDF::write2DBarcode($qr_code[$i], 'QRCODE,Q', 74, 5, 17, 17, $style, 'N');
					 }
				}
				else if($name == "THL1-sticker")
				{
					$qr_code_check=explode(',',$qr_code_check_fin[$i]);

					$lasttpn = substr($qr_code_check[3] , -3);
					$qr_check = $qr_code_check[0].",".$qr_code_check[2]."/".$lasttpn.",".$qr_code_check[3];
          if($qr_check == $qr_code[$i])
          {
						PDF::write2DBarcode($qr_code[$i], 'QRCODE,Q', 74, 5, 17, 17, $style, 'N');
					}
				}
				else if($name == "THL3-sticker")
				{
					$qr_code_check=explode(',',$qr_code_check_fin[$i]);

					$lasttpn = substr($qr_code_check[3] , -3);
					$qr_check = $qr_code_check[0].",".$qr_code_check[1]."/".$lasttpn.",".$qr_code_check[3];
          if($qr_check == $qr_code[$i])
          {
						PDF::write2DBarcode($qr_code[$i], 'QRCODE,Q', 75, 5, 17, 17, $style, 'N');
					}
				}
				
			}
			
			 $i++;
		}
		 
		if($name != "TCL4-sticker" && $name != "TCL2-sticker")
		{
			$j=0;
			if(count($svgarr) == $i)
			{
				foreach($svgarr as $value)
				{
					unlink(public_path('/storage/app/template/barcode_'.$j.'.png'));
					$j++;
				}
			}
		}
		PDF::Output( public_path('/storage/app/template/'.$name.'.pdf'), 'F');
		print_r(url('/storage/app/template/'.$name.'.pdf'));
		
		
		
//		$svgarr = explode(',_',$request->svg);
//		$name=$request->name;
//        $svg=urldecode($request->svg);
//        define ('PDF_FONT_NAME_MAIN', 'frankgothcnbt');
//        define ('PDF_FONT_NAME_DATA', 'frankgothcnbt');
//        PDF::setPrintHeader(false);
//        PDF::setPrintFooter(false);
//        PDF::SetFont('frankgothcnbt');
//		PDF::SetAutoPageBreak(TRUE);
//		$j=0;
//		foreach($svgarr as $value)
//		{
//			PDF::SetMargins(0, 0, 0, false);
//			PDF::setImageScale(1);
//			PDF::AddPage('L', array(80,127));
//			$svg=urldecode($value);
//			file_put_contents(public_path('/storage/app/template/demo_'.$j.'.svg'), $svg);
//			$html = '<img style="width:480px;height:302.36220472px;border:1px solid #333" src="'.public_path('/storage/app/template/demo_'.$j.'.svg').'">';
//			PDF::writeHTMLCell(129, 82.5, -1,0, $html, '', 0, 0, false, 'L', false);
//			$j++;
//		}
//		PDF::deletePage(1);
//        PDF::Output( public_path('/storage/app/template/'.$name.'.pdf'), 'F');
//        print_r(url('/storage/app/template/'.$name.'.pdf'));
	} 
	
	
		 /**
   * Print Preview For Stickers
   *
   * @return pdf
   */
  
	public function generateDivTescoSmall(Request $request)
	{
		$svgarr = explode(',_',$request->svg);
		$name=$request->name;
		$svg=urldecode($request->svg);
		define ('PDF_FONT_NAME_MAIN', 'frankgothcnbt');
		define ('PDF_FONT_NAME_DATA', 'frankgothcnbt');
		PDF::setImageScale(1.66);
		PDF::setPrintHeader(false);
		PDF::setPrintFooter(false);
		PDF::SetFont('frankgothcnbt');
		PDF::SetFont('myriad');
		PDF::SetAutoPageBreak(TRUE);
		$i=0;
		foreach($svgarr as $value)
		{
			 PDF::AddPage('L', array(30,80));
			 PDF::SetMargins(0, 0, 0, false);
			
			 $svg=urldecode($value);
			
			
			
			 PDF::ImageSVG('@' . $svg, $x=0, $y=0, $w=40, $h=30, $link='', $align='', $palign='', $border=0, $fitonpage=false);
			 PDF::ImageSVG('@' . $svg, $x=40, $y=0, $w=40, $h=30, $link='', $align='', $palign='', $border=0, $fitonpage=false);
			
			 if($name == "TSL3-sticker")
			 {
				 PDF::Image(public_path('/storage/app/template/barcode_'.$i.'.png'), 0.9, 2, 36.5, 13, 'PNG', '', '', true, 300, '', false, false, 0, false, false, false);
			
				PDF::Image(public_path('/storage/app/template/barcode_'.$i.'.png'), 40.9, 2, 36.5, 13, 'PNG', '', '', true, 300, '', false, false, 0, false, false, false);
			 }
			 else
			 {
//				 $img = Image::make(public_path('/storage/app/template/barcode_'.$i.'.png'));
//
//				// now you are able to resize the instance
//				$img->resize(150, 50);
//
//				// finally we save the image as a new file
//				$img->save(public_path('/storage/app/template/barcode_RESIZE.png'));
				 
				 PDF::Image(public_path('/storage/app/template/barcode_'.$i.'.png'), -1.1, 2,41.5, 13, 'PNG', '', '', true, 300, '', false, false, 0, false, false, false);
			
				PDF::Image(public_path('/storage/app/template/barcode_'.$i.'.png'), 38.7, 2, 41.5, 13, 'PNG', '', '', true, 300, '', false, false, 0, false, false, false);
			 }
			
			
			
			
//			PDF::Image(public_path('/storage/app/template/barcode_'.$i.'.png'), -1.7, 2, 42, 13, 'PNG', '', '', true, 300, '', false, false, 0, false, false, false);
//			
//			PDF::Image(public_path('/storage/app/template/barcode_'.$i.'.png'), 39.7, 2, 42, 13, 'PNG', '', '', true, 300, '', false, false, 0, false, false, false);
//			
			$i++;
		}
		$j=0;
		if(count($svgarr) == $i)
		{
			foreach($svgarr as $value)
			{
				unlink(public_path('/storage/app/template/barcode_'.$j.'.png'));
				$j++;
			}
		}
		PDF::Output( public_path('/storage/app/template/'.$name.'.pdf'), 'F');
		print_r(url('/storage/app/template/'.$name.'.pdf'));
	} 
	
	 /**
	 * This Function is used to generate barcode using java.
	 *
	 * @return path of barcode
	 */
	public function generateBarcodeTsl1(Request $request)
	{
		$name=$request['name'];
		$type=$request['type'];
		$barcode=$request['barcode'];
		
		$tabula       = Config::get('tabula.bar_jar');
		
		$info    = shell_exec('java -jar '.$tabula.' '.$barcode.' '.public_path('/storage/app/template/'.$name.'.png').' '.$type.' 33 13');
		$info    = json_encode($info, true);
		return $info;
	}
	/**
	 * This Function is used to generate barcode using java.
	 *
	 * @return path of barcode
	 */
	public function generateBarcodeTsl3(Request $request)
	{
		$name=$request['name'];
		$type=$request['type'];
		$barcode=$request['barcode'];
		
		$tabula       = Config::get('tabula.bar_jar');
		
		$info    = shell_exec('java -jar '.$tabula.' '.$barcode.' '.public_path('/storage/app/template/'.$name.'.png').' '.$type.' 33 13');
		$info    = json_encode($info, true);
		return $info;
	}
	/**
	 * This Function is used to generate barcode using java.
	 *
	 * @return path of barcode
	 */
	public function generateBarcodeEanBig(Request $request)
	{
		$name=$request['name'];
		$type=$request['type'];
		$barcode=$request['barcode'];
		
		$tabula       = Config::get('tabula.bar_jar_big');
		
		$info    = shell_exec('java -jar '.$tabula.' '.$barcode.' '.public_path('/storage/app/template/'.$name.'.png').' '.$type.' 50 16');
		$info    = json_encode($info, true);
		return $info;
	}
	/**
	 * This Function is used to generate barcode using java.
	 *
	 * @return path of barcode
	 */
	public function generateBarcodeItfBig(Request $request)
	{
		$name=$request['name'];
		$type=$request['type'];
		$barcode=$request['barcode'];
		
		$tabula       = Config::get('tabula.bar_jar_big');
		
		$info    = shell_exec('java -jar '.$tabula.' '.$barcode.' '.public_path('/storage/app/template/'.$name.'.png').' '.$type.' 50 16');
		$info    = json_encode($info, true);
		return $info;
	}


	public function scriptCheckOrder(){
		$erpOrderDetails = DB::table('erp_order_details')->select('order_qnty_table_name','order_qnty_table_id')->get();
		$count = 0 ; 
		foreach ($erpOrderDetails as $value) {
			$orderData = DB::table($value->order_qnty_table_name)->where('id',$value->order_qnty_table_id)->first();
			
			if(!empty($orderData)){
				echo "\n";
				echo "Exists";
				echo $orderData->id;
				echo $value->order_qnty_table_name;
			}
			else{
				echo "Not exists\n";
				echo $value->order_qnty_table_id;
				echo $value->order_qnty_table_name;
			}
		}
		}
		
		/**
		 * Function to update order status id value for existing data in erp_order_details table.
		 * @author <Praveen>
		 */
		public function scriptUpdateOrderStatusId()
		{
			$order_details = DB::table('erp_order_details')->get();
			
			foreach($order_details as $value)
			{
				$order_details_data  = 	DB::table($value->order_qnty_table_name)
										->where('id',$value->order_qnty_table_id)
										->select('*')
										->get();
				
				if(sizeof($order_details_data) > 0)
				{
					$order_table = str_replace("_quanty","",$value->order_qnty_table_name);
					$order_id    = strtolower(str_replace(" ","",$value->reference_name).'_order_id');
					$order_data  = 	DB::table($order_table)
											->where($order_table.'.id',$order_details_data[0]->$order_id)
											->select($order_table.'.*')
											->get();
					$order_status = $order_data[0]->order_status;

					$order_status_id  = DB::table('orders_status')->select('id')->where('status_name', 'ilike', $order_status)->first();
					
					$data = ErpOrderDetails::where('id',$value->id)
					->update([
										'order_status_id'  => $order_status_id->id
									]);
									echo  "array size= ".sizeof($order_details_data)." |  order status : ". $order_status. "| code_status : ".$data;
				}
				echo  "array size= ".sizeof($order_details_data);
			}
		}
	

 public function printPdfTable(Request $request)
	{
		 $name=$request->style_no."-STD-PS-".$request->buyer_no;
	 	 $id=$request->id;
		 $svg=urldecode($request->svg);
		 define ('PDF_FONT_NAME_MAIN', 'calibrib');
		 define ('PDF_FONT_NAME_DATA', 'calibrib');
//          print_r($svg);
	 
	 
	 
	   
	   $customer = DB::table("erp_orders")
						->join('customers', 'erp_orders.customer_id', 'customers.id')
						->select('customers.short_name')
						->where('erp_orders.id',$id)
						->get();
	 
	 
	 	$sticker = DB::table("orchestra_standard_carton_sticker_orders")
						->select('*')
						->where('uvc',$request->style_no)
						->where('po',$request->buyer_no)
						->get();
		 if(count($sticker) > 0)
		 {
			 $orchestra_color = DB::table('orchestra_color_code')->select('*')->where('code',$sticker[0]->color_code)->get();
			if(count($orchestra_color) > 0)
			{
				$color_code=$orchestra_color[0]->french;
			}
			else
			{
				$color_code="";
			}
		 }
		 else
		 {
			 $color_code="";
		 }

		 $customer_name = $customer[0]->short_name;
		 $this->customer_name = $customer_name;
		
		 $this->created_date = $request->created_at;
		
		 $this->template_name = "";
		 $this->template_zone = "";
	   $this->template_desc = "Standard Polybag";
		 $this->tesco_po_number = $request->buyer_no;
		 $this->style = $request->style_no;
//	   
//		 define ('PDF_FONT_NAME_MAIN', 'calibri');
//		 define ('PDF_FONT_NAME_DATA', 'calibri');
			// Custom Header
		  PDF::setHeaderCallback(function($pdf) {
			$pdf->SetY(0);
		  $name=$this->template_name;
		  $zone=$this->template_zone;
			$tesco_po_number=$this->tesco_po_number;
			$style=$this->style;
			$tesco_description=$this->template_desc;
			$date=date_create($this->created_date);
			$created_date =  date_format($date,"d.m.Y");
			$image_file= K_PATH_IMAGES.'logo_example.jpg';
			$svg='<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="188.244mm" height="50.1333mm" viewBox="0 0 1024 264" version="1.2" baseProfile="tiny">
					<title>Qt Svg Document</title>
					<desc>Generated with Qt</desc>
					<defs>
					</defs>
					<g fill="none" stroke="black" stroke-width="1" fill-rule="evenodd" stroke-linecap="square" stroke-linejoin="bevel">
					<g fill="#ffffff" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1024,0 L1024,264 L0,264 L0,0"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1024,0 L1024,264 L0,264 L0,0"/>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal" style="&#10;    /* border-radius: 27px; */&#10;">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,65 L666,65 L666,66 L499,66 L499,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,65 L500,65 L500,144 L499,144 L499,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,65 L821,65 L821,66 L665,66 L665,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,65 L969,65 L969,66 L820,66 L820,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M968,65 L969,65 L969,105 L968,105 L968,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M968,104 L969,104 L969,144 L968,144 L968,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,182 L821,182 L821,183 L499,183 L499,182"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,143 L500,143 L500,183 L499,183 L499,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,182 L969,182 L969,183 L820,183 L820,182"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M968,143 L969,143 L969,183 L968,183 L968,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,143 L666,143 L666,144 L499,144 L499,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,65 L666,65 L666,144 L665,144 L665,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,104 L821,104 L821,105 L665,105 L665,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,65 L666,65 L666,105 L665,105 L665,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,65 L821,65 L821,105 L820,105 L820,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L969,104 L969,105 L820,105 L820,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,65 L821,65 L821,105 L820,105 L820,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,104 L821,104 L821,105 L665,105 L665,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,104 L666,104 L666,143 L665,143 L665,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L821,104 L821,143 L820,143 L820,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L969,104 L969,105 L820,105 L820,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L969,143 L969,144 L820,144 L820,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L821,104 L821,144 L820,144 L820,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,143 L821,143 L821,144 L499,144 L499,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L821,143 L821,183 L820,183 L820,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L969,143 L969,144 L820,144 L820,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L821,143 L821,183 L820,183 L820,143"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,212 L1016,212 L1016,213 L8,213 L8,212"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,213 L1016,213 L1016,214 L8,214 L8,213"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,212 L8,214 L9,213 L9,212 L8,212"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1015,213 L1015,214 L1016,214 L1016,212 L1015,213"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,216 L1016,216 L1016,217 L8,217 L8,216"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,217 L1016,217 L1016,218 L8,218 L8,217"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,216 L8,218 L9,217 L9,216 L8,216"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1015,217 L1015,218 L1016,218 L1016,216 L1015,217"/>
					</g>
					<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="56cm" height="37cm" version="1.1">
							<image xlink:href="'.public_path('/images/jupiter.jpg').'" x="174" y="66" height="65px" width="116px" style=""/>
					</svg>
					<g xmlns="http://www.w3.org/2000/svg" fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="35" font-style="normal" font-weight="700">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="50" y="176" font-family="Times New Roman" font-size="30" font-weight="700" font-style="normal">Jupiter Garments Accessories</text>

					</g>
					<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="507" y="127" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">PO NO : '.$tesco_po_number.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="671" y="90" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">STYLE : '.$style.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="90" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">REF : '.$name.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="671" y="129" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">DATE : '.$created_date.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="129" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">Size: 150x100mm</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="505" y="168" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">DESCRIPTION : '.$tesco_description.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="168" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">REV.NO :</text>
					</g>
					</g>
					</svg>';
					$pdf->ImageSVG('@' . $svg, '', '', '', '', $link='', $align='', $palign='', $border=0, $fitonpage=false);
			});
	   
	   
			// Custom Footer
			PDF::setFooterCallback(function($pdf) {
			$tesco_po_number=$this->tesco_po_number;
			$customer_name=$this->customer_name;
			// Position at 15 mm from bottom
			$pdf->SetY(-50);
			$pdf->SetX(9.5);
			// Set font
			//$pdf->SetFont('frankgothcnbt');
			// Page number
			$svg='<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="188mm" height="65.2667mm" viewBox="0 0 1360 168" version="1.2" baseProfile="tiny">
					<title>Qt Svg Document</title>
					<desc>Generated with Qt</desc>
					<defs>
					</defs>
					<g fill="none" stroke="black" stroke-width="1" fill-rule="evenodd" stroke-linecap="square" stroke-linejoin="bevel">
					<g fill="#ffffff" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1360,0 L1360,168 L0,168 L0,0"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1360,0 L1360,168 L0,168 L0,0"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,52 L1352,52 L1352,53 L8,53 L8,52"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,53 L1352,53 L1352,54 L8,54 L8,53"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,52 L8,54 L9,53 L9,52 L8,52"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1351,53 L1351,54 L1352,54 L1352,52 L1351,53"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,56 L1352,56 L1352,57 L8,57 L8,56"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,57 L1352,57 L1352,58 L8,58 L8,57"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,56 L8,58 L9,57 L9,56 L8,56"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1351,57 L1351,58 L1352,58 L1352,56 L1351,57"/>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M385,79 L386,79 L386,157 L385,157 L385,79"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M764,79 L765,79 L765,157 L764,157 L764,79"/>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="41" y="100" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">CUSTOMER : '.$customer_name.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="41" y="152" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">PO NO : '.$tesco_po_number.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="405" y="100" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">APPROVED BY : </text>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="700" font-style="normal">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="405" y="152" font-family="Times New Roman" font-size="23" font-weight="700" font-style="normal">PREPARED BY:</text>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="784" y="103" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">COMMENTS IF ANY :</text>
					</g>
					</g>
					</svg>';
					
					 $pdf->ImageSVG('@' . $svg, '', '', '', '', $link='', $align='', $palign='', $border=0, $fitonpage=false);
		
		});
		PDF::setImageScale(1);
//		PDF::setPrintHeader(false);
//		PDF::setPrintFooter(false);
		PDF::SetFont('calibrib');
		PDF::AddPage();
		
		$style6 = array('width' => 0.353, 'cap' => '', 'join' => '', 'dash' => '', 'color' => '');
		//PDF::RoundedRect(95, 255, 40, 30, 0.1, '1111', null, $style6);
		//PDF::RoundedRect(140, 255, 40, 30, 0.1, '1111', 'null', $style6);
		PDF::RoundedRect(10, 47.5, 150, 100, 0.000001, '1111', null, $style6);
		PDF::RoundedRect(12.5, 50, 145, 95, 0.000001, '1111', null, $style6);
		
		//PDF::RoundedRect(10, 10, 150, 100, 0, '0000');
		//PDF::RoundedRect(12.5, 12.5, 145, 95, 0, '0000');
		$td_size="";
		$td_col="";
		$total=0;
		$size=explode(',',$request->size);
		$qty=explode(',',$request->qty);
	 	$sizebrk=explode(',',$request->sizebrk);
		$i=0;
		$index_arr=array();
		foreach($size as $val)
		{
				$size_var = explode(' ',$val);
			  if (!in_array($val, $index_arr)) 
			  { 
				   if($val != "")
					{
						array_push($index_arr,$val);
						if(count($size_var) > 1)
						{
							if($size_var[1] == "MOIS")
							{
								if(count($size) > 6)
								{
									$td_size.='<td style="font-size:10px;line-height:1.8"><br>'.$val.'</td>';
								}
								else
								{
									$td_size.="<td><br><br>".$val."</td>";
								}
							}
							else
							{
								if(count($size) > 7)
								{
									$td_size.='<td style="font-size:10px;line-height:1.8"><br>'.$val.'</td>';
								}
								else
								{
									$td_size.="<td><br><br>".$val."</td>";
								}
							}
						}
						else
						{
							if(count($size) > 7)
							{
								$td_size.='<td style="font-size:10px;line-height:1.8"><br>'.$val.'</td>';
							}
							else
							{
								if($val == "NAISSANCE")
								{
									$td_size.='<td style="font-size:11px;line-height:0.6"><br><br>'.$val.'</td>';
								}
								else if($val == "PREMATURE")
								{
									$td_size.='<td style="font-size:10px;line-height:0.7"><br><br>'.$val.'</td>';
								}
								else
								{
									$td_size.="<td><br><br>".$val."</td>";
								}
							}
						}
						
						$td_col.="<td>".$sizebrk[$i]."</td>";
					} 
			  } 
		   
			$total+=$sizebrk[$i];
			$i++;
		}
		if(count($size) > 6)
		{
			if(strlen($color_code) > 6)
			{
				if($color_code == "BLEU FONCE" || $color_code == "BLEU CLAIR" || $color_code == "GRIS CLAIR" || $color_code == "GRIS FONCE" || $color_code == "ROSE CLAIR" || $color_code == "ROSE FONCE" || $color_code == "VERT FONCE" || $color_code == "VERT CLAIR" || $color_code == "BEIGE CLAIR")
				{
					$line_height = "1.5";
				}
				else
				{
					$line_height = "1.2";	
				}
				$text_size = "7.5px";
				$line="1.5";
			}
			else
			{
				$text_size = "7.5px";
				$line_height = "2.5";
				$line="1.5";
			}
		}
		else
		{
			$text_size = "7.5px";
			$line_height = "2.5";
			$line="1.5";
		}
          $tbl = '<table  border="1" style="text-align:center;">
		  			<tr style="line-height:0.53">
                          <td style="font-size:14px;text-align:center;line-height:0.45"><br><br>Size:</td>
                          '.$td_size.'
                          <td><br><br>Total<br></td>
                          </tr>
                      <tr style="line-height:'.$line.'">
                          <td style="font-size:'.$text_size.';line-height:'.$line_height.'">'.$color_code.'</td>
                          '.$td_col.'
                          <td style="line-height:'.$line.'">'.$total.'</td>
                          </tr>
            </table>';
		PDF::SetFillColor(255, 255, 255);
		PDF::SetTextColor(0, 0, 0);
		
		PDF::ImageSVG('@' . $svg, $x=12.5, $y=50, $w=145, $h=95, $link='', $align='', $palign='', $border=0, $fitonpage=false);
		PDF::writeHTMLCell(147, '', '11.5', '92.5', $tbl, '', 1, 1, true, '', true);
		
  
		
		$style = array(
			'position' => '',
			'align' => 'C',
			'stretch' => false,
			'fitwidth' => true,
			'cellfitalign' => '',
			'border' => false,
			'hpadding' => 'auto',
			'vpadding' => 'auto',
			'fgcolor' => array(0,0,0),
			'bgcolor' => false, //array(255,255,255),
			'text' => true,
			'font' => 'calibrib',
			'fontsize' => 12,
			'stretchtext' => 4
		);
		 ///PDF::write1DBarcode('3531114195599', 'EAN13', '60', '72', '', 32, 0.45, $style, 'N');

	    $random = number_format(Carbon::now('Asia/Kolkata')->timestamp * rand(),0,'','');
		PDF::Output( public_path('/storage/app/template/'.$name.'.pdf'), 'F');
		//print_r(url('/storage/app/template/'.$name.'.pdf'));
	 
		$file_path =  "/storage/app/template/".$name."."."pdf";	

		return response()->json(['error'=> false,'message' =>'success', 'file_path' => $file_path,'url'=>url('/storage/app/template/'.$name.'.pdf')],200);	
		
	}
	
	/**
	 * Print Preview For 100 * 70 Size Stickers
	 *
	 * @return pdf
	 */
	
     public function proofPdfSmall(Request $request)
    {
		 
		 $name=$request->name;
		 $style_no=$request->style_no;
		 $buyer_no=$request->buyer_no;
		 
		 $color=$request->color;
		 $svgarr = explode(',_',$request->svg);
         //$svg=urldecode($request->svg);
         define ('PDF_FONT_NAME_MAIN', 'calibrib');
         define ('PDF_FONT_NAME_DATA', 'calibrib');
//          print_r($svg);
		
		$orchestra_color = DB::table('orchestra_color_code')->select('*')->where('code',$color)->get();
		if(count($orchestra_color) > 0)
		{
			$color_code=$orchestra_color[0]->french;
		}
		else
		{
			$color_code="";
		}
		
		PDF::setPrintHeader(false);
        PDF::setPrintFooter(false);
		PDF::SetAutoPageBreak(TRUE);
        PDF::SetFont('calibrib');
		//PDF::RoundedRect(10, 10, 100, 70, 0, '0000');
		//PDF::RoundedRect(12.5, 12.5, 95, 65, 0, '0000');
		 
		$style6 = array('width' => 0.353, 'cap' => '', 'join' => '', 'dash' => '', 'color' => '');
		 
//		PDF::SetAutoPageBreak(TRUE);
		foreach($svgarr as $value)
        {
			$svg=urldecode($value);
			PDF::SetMargins(0, 0, 0, false);
			PDF::AddPage('P',array(324,442));
			PDF::setImageScale(1.66);
			
			//Top Left Corner
			PDF::Line(11.5, 3, 11.5, 10.5, $style6); //straight
			PDF::Line(3, 10.5, 11.5, 10.5, $style6); //wide

			//Bottom Left Corner
			PDF::Line(11.5, 431.5, 11.5, 438.5, $style6);
			PDF::Line(3, 431.5, 11.5, 431.5, $style6);

			//Top Right Corner
			PDF::Line(312.5, 3, 312.5, 10.5, $style6);
			PDF::Line(312.5, 10.5, 321, 10.5, $style6);

			//Bottom Right Corner
			PDF::Line(312.5, 431.5, 321, 431.5, $style6);
			PDF::Line(312.5, 431.5, 312.5, 438, $style6);
			
			
			PDF::Line(11.5, 3, 11.5, 10.5, $style6);
			PDF::Line(112, 3, 112, 10.5, $style6);
			PDF::Line(212, 3, 212, 10.5, $style6);
			
			PDF::Line(3, 81, 11.5, 81, $style6);
			PDF::Line(3, 151, 11.5, 151, $style6);
			PDF::Line(3, 221, 11.5, 221, $style6);
			PDF::Line(3, 291, 11.5, 291, $style6);
			PDF::Line(3, 361, 11.5, 361, $style6);
			
			PDF::Line(312.5, 81, 321, 81, $style6);
			PDF::Line(312.5, 151, 321, 151, $style6);
			PDF::Line(312.5, 221, 321, 221, $style6);
			PDF::Line(312.5, 291, 321, 291, $style6);
			PDF::Line(312.5, 361, 321, 361, $style6);
			
			
			PDF::Line(112, 431.5, 112, 438.5, $style6);
			PDF::Line(212, 431.5, 212, 438.5, $style6);
			
			PDF::RoundedRect(14.5, 13.5, 95, 65, 0.000001, '1111', null, $style6);
			PDF::ImageSVG('@' . $svg, $x=14.5, $y=13.5, $w=95, $h=65, $link='', $align='', $palign='', $border=0, $fitonpage=false);
			
			PDF::RoundedRect(114.5, 13.5, 95, 65, 0.000001, '1111', null, $style6);
			PDF::ImageSVG('@' . $svg, $x=114.5, $y=13.5, $w=95, $h=65, $link='', $align='', $palign='', $border=0, $fitonpage=false);
			
			PDF::RoundedRect(214.5, 13.5, 95, 65, 0.000001, '1111', null, $style6);
			PDF::ImageSVG('@' . $svg, $x=214.5, $y=13.5, $w=95, $h=65, $link='', $align='', $palign='', $border=0, $fitonpage=false);
			
			PDF::RoundedRect(14.5, 83.5, 95, 65, 0.000001, '1111', null, $style6);
			PDF::ImageSVG('@' . $svg, $x=14.5, $y=83.5, $w=95, $h=65, $link='', $align='', $palign='', $border=0, $fitonpage=false);
			
			PDF::RoundedRect(114.5, 83.5, 95, 65, 0.000001, '1111', null, $style6);
			PDF::ImageSVG('@' . $svg, $x=114.5, $y=83.5, $w=95, $h=65, $link='', $align='', $palign='', $border=0, $fitonpage=false);
			
			PDF::RoundedRect(214.5, 83.5, 95, 65, 0.000001, '1111', null, $style6);
			PDF::ImageSVG('@' . $svg, $x=214.5, $y=83.5, $w=95, $h=65, $link='', $align='', $palign='', $border=0, $fitonpage=false);
			
			PDF::RoundedRect(14.5, 153.5, 95, 65, 0.000001, '1111', null, $style6);
			PDF::ImageSVG('@' . $svg, $x=14.5, $y=153.5, $w=95, $h=65, $link='', $align='', $palign='', $border=0, $fitonpage=false);
			
			PDF::RoundedRect(114.5, 153.5, 95, 65, 0.000001, '1111', null, $style6);
			PDF::ImageSVG('@' . $svg, $x=114.5, $y=153.5, $w=95, $h=65, $link='', $align='', $palign='', $border=0, $fitonpage=false);
			
			PDF::RoundedRect(214.5, 153.5, 95, 65, 0.000001, '1111', null, $style6);
			PDF::ImageSVG('@' . $svg, $x=214.5, $y=153.5, $w=95, $h=65, $link='', $align='', $palign='', $border=0, $fitonpage=false);
			
			PDF::RoundedRect(14.5, 223.5, 95, 65, 0.000001, '1111', null, $style6);
			PDF::ImageSVG('@' . $svg, $x=14.5, $y=223.5, $w=95, $h=65, $link='', $align='', $palign='', $border=0, $fitonpage=false);
			
			PDF::RoundedRect(114.5, 223.5, 95, 65, 0.000001, '1111', null, $style6);
			PDF::ImageSVG('@' . $svg, $x=114.5, $y=223.5, $w=95, $h=65, $link='', $align='', $palign='', $border=0, $fitonpage=false);
			
			PDF::RoundedRect(214.5, 223.5, 95, 65, 0.000001, '1111', null, $style6);
			PDF::ImageSVG('@' . $svg, $x=214.5, $y=223.5, $w=95, $h=65, $link='', $align='', $palign='', $border=0, $fitonpage=false);
			
			PDF::RoundedRect(14.5, 293.5, 95, 65, 0.000001, '1111', null, $style6);
			PDF::ImageSVG('@' . $svg, $x=14.5, $y=293.5, $w=95, $h=65, $link='', $align='', $palign='', $border=0, $fitonpage=false);
			
			PDF::RoundedRect(114.5, 293.5, 95, 65, 0.000001, '1111', null, $style6);
			PDF::ImageSVG('@' . $svg, $x=114.5, $y=293.5, $w=95, $h=65, $link='', $align='', $palign='', $border=0, $fitonpage=false);
			
			PDF::RoundedRect(214.5, 293.5, 95, 65, 0.000001, '1111', null, $style6);
			PDF::ImageSVG('@' . $svg, $x=214.5, $y=293.5, $w=95, $h=65, $link='', $align='', $palign='', $border=0, $fitonpage=false);
			
			PDF::RoundedRect(14.5, 363.5, 95, 65, 0.000001, '1111', null, $style6);
			PDF::ImageSVG('@' . $svg, $x=14.5, $y=363.5, $w=95, $h=65, $link='', $align='', $palign='', $border=0, $fitonpage=false);
			
			PDF::RoundedRect(114.5, 363.5, 95, 65, 0.000001, '1111', null, $style6);
			PDF::ImageSVG('@' . $svg, $x=114.5, $y=363.5, $w=95, $h=65, $link='', $align='', $palign='', $border=0, $fitonpage=false);
			
			PDF::RoundedRect(214.5, 363.5, 95, 65, 0.000001, '1111', null, $style6);
			PDF::ImageSVG('@' . $svg, $x=214.5, $y=363.5, $w=95, $h=65, $link='', $align='', $palign='', $border=0, $fitonpage=false);
		 }
     PDF::Output( public_path('/storage/app/template/'.$style_no.'-'.$name.'-'.$buyer_no.'.pdf'), 'F');
		 $file_path =  "/storage/app/template/".$style_no."-".$name."-".$buyer_no."."."pdf";	
		 return response()->json(['error'=> false,'message' =>'success', 'file_path' => $file_path,'url'=>url('/storage/app/template/'.$style_no.'-'.$name.'-'.$buyer_no.'.pdf')],200);	
    }
	
	
	/**
	 * Print Preview For Standard Poly Bag Stickers
	 * It Contains Dynamic Table
	 * This Function is used to create dynamic table
	 * @return pdf
	 */
	
    public function proofPdfTable(Request $request)
    {
         $name=$request->name;
		 $color=$request->color;
         $svg=urldecode($request->svg);
         define ('PDF_FONT_NAME_MAIN', 'calibrib');
         define ('PDF_FONT_NAME_DATA', 'calibrib');
//          print_r($svg);


$style_no=$request->style_no;
$buyer_no=$request->buyer_no;
		
		$sticker = DB::table("orchestra_standard_poly_bag_sticker_orders")
						->select('*')
						->where('uvc',$request->style_no)
						->where('po',$request->buyer_no)
						->get();
		 if(count($sticker) > 0)
		 {
			 $orchestra_color = DB::table('orchestra_color_code')->select('*')->where('code',$sticker[0]->color_code)->get();
			if(count($orchestra_color) > 0)
			{
				$color_code=$orchestra_color[0]->french;
			}
			else
			{
				$color_code="";
			}
		 }
		 else
		 {
			 $color_code="";
		 }
		
		PDF::setPrintHeader(false);
        PDF::setPrintFooter(false);
		PDF::SetAutoPageBreak(TRUE);
        PDF::SetFont('calibrib');
		PDF::SetMargins(0, 0, 0, false);
        PDF::AddPage('L',array(324,465));
		PDF::setImageScale(1.66);
		
		$style6 = array('width' => 0.353, 'cap' => '', 'join' => '', 'dash' => '', 'color' => '');
        $td_size="";
        $td_col="";
        $total=0;
        $size=explode(',',$request->size);
        $size_break=explode(',',$request->size_break);
        $i=0;
		$index_arr=array();
        foreach($size as $val)
        {
					$size_var = explode(' ',$val);
			  if (!in_array($val, $index_arr)) 
			  { 
				   if($val != "")
					{
							array_push($index_arr,$val);
							if(count($size_var) > 1)
							{
								if($size_var[1] == "MOIS")
								{
									if(count($size) > 6)
									{
										$td_size.='<td style="font-size:14px;line-height:2.3"><br>'.$val.'</td>';
									}
									else
									{
										$td_size.="<td><br><br>".$val."</td>";
									}
								}
								else
								{
									if(count($size) > 7)
									{
										$td_size.='<td style="font-size:14px;line-height:2.3"><br>'.$val.'</td>';
									}
									else
									{
										$td_size.="<td><br><br>".$val."</td>";
									}
								}
							}
							else
							{
								if(count($size) > 7)
								{
									$td_size.='<td style="font-size:14px;line-height:2.3"><br>'.$val.'</td>';
								}
								else
								{
									if($val == "NAISSANCE")
									{
										$td_size.='<td style="font-size:18px;line-height:0.6"><br><br>'.$val.'</td>';
									}
									else if($val == "PREMATURE")
									{
										$td_size.='<td style="font-size:16px;line-height:0.7"><br><br>'.$val.'</td>';
									}
									else
									{
										$td_size.="<td><br><br>".$val."</td>";
									}
								}
							}
							
						$td_col.='<td style="line-height:1.5">'.$size_break[$i].'</td>';
					} 
			  } 
           
            $total+=$size_break[$i];
            $i++;
        }
				if(strlen($color_code) > 6)
				{
					$text_size = "10px";
					$line_height = "3";
				}
				else
				{
					$text_size = "14px";
					$line_height = "2";
				}
		
							$tbl = '<table  border="1" style="text-align:center;">
								<tr style="line-height:0.53">
															<td style="font-size:14px;text-align:center;line-height:0.75"><br><br>Size:</td>
															'.$td_size.'
															<td><br><br>Total<br></td>
															</tr>
													<tr>
															<td style="font-size:'.$text_size.';text-align:center;line-height:'.$line_height.'">'.$color_code.'</td>
															'.$td_col.'
															<td style="line-height:1.5">'.$total.'</td>
															</tr>
								</table>';
        PDF::SetFillColor(255, 255, 255);
        PDF::SetTextColor(0, 0, 0);
        
		
		//Top Left Corner
		PDF::Line(7, 6, 7, 12, $style6);
		PDF::Line(1, 12, 7, 12, $style6);
		
		//Bottom Left Corner
		PDF::Line(7, 313, 7, 319, $style6);
		PDF::Line(1, 313, 7, 313, $style6);
		
		//Top Right Corner
		PDF::Line(458, 6, 458, 12, $style6);
		PDF::Line(458, 12, 464, 12, $style6);
		
		//Bottom Right Corner
		PDF::Line(458, 313, 464, 313, $style6);
		PDF::Line(458, 313, 458, 319, $style6);
		
		PDF::RoundedRect(10, 15, 145, 95, 0.000001, '1111', null, $style6);
        
        PDF::ImageSVG('@' . $svg, $x=10, $y=15, $w=145, $h=95, $link='', $align='', $palign='', $border=0, $fitonpage=false);
        PDF::writeHTMLCell(147, '', '9', '57.5', $tbl, '', 1, 1, true, '', true);
		
		PDF::Line(157.5, 6, 157.5, 12, $style6);
		PDF::Line(157.5, 313, 157.5, 319, $style6);
		
		PDF::RoundedRect(160, 15, 145, 95, 0.000001, '1111', null, $style6);
        PDF::ImageSVG('@' . $svg, $x=160, $y=15, $w=145, $h=95, $link='', $align='', $palign='', $border=0, $fitonpage=false);
		PDF::writeHTMLCell(147, '', '159', '57.5', $tbl, '', 1, 1, true, '', true);
		
		PDF::Line(307.5, 6, 307.5, 12, $style6);
		PDF::Line(307.5, 313, 307.5, 319, $style6);
		
		PDF::RoundedRect(310, 15, 145, 95, 0.000001, '1111', null, $style6);
        PDF::ImageSVG('@' . $svg, $x=310, $y= 15, $w=145, $h=95, $link='', $align='', $palign='', $border=0, $fitonpage=false);
		PDF::writeHTMLCell(147, '', '309', '57.5', $tbl, '', 1, 1, true, '', true);
		
		PDF::Line(1, 112.5, 7, 112.5, $style6);
		PDF::Line(458, 112.5, 464, 112.5, $style6);
		
		PDF::RoundedRect(10, 115, 145, 95, 0.000001, '1111', null, $style6);
        PDF::ImageSVG('@' . $svg, $x=10, $y=115, $w=145, $h=95, $link='', $align='', $palign='', $border=0, $fitonpage=false);
		PDF::writeHTMLCell(147, '', '9', '157.5', $tbl, '', 1, 1, true, '', true);
		
		PDF::RoundedRect(160, 115, 145, 95, 0.000001, '1111', null, $style6);
        PDF::ImageSVG('@' . $svg, $x=160, $y=115, $w=145, $h=95, $link='', $align='', $palign='', $border=0, $fitonpage=false);
		PDF::writeHTMLCell(147, '', '159', '157.5', $tbl, '', 1, 1, true, '', true);
		
		PDF::RoundedRect(310, 115, 145, 95, 0.000001, '1111', null, $style6);
        PDF::ImageSVG('@' . $svg, $x=310, $y=115, $w=145, $h=95, $link='', $align='', $palign='', $border=0, $fitonpage=false);
		PDF::writeHTMLCell(147, '', '309', '157.5', $tbl, '', 1, 1, true, '', true);
		
		PDF::Line(1, 212.5, 7, 212.5, $style6);
		PDF::Line(458, 212.5, 464, 212.5, $style6);
		
		PDF::RoundedRect(10, 215, 145, 95, 0.000001, '1111', null, $style6);
		PDF::ImageSVG('@' . $svg, $x=10, $y=215, $w=145, $h=95, $link='', $align='', $palign='', $border=0, $fitonpage=false);
		PDF::writeHTMLCell(147, '', '9', '257.5', $tbl, '', 1, 1, true, '', true);
		
		PDF::RoundedRect(160, 215, 145, 95, 0.000001, '1111', null, $style6);
		PDF::ImageSVG('@' . $svg, $x=160, $y=215, $w=145, $h=95, $link='', $align='', $palign='', $border=0, $fitonpage=false);
		PDF::writeHTMLCell(147, '', '159', '257.5', $tbl, '', 1, 1, true, '', true);
		
		PDF::RoundedRect(310, 215, 145, 95, 0.000001, '1111', null, $style6);
		PDF::ImageSVG('@' . $svg, $x=310, $y=215, $w=145, $h=95, $link='', $align='', $palign='', $border=0, $fitonpage=false);
		PDF::writeHTMLCell(147, '', '309', '257.5', $tbl, '', 1, 1, true, '', true);
		
        
	$file_path =  "/storage/app/template/".$style_no."-".$name."-".$buyer_no."."."pdf";	

        PDF::Output( public_path('/storage/app/template/'.$style_no.'-'.$name.'-'.$buyer_no.'.pdf'), 'F');
        return response()->json(['error'=> false,'message' =>'success', 'file_path' => $file_path,'url'=>url('/storage/app/template/'.$style_no.'-'.$name.'-'.$buyer_no.'.pdf')],200);	
        
    }
	
	
	/**
	 * Print Preview For 150 * 100 Size Stickers
	 *
	 * @return pdf
	 */
	
    public function proofPdf(Request $request)
    {
		 $name=$request->name;
         $svg=urldecode($request->svg);
				 $style_no=$request->style_no;
				 $buyer_no=$request->buyer_no;
         define ('PDF_FONT_NAME_MAIN', 'calibrib');
         define ('PDF_FONT_NAME_DATA', 'calibrib');
//          print_r($svg);
        PDF::setPrintHeader(false);
        PDF::setPrintFooter(false);
		PDF::SetAutoPageBreak(TRUE);
        PDF::SetFont('calibrib');
		PDF::SetMargins(0, 0, 0, false);
        PDF::AddPage('L',array(324,465));
		PDF::setImageScale(1.66);
		$style6 = array('width' => 0.353, 'cap' => '', 'join' => '', 'dash' => '', 'color' => '');
        //PDF::RoundedRect(95, 255, 40, 30, 0.1, '1111', null, $style6);
        //PDF::RoundedRect(140, 255, 40, 30, 0.1, '1111', 'null', $style6);
		
		//Top Left Corner
		PDF::Line(7, 6, 7, 12, $style6);
		PDF::Line(1, 12, 7, 12, $style6);
		
		//Bottom Left Corner
		PDF::Line(7, 313, 7, 319, $style6);
		PDF::Line(1, 313, 7, 313, $style6);
		
		//Top Right Corner
		PDF::Line(458, 6, 458, 12, $style6);
		PDF::Line(458, 12, 464, 12, $style6);
		
		//Bottom Right Corner
		PDF::Line(458, 313, 464, 313, $style6);
		PDF::Line(458, 313, 458, 319, $style6);
		
		PDF::RoundedRect(10, 15, 145, 95, 0.000001, '1111', null, $style6);
        PDF::ImageSVG('@' . $svg, $x=10, $y=15, $w=145, $h=95, $link='', $align='', $palign='', $border=0, $fitonpage=false);
		
		PDF::Line(157.5, 6, 157.5, 12, $style6);
		PDF::Line(157.5, 313, 157.5, 319, $style6);
		
		PDF::RoundedRect(160, 15, 145, 95, 0.000001, '1111', null, $style6);
        PDF::ImageSVG('@' . $svg, $x=160, $y=15, $w=145, $h=95, $link='', $align='', $palign='', $border=0, $fitonpage=false);
		
		PDF::Line(307.5, 6, 307.5, 12, $style6);
		PDF::Line(307.5, 313, 307.5, 319, $style6);
		
		PDF::RoundedRect(310, 15, 145, 95, 0.000001, '1111', null, $style6);
        PDF::ImageSVG('@' . $svg, $x=310, $y= 15, $w=145, $h=95, $link='', $align='', $palign='', $border=0, $fitonpage=false);
		
		PDF::Line(1, 112.5, 7, 112.5, $style6);
		PDF::Line(458, 112.5, 464, 112.5, $style6);
		
		PDF::RoundedRect(10, 115, 145, 95, 0.000001, '1111', null, $style6);
        PDF::ImageSVG('@' . $svg, $x=10, $y=115, $w=145, $h=95, $link='', $align='', $palign='', $border=0, $fitonpage=false);
		
		PDF::RoundedRect(160, 115, 145, 95, 0.000001, '1111', null, $style6);
        PDF::ImageSVG('@' . $svg, $x=160, $y=115, $w=145, $h=95, $link='', $align='', $palign='', $border=0, $fitonpage=false);
		
		PDF::RoundedRect(310, 115, 145, 95, 0.000001, '1111', null, $style6);
        PDF::ImageSVG('@' . $svg, $x=310, $y=115, $w=145, $h=95, $link='', $align='', $palign='', $border=0, $fitonpage=false);
		
		PDF::Line(1, 212.5, 7, 212.5, $style6);
		PDF::Line(458, 212.5, 464, 212.5, $style6);
		
		PDF::RoundedRect(10, 215, 145, 95, 0.000001, '1111', null, $style6);
		PDF::ImageSVG('@' . $svg, $x=10, $y=215, $w=145, $h=95, $link='', $align='', $palign='', $border=0, $fitonpage=false);
		
		PDF::RoundedRect(160, 215, 145, 95, 0.000001, '1111', null, $style6);
		PDF::ImageSVG('@' . $svg, $x=160, $y=215, $w=145, $h=95, $link='', $align='', $palign='', $border=0, $fitonpage=false);
		
		PDF::RoundedRect(310, 215, 145, 95, 0.000001, '1111', null, $style6);
		PDF::ImageSVG('@' . $svg, $x=310, $y=215, $w=145, $h=95, $link='', $align='', $palign='', $border=0, $fitonpage=false);

				$file_path =  "/storage/app/template/".$style_no."-".$name."-".$buyer_no."."."pdf";	
		
        PDF::Output( public_path('/storage/app/template/'.$style_no.'-'.$name.'-'.$buyer_no.'.pdf'), 'F');
        return response()->json(['error'=> false,'message' =>'success', 'file_path' => $file_path,'url'=>url('/storage/app/template/'.$style_no.'-'.$name.'-'.$buyer_no.'.pdf')],200);	
    } 
	
	
	
	
	/**
	 * Print Preview For 150 * 100 Size Stickers
	 *
	 * @return pdf
	 */
	
    public function proofPdfArr(Request $request)
    {
		 $name=$request->name;
		 $style_no=$request->style_no;
		 $buyer_no=$request->buyer_no;
		 
		 $svgarr = explode(',_',$request->svg);
         //$svg=urldecode($request->svg);
         define ('PDF_FONT_NAME_MAIN', 'calibrib');
         define ('PDF_FONT_NAME_DATA', 'calibrib');
//          print_r($svg);
        PDF::setPrintHeader(false);
        PDF::setPrintFooter(false);
		PDF::SetAutoPageBreak(TRUE);
        PDF::SetFont('calibrib');
		//PDF::RoundedRect(10, 10, 150, 100, 0, '0000');
		//PDF::RoundedRect(12.5, 12.5, 145, 95, 0, '0000');
		
		$style6 = array('width' => 0.353, 'cap' => '', 'join' => '', 'dash' => '', 'color' => '');
		PDF::SetAutoPageBreak(TRUE);
		foreach($svgarr as $value)
        {
			$svg=urldecode($value);
			
			PDF::SetMargins(0, 0, 0, false);
			PDF::AddPage('L',array(324,465));
			PDF::setImageScale(1.66);
			$style6 = array('width' => 0.353, 'cap' => '', 'join' => '', 'dash' => '', 'color' => '');
			//PDF::RoundedRect(95, 255, 40, 30, 0.1, '1111', null, $style6);
			//PDF::RoundedRect(140, 255, 40, 30, 0.1, '1111', 'null', $style6);

			//Top Left Corner
			PDF::Line(7, 6, 7, 12, $style6);
			PDF::Line(1, 12, 7, 12, $style6);

			//Bottom Left Corner
			PDF::Line(7, 313, 7, 319, $style6);
			PDF::Line(1, 313, 7, 313, $style6);

			//Top Right Corner
			PDF::Line(458, 6, 458, 12, $style6);
			PDF::Line(458, 12, 464, 12, $style6);

			//Bottom Right Corner
			PDF::Line(458, 313, 464, 313, $style6);
			PDF::Line(458, 313, 458, 319, $style6);

			PDF::RoundedRect(10, 15, 145, 95, 0.000001, '1111', null, $style6);
			PDF::ImageSVG('@' . $svg, $x=10, $y=15, $w=145, $h=95, $link='', $align='', $palign='', $border=0, $fitonpage=false);

			PDF::Line(157.5, 6, 157.5, 12, $style6);
			PDF::Line(157.5, 313, 157.5, 319, $style6);

			PDF::RoundedRect(160, 15, 145, 95, 0.000001, '1111', null, $style6);
			PDF::ImageSVG('@' . $svg, $x=160, $y=15, $w=145, $h=95, $link='', $align='', $palign='', $border=0, $fitonpage=false);

			PDF::Line(307.5, 6, 307.5, 12, $style6);
			PDF::Line(307.5, 313, 307.5, 319, $style6);

			PDF::RoundedRect(310, 15, 145, 95, 0.000001, '1111', null, $style6);
			PDF::ImageSVG('@' . $svg, $x=310, $y= 15, $w=145, $h=95, $link='', $align='', $palign='', $border=0, $fitonpage=false);

			PDF::Line(1, 112.5, 7, 112.5, $style6);
			PDF::Line(458, 112.5, 464, 112.5, $style6);

			PDF::RoundedRect(10, 115, 145, 95, 0.000001, '1111', null, $style6);
			PDF::ImageSVG('@' . $svg, $x=10, $y=115, $w=145, $h=95, $link='', $align='', $palign='', $border=0, $fitonpage=false);

			PDF::RoundedRect(160, 115, 145, 95, 0.000001, '1111', null, $style6);
			PDF::ImageSVG('@' . $svg, $x=160, $y=115, $w=145, $h=95, $link='', $align='', $palign='', $border=0, $fitonpage=false);

			PDF::RoundedRect(310, 115, 145, 95, 0.000001, '1111', null, $style6);
			PDF::ImageSVG('@' . $svg, $x=310, $y=115, $w=145, $h=95, $link='', $align='', $palign='', $border=0, $fitonpage=false);

			PDF::Line(1, 212.5, 7, 212.5, $style6);
			PDF::Line(458, 212.5, 464, 212.5, $style6);

			PDF::RoundedRect(10, 215, 145, 95, 0.000001, '1111', null, $style6);
			PDF::ImageSVG('@' . $svg, $x=10, $y=215, $w=145, $h=95, $link='', $align='', $palign='', $border=0, $fitonpage=false);

			PDF::RoundedRect(160, 215, 145, 95, 0.000001, '1111', null, $style6);
			PDF::ImageSVG('@' . $svg, $x=160, $y=215, $w=145, $h=95, $link='', $align='', $palign='', $border=0, $fitonpage=false);

			PDF::RoundedRect(310, 215, 145, 95, 0.000001, '1111', null, $style6);
			PDF::ImageSVG('@' . $svg, $x=310, $y=215, $w=145, $h=95, $link='', $align='', $palign='', $border=0, $fitonpage=false);
		 }
		 
		 PDF::Output( public_path('/storage/app/template/'.$style_no.'-'.$name.'-'.$buyer_no.'.pdf'), 'F');
		$file_path =  "/storage/app/template/".$style_no."-".$name."-".$buyer_no."."."pdf";	
		 return response()->json(['error'=> false,'message' =>'success', 'file_path' => $file_path,'url'=>url('/storage/app/template/'.$style_no.'-'.$name.'-'.$buyer_no.'.pdf')],200);	
		
    } 
	public function printPdfArr(Request $request)
    {
		 $name=$request->style_no."-SOL-CS-".$request->buyer_no;
		 $id=$request->id;
		 $svgarr = explode(',_',$request->svg);
         //$svg=urldecode($request->svg);
         define ('PDF_FONT_NAME_MAIN', 'calibrib');
         define ('PDF_FONT_NAME_DATA', 'calibrib');
//          print_r($svg);
		
			
		
	   
	   $customer = DB::table("erp_orders")
						->join('customers', 'erp_orders.customer_id', 'customers.id')
						->select('customers.short_name')
						->where('erp_orders.id',$id)
						->get();
		 $customer_name = $customer[0]->short_name;
		 $this->customer_name = $customer_name;
		
		 $this->created_date = $request->created_at;
		
		 $this->template_name = "";
		 $this->template_zone = "";
	     $this->template_desc = "Solid Carton";
		 $this->tesco_po_number = $request->buyer_no;
		 $this->style = $request->style_no;
//	   
//		 define ('PDF_FONT_NAME_MAIN', 'calibri');
//		 define ('PDF_FONT_NAME_DATA', 'calibri');
			// Custom Header
		  PDF::setHeaderCallback(function($pdf) {
			$pdf->SetY(0);
		  $name=$this->template_name;
		  $zone=$this->template_zone;
			$tesco_po_number=$this->tesco_po_number;
			$style=$this->style;
			$tesco_description=$this->template_desc;
			$date=date_create($this->created_date);
			$created_date =  date_format($date,"d.m.Y");
			$image_file= K_PATH_IMAGES.'logo_example.jpg';
			$svg='<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="188.244mm" height="50.1333mm" viewBox="0 0 1024 264" version="1.2" baseProfile="tiny">
					<title>Qt Svg Document</title>
					<desc>Generated with Qt</desc>
					<defs>
					</defs>
					<g fill="none" stroke="black" stroke-width="1" fill-rule="evenodd" stroke-linecap="square" stroke-linejoin="bevel">
					<g fill="#ffffff" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1024,0 L1024,264 L0,264 L0,0"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1024,0 L1024,264 L0,264 L0,0"/>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal" style="&#10;    /* border-radius: 27px; */&#10;">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,65 L666,65 L666,66 L499,66 L499,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,65 L500,65 L500,144 L499,144 L499,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,65 L821,65 L821,66 L665,66 L665,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,65 L969,65 L969,66 L820,66 L820,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M968,65 L969,65 L969,105 L968,105 L968,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M968,104 L969,104 L969,144 L968,144 L968,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,182 L821,182 L821,183 L499,183 L499,182"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,143 L500,143 L500,183 L499,183 L499,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,182 L969,182 L969,183 L820,183 L820,182"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M968,143 L969,143 L969,183 L968,183 L968,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,143 L666,143 L666,144 L499,144 L499,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,65 L666,65 L666,144 L665,144 L665,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,104 L821,104 L821,105 L665,105 L665,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,65 L666,65 L666,105 L665,105 L665,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,65 L821,65 L821,105 L820,105 L820,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L969,104 L969,105 L820,105 L820,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,65 L821,65 L821,105 L820,105 L820,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,104 L821,104 L821,105 L665,105 L665,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,104 L666,104 L666,143 L665,143 L665,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L821,104 L821,143 L820,143 L820,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L969,104 L969,105 L820,105 L820,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L969,143 L969,144 L820,144 L820,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L821,104 L821,144 L820,144 L820,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,143 L821,143 L821,144 L499,144 L499,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L821,143 L821,183 L820,183 L820,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L969,143 L969,144 L820,144 L820,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L821,143 L821,183 L820,183 L820,143"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,212 L1016,212 L1016,213 L8,213 L8,212"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,213 L1016,213 L1016,214 L8,214 L8,213"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,212 L8,214 L9,213 L9,212 L8,212"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1015,213 L1015,214 L1016,214 L1016,212 L1015,213"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,216 L1016,216 L1016,217 L8,217 L8,216"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,217 L1016,217 L1016,218 L8,218 L8,217"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,216 L8,218 L9,217 L9,216 L8,216"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1015,217 L1015,218 L1016,218 L1016,216 L1015,217"/>
					</g>
					<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="56cm" height="37cm" version="1.1">
							<image xlink:href="'.public_path('/images/jupiter.jpg').'" x="174" y="66" height="65px" width="116px" style=""/>
					</svg>
					<g xmlns="http://www.w3.org/2000/svg" fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="35" font-style="normal" font-weight="700">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="50" y="176" font-family="Times New Roman" font-size="30" font-weight="700" font-style="normal">Jupiter Garments Accessories</text>

					</g>
					<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="507" y="127" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">PO NO : '.$tesco_po_number.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="671" y="90" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">STYLE : '.$style.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="90" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">REF : '.$name.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="671" y="129" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">DATE : '.$created_date.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="129" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">Size: 150x100mm</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="505" y="168" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">DESCRIPTION : '.$tesco_description.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="168" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">REV.NO :</text>
					</g>
					</g>
					</svg>';
					$pdf->ImageSVG('@' . $svg, '', '', '', '', $link='', $align='', $palign='', $border=0, $fitonpage=false);
			});
	   
	   
			// Custom Footer
			PDF::setFooterCallback(function($pdf) {
			$tesco_po_number=$this->tesco_po_number;
			$customer_name=$this->customer_name;
			// Position at 15 mm from bottom
			$pdf->SetY(-50);
			$pdf->SetX(9.5);
			// Set font
			//$pdf->SetFont('frankgothcnbt');
			// Page number
			$svg='<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="188mm" height="65.2667mm" viewBox="0 0 1360 168" version="1.2" baseProfile="tiny">
					<title>Qt Svg Document</title>
					<desc>Generated with Qt</desc>
					<defs>
					</defs>
					<g fill="none" stroke="black" stroke-width="1" fill-rule="evenodd" stroke-linecap="square" stroke-linejoin="bevel">
					<g fill="#ffffff" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1360,0 L1360,168 L0,168 L0,0"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1360,0 L1360,168 L0,168 L0,0"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,52 L1352,52 L1352,53 L8,53 L8,52"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,53 L1352,53 L1352,54 L8,54 L8,53"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,52 L8,54 L9,53 L9,52 L8,52"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1351,53 L1351,54 L1352,54 L1352,52 L1351,53"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,56 L1352,56 L1352,57 L8,57 L8,56"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,57 L1352,57 L1352,58 L8,58 L8,57"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,56 L8,58 L9,57 L9,56 L8,56"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1351,57 L1351,58 L1352,58 L1352,56 L1351,57"/>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M385,79 L386,79 L386,157 L385,157 L385,79"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M764,79 L765,79 L765,157 L764,157 L764,79"/>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="41" y="100" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">CUSTOMER : '.$customer_name.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="41" y="152" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">PO NO : '.$tesco_po_number.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="405" y="100" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">APPROVED BY : </text>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="700" font-style="normal">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="405" y="152" font-family="Times New Roman" font-size="23" font-weight="700" font-style="normal">PREPARED BY:</text>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="784" y="103" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">COMMENTS IF ANY :</text>
					</g>
					</g>
					</svg>';
					
					 $pdf->ImageSVG('@' . $svg, '', '', '', '', $link='', $align='', $palign='', $border=0, $fitonpage=false);
		
		});
        PDF::setImageScale(1.66);
//        PDF::setPrintHeader(false);
//        PDF::setPrintFooter(false);
        PDF::SetFont('calibrib');
		PDF::AddPage('P', 'A4');
		//PDF::RoundedRect(10, 10, 150, 100, 0, '0000');
		//PDF::RoundedRect(12.5, 12.5, 145, 95, 0, '0000');
		
		$style6 = array('width' => 0.353, 'cap' => '', 'join' => '', 'dash' => '', 'color' => '');
        PDF::RoundedRect(10, 47.5, 150, 100, 0.000001, '1111', null, $style6);
        PDF::RoundedRect(12.5, 50, 145, 95, 0.000001, '1111', null, $style6);
		PDF::SetAutoPageBreak(TRUE);
		$i=0;
		$j=0;
		$yy=50;
		foreach($svgarr as $value)
        {
			$svg=urldecode($value);
			
			
			if($i == 0)
			{
				//PDF::RoundedRect(10, 160, 150, 100, 0, '0000');
				$yy=50;
				//PDF::RoundedRect(12.5, 162.5, 145, 95, 0, '0000');
				
				PDF::RoundedRect(10, 47.5, 150, 100, 0.000001, '1111', null, $style6);
        PDF::RoundedRect(12.5, 50, 145, 95, 0.000001, '1111', null, $style6);
				PDF::ImageSVG('@' . $svg, $x=12.5, $y=$yy, $w=145, $h=95, $link='', $align='', $palign='', $border=0, $fitonpage=false);
				
			}
			else if($i == 2)
			{
				PDF::AddPage('P', 'A4');
				//PDF::RoundedRect(10, 10, 150, 100, 0, '0000');
				$yy=50;
				//PDF::RoundedRect(12.5, 12.5, 145, 95, 0, '0000');
				
				PDF::RoundedRect(10, 47.5, 150, 100, 0.000001, '1111', null, $style6);
        PDF::RoundedRect(12.5, 50, 145, 95, 0.000001, '1111', null, $style6);
				PDF::ImageSVG('@' . $svg, $x=12.5, $y=$yy, $w=145, $h=95, $link='', $align='', $palign='', $border=0, $fitonpage=false);
				$i = 0;
			}
			else
			{
				//PDF::RoundedRect(10, 160, 150, 100, 0, '0000');
				$yy += 112.5;
				//PDF::RoundedRect(12.5, 162.5, 145, 95, 0, '0000');
				
				PDF::RoundedRect(10, 160, 150, 100, 0.000001, '1111', null, $style6);
				PDF::RoundedRect(12.5, 162.5, 145, 95, 0.000001, '1111', null, $style6);
				PDF::ImageSVG('@' . $svg, $x=12.5, $y=$yy, $w=145, $h=95, $link='', $align='', $palign='', $border=0, $fitonpage=false);
			}
			$i++;
			$j++;
		 }
		 $random = number_format(Carbon::now('Asia/Kolkata')->timestamp * rand(),0,'','');
		 PDF::Output( public_path('/storage/app/template/'.$name.'.pdf'), 'F');
		//print_r(url('/storage/app/template/'.$name.'.pdf'));
	 
		$file_path =  "/storage/app/template/".$name."."."pdf";	

		return response()->json(['error'=> false,'message' =>'success', 'file_path' => $file_path,'url'=>url('/storage/app/template/'.$name.'.pdf')],200);	
    } 

	public function printPdf(Request $request)
	{
		 $name=$request->style_no."-STD-CS-".$request->buyer_no;
		 $id=$request->id;
		 $svg=urldecode($request->svg);
//		 define ('PDF_FONT_NAME_MAIN', 'calibrib');
//		 define ('PDF_FONT_NAME_DATA', 'calibrib');
//          print_r($svg);
		
		
	   
	   $customer = DB::table("erp_orders")
						->join('customers', 'erp_orders.customer_id', 'customers.id')
						->select('customers.short_name')
						->where('erp_orders.id',$id)
						->get();
		 $customer_name = $customer[0]->short_name;
		 $this->customer_name = $customer_name;
		
		 $this->created_date = $request->created_at;
		
		 $this->template_name = "";
		 $this->template_zone = "";
	   $this->template_desc = "Standard Carton";
		 $this->tesco_po_number = $request->buyer_no;
		 $this->style = $request->style_no;
	   
		 define ('PDF_FONT_NAME_MAIN', 'calibri');
		 define ('PDF_FONT_NAME_DATA', 'calibri');
			// Custom Header
		  PDF::setHeaderCallback(function($pdf) {
			$pdf->SetY(0);
		  $name=$this->template_name;
		  $zone=$this->template_zone;
			$tesco_po_number=$this->tesco_po_number;
			$style=$this->style;
			$tesco_description=$this->template_desc;
			$date=date_create($this->created_date);
			$created_date =  date_format($date,"d.m.Y");
			$image_file= K_PATH_IMAGES.'logo_example.jpg';
			$svg='<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="188.244mm" height="50.1333mm" viewBox="0 0 1024 264" version="1.2" baseProfile="tiny">
					<title>Qt Svg Document</title>
					<desc>Generated with Qt</desc>
					<defs>
					</defs>
					<g fill="none" stroke="black" stroke-width="1" fill-rule="evenodd" stroke-linecap="square" stroke-linejoin="bevel">
					<g fill="#ffffff" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1024,0 L1024,264 L0,264 L0,0"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1024,0 L1024,264 L0,264 L0,0"/>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal" style="&#10;    /* border-radius: 27px; */&#10;">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,65 L666,65 L666,66 L499,66 L499,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,65 L500,65 L500,144 L499,144 L499,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,65 L821,65 L821,66 L665,66 L665,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,65 L969,65 L969,66 L820,66 L820,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M968,65 L969,65 L969,105 L968,105 L968,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M968,104 L969,104 L969,144 L968,144 L968,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,182 L821,182 L821,183 L499,183 L499,182"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,143 L500,143 L500,183 L499,183 L499,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,182 L969,182 L969,183 L820,183 L820,182"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M968,143 L969,143 L969,183 L968,183 L968,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,143 L666,143 L666,144 L499,144 L499,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,65 L666,65 L666,144 L665,144 L665,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,104 L821,104 L821,105 L665,105 L665,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,65 L666,65 L666,105 L665,105 L665,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,65 L821,65 L821,105 L820,105 L820,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L969,104 L969,105 L820,105 L820,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,65 L821,65 L821,105 L820,105 L820,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,104 L821,104 L821,105 L665,105 L665,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,104 L666,104 L666,143 L665,143 L665,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L821,104 L821,143 L820,143 L820,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L969,104 L969,105 L820,105 L820,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L969,143 L969,144 L820,144 L820,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L821,104 L821,144 L820,144 L820,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,143 L821,143 L821,144 L499,144 L499,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L821,143 L821,183 L820,183 L820,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L969,143 L969,144 L820,144 L820,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L821,143 L821,183 L820,183 L820,143"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,212 L1016,212 L1016,213 L8,213 L8,212"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,213 L1016,213 L1016,214 L8,214 L8,213"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,212 L8,214 L9,213 L9,212 L8,212"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1015,213 L1015,214 L1016,214 L1016,212 L1015,213"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,216 L1016,216 L1016,217 L8,217 L8,216"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,217 L1016,217 L1016,218 L8,218 L8,217"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,216 L8,218 L9,217 L9,216 L8,216"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1015,217 L1015,218 L1016,218 L1016,216 L1015,217"/>
					</g>
					<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="56cm" height="37cm" version="1.1">
							<image xlink:href="'.public_path('/images/jupiter.jpg').'" x="174" y="66" height="65px" width="116px" style=""/>
					</svg>
					<g xmlns="http://www.w3.org/2000/svg" fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="35" font-style="normal" font-weight="700">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="50" y="176" font-family="Times New Roman" font-size="30" font-weight="700" font-style="normal">Jupiter Garments Accessories</text>

					</g>
					<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="507" y="127" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">PO NO : '.$tesco_po_number.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="671" y="90" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">STYLE : '.$style.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="90" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">REF : '.$name.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="671" y="129" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">DATE : '.$created_date.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="129" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">Size: 150x100mm</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="505" y="168" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">DESCRIPTION : '.$tesco_description.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="168" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">REV.NO :</text>
					</g>
					</g>
					</svg>';
					$pdf->ImageSVG('@' . $svg, '', '', '', '', $link='', $align='', $palign='', $border=0, $fitonpage=false);
			});
	   
	   
			// Custom Footer
			PDF::setFooterCallback(function($pdf) {
			$tesco_po_number=$this->tesco_po_number;
			$customer_name=$this->customer_name;
			// Position at 15 mm from bottom
			$pdf->SetY(-50);
			$pdf->SetX(9.5);
			// Set font
			//$pdf->SetFont('frankgothcnbt');
			// Page number
			$svg='<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="188mm" height="65.2667mm" viewBox="0 0 1360 168" version="1.2" baseProfile="tiny">
					<title>Qt Svg Document</title>
					<desc>Generated with Qt</desc>
					<defs>
					</defs>
					<g fill="none" stroke="black" stroke-width="1" fill-rule="evenodd" stroke-linecap="square" stroke-linejoin="bevel">
					<g fill="#ffffff" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1360,0 L1360,168 L0,168 L0,0"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1360,0 L1360,168 L0,168 L0,0"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,52 L1352,52 L1352,53 L8,53 L8,52"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,53 L1352,53 L1352,54 L8,54 L8,53"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,52 L8,54 L9,53 L9,52 L8,52"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1351,53 L1351,54 L1352,54 L1352,52 L1351,53"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,56 L1352,56 L1352,57 L8,57 L8,56"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,57 L1352,57 L1352,58 L8,58 L8,57"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,56 L8,58 L9,57 L9,56 L8,56"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1351,57 L1351,58 L1352,58 L1352,56 L1351,57"/>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M385,79 L386,79 L386,157 L385,157 L385,79"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M764,79 L765,79 L765,157 L764,157 L764,79"/>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="41" y="100" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">CUSTOMER : '.$customer_name.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="41" y="152" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">PO NO : '.$tesco_po_number.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="405" y="100" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">APPROVED BY : </text>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="700" font-style="normal">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="405" y="152" font-family="Times New Roman" font-size="23" font-weight="700" font-style="normal">PREPARED BY:</text>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="784" y="103" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">COMMENTS IF ANY :</text>
					</g>
					</g>
					</svg>';
					
					 $pdf->ImageSVG('@' . $svg, '', '', '', '', $link='', $align='', $palign='', $border=0, $fitonpage=false);
		
		});
		PDF::setImageScale(1);
//		PDF::setPrintHeader(false);
//		PDF::setPrintFooter(false);
		PDF::SetFont('calibrib');
		PDF::AddPage();
		$style6 = array('width' => 0.353, 'cap' => '', 'join' => '', 'dash' => '', 'color' => '');
		//PDF::RoundedRect(95, 255, 40, 30, 0.1, '1111', null, $style6);
		//PDF::RoundedRect(140, 255, 40, 30, 0.1, '1111', 'null', $style6);
		PDF::RoundedRect(10, 47.5, 150, 100, 0.000001, '1111', null, $style6);
		PDF::RoundedRect(12.5, 50, 145, 95, 0.000001, '1111', null, $style6);
		PDF::ImageSVG('@' . $svg, $x=12.5, $y=50, $w=145, $h=95, $link='', $align='', $palign='', $border=0, $fitonpage=false);
		$random = number_format(Carbon::now('Asia/Kolkata')->timestamp * rand(),0,'','');
		PDF::Output( public_path('/storage/app/template/'.$name.'.pdf'), 'F');
		//print_r(url('/storage/app/template/'.$name.'.pdf'));
	 
		$file_path =  "/storage/app/template/".$name."."."pdf";	

		return response()->json(['error'=> false,'message' =>'success', 'file_path' => $file_path,'url'=>url('/storage/app/template/'.$name.'.pdf')],200);	
	} 




	public function printPdfSmall(Request $request)
	{
		 
		 $name=$request->style_no."-SOL-PS-".$request->buyer_no;
		$id=$request->id;
		 $svgarr = explode(',_',$request->svg);
		 //$svg=urldecode($request->svg);
//		 define ('PDF_FONT_NAME_MAIN', 'calibrib');
//		 define ('PDF_FONT_NAME_DATA', 'calibrib');
//          print_r($svg);
		
		  $customer = DB::table("erp_orders")
						->join('customers', 'erp_orders.customer_id', 'customers.id')
						->select('customers.short_name')
						->where('erp_orders.id',$id)
						->get();
		 $customer_name = $customer[0]->short_name;
		 $this->customer_name = $customer_name;
		
		 $this->created_date = $request->created_at;
		
		 $this->template_name = "";
		 $this->template_zone = "";
	     $this->template_desc = "Solid Polybag";
		 $this->tesco_po_number = $request->buyer_no;
		 $this->style = $request->style_no;
	   
		 define ('PDF_FONT_NAME_MAIN', 'calibri');
		 define ('PDF_FONT_NAME_DATA', 'calibri');
			// Custom Header
		  PDF::setHeaderCallback(function($pdf) {
			$pdf->SetY(0);
		  $name=$this->template_name;
		  $zone=$this->template_zone;
			$tesco_po_number=$this->tesco_po_number;
			$style=$this->style;
			$tesco_description=$this->template_desc;
			$date=date_create($this->created_date);
			$created_date =  date_format($date,"d.m.Y");
			$image_file= K_PATH_IMAGES.'logo_example.jpg';
			$svg='<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="188.244mm" height="50.1333mm" viewBox="0 0 1024 264" version="1.2" baseProfile="tiny">
					<title>Qt Svg Document</title>
					<desc>Generated with Qt</desc>
					<defs>
					</defs>
					<g fill="none" stroke="black" stroke-width="1" fill-rule="evenodd" stroke-linecap="square" stroke-linejoin="bevel">
					<g fill="#ffffff" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1024,0 L1024,264 L0,264 L0,0"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1024,0 L1024,264 L0,264 L0,0"/>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal" style="&#10;    /* border-radius: 27px; */&#10;">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,65 L666,65 L666,66 L499,66 L499,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,65 L500,65 L500,144 L499,144 L499,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,65 L821,65 L821,66 L665,66 L665,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,65 L969,65 L969,66 L820,66 L820,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M968,65 L969,65 L969,105 L968,105 L968,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M968,104 L969,104 L969,144 L968,144 L968,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,182 L821,182 L821,183 L499,183 L499,182"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,143 L500,143 L500,183 L499,183 L499,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,182 L969,182 L969,183 L820,183 L820,182"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M968,143 L969,143 L969,183 L968,183 L968,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,143 L666,143 L666,144 L499,144 L499,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,65 L666,65 L666,144 L665,144 L665,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,104 L821,104 L821,105 L665,105 L665,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,65 L666,65 L666,105 L665,105 L665,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,65 L821,65 L821,105 L820,105 L820,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L969,104 L969,105 L820,105 L820,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,65 L821,65 L821,105 L820,105 L820,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,104 L821,104 L821,105 L665,105 L665,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,104 L666,104 L666,143 L665,143 L665,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L821,104 L821,143 L820,143 L820,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L969,104 L969,105 L820,105 L820,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L969,143 L969,144 L820,144 L820,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L821,104 L821,144 L820,144 L820,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,143 L821,143 L821,144 L499,144 L499,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L821,143 L821,183 L820,183 L820,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L969,143 L969,144 L820,144 L820,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L821,143 L821,183 L820,183 L820,143"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,212 L1016,212 L1016,213 L8,213 L8,212"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,213 L1016,213 L1016,214 L8,214 L8,213"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,212 L8,214 L9,213 L9,212 L8,212"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1015,213 L1015,214 L1016,214 L1016,212 L1015,213"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,216 L1016,216 L1016,217 L8,217 L8,216"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,217 L1016,217 L1016,218 L8,218 L8,217"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,216 L8,218 L9,217 L9,216 L8,216"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1015,217 L1015,218 L1016,218 L1016,216 L1015,217"/>
					</g>
					<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="56cm" height="37cm" version="1.1">
							<image xlink:href="'.public_path('/images/jupiter.jpg').'" x="174" y="66" height="65px" width="116px" style=""/>
					</svg>
					<g xmlns="http://www.w3.org/2000/svg" fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="35" font-style="normal" font-weight="700">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="50" y="176" font-family="Times New Roman" font-size="30" font-weight="700" font-style="normal">Jupiter Garments Accessories</text>

					</g>
					<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="507" y="127" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">PO NO : '.$tesco_po_number.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="671" y="90" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">STYLE : '.$style.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="90" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">REF : '.$name.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="671" y="129" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">DATE : '.$created_date.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="129" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">Size: 100x70mm</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="505" y="168" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">DESCRIPTION : '.$tesco_description.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="168" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">REV.NO :</text>
					</g>
					</g>
					</svg>';
					$pdf->ImageSVG('@' . $svg, '', '', '', '', $link='', $align='', $palign='', $border=0, $fitonpage=false);
			});
	   
	   
			// Custom Footer
			PDF::setFooterCallback(function($pdf) {
			$tesco_po_number=$this->tesco_po_number;
			$customer_name=$this->customer_name;
			// Position at 15 mm from bottom
			$pdf->SetY(-50);
			$pdf->SetX(9.5);
			// Set font
			//$pdf->SetFont('frankgothcnbt');
			// Page number
			$svg='<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="188mm" height="65.2667mm" viewBox="0 0 1360 168" version="1.2" baseProfile="tiny">
					<title>Qt Svg Document</title>
					<desc>Generated with Qt</desc>
					<defs>
					</defs>
					<g fill="none" stroke="black" stroke-width="1" fill-rule="evenodd" stroke-linecap="square" stroke-linejoin="bevel">
					<g fill="#ffffff" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1360,0 L1360,168 L0,168 L0,0"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1360,0 L1360,168 L0,168 L0,0"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,52 L1352,52 L1352,53 L8,53 L8,52"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,53 L1352,53 L1352,54 L8,54 L8,53"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,52 L8,54 L9,53 L9,52 L8,52"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1351,53 L1351,54 L1352,54 L1352,52 L1351,53"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,56 L1352,56 L1352,57 L8,57 L8,56"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,57 L1352,57 L1352,58 L8,58 L8,57"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,56 L8,58 L9,57 L9,56 L8,56"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1351,57 L1351,58 L1352,58 L1352,56 L1351,57"/>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M385,79 L386,79 L386,157 L385,157 L385,79"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M764,79 L765,79 L765,157 L764,157 L764,79"/>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="41" y="100" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">CUSTOMER : '.$customer_name.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="41" y="152" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">PO NO : '.$tesco_po_number.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="405" y="100" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">APPROVED BY : </text>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="700" font-style="normal">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="405" y="152" font-family="Times New Roman" font-size="23" font-weight="700" font-style="normal">PREPARED BY:</text>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="784" y="103" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">COMMENTS IF ANY :</text>
					</g>
					</g>
					</svg>';
					
					 $pdf->ImageSVG('@' . $svg, '', '', '', '', $link='', $align='', $palign='', $border=0, $fitonpage=false);
		
		});
		PDF::setImageScale(1.66);
//		PDF::setPrintHeader(false);
//		PDF::setPrintFooter(false);
		PDF::SetFont('calibrib');
		PDF::AddPage('P', 'A4');
		//PDF::RoundedRect(10, 10, 100, 70, 0, '0000');
		//PDF::RoundedRect(12.5, 12.5, 95, 65, 0, '0000');
		 
		$style6 = array('width' => 0.353, 'cap' => '', 'join' => '', 'dash' => '', 'color' => '');
		 
//		PDF::SetAutoPageBreak(TRUE);
		$i=0;
		$j=0;
		$yy=50;
		foreach($svgarr as $value)
        {
			$svg=urldecode($value);
			
			if($i == 3)
			{
				PDF::AddPage('P', 'A4');
				//PDF::RoundedRect(10, 10, 100, 70, 0, '0000');
				//PDF::RoundedRect(12.5, 12.5, 95, 65, 0, '0000');
				
				PDF::RoundedRect(55, 47.5, 100, 70, 0.000001, '1111', null, $style6);
				PDF::RoundedRect(57.5, 50, 95, 65, 0.000001, '1111', null, $style6);
				$yy=50;
				PDF::ImageSVG('@' . $svg, $x=57.5, $y=$yy, $w=95, $h=65, $link='', $align='', $palign='', $border=0, $fitonpage=false);
				$i = 0;
			}
			else  if($i == 0)
			{
				//PDF::RoundedRect(10, 100, 100, 70, 0, '0000');
				//PDF::RoundedRect(12.5, 102.5, 95, 65, 0, '0000');
				
				PDF::RoundedRect(55, 47.5, 100, 70, 0.000001, '1111', null, $style6);
				PDF::RoundedRect(57.5, 50, 95, 65, 0.000001, '1111', null, $style6);
				$yy=50;
				PDF::ImageSVG('@' . $svg, $x=57.5, $y=$yy, $w=95, $h=65, $link='', $align='', $palign='', $border=0, $fitonpage=false);
				
			}
			else if($i == 1)
			{
				//PDF::RoundedRect(10, 100, 100, 70, 0, '0000');
				//PDF::RoundedRect(12.5, 102.5, 95, 65, 0, '0000');
				PDF::RoundedRect(55, 122.5, 100, 70, 0.000001, '1111', null, $style6);
				PDF::RoundedRect(57.5, 125, 95, 65, 0.000001, '1111', null, $style6);
				$yy += 75;
				PDF::ImageSVG('@' . $svg, $x=57.5, $y=$yy, $w=95, $h=65, $link='', $align='', $palign='', $border=0, $fitonpage=false);
			}
			else
			{
				//PDF::RoundedRect(10, 190, 100, 70, 0, '0000');
				//PDF::RoundedRect(12.5, 192.5, 95, 65, 0, '0000');
				PDF::RoundedRect(55, 197.5, 100, 70, 0.000001, '1111', null, $style6);
				PDF::RoundedRect(57.5, 200, 95, 65, 0.000001, '1111', null, $style6);
				$yy += 75;
				PDF::ImageSVG('@' . $svg, $x=57.5, $y=$yy, $w=95, $h=65, $link='', $align='', $palign='', $border=0, $fitonpage=false);
			}
			$i++;
			$j++;
		 }
		$random = number_format(Carbon::now('Asia/Kolkata')->timestamp * rand(),0,'','');
		PDF::Output( public_path('/storage/app/template/'.$name.'.pdf'), 'F');
		//print_r(url('/storage/app/template/'.$name.'.pdf'));
	 
		$file_path =  "/storage/app/template/".$name."."."pdf";	

		return response()->json(['error'=> false,'message' =>'success', 'file_path' => $file_path,'url'=>url('/storage/app/template/'.$name.'.pdf')],200);	
	}

	/**
	 * Print Preview For Hang Tag
	 *
	 * @return pdf
	 */
	
	public function printHangPdf(Request $request)
	{
		$svgarrfront=explode(',_',$request->svg); 
		$name = $request->name;
		//$svg1=urldecode($request->svg1); 
		$svgarr=explode(',_',$request->svgRoot); 
		 $id = $request->id;
		 
	   
	   $customer = DB::table("erp_orders")
						->join('customers', 'erp_orders.customer_id', 'customers.id')
						->select('customers.short_name')
						->where('erp_orders.id',$id)
						->get();
		 $customer_name = $customer[0]->short_name;
		 $this->customer_name = $customer_name;
		
		 $this->created_date = $request->created_at;
		
		 $this->template_name = $request->name;
		 $this->template_zone = "";
	     $this->template_desc = "";
		 $this->tesco_po_number = $request->buyer_no;
		 $this->style = $request->style_no;
	   
		 define ('PDF_FONT_NAME_MAIN', 'calibri');
		 define ('PDF_FONT_NAME_DATA', 'calibri');
			// Custom Header
		  PDF::setHeaderCallback(function($pdf) {
			$pdf->SetY(0);
		  $name=$this->template_name;
		  $zone=$this->template_zone;
			$tesco_po_number=$this->tesco_po_number;
			$style=$this->style;
			$tesco_description=$this->template_desc;
			$date=date_create($this->created_date);
			$created_date =  date_format($date,"d.m.Y");
			$image_file= K_PATH_IMAGES.'logo_example.jpg';
			$svg='<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="188.244mm" height="50.1333mm" viewBox="0 0 1024 264" version="1.2" baseProfile="tiny">
					<title>Qt Svg Document</title>
					<desc>Generated with Qt</desc>
					<defs>
					</defs>
					<g fill="none" stroke="black" stroke-width="1" fill-rule="evenodd" stroke-linecap="square" stroke-linejoin="bevel">
					<g fill="#ffffff" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1024,0 L1024,264 L0,264 L0,0"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1024,0 L1024,264 L0,264 L0,0"/>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal" style="&#10;    /* border-radius: 27px; */&#10;">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,65 L666,65 L666,66 L499,66 L499,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,65 L500,65 L500,144 L499,144 L499,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,65 L821,65 L821,66 L665,66 L665,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,65 L969,65 L969,66 L820,66 L820,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M968,65 L969,65 L969,105 L968,105 L968,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M968,104 L969,104 L969,144 L968,144 L968,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,182 L821,182 L821,183 L499,183 L499,182"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,143 L500,143 L500,183 L499,183 L499,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,182 L969,182 L969,183 L820,183 L820,182"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M968,143 L969,143 L969,183 L968,183 L968,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,143 L666,143 L666,144 L499,144 L499,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,65 L666,65 L666,144 L665,144 L665,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,104 L821,104 L821,105 L665,105 L665,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,65 L666,65 L666,105 L665,105 L665,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,65 L821,65 L821,105 L820,105 L820,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L969,104 L969,105 L820,105 L820,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,65 L821,65 L821,105 L820,105 L820,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,104 L821,104 L821,105 L665,105 L665,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,104 L666,104 L666,143 L665,143 L665,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L821,104 L821,143 L820,143 L820,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L969,104 L969,105 L820,105 L820,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L969,143 L969,144 L820,144 L820,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L821,104 L821,144 L820,144 L820,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,143 L821,143 L821,144 L499,144 L499,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L821,143 L821,183 L820,183 L820,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L969,143 L969,144 L820,144 L820,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L821,143 L821,183 L820,183 L820,143"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,212 L1016,212 L1016,213 L8,213 L8,212"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,213 L1016,213 L1016,214 L8,214 L8,213"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,212 L8,214 L9,213 L9,212 L8,212"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1015,213 L1015,214 L1016,214 L1016,212 L1015,213"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,216 L1016,216 L1016,217 L8,217 L8,216"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,217 L1016,217 L1016,218 L8,218 L8,217"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,216 L8,218 L9,217 L9,216 L8,216"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1015,217 L1015,218 L1016,218 L1016,216 L1015,217"/>
					</g>
					<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="56cm" height="37cm" version="1.1">
							<image xlink:href="'.public_path('/images/jupiter.jpg').'" x="174" y="66" height="65px" width="116px" style=""/>
					</svg>
					<g xmlns="http://www.w3.org/2000/svg" fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="35" font-style="normal" font-weight="700">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="50" y="176" font-family="Times New Roman" font-size="30" font-weight="700" font-style="normal">Jupiter Garments Accessories</text>

					</g>
					<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="507" y="127" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">PO NO : '.$tesco_po_number.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="671" y="90" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">STYLE : '.$style.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="90" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">REF : '.$name.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="671" y="129" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">DATE : '.$created_date.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="129" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">Size: 127x80mm</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="505" y="168" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">DESCRIPTION : '.$tesco_description.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="168" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">REV.NO :</text>
					</g>
					</g>
					</svg>';
					$pdf->ImageSVG('@' . $svg, '', '', '', '', $link='', $align='', $palign='', $border=0, $fitonpage=false);
			});
	   
	   
			// Custom Footer
			PDF::setFooterCallback(function($pdf) {
			$tesco_po_number=$this->tesco_po_number;
			$customer_name=$this->customer_name;
			// Position at 15 mm from bottom
			$pdf->SetY(-50);
			$pdf->SetX(9.5);
			// Set font
			//$pdf->SetFont('frankgothcnbt');
			// Page number
			$svg='<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="188mm" height="65.2667mm" viewBox="0 0 1360 168" version="1.2" baseProfile="tiny">
					<title>Qt Svg Document</title>
					<desc>Generated with Qt</desc>
					<defs>
					</defs>
					<g fill="none" stroke="black" stroke-width="1" fill-rule="evenodd" stroke-linecap="square" stroke-linejoin="bevel">
					<g fill="#ffffff" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1360,0 L1360,168 L0,168 L0,0"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1360,0 L1360,168 L0,168 L0,0"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,52 L1352,52 L1352,53 L8,53 L8,52"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,53 L1352,53 L1352,54 L8,54 L8,53"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,52 L8,54 L9,53 L9,52 L8,52"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1351,53 L1351,54 L1352,54 L1352,52 L1351,53"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,56 L1352,56 L1352,57 L8,57 L8,56"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,57 L1352,57 L1352,58 L8,58 L8,57"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,56 L8,58 L9,57 L9,56 L8,56"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1351,57 L1351,58 L1352,58 L1352,56 L1351,57"/>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M385,79 L386,79 L386,157 L385,157 L385,79"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M764,79 L765,79 L765,157 L764,157 L764,79"/>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="41" y="100" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">CUSTOMER : '.$customer_name.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="41" y="152" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">PO NO : '.$tesco_po_number.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="405" y="100" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">APPROVED BY : </text>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="700" font-style="normal">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="405" y="152" font-family="Times New Roman" font-size="23" font-weight="700" font-style="normal">PREPARED BY:</text>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="784" y="103" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">COMMENTS IF ANY :</text>
					</g>
					</g>
					</svg>';
					
					 $pdf->ImageSVG('@' . $svg, '', '', '', '', $link='', $align='', $palign='', $border=0, $fitonpage=false);
		
		});
		
		PDF::setImageScale(1);
//		PDF::setPrintHeader(false);
//		PDF::setPrintFooter(false);
		PDF::SetFont('alternategothic2bt');
		PDF::AddPage();
		//PDF::RoundedRect(10, 10, 61, 131, 0, '0000');
		//PDF::SetFillColor(255, 255, 255);
		//PDF::SetTextColor(0, 0, 0);
		//PDF::ImageSVG('@' . $svg, $x=12.5, $y=12.5, '49', '117', $link='', $align='', $palign='', $border=0, $fitonpage=false);
		
		PDF::SetFont('arial');
		$count=0;
		$xx=12.5;
		$i=0;
		$yy=60;
		$arr_mer=array_merge($svgarrfront,$svgarr);
		$fir=0;
		$sec = count($arr_mer) / 2;
		foreach($arr_mer as $value)
		{
			$svg=urldecode($value);
			//PDF::RoundedRect(80, 10, 61, 131, 0, '0000');
			//PDF::ImageSVG('@' . $svg,  $x=$xx, $y=12.5, '49', '117', $link='', $align='', $palign='', $border=0, $fitonpage=false);
			
			
			if($count%2==0)  
			{  
				PDF::ImageSVG('@' . urldecode($arr_mer[$fir]),  $x=$xx, $y=$yy, '49', '117', $link='', $align='', $palign='', $border=0, $fitonpage=false);
				$fir++;
			}  
			else  
			{  
				PDF::ImageSVG('@' . urldecode($arr_mer[$sec]),  $x=$xx, $y=$yy, '49', '117', $link='', $align='', $palign='', $border=0, $fitonpage=false);
				$sec++;
			}   
			
			$xx += 65;
			$i++;
			
			if($i == 3)
			{
				PDF::AddPage();
				$i=0;
				$xx=12.5;
			}
			
			$count++;
		}
		
		PDF::Output( public_path('/storage/app/template/'.$name.'.pdf'), 'F');
		
		exec("gs -dSAFER -dBATCH -dNOPAUSE -dNOCACHE -sDEVICE=pdfwrite -sColorConversionStrategy=CMYK -dProcessColorModel=/DeviceCMYK -sOutputFile=".public_path('/storage/app/template/'.$name.'_cmyk.pdf')." ".public_path('/storage/app/template/'.$name.'.pdf'));
		
//        print_r(url('/storage/app/template/'.$name.'_cmyk.pdf'));
		
		//print_r(url('/storage/app/template/solid1.pdf'));
		$file_path =  "/storage/app/template/".$name."_cmyk.pdf";	

		return response()->json(['error'=> false,'message' =>'success', 'file_path' => $file_path,'url'=>url('/storage/app/template/'.$name.'_cmyk.pdf')],200);	
	} 	

	public function printHeatTransfer(Request $request)
	{
		$svgarrfront=explode(',_',$request->svg); 
		$db = $request->db;
		$id = $request->id;
		$name = $request->name;
		$template_id = $request->template_id;
		define ('PDF_FONT_NAME_MAIN', 'calibrib');
		define ('PDF_FONT_NAME_DATA', 'calibrib');
		
		
			
		$orchestra_item_master = DB::table("orchestra_item_master")
					->select('*')
					->where('id',$template_id)
					->get();
	   
	    $customer = DB::table("erp_orders")
						->join('customers', 'erp_orders.customer_id', 'customers.id')
						->select('customers.short_name')
						->where('erp_orders.id',$id)
						->get();
		
		 $customer_name = $customer[0]->short_name;
		 $this->customer_name = $customer_name;
		
		 $this->created_date = $request->created_at;
		
		 $this->template_name = $orchestra_item_master[0]->ref_no;
		 $this->template_zone = "";
	     $this->template_desc = "Heat Transfer";
		 $this->tesco_po_number = $request->buyer_no;
		 $this->style = $request->style_no;
	   
			// Custom Header
		  PDF::setHeaderCallback(function($pdf) {
			$pdf->SetY(0);
		  $name=$this->template_name;
		  $zone=$this->template_zone;
			$tesco_po_number=$this->tesco_po_number;
			$style=$this->style;
			$tesco_description=$this->template_desc;
			$date=date_create($this->created_date);
			$created_date =  date_format($date,"d.m.Y");
			$image_file= K_PATH_IMAGES.'logo_example.jpg';
			$svg='<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="188.244mm" height="50.1333mm" viewBox="0 0 1024 264" version="1.2" baseProfile="tiny">
					<title>Qt Svg Document</title>
					<desc>Generated with Qt</desc>
					<defs>
					</defs>
					<g fill="none" stroke="black" stroke-width="1" fill-rule="evenodd" stroke-linecap="square" stroke-linejoin="bevel">
					<g fill="#ffffff" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1024,0 L1024,264 L0,264 L0,0"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1024,0 L1024,264 L0,264 L0,0"/>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal" style="&#10;    /* border-radius: 27px; */&#10;">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,65 L666,65 L666,66 L499,66 L499,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,65 L500,65 L500,144 L499,144 L499,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,65 L821,65 L821,66 L665,66 L665,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,65 L969,65 L969,66 L820,66 L820,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M968,65 L969,65 L969,105 L968,105 L968,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M968,104 L969,104 L969,144 L968,144 L968,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,182 L821,182 L821,183 L499,183 L499,182"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,143 L500,143 L500,183 L499,183 L499,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,182 L969,182 L969,183 L820,183 L820,182"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M968,143 L969,143 L969,183 L968,183 L968,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,143 L666,143 L666,144 L499,144 L499,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,65 L666,65 L666,144 L665,144 L665,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,104 L821,104 L821,105 L665,105 L665,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,65 L666,65 L666,105 L665,105 L665,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,65 L821,65 L821,105 L820,105 L820,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L969,104 L969,105 L820,105 L820,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,65 L821,65 L821,105 L820,105 L820,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,104 L821,104 L821,105 L665,105 L665,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,104 L666,104 L666,143 L665,143 L665,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L821,104 L821,143 L820,143 L820,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L969,104 L969,105 L820,105 L820,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L969,143 L969,144 L820,144 L820,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L821,104 L821,144 L820,144 L820,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,143 L821,143 L821,144 L499,144 L499,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L821,143 L821,183 L820,183 L820,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L969,143 L969,144 L820,144 L820,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L821,143 L821,183 L820,183 L820,143"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,212 L1016,212 L1016,213 L8,213 L8,212"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,213 L1016,213 L1016,214 L8,214 L8,213"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,212 L8,214 L9,213 L9,212 L8,212"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1015,213 L1015,214 L1016,214 L1016,212 L1015,213"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,216 L1016,216 L1016,217 L8,217 L8,216"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,217 L1016,217 L1016,218 L8,218 L8,217"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,216 L8,218 L9,217 L9,216 L8,216"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1015,217 L1015,218 L1016,218 L1016,216 L1015,217"/>
					</g>
					<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="56cm" height="37cm" version="1.1">
							<image xlink:href="'.public_path('/images/jupiter.jpg').'" x="174" y="66" height="65px" width="116px" style=""/>
					</svg>
					<g xmlns="http://www.w3.org/2000/svg" fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="35" font-style="normal" font-weight="700">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="50" y="176" font-family="Times New Roman" font-size="30" font-weight="700" font-style="normal">Jupiter Garments Accessories</text>

					</g>
					<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="507" y="127" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">PO NO : '.$tesco_po_number.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="671" y="90" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">STYLE : '.$style.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="90" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">REF : '.$name.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="671" y="129" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">DATE : '.$created_date.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="129" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">Size: 127x80mm</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="505" y="168" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">DESCRIPTION : '.$tesco_description.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="168" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">REV.NO :</text>
					</g>
					</g>
					</svg>';
					$pdf->ImageSVG('@' . $svg, '', '', '', '', $link='', $align='', $palign='', $border=0, $fitonpage=false);
			});
	   
	   
			// Custom Footer
			PDF::setFooterCallback(function($pdf) {
			$tesco_po_number=$this->tesco_po_number;
			$customer_name=$this->customer_name;
			// Position at 15 mm from bottom
			$pdf->SetY(-50);
			$pdf->SetX(9.5);
			// Set font
			//$pdf->SetFont('frankgothcnbt');
			// Page number
			$svg='<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="188mm" height="65.2667mm" viewBox="0 0 1360 168" version="1.2" baseProfile="tiny">
					<title>Qt Svg Document</title>
					<desc>Generated with Qt</desc>
					<defs>
					</defs>
					<g fill="none" stroke="black" stroke-width="1" fill-rule="evenodd" stroke-linecap="square" stroke-linejoin="bevel">
					<g fill="#ffffff" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1360,0 L1360,168 L0,168 L0,0"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1360,0 L1360,168 L0,168 L0,0"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,52 L1352,52 L1352,53 L8,53 L8,52"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,53 L1352,53 L1352,54 L8,54 L8,53"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,52 L8,54 L9,53 L9,52 L8,52"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1351,53 L1351,54 L1352,54 L1352,52 L1351,53"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,56 L1352,56 L1352,57 L8,57 L8,56"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,57 L1352,57 L1352,58 L8,58 L8,57"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,56 L8,58 L9,57 L9,56 L8,56"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1351,57 L1351,58 L1352,58 L1352,56 L1351,57"/>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M385,79 L386,79 L386,157 L385,157 L385,79"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M764,79 L765,79 L765,157 L764,157 L764,79"/>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="41" y="100" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">CUSTOMER : '.$customer_name.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="41" y="152" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">PO NO : '.$tesco_po_number.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="405" y="100" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">APPROVED BY : </text>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="700" font-style="normal">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="405" y="152" font-family="Times New Roman" font-size="23" font-weight="700" font-style="normal">PREPARED BY:</text>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="784" y="103" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">COMMENTS IF ANY :</text>
					</g>
					</g>
					</svg>';
					
					 $pdf->ImageSVG('@' . $svg, '', '', '', '', $link='', $align='', $palign='', $border=0, $fitonpage=false);
		
		});
		PDF::setImageScale(1);
//		PDF::setPrintHeader(false);
//		PDF::setPrintFooter(false);
		PDF::SetFont('alternategothic2bt');
		PDF::AddPage();
		
		$template_file = DB::table('orchestra_template')->select('*')->where('item_id',$template_id)->get();
		
		PDF::Image(public_path($template_file[0]->template_file), 6, 54, 100, 100, 'PNG', '', '', true, 300, '', false, false, 0, false, false, false);
		
		PDF::Output( public_path('/storage/app/template/'.$name.'.pdf'), 'F');
		
		$file_path =  "/storage/app/template/".$name.".pdf";	

		return response()->json(['error'=> false,'message' =>'success', 'file_path' => $file_path,'url'=>url('/storage/app/template/'.$name.'.pdf')],200);	
	}
	
	public function printSpecialTag(Request $request)
	{
		$svgarrfront=explode(',_',$request->svg); 
		$db = $request->db;
		$id = $request->id;
		$name = $request->name;
		$template_id = $request->template_id;
		define ('PDF_FONT_NAME_MAIN', 'calibrib');
		define ('PDF_FONT_NAME_DATA', 'calibrib');
		
		 $orchestra_item_master = DB::table("orchestra_item_master")
					->select('*')
					->where('id',$template_id)
					->get();
	   $customer = DB::table("erp_orders")
						->join('customers', 'erp_orders.customer_id', 'customers.id')
						->select('customers.short_name')
						->where('erp_orders.id',$id)
						->get();
		 $customer_name = $customer[0]->short_name;
		 $this->customer_name = $customer_name;
		
		 $this->created_date = $request->created_at;
		
		 $this->template_name = $orchestra_item_master[0]->ref_no;
		 $this->template_zone = "";
	     $this->template_desc = "Special Tag";
		 $this->tesco_po_number = $request->buyer_no;
		 $this->style = $request->style_no;
	   
			// Custom Header
		  PDF::setHeaderCallback(function($pdf) {
			$pdf->SetY(0);
		  $name=$this->template_name;
		  $zone=$this->template_zone;
			$tesco_po_number=$this->tesco_po_number;
			$style=$this->style;
			$tesco_description=$this->template_desc;
			$date=date_create($this->created_date);
			$created_date =  date_format($date,"d.m.Y");
			$image_file= K_PATH_IMAGES.'logo_example.jpg';
			$svg='<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="188.244mm" height="50.1333mm" viewBox="0 0 1024 264" version="1.2" baseProfile="tiny">
					<title>Qt Svg Document</title>
					<desc>Generated with Qt</desc>
					<defs>
					</defs>
					<g fill="none" stroke="black" stroke-width="1" fill-rule="evenodd" stroke-linecap="square" stroke-linejoin="bevel">
					<g fill="#ffffff" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1024,0 L1024,264 L0,264 L0,0"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1024,0 L1024,264 L0,264 L0,0"/>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal" style="&#10;    /* border-radius: 27px; */&#10;">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,65 L666,65 L666,66 L499,66 L499,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,65 L500,65 L500,144 L499,144 L499,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,65 L821,65 L821,66 L665,66 L665,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,65 L969,65 L969,66 L820,66 L820,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M968,65 L969,65 L969,105 L968,105 L968,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M968,104 L969,104 L969,144 L968,144 L968,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,182 L821,182 L821,183 L499,183 L499,182"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,143 L500,143 L500,183 L499,183 L499,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,182 L969,182 L969,183 L820,183 L820,182"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M968,143 L969,143 L969,183 L968,183 L968,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,143 L666,143 L666,144 L499,144 L499,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,65 L666,65 L666,144 L665,144 L665,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,104 L821,104 L821,105 L665,105 L665,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,65 L666,65 L666,105 L665,105 L665,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,65 L821,65 L821,105 L820,105 L820,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L969,104 L969,105 L820,105 L820,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,65 L821,65 L821,105 L820,105 L820,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,104 L821,104 L821,105 L665,105 L665,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,104 L666,104 L666,143 L665,143 L665,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L821,104 L821,143 L820,143 L820,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L969,104 L969,105 L820,105 L820,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L969,143 L969,144 L820,144 L820,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L821,104 L821,144 L820,144 L820,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,143 L821,143 L821,144 L499,144 L499,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L821,143 L821,183 L820,183 L820,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L969,143 L969,144 L820,144 L820,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L821,143 L821,183 L820,183 L820,143"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,212 L1016,212 L1016,213 L8,213 L8,212"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,213 L1016,213 L1016,214 L8,214 L8,213"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,212 L8,214 L9,213 L9,212 L8,212"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1015,213 L1015,214 L1016,214 L1016,212 L1015,213"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,216 L1016,216 L1016,217 L8,217 L8,216"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,217 L1016,217 L1016,218 L8,218 L8,217"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,216 L8,218 L9,217 L9,216 L8,216"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1015,217 L1015,218 L1016,218 L1016,216 L1015,217"/>
					</g>
					<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="56cm" height="37cm" version="1.1">
							<image xlink:href="'.public_path('/images/jupiter.jpg').'" x="174" y="66" height="65px" width="116px" style=""/>
					</svg>
					<g xmlns="http://www.w3.org/2000/svg" fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="35" font-style="normal" font-weight="700">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="50" y="176" font-family="Times New Roman" font-size="30" font-weight="700" font-style="normal">Jupiter Garments Accessories</text>

					</g>
					<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="507" y="127" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">PO NO : '.$tesco_po_number.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="671" y="90" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">STYLE : '.$style.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="90" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">REF : '.$name.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="671" y="129" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">DATE : '.$created_date.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="129" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">Size: 127x80mm</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="505" y="168" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">DESCRIPTION : '.$tesco_description.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="168" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">REV.NO :</text>
					</g>
					</g>
					</svg>';
					$pdf->ImageSVG('@' . $svg, '', '', '', '', $link='', $align='', $palign='', $border=0, $fitonpage=false);
			});
	   
	   
			// Custom Footer
			PDF::setFooterCallback(function($pdf) {
			$tesco_po_number=$this->tesco_po_number;
			$customer_name=$this->customer_name;
			// Position at 15 mm from bottom
			$pdf->SetY(-50);
			$pdf->SetX(9.5);
			// Set font
			//$pdf->SetFont('frankgothcnbt');
			// Page number
			$svg='<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="188mm" height="65.2667mm" viewBox="0 0 1360 168" version="1.2" baseProfile="tiny">
					<title>Qt Svg Document</title>
					<desc>Generated with Qt</desc>
					<defs>
					</defs>
					<g fill="none" stroke="black" stroke-width="1" fill-rule="evenodd" stroke-linecap="square" stroke-linejoin="bevel">
					<g fill="#ffffff" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1360,0 L1360,168 L0,168 L0,0"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1360,0 L1360,168 L0,168 L0,0"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,52 L1352,52 L1352,53 L8,53 L8,52"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,53 L1352,53 L1352,54 L8,54 L8,53"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,52 L8,54 L9,53 L9,52 L8,52"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1351,53 L1351,54 L1352,54 L1352,52 L1351,53"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,56 L1352,56 L1352,57 L8,57 L8,56"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,57 L1352,57 L1352,58 L8,58 L8,57"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,56 L8,58 L9,57 L9,56 L8,56"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1351,57 L1351,58 L1352,58 L1352,56 L1351,57"/>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M385,79 L386,79 L386,157 L385,157 L385,79"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M764,79 L765,79 L765,157 L764,157 L764,79"/>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="41" y="100" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">CUSTOMER : '.$customer_name.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="41" y="152" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">PO NO : '.$tesco_po_number.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="405" y="100" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">APPROVED BY : </text>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="700" font-style="normal">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="405" y="152" font-family="Times New Roman" font-size="23" font-weight="700" font-style="normal">PREPARED BY:</text>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="784" y="103" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">COMMENTS IF ANY :</text>
					</g>
					</g>
					</svg>';
					
					 $pdf->ImageSVG('@' . $svg, '', '', '', '', $link='', $align='', $palign='', $border=0, $fitonpage=false);
		
		});
		PDF::setImageScale(1);
//		PDF::setPrintHeader(false);
//		PDF::setPrintFooter(false);
		PDF::SetFont('alternategothic2bt');
		PDF::AddPage();
		
		$template_file = DB::table('orchestra_template')->select('*')->where('item_id',$template_id)->get();
		
		PDF::Image(public_path($template_file[0]->template_file), 6, 54, 100, 100, 'PNG', '', '', true, 300, '', false, false, 0, false, false, false);
		
		PDF::Output( public_path('/storage/app/template/'.$name.'.pdf'), 'F');
		
		$file_path =  "/storage/app/template/".$name.".pdf";	

		return response()->json(['error'=> false,'message' =>'success', 'file_path' => $file_path,'url'=>url('/storage/app/template/'.$name.'.pdf')],200);	
	}
	
	public function printMainLabel(Request $request)
	{
		$svgarrfront=explode(',_',$request->svg); 
		$db = $request->db;
		$id = $request->id;
		$name = $request->name;
		$template_id = $request->template_id;
		define ('PDF_FONT_NAME_MAIN', 'calibrib');
		define ('PDF_FONT_NAME_DATA', 'calibrib');
		
		
	   $orchestra_item_master = DB::table("orchestra_item_master")
					->select('*')
					->where('id',$template_id)
					->get();
	   $customer = DB::table("erp_orders")
						->join('customers', 'erp_orders.customer_id', 'customers.id')
						->select('customers.short_name')
						->where('erp_orders.id',$id)
						->get();
		 $customer_name = $customer[0]->short_name;
		 $this->customer_name = $customer_name;
		
		 $this->created_date = $request->created_at;
		
		 $this->template_name = $orchestra_item_master[0]->ref_no;
		 $this->template_zone = "";
	     $this->template_desc = "Woven Main Label";
		 $this->tesco_po_number = $request->buyer_no;
		 $this->style = $request->style_no;
	   
			// Custom Header
		  PDF::setHeaderCallback(function($pdf) {
			$pdf->SetY(0);
		  $name=$this->template_name;
		  $zone=$this->template_zone;
			$tesco_po_number=$this->tesco_po_number;
			$style=$this->style;
			$tesco_description=$this->template_desc;
			$date=date_create($this->created_date);
			$created_date =  date_format($date,"d.m.Y");
			$image_file= K_PATH_IMAGES.'logo_example.jpg';
			$svg='<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="188.244mm" height="50.1333mm" viewBox="0 0 1024 264" version="1.2" baseProfile="tiny">
					<title>Qt Svg Document</title>
					<desc>Generated with Qt</desc>
					<defs>
					</defs>
					<g fill="none" stroke="black" stroke-width="1" fill-rule="evenodd" stroke-linecap="square" stroke-linejoin="bevel">
					<g fill="#ffffff" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1024,0 L1024,264 L0,264 L0,0"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1024,0 L1024,264 L0,264 L0,0"/>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal" style="&#10;    /* border-radius: 27px; */&#10;">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,65 L666,65 L666,66 L499,66 L499,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,65 L500,65 L500,144 L499,144 L499,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,65 L821,65 L821,66 L665,66 L665,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,65 L969,65 L969,66 L820,66 L820,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M968,65 L969,65 L969,105 L968,105 L968,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M968,104 L969,104 L969,144 L968,144 L968,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,182 L821,182 L821,183 L499,183 L499,182"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,143 L500,143 L500,183 L499,183 L499,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,182 L969,182 L969,183 L820,183 L820,182"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M968,143 L969,143 L969,183 L968,183 L968,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,143 L666,143 L666,144 L499,144 L499,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,65 L666,65 L666,144 L665,144 L665,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,104 L821,104 L821,105 L665,105 L665,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,65 L666,65 L666,105 L665,105 L665,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,65 L821,65 L821,105 L820,105 L820,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L969,104 L969,105 L820,105 L820,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,65 L821,65 L821,105 L820,105 L820,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,104 L821,104 L821,105 L665,105 L665,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,104 L666,104 L666,143 L665,143 L665,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L821,104 L821,143 L820,143 L820,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L969,104 L969,105 L820,105 L820,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L969,143 L969,144 L820,144 L820,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L821,104 L821,144 L820,144 L820,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,143 L821,143 L821,144 L499,144 L499,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L821,143 L821,183 L820,183 L820,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L969,143 L969,144 L820,144 L820,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L821,143 L821,183 L820,183 L820,143"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,212 L1016,212 L1016,213 L8,213 L8,212"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,213 L1016,213 L1016,214 L8,214 L8,213"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,212 L8,214 L9,213 L9,212 L8,212"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1015,213 L1015,214 L1016,214 L1016,212 L1015,213"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,216 L1016,216 L1016,217 L8,217 L8,216"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,217 L1016,217 L1016,218 L8,218 L8,217"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,216 L8,218 L9,217 L9,216 L8,216"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1015,217 L1015,218 L1016,218 L1016,216 L1015,217"/>
					</g>
					<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="56cm" height="37cm" version="1.1">
							<image xlink:href="'.public_path('/images/jupiter.jpg').'" x="174" y="66" height="65px" width="116px" style=""/>
					</svg>
					<g xmlns="http://www.w3.org/2000/svg" fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="35" font-style="normal" font-weight="700">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="50" y="176" font-family="Times New Roman" font-size="30" font-weight="700" font-style="normal">Jupiter Garments Accessories</text>

					</g>
					<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="507" y="127" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">PO NO : '.$tesco_po_number.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="671" y="90" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">STYLE : '.$style.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="90" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">REF : '.$name.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="671" y="129" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">DATE : '.$created_date.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="129" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">Size: 127x80mm</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="505" y="168" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">DESCRIPTION : '.$tesco_description.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="168" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">REV.NO :</text>
					</g>
					</g>
					</svg>';
					$pdf->ImageSVG('@' . $svg, '', '', '', '', $link='', $align='', $palign='', $border=0, $fitonpage=false);
			});
	   
	   
			// Custom Footer
			PDF::setFooterCallback(function($pdf) {
			$tesco_po_number=$this->tesco_po_number;
			$customer_name=$this->customer_name;
			// Position at 15 mm from bottom
			$pdf->SetY(-50);
			$pdf->SetX(9.5);
			// Set font
			//$pdf->SetFont('frankgothcnbt');
			// Page number
			$svg='<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="188mm" height="65.2667mm" viewBox="0 0 1360 168" version="1.2" baseProfile="tiny">
					<title>Qt Svg Document</title>
					<desc>Generated with Qt</desc>
					<defs>
					</defs>
					<g fill="none" stroke="black" stroke-width="1" fill-rule="evenodd" stroke-linecap="square" stroke-linejoin="bevel">
					<g fill="#ffffff" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1360,0 L1360,168 L0,168 L0,0"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1360,0 L1360,168 L0,168 L0,0"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,52 L1352,52 L1352,53 L8,53 L8,52"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,53 L1352,53 L1352,54 L8,54 L8,53"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,52 L8,54 L9,53 L9,52 L8,52"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1351,53 L1351,54 L1352,54 L1352,52 L1351,53"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,56 L1352,56 L1352,57 L8,57 L8,56"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,57 L1352,57 L1352,58 L8,58 L8,57"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,56 L8,58 L9,57 L9,56 L8,56"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1351,57 L1351,58 L1352,58 L1352,56 L1351,57"/>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M385,79 L386,79 L386,157 L385,157 L385,79"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M764,79 L765,79 L765,157 L764,157 L764,79"/>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="41" y="100" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">CUSTOMER : '.$customer_name.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="41" y="152" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">PO NO : '.$tesco_po_number.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="405" y="100" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">APPROVED BY : </text>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="700" font-style="normal">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="405" y="152" font-family="Times New Roman" font-size="23" font-weight="700" font-style="normal">PREPARED BY:</text>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="784" y="103" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">COMMENTS IF ANY :</text>
					</g>
					</g>
					</svg>';
					
					 $pdf->ImageSVG('@' . $svg, '', '', '', '', $link='', $align='', $palign='', $border=0, $fitonpage=false);
		
		});
		
		PDF::setImageScale(1);
//		PDF::setPrintHeader(false);
//		PDF::setPrintFooter(false);
		PDF::SetFont('alternategothic2bt');
		PDF::AddPage();
		
		$template_file = DB::table('orchestra_template')->select('*')->where('item_id',$template_id)->get();
		
		PDF::Image(public_path($template_file[0]->template_file), 6, 54, 100, 100, 'PNG', '', '', true, 300, '', false, false, 0, false, false, false);
		
		PDF::Output( public_path('/storage/app/template/'.$name.'.pdf'), 'F');
		
		$file_path =  "/storage/app/template/".$name.".pdf";	

		return response()->json(['error'=> false,'message' =>'success', 'file_path' => $file_path,'url'=>url('/storage/app/template/'.$name.'.pdf')],200);	
	}
	
	public function printSizeLabel(Request $request)
	{
		$svgarrfront=explode(',_',$request->svg); 
		$db = $request->db;
		$id = $request->id;
		$name = $request->name;
		$template_id = $request->template_id;
		define ('PDF_FONT_NAME_MAIN', 'calibrib');
		define ('PDF_FONT_NAME_DATA', 'calibrib');
		
		 $orchestra_item_master = DB::table("orchestra_item_master")
					->select('*')
					->where('id',$template_id)
					->get();
	   
	   $customer = DB::table("erp_orders")
						->join('customers', 'erp_orders.customer_id', 'customers.id')
						->select('customers.short_name')
						->where('erp_orders.id',$id)
						->get();
		 $customer_name = $customer[0]->short_name;
		 $this->customer_name = $customer_name;
		
		 $this->created_date = $request->created_at;
		
		 $this->template_name = $orchestra_item_master[0]->ref_no;
		 $this->template_zone = "";
	     $this->template_desc = "Woven Size Label";
		 $this->tesco_po_number = $request->buyer_no;
		 $this->style = $request->style_no;
	   
			// Custom Header
		  PDF::setHeaderCallback(function($pdf) {
			$pdf->SetY(0);
		  $name=$this->template_name;
		  $zone=$this->template_zone;
			$tesco_po_number=$this->tesco_po_number;
			$style=$this->style;
			$tesco_description=$this->template_desc;
			$date=date_create($this->created_date);
			$created_date =  date_format($date,"d.m.Y");
			$image_file= K_PATH_IMAGES.'logo_example.jpg';
			$svg='<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="188.244mm" height="50.1333mm" viewBox="0 0 1024 264" version="1.2" baseProfile="tiny">
					<title>Qt Svg Document</title>
					<desc>Generated with Qt</desc>
					<defs>
					</defs>
					<g fill="none" stroke="black" stroke-width="1" fill-rule="evenodd" stroke-linecap="square" stroke-linejoin="bevel">
					<g fill="#ffffff" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1024,0 L1024,264 L0,264 L0,0"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1024,0 L1024,264 L0,264 L0,0"/>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal" style="&#10;    /* border-radius: 27px; */&#10;">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,65 L666,65 L666,66 L499,66 L499,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,65 L500,65 L500,144 L499,144 L499,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,65 L821,65 L821,66 L665,66 L665,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,65 L969,65 L969,66 L820,66 L820,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M968,65 L969,65 L969,105 L968,105 L968,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M968,104 L969,104 L969,144 L968,144 L968,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,182 L821,182 L821,183 L499,183 L499,182"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,143 L500,143 L500,183 L499,183 L499,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,182 L969,182 L969,183 L820,183 L820,182"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M968,143 L969,143 L969,183 L968,183 L968,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,143 L666,143 L666,144 L499,144 L499,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,65 L666,65 L666,144 L665,144 L665,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,104 L821,104 L821,105 L665,105 L665,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,65 L666,65 L666,105 L665,105 L665,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,65 L821,65 L821,105 L820,105 L820,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L969,104 L969,105 L820,105 L820,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,65 L821,65 L821,105 L820,105 L820,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,104 L821,104 L821,105 L665,105 L665,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,104 L666,104 L666,143 L665,143 L665,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L821,104 L821,143 L820,143 L820,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L969,104 L969,105 L820,105 L820,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L969,143 L969,144 L820,144 L820,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L821,104 L821,144 L820,144 L820,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,143 L821,143 L821,144 L499,144 L499,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L821,143 L821,183 L820,183 L820,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L969,143 L969,144 L820,144 L820,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L821,143 L821,183 L820,183 L820,143"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,212 L1016,212 L1016,213 L8,213 L8,212"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,213 L1016,213 L1016,214 L8,214 L8,213"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,212 L8,214 L9,213 L9,212 L8,212"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1015,213 L1015,214 L1016,214 L1016,212 L1015,213"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,216 L1016,216 L1016,217 L8,217 L8,216"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,217 L1016,217 L1016,218 L8,218 L8,217"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,216 L8,218 L9,217 L9,216 L8,216"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1015,217 L1015,218 L1016,218 L1016,216 L1015,217"/>
					</g>
					<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="56cm" height="37cm" version="1.1">
							<image xlink:href="'.public_path('/images/jupiter.jpg').'" x="174" y="66" height="65px" width="116px" style=""/>
					</svg>
					<g xmlns="http://www.w3.org/2000/svg" fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="35" font-style="normal" font-weight="700">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="50" y="176" font-family="Times New Roman" font-size="30" font-weight="700" font-style="normal">Jupiter Garments Accessories</text>

					</g>
					<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="507" y="127" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">PO NO : '.$tesco_po_number.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="671" y="90" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">STYLE : '.$style.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="90" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">REF : '.$name.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="671" y="129" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">DATE : '.$created_date.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="129" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">Size: 127x80mm</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="505" y="168" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">DESCRIPTION : '.$tesco_description.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="168" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">REV.NO :</text>
					</g>
					</g>
					</svg>';
					$pdf->ImageSVG('@' . $svg, '', '', '', '', $link='', $align='', $palign='', $border=0, $fitonpage=false);
			});
	   
	   
			// Custom Footer
			PDF::setFooterCallback(function($pdf) {
			$tesco_po_number=$this->tesco_po_number;
			$customer_name=$this->customer_name;
			// Position at 15 mm from bottom
			$pdf->SetY(-50);
			$pdf->SetX(9.5);
			// Set font
			//$pdf->SetFont('frankgothcnbt');
			// Page number
			$svg='<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="188mm" height="65.2667mm" viewBox="0 0 1360 168" version="1.2" baseProfile="tiny">
					<title>Qt Svg Document</title>
					<desc>Generated with Qt</desc>
					<defs>
					</defs>
					<g fill="none" stroke="black" stroke-width="1" fill-rule="evenodd" stroke-linecap="square" stroke-linejoin="bevel">
					<g fill="#ffffff" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1360,0 L1360,168 L0,168 L0,0"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1360,0 L1360,168 L0,168 L0,0"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,52 L1352,52 L1352,53 L8,53 L8,52"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,53 L1352,53 L1352,54 L8,54 L8,53"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,52 L8,54 L9,53 L9,52 L8,52"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1351,53 L1351,54 L1352,54 L1352,52 L1351,53"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,56 L1352,56 L1352,57 L8,57 L8,56"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,57 L1352,57 L1352,58 L8,58 L8,57"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,56 L8,58 L9,57 L9,56 L8,56"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1351,57 L1351,58 L1352,58 L1352,56 L1351,57"/>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M385,79 L386,79 L386,157 L385,157 L385,79"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M764,79 L765,79 L765,157 L764,157 L764,79"/>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="41" y="100" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">CUSTOMER : '.$customer_name.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="41" y="152" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">PO NO : '.$tesco_po_number.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="405" y="100" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">APPROVED BY : </text>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="700" font-style="normal">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="405" y="152" font-family="Times New Roman" font-size="23" font-weight="700" font-style="normal">PREPARED BY:</text>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="784" y="103" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">COMMENTS IF ANY :</text>
					</g>
					</g>
					</svg>';
					
					 $pdf->ImageSVG('@' . $svg, '', '', '', '', $link='', $align='', $palign='', $border=0, $fitonpage=false);
		
		});
		PDF::setImageScale(1);
//		PDF::setPrintHeader(false);
//		PDF::setPrintFooter(false);
		PDF::SetFont('alternategothic2bt');
		PDF::AddPage();
		
		$template_file = DB::table('orchestra_template')->select('*')->where('item_id',$template_id)->get();
		
		PDF::Image(public_path($template_file[0]->template_file), 6, 54, 100, 100, 'PNG', '', '', true, 300, '', false, false, 0, false, false, false);
		
		PDF::Output( public_path('/storage/app/template/'.$name.'.pdf'), 'F');
		
		$file_path =  "/storage/app/template/".$name.".pdf";	

		return response()->json(['error'=> false,'message' =>'success', 'file_path' => $file_path,'url'=>url('/storage/app/template/'.$name.'.pdf')],200);	
	}
	
   public function printWashPdf(Request $request)
	{			

		$svgarr = explode(',_',$request->svg);
	    $name = $request->name;
	    $id = $request->id;
	   
	   
	   $customer = DB::table("erp_orders")
						->join('customers', 'erp_orders.customer_id', 'customers.id')
						->select('customers.short_name')
						->where('erp_orders.id',$id)
						->get();
		 $customer_name = $customer[0]->short_name;
		 $this->customer_name = $customer_name;
		
		 $this->created_date = $request->created_at;
		
		 $this->template_name = "";
		 $this->template_zone = "";
	     $this->template_desc = "Taffeta Wash Care Label";
		 $this->tesco_po_number = $request->buyer_no;
		 $this->style = $request->style_no;
	   
		 define ('PDF_FONT_NAME_MAIN', 'calibri');
		 define ('PDF_FONT_NAME_DATA', 'calibri');
			// Custom Header
		  PDF::setHeaderCallback(function($pdf) {
			$pdf->SetY(0);
		  $name=$this->template_name;
		  $zone=$this->template_zone;
			$tesco_po_number=$this->tesco_po_number;
			if($tesco_po_number == null || $tesco_po_number == "null")
			{
				$tesco_po_number = "";
			}
			else
			{
				$tesco_po_number = $tesco_po_number;
			}
			$style=$this->style;
			$tesco_description=$this->template_desc;
			$date=date_create($this->created_date);
			$created_date =  date_format($date,"d.m.Y");
			$image_file= K_PATH_IMAGES.'logo_example.jpg';
			$svg='<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="188.244mm" height="50.1333mm" viewBox="0 0 1024 264" version="1.2" baseProfile="tiny">
					<title>Qt Svg Document</title>
					<desc>Generated with Qt</desc>
					<defs>
					</defs>
					<g fill="none" stroke="black" stroke-width="1" fill-rule="evenodd" stroke-linecap="square" stroke-linejoin="bevel">
					<g fill="#ffffff" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1024,0 L1024,264 L0,264 L0,0"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1024,0 L1024,264 L0,264 L0,0"/>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal" style="&#10;    /* border-radius: 27px; */&#10;">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,65 L666,65 L666,66 L499,66 L499,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,65 L500,65 L500,144 L499,144 L499,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,65 L821,65 L821,66 L665,66 L665,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,65 L969,65 L969,66 L820,66 L820,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M968,65 L969,65 L969,105 L968,105 L968,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M968,104 L969,104 L969,144 L968,144 L968,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,182 L821,182 L821,183 L499,183 L499,182"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,143 L500,143 L500,183 L499,183 L499,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,182 L969,182 L969,183 L820,183 L820,182"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M968,143 L969,143 L969,183 L968,183 L968,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,143 L666,143 L666,144 L499,144 L499,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,65 L666,65 L666,144 L665,144 L665,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,104 L821,104 L821,105 L665,105 L665,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,65 L666,65 L666,105 L665,105 L665,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,65 L821,65 L821,105 L820,105 L820,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L969,104 L969,105 L820,105 L820,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,65 L821,65 L821,105 L820,105 L820,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,104 L821,104 L821,105 L665,105 L665,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,104 L666,104 L666,143 L665,143 L665,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L821,104 L821,143 L820,143 L820,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L969,104 L969,105 L820,105 L820,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L969,143 L969,144 L820,144 L820,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L821,104 L821,144 L820,144 L820,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,143 L821,143 L821,144 L499,144 L499,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L821,143 L821,183 L820,183 L820,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L969,143 L969,144 L820,144 L820,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L821,143 L821,183 L820,183 L820,143"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,212 L1016,212 L1016,213 L8,213 L8,212"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,213 L1016,213 L1016,214 L8,214 L8,213"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,212 L8,214 L9,213 L9,212 L8,212"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1015,213 L1015,214 L1016,214 L1016,212 L1015,213"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,216 L1016,216 L1016,217 L8,217 L8,216"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,217 L1016,217 L1016,218 L8,218 L8,217"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,216 L8,218 L9,217 L9,216 L8,216"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1015,217 L1015,218 L1016,218 L1016,216 L1015,217"/>
					</g>
					<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="56cm" height="37cm" version="1.1">
							<image xlink:href="'.public_path('/images/jupiter.jpg').'" x="174" y="66" height="65px" width="116px" style=""/>
					</svg>
					<g xmlns="http://www.w3.org/2000/svg" fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="35" font-style="normal" font-weight="700">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="50" y="176" font-family="Times New Roman" font-size="30" font-weight="700" font-style="normal">Jupiter Garments Accessories</text>

					</g>
					<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="507" y="127" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">PO NO : '.$tesco_po_number.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="671" y="90" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">STYLE : '.$style.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="90" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">REF : '.$name.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="671" y="129" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">DATE : '.$created_date.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="129" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">Size: 127x80mm</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="505" y="168" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">DESCRIPTION : '.$tesco_description.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="168" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">REV.NO :</text>
					</g>
					</g>
					</svg>';
					$pdf->ImageSVG('@' . $svg, '', '', '', '', $link='', $align='', $palign='', $border=0, $fitonpage=false);
			});
	   
	   
			// Custom Footer
			PDF::setFooterCallback(function($pdf) {
			$tesco_po_number=$this->tesco_po_number;
			if($tesco_po_number == null || $tesco_po_number == "null")
			{
				$tesco_po_number = "";
			}
			else
			{
				$tesco_po_number = $tesco_po_number;
			}
			$customer_name=$this->customer_name;
			// Position at 15 mm from bottom
			$pdf->SetY(-50);
			$pdf->SetX(9.5);
			// Set font
			//$pdf->SetFont('frankgothcnbt');
			// Page number
			$svg='<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="188mm" height="65.2667mm" viewBox="0 0 1360 168" version="1.2" baseProfile="tiny">
					<title>Qt Svg Document</title>
					<desc>Generated with Qt</desc>
					<defs>
					</defs>
					<g fill="none" stroke="black" stroke-width="1" fill-rule="evenodd" stroke-linecap="square" stroke-linejoin="bevel">
					<g fill="#ffffff" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1360,0 L1360,168 L0,168 L0,0"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1360,0 L1360,168 L0,168 L0,0"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,52 L1352,52 L1352,53 L8,53 L8,52"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,53 L1352,53 L1352,54 L8,54 L8,53"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,52 L8,54 L9,53 L9,52 L8,52"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1351,53 L1351,54 L1352,54 L1352,52 L1351,53"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,56 L1352,56 L1352,57 L8,57 L8,56"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,57 L1352,57 L1352,58 L8,58 L8,57"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,56 L8,58 L9,57 L9,56 L8,56"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1351,57 L1351,58 L1352,58 L1352,56 L1351,57"/>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M385,79 L386,79 L386,157 L385,157 L385,79"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M764,79 L765,79 L765,157 L764,157 L764,79"/>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="41" y="100" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">CUSTOMER : '.$customer_name.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="41" y="152" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">PO NO : '.$tesco_po_number.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="405" y="100" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">APPROVED BY : </text>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="700" font-style="normal">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="405" y="152" font-family="Times New Roman" font-size="23" font-weight="700" font-style="normal">PREPARED BY:</text>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="784" y="103" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">COMMENTS IF ANY :</text>
					</g>
					</g>
					</svg>';
					
					 $pdf->ImageSVG('@' . $svg, '', '', '', '', $link='', $align='', $palign='', $border=0, $fitonpage=false);
		
		});


	
		$selectedWashCare = OrchestraWashCare::where('id',$request->parentId)->first();
	

		$orchestra_countries = DB::table('orchestra_countries')->select('*')->where('id',$selectedWashCare->made_in_id)->get();
		
		$orchestra_care_instruction = DB::table('orchestra_care_instruction')
					->select('*')->whereIn('id',explode(',',$selectedWashCare->care_instruction_id))->get();
		
		$svg=urldecode($request->svg); 
		$svg_back=urldecode($request->svg_back); 
		$svg_middle=urldecode($request->svg_middle); 
		
		PDF::setImageScale(1);
		PDF::SetFont('tahoma', '',  1.5, '', true);
		PDF::AddPage();
		
	
		$tbl='<p  style="font-size:5px;color:cmyk(0%, 0%, 0%, 100%);font-family:Calibri;line-height:1.35">Fabriqué en - Made in - <br> Fabricado en - <br>Fabricado em - <br>Fabbricato in - <br>Vervaardigd in  - <br>Hergestellt in -<br><span style="font-family:Tahoma">  ﺻﻨﻊ ب </span>-<br> παράγεται στην</p>';
		
		$tbl1='<p  style="font-size:5px;color:cmyk(0%, 0%, 0%, 100%);font-family:Calibri">'.$orchestra_countries[0]->french.' - '.$orchestra_countries[0]->english.' - '.$orchestra_countries[0]->spanish.' - '.$orchestra_countries[0]->portuguese.' - <br>'.$orchestra_countries[0]->italian.' - '.$orchestra_countries[0]->dutch.' - '.$orchestra_countries[0]->german.' - <br><span style="font-family:Tahoma">'.$orchestra_countries[0]->arabic.'</span> - '.$orchestra_countries[0]->greek.'</p>';
		$tbl2="";
		foreach($orchestra_care_instruction as $value)
		{
			$tbl2.='<p style="font-size:5px;color:cmyk(0%, 0%, 0%, 100%);font-family:Calibri;text-align:left">'.$value->french.' - '.$value->english.' - '.$value->spanish.' - '.$value->portuguese.' - '.$value->italian.' - '.$value->dutch.' - '.$value->german.' - <span style="font-family:Tahoma">'.$value->arabic.'</span> - '.$value->greek.'</p><br><br>';
		}
		
		$tbl3="";
		$orchestra_wash_care_composition = DB::table('orchestra_wash_care_composition')
					->select('*')->where('owcl_id',$request->parentId)->get();
		$composition = json_decode($request->composition);
		$page_id = "";
		foreach($orchestra_wash_care_composition as $key => $value)
		{
			
			if($value->page_id != $page_id )
			{
					$orchestra_parts = DB::table('orchestra_parts')->select('*')->where('id',$value->parts_id)->get();
					if(count($orchestra_parts) > 0)
					{
						$tbl3.='<p style="font-size:5px;color:cmyk(0%, 0%, 0%, 100%)">'.$orchestra_parts[0]->french.' - '.$orchestra_parts[0]->english.' - '.$orchestra_parts[0]->spanish.' - '.$orchestra_parts[0]->portuguese.' - '.$orchestra_parts[0]->italian.' - '.$orchestra_parts[0]->dutch.' - '.$orchestra_parts[0]->german.' - <span>'.$orchestra_parts[0]->arabic.'</span> - '.$orchestra_parts[0]->greek.'</p><br><br>';
					}
			}
			
				$orchestra_compositions = DB::table('orchestra_compositions')->select('*')->where('id',$value->composition_id)->get();
				
				$tbl3.='<p style="font-size:5px;color:cmyk(0%, 0%, 0%, 100%);font-family:Calibri">'.$value->percentage.'% '.$orchestra_compositions[0]->french.' - '.$orchestra_compositions[0]->english.' - '.$orchestra_compositions[0]->spanish.' - '.$orchestra_compositions[0]->portuguese.' - '.$orchestra_compositions[0]->italian.' - '.$orchestra_compositions[0]->dutch.' - '.$orchestra_compositions[0]->german.' - <span style="font-family:Tahoma">'.$orchestra_compositions[0]->arabic.'</span> - '.$orchestra_compositions[0]->greek.'</p><br><br>';
				$page_id = $value->page_id;

		}
		$xx=12.5;
		$i=0;
		foreach($svgarr as $value)
		{
			$svg=urldecode($value);
			PDF::writeHTMLCell('25.5', '40', $xx, $y=42.5, '<p style="font-size:12px">Back</p>', '', 0, 0, true, 'C', true);
        	PDF::ImageSVG('@' . $svg, $x=$xx, $y=50, '25.5', '165', $link='', $align='', $palign='', $border=0, $fitonpage=false);
			PDF::SetFillColor(255, 255, 255);
			PDF::SetTextColor(0, 0, 0);
			PDF::writeHTMLCell('25.5', '40', $xx, '172.5', $tbl, '', 0, 0, true, 'C', true);
			PDF::writeHTMLCell('26', '40', $xx, '191.8', $tbl1, '', 0, 0, true, 'C', true);
			PDF::writeHTMLCell('25.5', '40', $xx, $y=217.5, '<p style="font-size:12px">Front</p>', '', 0, 0, true, 'C', true);
			$xx += 37.5;
			$i++;
			
			if($i == 5)
			{
				PDF::AddPage();
				$i=0;
				$xx=12.5;
			}
		}
		PDF::writeHTMLCell('25.5', '40', $xx, $y=42.5, '<p style="font-size:12px">Back</p>', '', 0, 0, true, 'C', true);
		PDF::ImageSVG('@' . $svg_back, $xx, $y=50, '25.5', '165', $link='', $align='', $palign='', $border=0, $fitonpage=false);
		PDF::writeHTMLCell('22', '40', $xx + 2.25, '62.5', $tbl2, '', 0, 0, true, 'C', true);
		PDF::writeHTMLCell('25', '40', $xx + 1, '147.5', $tbl3, '', 0, 0, true, 'C', true);
		PDF::writeHTMLCell('25.5', '40', $xx, $y=217.5, '<p style="font-size:12px">Front</p>', '', 0, 0, true, 'C', true);
		PDF::AddPage();
		PDF::ImageSVG('@' . $svg_middle, $x=50, $y=50, '55.5', '82.5', $link='', $align='', $palign='', $border=0, $fitonpage=false);
		
		//print_r($i);
//		if($i == 0)
//		{
//			PDF::ImageSVG('@' . $svg_back, $x=12.5, $y=12.5, '25', '165', $link='', $align='', $palign='', $border=0, $fitonpage=false);
//      		PDF::ImageSVG('@' . $svg_middle, $x=50, $y=12.5, '55', '82.5', $link='', $align='', $palign='', $border=0, $fitonpage=false);
//			PDF::writeHTMLCell('22', '40', '14', '25', $tbl2, '', 1, 1, true, 'C', true);
//			PDF::writeHTMLCell('25', '40', '13.5', '110', $tbl3, '', 1, 1, true, 'C', true);
//
//		}
//		else if($i == 1)
//		{
//			PDF::ImageSVG('@' . $svg_back, $x=$xx, $y=12.5, '25', '165', $link='', $align='', $palign='', $border=0, $fitonpage=false);
//      		PDF::ImageSVG('@' . $svg_middle, $x=$xx+37.5, $y=12.5, '55', '82.5', $link='', $align='', $palign='', $border=0, $fitonpage=false);
//			PDF::writeHTMLCell('22', '40', $xx+1.5, '25', $tbl2, '', 1, 1, true, 'C', true);
//			PDF::writeHTMLCell('25', '40', $xx+0.5, '110', $tbl3, '', 1, 1, true, 'C', true);
//		}
//		else if($i == 2)
//		{
//			PDF::ImageSVG('@' . $svg_back, $x=$xx, $y=12.5, '25', '165', $link='', $align='', $palign='', $border=0, $fitonpage=false);
//      		PDF::ImageSVG('@' . $svg_middle, $x=$xx+37.5, $y=12.5, '55', '82.5', $link='', $align='', $palign='', $border=0, $fitonpage=false);
//			PDF::writeHTMLCell('22', '40', $xx+1.5, '25', $tbl2, '', 1, 1, true, 'C', true);
//			PDF::writeHTMLCell('25', '40', $xx+0.5, '110', $tbl3, '', 1, 1, true, 'C', true);
//		}
		
	  
		
//		PDF::writeHTMLCell('8', '10', '16.2', '61', $tbl, '', 1, 1, true, 'C', true);
		
	
		PDF::Output( public_path('/storage/app/template/'.$name.'.pdf'), 'F');
		//print_r(url('/storage/app/template/solid1.pdf'));
	   
	    $file_path =  "/storage/app/template/".$name.".pdf";	

		return response()->json(['error'=> false,'message' =>'success', 'file_path' => $file_path,'url'=>url('/storage/app/template/'.$name.'.pdf')],200);	
	}
		/**
		 * Function to update uuid value for existing data in erp_order_details table.
		 * @author <Praveen>
		 */
		public function scriptUpdateUUID()
		{
			$order_details = DB::table('erp_order_details')->select('erp_order_id', 'style_no', 'order_qnty_table_name', 'created_at', 'buyer_po_number', 'order_type', 'order_type', 'reference_name')->groupBy('erp_order_id', 'style_no', 'order_qnty_table_name', 'created_at', 'buyer_po_number', 'order_type', 'order_type', 'reference_name')->get();
			
			foreach($order_details as $value)
			{
				// unique id for array creation
				// combination of erpid-refname-styleno-buyerpo-ordertype
				$unique_id = 'ERP-'.str_replace(' ', '-', $value->reference_name).'-'.$value->erp_order_id.'-'.$value->style_no.'-'.$value->buyer_po_number.'-'.$value->order_type;
			
				ErpOrderDetails::where(['erp_order_id' => $value->erp_order_id, 'style_no' => $value->style_no, 'order_qnty_table_name'=> $value->order_qnty_table_name, 'created_at'=> $value->created_at, 'buyer_po_number'=> $value->buyer_po_number, 'order_type'=> $value->order_type])->whereDate('created_at', '>', '2020-06-11')
					->update([
								'uuid'  => $unique_id
							]);
				
			}
			
			return "uuid updated successfully !!!!";
		}

		public function proofDivTescoLabelos(Request $request)
     {

        $data_back=explode(',_',$request->data_back);
        $data_back_text=explode(',_',$request->data_back_text);
        $data_front=explode(',_',$request->data_front);
        $data_front_image=explode(',_',$request->data_front_image);
        $svgStringBack=urldecode($data_back[0]);
        $svgStringBackText=urldecode($data_back_text[0]);
				$svgStringFrontImage=urldecode($data_front_image[0]);
				
				$wash_astm = $request->wash_astm;
        $wash_iso = $request->wash_iso;
        $whit_astm = $request->whit_astm;
        $whit_iso = $request->whit_iso;
        $dry_astm = $request->dry_astm;
        $dry_iso = $request->dry_iso;
        $iron_astm = $request->iron_astm;
        $iron_iso = $request->iron_iso;
        $clean_astm = $request->clean_astm;
        $clean_iso = $request->clean_iso;

        define ('PDF_FONT_NAME_MAIN', 'arialuni');
        define ('PDF_FONT_NAME_DATA', 'arialuni');
        // PDF::setPrintHeader(false);
        // PDF::setPrintFooter(false);
        PDF::SetFont('arialuni');

        $this->tesco_po_number = $request->tesco_po_number;
        $this->style = $request->style;
        $this->template_name = $request->template_name;
        $this->created_date = $request->created_date;
        $this->customer_name = $request->customer_name;

        PDF::setHeaderCallback(function($pdf) {
            $name=$this->template_name;
            $zone="";
              $tesco_po_number=$this->tesco_po_number;
              $style=$this->style;
              $tesco_description="";
              $date=date_create($this->created_date);
              $created_date =  date_format($date,"d.m.Y");
              $image_file= K_PATH_IMAGES.'logo_example.jpg';
              $svg='<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="188.244mm" height="50.1333mm" viewBox="0 0 1024 264" version="1.2" baseProfile="tiny">
                      <title>Qt Svg Document</title>
                      <desc>Generated with Qt</desc>
                      <defs>
                      </defs>
                      <g fill="none" stroke="black" stroke-width="1" fill-rule="evenodd" stroke-linecap="square" stroke-linejoin="bevel">
                      <g fill="#ffffff" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
                      <path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1024,0 L1024,264 L0,264 L0,0"/>
                      <path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1024,0 L1024,264 L0,264 L0,0"/>
                      </g>
                      <g fill="#000000" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal" style="&#10;    /* border-radius: 27px; */&#10;">
                      <path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,65 L666,65 L666,66 L499,66 L499,65"/>
                      <path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,65 L500,65 L500,144 L499,144 L499,65"/>
                      <path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,65 L821,65 L821,66 L665,66 L665,65"/>
                      <path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,65 L969,65 L969,66 L820,66 L820,65"/>
                      <path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M968,65 L969,65 L969,105 L968,105 L968,65"/>
                      <path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M968,104 L969,104 L969,144 L968,144 L968,104"/>
                      <path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,182 L821,182 L821,183 L499,183 L499,182"/>
                      <path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,143 L500,143 L500,183 L499,183 L499,143"/>
                      <path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,182 L969,182 L969,183 L820,183 L820,182"/>
                      <path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M968,143 L969,143 L969,183 L968,183 L968,143"/>
                      <path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,143 L666,143 L666,144 L499,144 L499,143"/>
                      <path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,65 L666,65 L666,144 L665,144 L665,65"/>
                      <path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,104 L821,104 L821,105 L665,105 L665,104"/>
                      <path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,65 L666,65 L666,105 L665,105 L665,65"/>
                      <path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,65 L821,65 L821,105 L820,105 L820,65"/>
                      <path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L969,104 L969,105 L820,105 L820,104"/>
                      <path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,65 L821,65 L821,105 L820,105 L820,65"/>
                      <path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,104 L821,104 L821,105 L665,105 L665,104"/>
                      <path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,104 L666,104 L666,143 L665,143 L665,104"/>
                      <path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L821,104 L821,143 L820,143 L820,104"/>
                      <path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L969,104 L969,105 L820,105 L820,104"/>
                      <path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L969,143 L969,144 L820,144 L820,143"/>
                      <path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L821,104 L821,144 L820,144 L820,104"/>
                      <path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,143 L821,143 L821,144 L499,144 L499,143"/>
                      <path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L821,143 L821,183 L820,183 L820,143"/>
                      <path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L969,143 L969,144 L820,144 L820,143"/>
                      <path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L821,143 L821,183 L820,183 L820,143"/>
                      </g>
                      <g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
                      <path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,212 L1016,212 L1016,213 L8,213 L8,212"/>
                      </g>
                      <g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
                      <path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,213 L1016,213 L1016,214 L8,214 L8,213"/>
                      </g>
                      <g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
                      <path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,212 L8,214 L9,213 L9,212 L8,212"/>
                      </g>
                      <g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
                      <path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1015,213 L1015,214 L1016,214 L1016,212 L1015,213"/>
                      </g>
                      <g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
                      <path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,216 L1016,216 L1016,217 L8,217 L8,216"/>
                      </g>
                      <g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
                      <path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,217 L1016,217 L1016,218 L8,218 L8,217"/>
                      </g>
                      <g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
                      <path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,216 L8,218 L9,217 L9,216 L8,216"/>
                      </g>
                      <g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
                      <path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1015,217 L1015,218 L1016,218 L1016,216 L1015,217"/>
                      </g>
                      <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="56cm" height="37cm" version="1.1">
                              <image xlink:href="'.public_path('/images/jupiter.jpg').'" x="174" y="66" height="65px" width="116px" style=""/>
                      </svg>
                      <g xmlns="http://www.w3.org/2000/svg" fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="35" font-style="normal" font-weight="700">
                      <text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="50" y="176" font-family="Times New Roman" font-size="30" font-weight="700" font-style="normal">Jupiter Garments Accessories</text>
    
                      </g>
                      <g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">
                      <text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="507" y="127" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">PO NO : '.$tesco_po_number.'</text>
                      <text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="671" y="90" font-family="Times New Roman" font-size="12" font-weight="400" font-style="normal">STYLE : '.$style.'</text>
                      <text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="90" font-family="Times New Roman" font-size="12" font-weight="400" font-style="normal">REF : '.$name.'</text>
                      <text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="671" y="129" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">DATE : '.$created_date.'</text>
                      <text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="129" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">SIZE: 25x135mm</text>
                      <text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="505" y="168" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">DESCRIPTION : '.$tesco_description.'</text>
                      <text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="168" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">REV.NO:</text>
                      </g>
                      </g>
                      </svg>';
                      $pdf->ImageSVG('@' . $svg, '', '', '', '', $link='', $align='', $palign='', $border=0, $fitonpage=false);
              });
                              
              // Custom Footer
              PDF::setFooterCallback(function($pdf) {
              $tesco_po_number=$this->tesco_po_number;
              $customer_name=$this->customer_name;
              // Position at 15 mm from bottom
              $pdf->SetY(-50);
              $pdf->SetX(9.5);
              // Set font
              //$pdf->SetFont('frankgothcnbt');
              // Page number
              $svg='<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="188mm" height="65.2667mm" viewBox="0 0 1360 168" version="1.2" baseProfile="tiny">
                      <title>Qt Svg Document</title>
                      <desc>Generated with Qt</desc>
                      <defs>
                      </defs>
                      <g fill="none" stroke="black" stroke-width="1" fill-rule="evenodd" stroke-linecap="square" stroke-linejoin="bevel">
                      <g fill="#ffffff" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
                      <path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1360,0 L1360,168 L0,168 L0,0"/>
                      <path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1360,0 L1360,168 L0,168 L0,0"/>
                      </g>
                      <g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
                      <path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,52 L1352,52 L1352,53 L8,53 L8,52"/>
                      </g>
                      <g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
                      <path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,53 L1352,53 L1352,54 L8,54 L8,53"/>
                      </g>
                      <g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
                      <path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,52 L8,54 L9,53 L9,52 L8,52"/>
                      </g>
                      <g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
                      <path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1351,53 L1351,54 L1352,54 L1352,52 L1351,53"/>
                      </g>
                      <g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
                      <path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,56 L1352,56 L1352,57 L8,57 L8,56"/>
                      </g>
                      <g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
                      <path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,57 L1352,57 L1352,58 L8,58 L8,57"/>
                      </g>
                      <g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
                      <path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,56 L8,58 L9,57 L9,56 L8,56"/>
                      </g>
                      <g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
                      <path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1351,57 L1351,58 L1352,58 L1352,56 L1351,57"/>
                      </g>
                      <g fill="#000000" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
                      <path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M385,79 L386,79 L386,157 L385,157 L385,79"/>
                      <path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M764,79 L765,79 L765,157 L764,157 L764,79"/>
                      </g>
                      <g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">
                      <text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="41" y="100" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">CUSTOMER : '.$customer_name.'</text>
                      <text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="41" y="152" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">PO NO : '.$tesco_po_number.'</text>
                      <text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="405" y="100" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">APPROVED BY : </text>
                      </g>
                      <g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="700" font-style="normal">
                      <text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="405" y="152" font-family="Times New Roman" font-size="23" font-weight="700" font-style="normal">PREPARED BY:</text>
                      </g>
                      <g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">
                      <text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="784" y="103" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">COMMENTS IF ANY :</text>
                      </g>
                      </g>
                      </svg>';
                      
                       $pdf->ImageSVG('@' . $svg, '', '', '', '', $link='', $align='', $palign='', $border=0, $fitonpage=false);
          
          });
        
        
          
        $i=0;
        $xx=15;
        foreach($data_front as $value)
        {
            
            if($i == 6)
            {
                $i=0;
                $xx=15;
            }

            if($i == 0)
            {
                PDF::AddPage();
            }
            
            $svgString=urldecode($value);
            //print_r($svgString);
            PDF::ImageSVG('@' . $svgString, $x=$xx, $y=80, $w=25, $h=135, $link='', $align='', $palign='', $border=0, $fitonpage=false);
            PDF::ImageSVG('@' . $svgStringFrontImage, $x=$xx, $y=80, $w=25, $h=135, $link='', $align='', $palign='', $border=0, $fitonpage=false);
            $xx+=30;
            $i++;
				}
				
				if($i == 6)
        {
            $xx=15;
            PDF::AddPage();
        }

        PDF::ImageSVG('@' . $svgStringBack, $x=$xx, $y=80, $w=25, $h=135, $link='', $align='', $palign='', $border=0, $fitonpage=false);
        

				PDF::ImageSVG('@' . $svgStringBackText, $x=$xx, $y=80, $w=25, $h=135, $link='', $align='', $palign='', $border=0, $fitonpage=false);
				
				PDF::ImageSVG($file=public_path($wash_astm), $x=$xx+1.5, $y=94.5, $w=4, $h=4, $link='', $align='', $palign='', $border=0, $fitonpage=false);
        PDF::ImageSVG($file=public_path($wash_iso), $x=$xx+1.5, $y=99, $w=4, $h=4, $link='', $align='', $palign='', $border=0, $fitonpage=false);


        PDF::ImageSVG($file=public_path($whit_astm), $x=$xx+5.8, $y=94.5, $w=4, $h=4, $link='', $align='', $palign='', $border=0, $fitonpage=false);
        PDF::ImageSVG($file=public_path($whit_iso), $x=$xx+5.8, $y=99, $w=4, $h=4, $link='', $align='', $palign='', $border=0, $fitonpage=false);

        PDF::ImageSVG($file=public_path($dry_astm), $x=$xx+10.5, $y=94.5, $w=4, $h=4, $link='', $align='', $palign='', $border=0, $fitonpage=false);
        PDF::ImageSVG($file=public_path($dry_iso), $x=$xx+10.5, $y=99, $w=4, $h=4, $link='', $align='', $palign='', $border=0, $fitonpage=false);

        PDF::ImageSVG($file=public_path($iron_astm), $x=$xx+15.5, $y=94.5, $w=4, $h=4, $link='', $align='', $palign='', $border=0, $fitonpage=false);
        PDF::ImageSVG($file=public_path($iron_iso), $x=$xx+15.5, $y=99, $w=4, $h=4, $link='', $align='', $palign='', $border=0, $fitonpage=false);

        PDF::ImageSVG($file=public_path($clean_astm), $x=$xx+20, $y=94.5, $w=4, $h=4, $link='', $align='', $palign='', $border=0, $fitonpage=false);
        PDF::ImageSVG($file=public_path($clean_iso), $x=$xx+20, $y=99, $w=4, $h=4, $link='', $align='', $palign='', $border=0, $fitonpage=false);


        //print_r($svgStringBack);

        PDF::Output( public_path('/storage/app/template/label.pdf'), 'F');
				//print_r(url('/storage/app/template/label.pdf'));
				
				$file_path =  "/storage/app/template/label.pdf";	
				
				return response()->json(['error'=> false,'message' =>'success', 'file_path' => $file_path,'url'=>url('/storage/app/template/label.pdf')],200);	
		 } 
		 
		 public function printDivTescoLabelos(Request $request)
     {

        $data_back=explode(',_',$request->data_back);
        $data_back_text=explode(',_',$request->data_back_text);
        $data_front=explode(',_',$request->data_front);
        $data_front_image=explode(',_',$request->data_front_image);
        $svgStringBack=urldecode($data_back[0]);
        $svgStringBackText=urldecode($data_back_text[0]);
				$svgStringFrontImage=urldecode($data_front_image[0]);
				
				$wash_astm = $request->wash_astm;
        $wash_iso = $request->wash_iso;
        $whit_astm = $request->whit_astm;
        $whit_iso = $request->whit_iso;
        $dry_astm = $request->dry_astm;
        $dry_iso = $request->dry_iso;
        $iron_astm = $request->iron_astm;
        $iron_iso = $request->iron_iso;
        $clean_astm = $request->clean_astm;
        $clean_iso = $request->clean_iso;

        define ('PDF_FONT_NAME_MAIN', 'arialuni');
        define ('PDF_FONT_NAME_DATA', 'arialuni');
        PDF::setPrintHeader(false);
        PDF::setPrintFooter(false);
        PDF::SetFont('arialuni');

        $i=0;
        $xx=15;
        foreach($data_front as $value)
        {
            
            if($i == 6)
            {
                $i=0;
                $xx=15;
            }

            if($i == 0)
            {
                PDF::AddPage();
            }
            
            $svgString=urldecode($value);
            //print_r($svgString);
            PDF::ImageSVG('@' . $svgString, $x=$xx, $y=80, $w=25, $h=135, $link='', $align='', $palign='', $border=0, $fitonpage=false);
            PDF::ImageSVG('@' . $svgStringFrontImage, $x=$xx, $y=80, $w=25, $h=135, $link='', $align='', $palign='', $border=0, $fitonpage=false);
            $xx+=30;
            $i++;
				}
				
				if($i == 6)
        {
            $xx=15;
            PDF::AddPage();
        }

        PDF::ImageSVG('@' . $svgStringBack, $x=$xx, $y=80, $w=25, $h=135, $link='', $align='', $palign='', $border=0, $fitonpage=false);
        

				PDF::ImageSVG('@' . $svgStringBackText, $x=$xx, $y=80, $w=25, $h=135, $link='', $align='', $palign='', $border=0, $fitonpage=false);
				
				PDF::ImageSVG($file=public_path($wash_astm), $x=$xx+1.5, $y=94.5, $w=4, $h=4, $link='', $align='', $palign='', $border=0, $fitonpage=false);
        PDF::ImageSVG($file=public_path($wash_iso), $x=$xx+1.5, $y=99, $w=4, $h=4, $link='', $align='', $palign='', $border=0, $fitonpage=false);


        PDF::ImageSVG($file=public_path($whit_astm), $x=$xx+5.8, $y=94.5, $w=4, $h=4, $link='', $align='', $palign='', $border=0, $fitonpage=false);
        PDF::ImageSVG($file=public_path($whit_iso), $x=$xx+5.8, $y=99, $w=4, $h=4, $link='', $align='', $palign='', $border=0, $fitonpage=false);

        PDF::ImageSVG($file=public_path($dry_astm), $x=$xx+10.5, $y=94.5, $w=4, $h=4, $link='', $align='', $palign='', $border=0, $fitonpage=false);
        PDF::ImageSVG($file=public_path($dry_iso), $x=$xx+10.5, $y=99, $w=4, $h=4, $link='', $align='', $palign='', $border=0, $fitonpage=false);

        PDF::ImageSVG($file=public_path($iron_astm), $x=$xx+15.5, $y=94.5, $w=4, $h=4, $link='', $align='', $palign='', $border=0, $fitonpage=false);
        PDF::ImageSVG($file=public_path($iron_iso), $x=$xx+15.5, $y=99, $w=4, $h=4, $link='', $align='', $palign='', $border=0, $fitonpage=false);

        PDF::ImageSVG($file=public_path($clean_astm), $x=$xx+20, $y=94.5, $w=4, $h=4, $link='', $align='', $palign='', $border=0, $fitonpage=false);
        PDF::ImageSVG($file=public_path($clean_iso), $x=$xx+20, $y=99, $w=4, $h=4, $link='', $align='', $palign='', $border=0, $fitonpage=false);


        //print_r($svgStringBack);

        PDF::Output( public_path('/storage/app/template/label.pdf'), 'F');
				//print_r(url('/storage/app/template/label.pdf'));
				
				$file_path =  "/storage/app/template/label.pdf";	
				
				return response()->json(['error'=> false,'message' =>'success', 'file_path' => $file_path,'url'=>url('/storage/app/template/label.pdf')],200);	
		 } 

}