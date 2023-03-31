<?php

namespace App\Http\Controllers\Merchandiser;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Response;
use View;
use DB;
use PDF;
use Auth;
use App\WashLabelOrders;
use App\WashLabelOrdersQty;
use Carbon\Carbon;
use App\ErpOrders as Erp;
use App\ErpOrderDetails as ErpOrderDetails;
/**
 * Merchandiser Page controller
 * @author <Praveen>
 */
class HomeController extends Controller
{
    private $current_date;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('merchandiser_auth');

        // get the current time
        $this->current_date = Carbon::now('Asia/Kolkata')->toDateTimeString();
    }

    /**
     * Show the merchandiser Pending orders page.
     * @author [Praveen] <[praveen@wizroots.com]>
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
			 $merchandiser_id = Auth::guard('web_merchandiser')->id();
             $orders = DB::table('orchestra_wash_care_label_orders')
                        ->join('orchestra_item_master','orchestra_item_master.id', 'orchestra_wash_care_label_orders.template_id')
                        ->join('orchestra_template','orchestra_template.item_id', 'orchestra_wash_care_label_orders.template_id')
                        ->leftjoin('customers','customers.id', 'orchestra_wash_care_label_orders.customer_id')
                        ->join('merchandisers','merchandisers.id','=','orchestra_wash_care_label_orders.merchandisers_id')
                        ->select('orchestra_wash_care_label_orders.uvc as UVC',DB::raw("'WashCareLabel' as \"Artwork\""),'customers.company_name as SupplierName','orchestra_wash_care_label_orders.created_at as OrderDate','orchestra_wash_care_label_orders.delivery_date as DeliveryDate','orchestra_wash_care_label_orders.status as Status',DB::raw("orchestra_wash_care_label_orders.season_code as \"Season\""),'merchandisers.merchandiser_name','orchestra_wash_care_label_orders.id','orchestra_template.template_file')
                        ->where('orchestra_wash_care_label_orders.status','!=','Order Cancelled')
                        ->where('orchestra_wash_care_label_orders.status','=','Buyer Approval Pending')
                        ->where('orchestra_wash_care_label_orders.merchandisers_id','=',$merchandiser_id)
                        ->groupBy('orchestra_wash_care_label_orders.uvc','orchestra_wash_care_label_orders.id','customers.company_name','merchandisers.merchandiser_name','orchestra_template.template_file')
                        ->get();
              $buyers = DB::table('buyers')->where('is_active','=',1)->pluck('buyer_name','id')->all();
             return view('merchandiser.home',['orders' => $orders,'buyers' => $buyers]);
        } catch (\Exception $e)
          {
             return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
          }
        
    }
    /**
       * Get orchestra wcl by ID.
       * @param wcl id
       * @return \Illuminate\Http\Response
       */
      public function getWCLById(Request $request)
      {
        try {
				$id = $request['id'];
				$wcl_orders_qty = DB::table('orchestra_wash_care_label_orders_qty')->select('size','uvc_tco','quantity')->where('orchestra_wash_care_label_orders_qty.owcl_id',$id)->get();
			
				//To get Wash care label composition
				
			    $wcl_orders = DB::table('orchestra_wash_care_label_orders')->select('*')->where('orchestra_wash_care_label_orders.id',$id)->get();
			
			    $uvc = $wcl_orders{0}->uvc; 
			
			    $made_in_id = $wcl_orders{0}->made_in_id;
			$washing_id = $wcl_orders{0}->washing_id;
			$whitening_id = $wcl_orders{0}->whitening_id;
			$dryer_id = $wcl_orders{0}->dryer_id;
			$ironing_id = $wcl_orders{0}->ironing_id;
			$cleaning_id = $wcl_orders{0}->cleaning_id;
			
			    $care_instruction_id = explode(',',$wcl_orders{0}->care_instruction_id);
			
				 $care_instruction = DB::table('orchestra_care_instruction')->select('*')->whereIn('id',$care_instruction_id)->get(); 
			
			$washing = DB::table('orchestra_care_symbols')->select('*')->where('id',$washing_id)->get(); 
			$whitening = DB::table('orchestra_care_symbols')->select('*')->where('id',$whitening_id)->get(); 
			$dryer = DB::table('orchestra_care_symbols')->select('*')->where('id',$dryer_id)->get(); 
			$ironing = DB::table('orchestra_care_symbols')->select('*')->where('id',$ironing_id)->get(); 
			$cleaning = DB::table('orchestra_care_symbols')->select('*')->where('id',$cleaning_id)->get(); 
			
				$disney = DB::table('orchestra_template')->select('*')->where('item_id',$wcl_orders{0}->template_id)->get(); 
			
			    $made_in= DB::table('orchestra_countries')->select('*')->where('id',$made_in_id)->get();
			
				if($disney{0}->disney == 0)
				{
					$orchestra_wash_template_front="/storage/app/template/Orchestra/WCL/wcl_1.svg";
					$orchestra_wash_template_back="/storage/app/template/Orchestra/WCL/wcl_2.svg";
					$orchestra_wash_template_middle="/storage/app/template/Orchestra/WCL/wcl_3.svg";
					$orchestra_wash_template_last="/storage/app/template/Orchestra/WCL/wcl_4.svg";
				}
				else
				{
					$orchestra_wash_template_front="/storage/app/template/Orchestra/WCL/wcl_1.svg";
					$orchestra_wash_template_back="/storage/app/template/Orchestra/WCL/wcl_2.svg";
					$orchestra_wash_template_middle="/storage/app/template/Orchestra/WCL/wcl_3.svg";
					$orchestra_wash_template_last="/storage/app/template/Orchestra/WCL/wcl_4.svg";
				}
			    
				foreach($wcl_orders as $value1)
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
			
				//To get Order Details like UVC,Size
			
				$order_details = DB::table('orchestra_parcel_details') ->leftJoin('orchestra_size_chart','orchestra_parcel_details.Size','=','orchestra_size_chart.age')->select('*',DB::raw("sum(\"Quantity\") as Sum"))->where('UVC',$uvc)->groupBy('Size','orchestra_parcel_details.id','orchestra_size_chart.id')->orderBy('orchestra_parcel_details.id')->get();
				
			    $code=$order_details{0}->Family_Code;
				
				//To get Product Family
			    $product_family = DB::table('orchestra_product_family')->select('*')->where('code',$code)->get();
			
                // $data['orchestra_wash_template_id']=2;
                return response()->json(['error'=>'false','message' =>'success', 'wcl_orders_qty' => $wcl_orders_qty,'order_details'=>$order_details,'care_instruction'=>$care_instruction,'orchestra_wash_care_label_orders'=>$wcl_orders,'product_family'=>$product_family,'made_in'=>$made_in,'orchestra_wash_template_front'=>$orchestra_wash_template_front,'orchestra_wash_template_last'=>$orchestra_wash_template_last,'orchestra_wash_template_back'=>$orchestra_wash_template_back,'orchestra_wash_template_middle'=>$orchestra_wash_template_middle,'washing'=>$washing,'whitening'=>$whitening,'dryer'=>$dryer,'ironing'=>$ironing,'cleaning'=>$cleaning],200);
            } catch (\Exception $e)
            {
              return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
            }
      }
      /**
     * Add merchandiser approved comment for wcl.
     * @param \Illuminate\Http\Request
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function addWCLApproveCommentById(Request $request)
      {
        try {
        	      $id        = $request['id'];
                $comment   = $request['comment'];
                $buyer_id = DB::table("buyers")->where('buyer_name','ilike','Orchestra')->pluck('id')->first();
                
                WashLabelOrders::where('id',$id)
                ->update([
                  'merchandiser_comment' => $comment,
                  'approved_date'        => $this->current_date,
                  'status'               => "Order Approved"
                ]);
                $order_details  = WashLabelOrders::where('id',$id)
                                  ->select('id','status as order_status','customer_id','po_number','pi_id', 'order_created_at', 'order_approved_at')
                                  ->first();

                $order_qty_details  = WashLabelOrdersQty::where('owcl_id', $order_details->id)
                ->select('id', DB::raw("'orchestra_wash_care_label_orders_qty' as \"table_name\""))->get();

                $order_status_id  = DB::table('orders_status')->select('id')->where('status_name', 'ilike', $order_details->order_status)->first();

                $customer_data  = DB::table('customers')->select('pi_required', 'po_required')->where('id', $order_details->customer_id)->first();

                if ($customer_data->pi_required === 1 && $customer_data->po_required === 0) 
                {
                  $po_number     = $order_details->pi_id;
                }
                else if($customer_data->po_required === 1)
                {
                  $po_number     = $order_details->po_number;
                }         
                
                $erp_orders = Erp::where('buyer_id',$buyer_id)->where('customer_id',$order_details->customer_id)->where('po_no', $po_number)->first();
                
                if ($erp_orders) 
                {
                  // update order status in erp_order_details table
                    foreach ($order_qty_details as $key => $value) 
                    {
                      $erp_order_qty = ErpOrderDetails::where('order_qnty_table_name',$value->table_name)->where('order_qnty_table_id',$value->id)->first();

                      if ($erp_order_qty) 
                      {
                        ErpOrderDetails::where('id',$erp_order_qty->id)
                              ->update([
                                        'order_status_id'  => $order_status_id->id,
                                        'order_date'       => $order_details->order_created_at,
                                        'aw_date'          => $order_details->order_approved_at
                                      ]);
                      }
                    }
                }

             $result = response()->json(['error'=> false,'message' =>'Success'],200);

            } catch (\Exception $e)
            {
              $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
            }
            return $result;
      }
      /**
     * Add merchandiser rejected comment for wcl.
     * @param \Illuminate\Http\Request
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function addWCLRejectCommentById(Request $request)
      {
        try {
        	      $id        = $request['id'];
		          $comment   = $request['comment'];
		          
		          WashLabelOrders::where('id',$id)
		           ->update([
		             'merchandiser_comment' => $comment,
		             'rejected_date'        => $this->current_date,
		             'status'               => "Order Rejected"
		          ]);

                $result = response()->json(['error'=> false,'message' =>'Success'],200);

            } catch (\Exception $e)
            {
                $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
            }
            return $result;
      }
       /**
	     * Show the merchandiser past orders page.
	     * @author [Praveen] <[praveen@wizroots.com]>
	     * @return \Illuminate\Http\Response
	     */
	    public function pastOrders()
	    {
	        try{
				 $merchandiser_id = Auth::guard('web_merchandiser')->id();
         
	            // $orders = DB::table('orchestra_wash_care_label_orders')
              //           ->join('orchestra_item_master','orchestra_item_master.id', 'orchestra_wash_care_label_orders.template_id')
              //           ->join('orchestra_template','orchestra_template.item_id', 'orchestra_wash_care_label_orders.template_id')
              //           ->leftjoin('customers','customers.id', 'orchestra_wash_care_label_orders.customer_id')
              //           ->join('merchandisers','merchandisers.id','=','orchestra_wash_care_label_orders.merchandisers_id')
              //           ->select('orchestra_wash_care_label_orders.uvc as UVC',DB::raw("'WashCareLabel' as \"Artwork\""),'customers.company_name as SupplierName','orchestra_wash_care_label_orders.created_at as OrderDate','orchestra_wash_care_label_orders.delivery_date as DeliveryDate','orchestra_wash_care_label_orders.status as Status',DB::raw("orchestra_wash_care_label_orders.season_code as \"Season\""),'merchandisers.merchandiser_name','orchestra_wash_care_label_orders.id','orchestra_wash_care_label_orders.approved_date as ApprovedDate','orchestra_wash_care_label_orders.rejected_date as RejectedDate')
              //           ->where('orchestra_wash_care_label_orders.status','!=','Order Cancelled')
              //           ->where('orchestra_wash_care_label_orders.status','<>','Buyer Approval Pending')
              //           ->where('orchestra_wash_care_label_orders.merchandisers_id','=',$merchandiser_id)
              //           ->groupBy('orchestra_wash_care_label_orders.uvc','orchestra_wash_care_label_orders.id','customers.company_name','merchandisers.merchandiser_name')
              //           ->get();

              $buyers = DB::table('buyers')->where('is_active','=',1)->pluck('buyer_name','id')->all();

              $date_start = date("Y-m-d", strtotime(" -6 month"));
              $date_end = date("Y-m-d");

              $owcl_season = DB::table("orchestra_wash_care_label_orders")
                                ->whereBetween('orchestra_wash_care_label_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                                ->where('orchestra_wash_care_label_orders.status','!=','Order Cancelled')
                                ->where('orchestra_wash_care_label_orders.status','<>','Buyer Approval Pending')
                                ->where('orchestra_wash_care_label_orders.merchandisers_id','=',$merchandiser_id)
                                ->select("season_code")
                                ->groupBy('season_code')
                                ->get();

              $owcl_style = DB::table("orchestra_wash_care_label_orders")
                                ->whereBetween('orchestra_wash_care_label_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                                ->where('orchestra_wash_care_label_orders.status','!=','Order Cancelled')
                                ->where('orchestra_wash_care_label_orders.status','<>','Buyer Approval Pending')
                                ->where('orchestra_wash_care_label_orders.merchandisers_id','=',$merchandiser_id)
                                ->select("uvc")
                                ->groupBy('uvc')
                                ->get();

              $owcl_supplier = DB::table('orchestra_wash_care_label_orders')
                                ->leftjoin('customers','customers.id', 'orchestra_wash_care_label_orders.customer_id')
                                ->whereBetween('orchestra_wash_care_label_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                                ->where('orchestra_wash_care_label_orders.status','!=','Order Cancelled')
                                ->where('orchestra_wash_care_label_orders.status','<>','Buyer Approval Pending')
                                ->where('orchestra_wash_care_label_orders.merchandisers_id','=',$merchandiser_id)
                                ->select('customer_id','customers.company_name')
                                ->groupBy('customer_id','customers.company_name')
                                ->get();

	            return view('merchandiser.past-orders',['owcl_season' => $owcl_season,'owcl_style' => $owcl_style,'buyers' => $buyers,'owcl_supplier' => $owcl_supplier]);
	        } catch (\Exception $e)
	          {
	             return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
	          }
	        
	    }

  public function getOrderDetails(Request $request)
	{
        $table_name        =   $request->table_name;
        $table_id          =   $request->table_id;
        
        if($table_name == "orchestra_wash_care_label_orders")
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

    public function getTescoTSLSizechart(Request $request)
    {
       try {
             
		   $size = $request['size'];
		   $uvc_tco = $request['uvc_tco'];

		   $size = DB::table('orchestra_size_chart')->select('cm as size')->where('age', 'ilike', $size)->where('is_enabled','=',1)->get();
		   
		   $orchestra_parcel_quantity_details = DB::table('orchestra_parcel_quantity_details')->select('*')->where('UVC_TCO', $uvc_tco)->get();
           
           return response()->json(['size_chart' => $size,'opqt' => $orchestra_parcel_quantity_details]);
		   
       } catch (\Exception $e) {
           return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
       }
    }

    public function printWashPdf(Request $request)
    {
        
		$svgarr = explode(',_',$request->svg);
		$pro_family_image = $request->pro_family_image;
		$id = $request->id;
		$activity_label = $request->activity_label;

		$owcl = DB::table('orchestra_wash_care_label_orders')->join('orchestra_item_master','orchestra_item_master.id','orchestra_wash_care_label_orders.template_id')->where('orchestra_wash_care_label_orders.status','!=','Order Cancelled')->select('orchestra_wash_care_label_orders.*','orchestra_item_master.item_name')->where('orchestra_wash_care_label_orders.id',$id)->first();

		$customer_id = $owcl->customer_id;
		$customer = DB::table('customers')->select('*')->where('id',$customer_id)->first();

		$customer_name = $customer->short_name;
		$this->customer_name = $customer_name;
	   
		$this->created_date = $owcl->created_at;
	   
		$this->template_name = "";
		$this->template_zone = "";
		$this->template_desc = "Taffeta Wash Care Label";
		$this->tesco_po_number = $owcl->po;
		$this->style = $owcl->uvc;

		if($activity_label == "PUERICULTURE")
		{
			$this->size_text = "25 x 152mm";
		}
		else
		{
			$this->size_text = "25 x 165mm";
		}

        //$svg=urldecode($request->svg);
		
		$made_in_select=$request->made_in_select;
		$orchestra_countries = DB::table('orchestra_countries')->select('*')->where('id',$made_in_select)->get();
		
		
		$care_instruction_select = explode(',',$request->care_instruction_select_fin); 
		$casval = "";
		$cascount = 1 ;
		foreach($care_instruction_select as $cval)
		{
			$casval .= 'WHEN '.$cval.' THEN '.$cascount;
			$cascount++;
		}
		$orchestra_care_instruction = DB::table('orchestra_care_instruction')->select('*')->whereIn('id',$care_instruction_select)->orderByRaw("CASE id
		".$casval."
		ELSE ".$cascount." END")->get();
		
		//print_r($orchestra_countries);
		
		//print_r($orchestra_countries);
		
        $svg=urldecode($request->svg); 
        $svg_back=urldecode($request->svg_back); 
        $svg_middle=urldecode($request->svg_middle); 
        define ('PDF_FONT_NAME_MAIN', 'calibri');
		define ('PDF_FONT_NAME_DATA', 'calibri');
		
		PDF::setHeaderCallback(function($pdf) {
			$pdf->SetY(0);
		  $name=$this->template_name;
		  $zone=$this->template_zone;
		  $size_text=$this->size_text;
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
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="129" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">SIZE: '.$size_text.'</text>
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

        PDF::setImageScale(1);
        PDF::SetFont('calibrib');
		PDF::AddPage('P', 'A4');


        $data_fin_back=explode(',_',$request->data_fin_back); 

		$tbl='<p  style="font-size:5px;color:cmyk(0%, 0%, 0%, 100%);font-family:Calibri;line-height:1.35">Fabriqué en - Made in - <br> Fabricado en - <br>Fabricado em - <br>Fabbricato in - <br>Vervaardigd in  - <br>Hergestellt in -<br><span style="font-family:Tahoma">  ﺻﻨﻊ ب </span>-<br> παράγεται στην</p>';
		


		$orchestra_washing_select = DB::table('orchestra_care_symbols')->select('*')->where('id',$request->washing_select)->get();
		$orchestra_whitening_select = DB::table('orchestra_care_symbols')->select('*')->where('id',$request->whitening_select)->get();
		$orchestra_dryer_select = DB::table('orchestra_care_symbols')->select('*')->where('id',$request->dryer_select)->get();
		$orchestra_ironing_select = DB::table('orchestra_care_symbols')->select('*')->where('id',$request->ironing_select)->get();
		$orchestra_cleaning_select = DB::table('orchestra_care_symbols')->select('*')->where('id',$request->cleaning_select)->get();

		$wash_iso = $orchestra_washing_select{0}->cofreet_symbols;
		$wash_astm = $orchestra_washing_select{0}->astms_symbol;

		$whit_iso = $orchestra_whitening_select{0}->cofreet_symbols;
		$whit_astm = $orchestra_whitening_select{0}->astms_symbol;

		$dry_iso = $orchestra_dryer_select{0}->cofreet_symbols;
		$dry_astm = $orchestra_dryer_select{0}->astms_symbol;

		$iron_iso = $orchestra_ironing_select{0}->cofreet_symbols;
		$iron_astm = $orchestra_ironing_select{0}->astms_symbol;

		$clean_iso = $orchestra_cleaning_select{0}->cofreet_symbols;
		$clean_astm = $orchestra_cleaning_select{0}->astms_symbol;

		if($activity_label == "PUERICULTURE")
		{
			$tbl1='<p  style="font-size:5px;color:cmyk(0%, 0%, 0%, 100%);font-family:Calibri">'.$orchestra_countries{0}->french.' - '.$orchestra_countries{0}->english.' - '.$orchestra_countries{0}->dutch.' - <br>'.$orchestra_countries{0}->german.' - '.$orchestra_countries{0}->spanish.' - '.$orchestra_countries{0}->greek.' - <br><span style="font-family:Tahoma"> - '.$orchestra_countries{0}->arabic.'</span></p>';
			$tbl2="";
			foreach($orchestra_care_instruction as $value)
			{
				$tbl2.='<p style="font-size:5px;color:cmyk(0%, 0%, 0%, 100%);font-family:Calibri;text-align:left">'.$value->french.' - '.$value->english.' - '.$value->dutch.' - '.$value->german.' - '.$value->spanish.' - '.$value->greek.' - <span style="font-family:Tahoma">'.$value->arabic.'</span></p><br><br>';
			}


			//print_r($tbl2);
			$tbl3="";
			$composition = json_decode($request->composition);
			//print_r($composition);
			foreach($composition as $key => $value)
			{
				
				$express_value = explode('_',$value[0]);
				if($express_value[0] != "Select Option" && $express_value[0] != 0)
				{
						$exp_value = explode('_',$value[0]);
						$orchestra_parts = DB::table('orchestra_parts')->select('*')->where('id',$exp_value[0])->get();
					
						$tbl3.='<p style="font-size:5px;color:cmyk(0%, 0%, 0%, 100%);font-family:Calibri">'.$orchestra_parts{0}->french.' - '.$orchestra_parts{0}->english.' - '.$orchestra_parts{0}->dutch.' - '.$orchestra_parts{0}->german.' - '.$orchestra_parts{0}->spanish.' - '.$orchestra_parts{0}->greek.' - <span style="font-family:Tahoma">'.$orchestra_parts{0}->arabic.'</span></p><br><br>';
				}
				foreach($value as $key1 => $value1)
				{
					$exp_val = explode('_',$value1);
					$orchestra_compositions = DB::table('orchestra_compositions')->select('*')->where('english',$exp_val[1])->get();
					
					$tbl3.='<p style="font-size:5px;color:cmyk(0%, 0%, 0%, 100%);font-family:Calibri">'.$exp_val[2].'% '.$orchestra_compositions{0}->french.' - '.$orchestra_compositions{0}->english.' - '.$orchestra_compositions{0}->dutch.' - '.$orchestra_compositions{0}->german.' - '.$orchestra_compositions{0}->spanish.' - '.$orchestra_compositions{0}->greek.' - <span style="font-family:Tahoma">'.$orchestra_compositions{0}->arabic.'</span></p><br><br>';
				}
			}

			$xx=12.5;
			$i=0;
			$j=0;
			foreach($svgarr as $value)
			{
				if($i == 3)
				{
					PDF::AddPage('P', 'A4');
					$i=0;
					$xx=12.5;
				}

				$svg=urldecode($value);
				$svg_back=urldecode($data_fin_back[$j]);

				PDF::Rect($x=$xx + 27.5, $y=50, '25', '152');
				PDF::Rect($x=$xx, $y=50, '25', '152');
			
				PDF::ImageSVG('@' . $svg, $x=$xx, $y=50, '25', '152', $link='', $align='', $palign='', $border=0, $fitonpage=false);
				PDF::ImageSVG('@' . $svg_back, $x=$xx + 27.5, $y=50, '25', '152', $link='', $align='', $palign='', $border=0, $fitonpage=false);


				PDF::ImageSVG($file=public_path($wash_astm), $x=$xx+1.5, $y=150.7, $w=4, $h=4, $link='', $align='', $palign='', $border=0, $fitonpage=false);
		
		
				PDF::ImageSVG($file=public_path($whit_astm), $x=$xx+5.8, $y=150.7, $w=4, $h=4, $link='', $align='', $palign='', $border=0, $fitonpage=false);
		
				PDF::ImageSVG($file=public_path($dry_astm),  $x=$xx+10.5, $y=150.7, $w=4, $h=4, $link='', $align='', $palign='', $border=0, $fitonpage=false);
		
				PDF::ImageSVG($file=public_path($iron_astm), $x=$xx+15.5, $y=150.7, $w=4, $h=4, $link='', $align='', $palign='', $border=0, $fitonpage=false);
		
				PDF::ImageSVG($file=public_path($clean_astm), $x=$xx+20, $y=150.7, $w=4, $h=4, $link='', $align='', $palign='', $border=0, $fitonpage=false);

				PDF::ImageSVG($file=public_path($pro_family_image), $x=$xx+36, $y=50, $w=15, $h=15, $link='', $align='R', $palign='', $border=0, $fitonpage=false);

				
				PDF::writeHTMLCell('26', '40', $xx, '176', $tbl1, '', 0, 0, true, 'C', true);
				PDF::writeHTMLCell('22', '40', $xx + 29.5, '60', $tbl2, '', 0, 0, true, 'C', true);
				PDF::SetFillColor(255, 255, 255);
				PDF::SetTextColor(0, 0, 0);
				$xx += 65;
				$i++;
				$j++;
			}

			PDF::AddPage();

			PDF::Rect($x=75, $y=50, '25', '152');
			PDF::Rect($x=105, $y=50, '25', '152');

			PDF::ImageSVG('@' . $svg_middle, $x=75, $y=50, '25', '152', $link='', $align='', $palign='', $border=0, $fitonpage=false);
			PDF::writeHTMLCell('25', '40', 75.3, '142.5', $tbl3, '', 0, 0, true, 'C', true);
			PDF::ImageSVG($file=public_path('/storage/app/template/Orchestra/WCL/premaman_4.svg'), $x=105, $y=50, '25', '152', $link='', $align='', $palign='', $border=0, $fitonpage=false);
		}
		else
		{
			$country_svg = '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
			width="71.37px" height="468.22px" viewBox="0 0 71.37 468.22" style="enable-background:new 0 0 71.37 468.22;"
			xml:space="preserve">

			<text transform="transform=matrix(1.0909 0 0 1 5 410)" style="font-size: 5.5px;font-family: Calibri"><tspan x="0" y="0">'.$orchestra_countries{0}->french.'</tspan><tspan style="stroke-width:0.3;stroke: cmyk(0%, 0%, 0%, 100%);fill:cmyk(0%, 0%, 0%, 100%);"> - </tspan><tspan>'.$orchestra_countries{0}->english.'</tspan><tspan style="stroke-width:0.3;stroke: cmyk(0%, 0%, 0%, 100%);fill:cmyk(0%, 0%, 0%, 100%);"> - </tspan><tspan>'.$orchestra_countries{0}->spanish.'</tspan><tspan style="stroke-width:0.3;stroke: cmyk(0%, 0%, 0%, 100%);fill:cmyk(0%, 0%, 0%, 100%);"> - </tspan><tspan>'.$orchestra_countries{0}->portuguese.'</tspan><tspan x="5" y="6">'.$orchestra_countries{0}->italian.'</tspan><tspan style="stroke-width:0.3;stroke: cmyk(0%, 0%, 0%, 100%);fill:cmyk(0%, 0%, 0%, 100%);"> - </tspan><tspan>'.$orchestra_countries{0}->dutch.'</tspan><tspan style="stroke-width:0.3;stroke: cmyk(0%, 0%, 0%, 100%);fill:cmyk(0%, 0%, 0%, 100%);"> - </tspan><tspan>'.$orchestra_countries{0}->german.'</tspan><tspan x="12" y="12" style="font-family:Tahoma">'.$orchestra_countries{0}->arabic.'</tspan><tspan style="stroke-width:0.3;stroke: cmyk(0%, 0%, 0%, 100%);fill:cmyk(0%, 0%, 0%, 100%);"> - </tspan><tspan>'.$orchestra_countries{0}->greek.'</tspan></text>
			
			</svg>';

			$tbl1='<p  style="font-size:5.5px;color:cmyk(0%, 0%, 0%, 100%);font-family:Calibri;">'.$orchestra_countries{0}->french.' - '.$orchestra_countries{0}->english.' - '.$orchestra_countries{0}->spanish.' - '.$orchestra_countries{0}->portuguese.' - <br>'.$orchestra_countries{0}->italian.' - '.$orchestra_countries{0}->dutch.' - '.$orchestra_countries{0}->german.' - <br><span style="font-family:Tahoma;">'.$orchestra_countries{0}->arabic.'</span> - '.$orchestra_countries{0}->greek.'</p>';
			$tbl2="";
			foreach($orchestra_care_instruction as $value)
			{
				$tbl2.='<p style="font-size:4.5px;color:cmyk(0%, 0%, 0%, 100%);font-family:Calibri;text-align:left;font-weight:bold;">'.ucfirst($value->french).' - '.ucfirst($value->english).' - '.ucfirst($value->spanish).' - '.ucfirst($value->portuguese).' - '.ucfirst($value->italian).' - '.ucfirst($value->dutch).' - '.ucfirst($value->german).' - <span style="font-family:Tahoma;font-weight:bold;">'.ucfirst($value->arabic).'</span> - '.ucfirst($value->greek).'</p>';
			}

			//print_r($tbl2);
			$tbl3="";
			$composition = json_decode($request->composition);
			//print_r($composition);
			foreach($composition as $key => $value)
			{
				
				$express_value = explode('_',$value[0]);
				if($express_value[0] != 0)
				{
						$exp_value = explode('_',$value[0]);
						$orchestra_parts = DB::table('orchestra_parts')->select('*')->where('id',$exp_value[0])->get();
					
						$tbl3.='<p style="font-size:5.5px;color:cmyk(0%, 0%, 0%, 100%);font-family:Calibri;font-weight:bold;">'.$orchestra_parts{0}->french.' - '.$orchestra_parts{0}->english.' - '.$orchestra_parts{0}->spanish.' - '.$orchestra_parts{0}->portuguese.' - '.$orchestra_parts{0}->italian.' - '.$orchestra_parts{0}->dutch.' - '.$orchestra_parts{0}->german.' - <span style="font-family:Tahoma;font-weight:bold;">'.$orchestra_parts{0}->arabic.'</span> - '.$orchestra_parts{0}->greek.'</p><br><br>';
				}
				foreach($value as $key1 => $value1)
				{
					$exp_val = explode('_',$value1);
					$orchestra_compositions = DB::table('orchestra_compositions')->select('*')->where('english',$exp_val[1])->get();
					
					$tbl3.='<p style="font-size:5.5px;color:cmyk(0%, 0%, 0%, 100%);font-family:Calibri;font-weight:bold;">'.$exp_val[2].'% '.$orchestra_compositions{0}->french.' - '.$orchestra_compositions{0}->english.' - '.$orchestra_compositions{0}->spanish.' - '.$orchestra_compositions{0}->portuguese.' - '.$orchestra_compositions{0}->italian.' - '.$orchestra_compositions{0}->dutch.' - '.$orchestra_compositions{0}->german.' - <span style="font-family:Tahoma;font-weight:bold;">'.$orchestra_compositions{0}->arabic.'</span> - '.$orchestra_compositions{0}->greek.'</p><br><br>';
				}
			}


			$xx=12.5;
			$i=0;
			$j=0;
			foreach($svgarr as $value)
			{
				if($i == 3)
				{
					PDF::AddPage('P', 'A4');
					$i=0;
					$xx=12.5;
				}

				$svg=urldecode($value);
				$svg_back=urldecode($data_fin_back[$j]);

				PDF::Rect($x=$xx, $y=50, '25', '165');
				PDF::Rect($x=$xx + 27.5, $y=50, '25', '165');

				PDF::ImageSVG('@' . $svg, $x=$xx, $y=50, '25', '165', $link='', $align='', $palign='', $border=0, $fitonpage=false);

				PDF::ImageSVG('@' . $country_svg, $x=$xx, $y=50, '25', '165', $link='', $align='', $palign='', $border=0, $fitonpage=false);
				
				PDF::ImageSVG('@' . $svg_back, $x=$xx + 27.5, $y=50, '25', '165', $link='', $align='', $palign='', $border=0, $fitonpage=false);


				PDF::ImageSVG($file=public_path($wash_astm), $x=$xx+1.5, $y=151, $w=4, $h=4, $link='', $align='', $palign='', $border=0, $fitonpage=false);
				PDF::ImageSVG($file=public_path($wash_iso), $x=$xx+1.5, $y=160, $w=4, $h=4, $link='', $align='', $palign='', $border=0, $fitonpage=false);
		
		
				PDF::ImageSVG($file=public_path($whit_astm), $x=$xx+5.8, $y=151, $w=4, $h=4, $link='', $align='', $palign='', $border=0, $fitonpage=false);
				PDF::ImageSVG($file=public_path($whit_iso), $x=$xx+5.8, $y=160, $w=4, $h=4, $link='', $align='', $palign='', $border=0, $fitonpage=false);
		
				PDF::ImageSVG($file=public_path($dry_astm), $x=$xx+10.5, $y=151, $w=4, $h=4, $link='', $align='', $palign='', $border=0, $fitonpage=false);
				PDF::ImageSVG($file=public_path($dry_iso), $x=$xx+10.5, $y=160, $w=4, $h=4, $link='', $align='', $palign='', $border=0, $fitonpage=false);
		
				PDF::ImageSVG($file=public_path($iron_astm), $x=$xx+15.5, $y=151, $w=4, $h=4, $link='', $align='', $palign='', $border=0, $fitonpage=false);
				PDF::ImageSVG($file=public_path($iron_iso), $x=$xx+15.5, $y=160, $w=4, $h=4, $link='', $align='', $palign='', $border=0, $fitonpage=false);
		
				PDF::ImageSVG($file=public_path($clean_astm), $x=$xx+20, $y=151, $w=4, $h=4, $link='', $align='', $palign='', $border=0, $fitonpage=false);
				PDF::ImageSVG($file=public_path($clean_iso), $x=$xx+20, $y=160, $w=4, $h=4, $link='', $align='', $palign='', $border=0, $fitonpage=false);


				PDF::ImageSVG($file=public_path($pro_family_image), $x=$xx+36, $y=50, $w=15, $h=15, $link='', $align='R', $palign='', $border=0, $fitonpage=false);

				
				//PDF::writeHTMLCell('26', '40', $xx-0.1, '192.5', $tbl1, '', 0, 0, true, 'C', true);
				PDF::writeHTMLCell('22.7', '40', $xx + 29, '62.5', $tbl2, '', 0, 0, true, 'C', true);
				PDF::SetFillColor(255, 255, 255);
				PDF::SetTextColor(0, 0, 0);
				$xx += 65;
				$i++;
				$j++;
			}

			PDF::AddPage();
			PDF::Rect($x=75, $y=50, '25', '165');
			PDF::ImageSVG('@' . $svg_middle, $x=75, $y=50, '25', '165', $link='', $align='', $palign='', $border=0, $fitonpage=false);
			PDF::writeHTMLCell('25', '40', 75.3, '147.5', $tbl3, '', 0, 0, true, 'C', true);
			PDF::Rect($x=105, $y=50, '25', '165');
			PDF::ImageSVG($file=public_path('/storage/app/template/Orchestra/WCL/wcl_4.svg'), $x=105, $y=50, '25', '165', $link='', $align='', $palign='', $border=0, $fitonpage=false);
			
		}

		$item_name = str_replace( " ", "_", $owcl->item_name );
		
		PDF::Output( public_path('/storage/app/template/'.$item_name.'_ID_'.$owcl->uvc.'.pdf'), 'F');
    	print_r(url('/storage/app/template/'.$item_name.'_ID_'.$owcl->uvc.'.pdf'));

		// PDF::Output( public_path('/storage/app/template/solid1.pdf'), 'F');
        // print_r(url('/storage/app/template/solid1.pdf'));

    } 
    
    public function orderSubmitFilter(Request $request)
    {
      try{

          $merchandiser_id = Auth::guard('web_merchandiser')->id();

          $buyers = DB::table('buyers')->where('is_active','=',1)->pluck('buyer_name','id')->all();

          $date_start = $request->start_date;
          $date_end = $request->end_date;

          $owcl_season = DB::table("orchestra_wash_care_label_orders")
                            ->whereBetween('orchestra_wash_care_label_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                            ->where('orchestra_wash_care_label_orders.status','!=','Order Cancelled')
                            ->where('orchestra_wash_care_label_orders.status','<>','Buyer Approval Pending')
                            ->where('orchestra_wash_care_label_orders.merchandisers_id','=',$merchandiser_id)
                            ->select("season_code")
                            ->groupBy('season_code')
                            ->get();

          $owcl_style = DB::table("orchestra_wash_care_label_orders")
                            ->whereBetween('orchestra_wash_care_label_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                            ->where('orchestra_wash_care_label_orders.status','!=','Order Cancelled')
                            ->where('orchestra_wash_care_label_orders.status','<>','Buyer Approval Pending')
                            ->where('orchestra_wash_care_label_orders.merchandisers_id','=',$merchandiser_id)
                            ->select("uvc")
                            ->groupBy('uvc')
                            ->get();

          $owcl_supplier = DB::table('orchestra_wash_care_label_orders')
                            ->leftjoin('customers','customers.id', 'orchestra_wash_care_label_orders.customer_id')
                            ->whereBetween('orchestra_wash_care_label_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                            ->where('orchestra_wash_care_label_orders.status','!=','Order Cancelled')
                            ->where('orchestra_wash_care_label_orders.status','<>','Buyer Approval Pending')
                            ->where('orchestra_wash_care_label_orders.merchandisers_id','=',$merchandiser_id)
                            ->select('customer_id','customers.company_name')
                            ->groupBy('customer_id','customers.company_name')
                            ->get();

          return response()->json(['owcl_season' => $owcl_season,'owcl_style' => $owcl_style,'buyers' => $buyers,'owcl_supplier' => $owcl_supplier],200);

      } 
      catch (\Exception $e)
      {
        return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
      }
    }

    public function orderSubmitOs(Request $request)
    {
        try
        {
          $merchandiser_id = Auth::guard('web_merchandiser')->id();
          if($request->seasonos !== null)
          {
              $seasonos = $request->seasonos;
          }
          else
          {
              $seasonos = array();
          }

          if($request->ukstylenoos !== null)
          {
              $ukstylenoos = $request->ukstylenoos;
          }
          else
          {
              $ukstylenoos = array();
          }

          if($request->supplieros !== null)
          {
              $supplieros = $request->supplieros;
          }
          else
          {
              $supplieros = array();
          }

          if($request->statusos !== null)
          {
              $statusos = $request->statusos;
          }
          else
          {
              $statusos = array();
          }

          if($request->start_date !== null)
          {
              $date_start = $request->start_date;
          }
          else
          {
              $date_start = date("Y-m-d", strtotime(" -6 month"));
          }
          
          if($request->end_date !== null)
          {
              $date_end = $request->end_date;
          }
          else
          {
              $date_end = date("Y-m-d");
          }

          $orders = DB::table('orchestra_wash_care_label_orders')
                        ->join('orchestra_item_master','orchestra_item_master.id', 'orchestra_wash_care_label_orders.template_id')
                        ->join('orchestra_template','orchestra_template.item_id', 'orchestra_wash_care_label_orders.template_id')
                        ->leftjoin('customers','customers.id', 'orchestra_wash_care_label_orders.customer_id')
                        ->join('merchandisers','merchandisers.id','=','orchestra_wash_care_label_orders.merchandisers_id')
                        ->select('orchestra_wash_care_label_orders.uvc as UVC',DB::raw("'WashCareLabel' as \"Artwork\""),'customers.company_name as SupplierName','orchestra_wash_care_label_orders.created_at as OrderDate','orchestra_wash_care_label_orders.delivery_date as DeliveryDate','orchestra_wash_care_label_orders.status as Status',DB::raw("orchestra_wash_care_label_orders.season_code as \"Season\""),'merchandisers.merchandiser_name','orchestra_wash_care_label_orders.id','orchestra_wash_care_label_orders.approved_date as ApprovedDate','orchestra_wash_care_label_orders.rejected_date as RejectedDate')
                        ->where(function($query) use ($seasonos){
                          if(count($seasonos))
                          {
                              $query->orWhereIn('season_code', $seasonos);
                          }
                        })
                        ->where(function($query) use ($supplieros){
                          if(count($supplieros))
                          {
                              $query->orWhereIn('customer_id', $supplieros);
                          }
                        })
                        ->where(function($query) use ($ukstylenoos){
                          if(count($ukstylenoos))
                          {
                              $query->orWhereIn('uvc', $ukstylenoos);
                          }
                        })
                        ->where(function($query) use ($statusos){
                          if(count($statusos))
                          {
                              $query->orWhereIn('status', $statusos);
                          }
                        })
                        ->whereBetween('orchestra_wash_care_label_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                        ->where('orchestra_wash_care_label_orders.status','!=','Order Cancelled')
                        ->where('orchestra_wash_care_label_orders.status','<>','Buyer Approval Pending')
                        ->where('orchestra_wash_care_label_orders.merchandisers_id','=',$merchandiser_id)
                        ->groupBy('orchestra_wash_care_label_orders.uvc','orchestra_wash_care_label_orders.id','customers.company_name','merchandisers.merchandiser_name')
                        ->get();

          return response()->json(['orders' => $orders],200);

        }
        catch (\Exception $e)
        {
            return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
    }
    
}
