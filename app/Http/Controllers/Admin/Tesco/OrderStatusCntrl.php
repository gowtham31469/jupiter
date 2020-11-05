<?php

namespace App\Http\Controllers\Admin\Tesco;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use App\Classes\Modules;
use App\Classes\ProformaInvoice;
use App\ProfomaInvoice as PI;
use App\Classes\ErpOrders;
use App\ProfomaInvoiceItemDetails;
use Response;
use View;
use DB;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Config;
use App\Classes\TescoOrders;
use App\Classes\PiTCPDF;
use Carbon\Carbon;
use App\Classes\EditErpOrders;
use App\Classes\ERPOrderStatusCheck;
use Auth;
use Illuminate\Support\Facades\Redis;
use App\ErpOrders as Erp;
use App\ErpOrderDetails as ErpOrderDetails;
use App\Classes\CleanString;

class OrderStatusCntrl extends Controller
{
    private $ratio_pack_type;
    private $updated_erp_table_array;
    private $class_status;
    private $erp_obj;
	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       $this->middleware('auth');
       $this->ratio_pack_type = ['RatA', 'RatB', 'RatC', 'RatD', 'RatE', 'RatF', 'RatG', 'RatH', 'RatI', 'RatJ', 'RatK', 'RatL', 'RatM', 'RatN', 'RatO', 'RatP', 'RatQ', 'RatR', 'RatS', 'RatT', 'RatU', 'RatV', 'RatW', 'RatX', 'RatY', 'RatZ'];

       $this->updated_erp_table_array = [];
       $this->class_status = 'success';
       $this->erp_obj    = new ErpOrders();
    }
    /**
     * method to show the tesco order status page
     * @return [type] [description]
     */
	public function index()
	{
		try
		{	
			$tcl1 = "TCL 1";
			$tesco_tcl1_orders = "tesco_tcl1_orders";
			$actions="-";
			$ddmmyy="DD-MM-YYYY";
            $data['tcl1'] = DB::table('tesco_tcl1_orders')
                             ->join('customers','tesco_tcl1_orders.tesco_supplier_id','=','customers.id')
                            // ->where('tesco_tcl1_orders.is_order_count_empty',0)
                            // ->where('tesco_tcl1_orders.order_status', '!=', 'Saved')
                            ->whereNotIn('tesco_tcl1_orders.order_status',['Saved','Order Cancelled'])
                            ->select(DB::raw("'".$actions."'".' as "StyleNo"'),'tesco_tcl1_orders.uk_style_ref','tesco_tcl1_orders.ce_style_ref','tesco_tcl1_orders.po_number as BuyerPO','tesco_tcl1_orders.zone as Zone',DB::raw("'".$tcl1."'".' as "TemplateName"'),'tesco_tcl1_orders.template_type as Template Type','customers.id as customer_id','customers.company_name as Company','tesco_tcl1_orders.created_at as OrderDate',DB::raw('to_char(tesco_tcl1_orders.delivery_date,'."'".$ddmmyy."'".') as "DeliveryDate"'),'tesco_tcl1_orders.order_status as Artwork Status','tesco_tcl1_orders.pi_status as PI Status','tesco_tcl1_orders.po_status as PO Status','tesco_tcl1_orders.purchase_order_no as PO Number',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'".$tesco_tcl1_orders."'".' as "tableName"'),'tesco_tcl1_orders.id as tableID','tesco_tcl1_orders.po_file','customers.pi_required','customers.po_required','tesco_tcl1_orders.template_id','tesco_tcl1_orders.order_flag','customers.delivery_day_count','is_order_count_empty')
                            ->get();
			$tcl2 = "TCL 2";
			$tesco_tcl2_orders = "tesco_tcl2_orders";
            $data['tcl2'] = DB::table('tesco_tcl2_orders')
                            ->join('customers','tesco_tcl2_orders.tesco_supplier_id','=','customers.id')
                            // ->where('tesco_tcl2_orders.is_order_count_empty',0)
                            ->whereNotIn('tesco_tcl2_orders.order_status',['Saved','Order Cancelled'])
                            ->select(DB::raw("'".$actions."'".' as "StyleNo"'),'tesco_tcl2_orders.uk_style_ref','tesco_tcl2_orders.ce_style_ref','tesco_tcl2_orders.po_number as BuyerPO','tesco_tcl2_orders.zone as Zone',DB::raw("'".$tcl2."'".' as "TemplateName"'),'tesco_tcl2_orders.template_type as Template Type','customers.id as customer_id','customers.company_name as Company','tesco_tcl2_orders.created_at as OrderDate',DB::raw('to_char(tesco_tcl2_orders.delivery_date,'."'".$ddmmyy."'".') as "DeliveryDate"'),'tesco_tcl2_orders.order_status as Artwork Status','tesco_tcl2_orders.pi_status as PI Status','tesco_tcl2_orders.po_status as PO Status','tesco_tcl2_orders.purchase_order_no as PO Number',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'".$tesco_tcl2_orders."'".' as "tableName"'),'tesco_tcl2_orders.id as tableID','tesco_tcl2_orders.po_file','customers.pi_required','customers.po_required','tesco_tcl2_orders.template_id','tesco_tcl2_orders.order_flag','customers.delivery_day_count','is_order_count_empty')
                            ->get();
			$tcl4 = "TCL 4";
			$tesco_tcl4_orders = "tesco_tcl4_orders";
            $data['tcl4'] = DB::table('tesco_tcl4_orders')
                            ->join('customers','tesco_tcl4_orders.tesco_supplier_id','=','customers.id')
                            // ->where('tesco_tcl4_orders.is_order_count_empty',0)
                            // ->where('tesco_tcl4_orders.order_status', '!=', 'Saved')
                            ->whereNotIn('tesco_tcl4_orders.order_status',['Saved','Order Cancelled'])
                            ->select(DB::raw("'".$actions."'".' as "StyleNo"'),'tesco_tcl4_orders.uk_style_ref','tesco_tcl4_orders.ce_style_ref','tesco_tcl4_orders.po_number as BuyerPO','tesco_tcl4_orders.zone as Zone',DB::raw("'".$tcl4."'".' as "TemplateName"'),'tesco_tcl4_orders.template_type as Template Type','customers.id as customer_id','customers.company_name as Company','tesco_tcl4_orders.created_at as OrderDate',DB::raw('to_char(tesco_tcl4_orders.delivery_date,'."'".$ddmmyy."'".') as "DeliveryDate"'),'tesco_tcl4_orders.order_status as Artwork Status','tesco_tcl4_orders.pi_status as PI Status','tesco_tcl4_orders.po_status as PO Status','tesco_tcl4_orders.purchase_order_no as PO Number',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'".$tesco_tcl4_orders."'".' as "tableName"'),'tesco_tcl4_orders.id as tableID','tesco_tcl4_orders.po_file','customers.pi_required','customers.po_required','tesco_tcl4_orders.template_id','tesco_tcl4_orders.order_flag','customers.delivery_day_count','is_order_count_empty')
                            ->get();
			$thl1 = "THL 1";
			$tesco_thl1_orders = "tesco_thl1_orders";
            $data['thl1'] = DB::table('tesco_thl1_orders')
                            ->join('customers','tesco_thl1_orders.tesco_supplier_id','=','customers.id')
                            ->where('tesco_thl1_orders.is_order_count_empty',0)
                            // ->where('tesco_thl1_orders.order_status', '!=', 'Saved')
                            ->whereNotIn('tesco_thl1_orders.order_status',['Saved','Order Cancelled'])
                            ->select(DB::raw("'".$actions."'".' as "StyleNo"'),'tesco_thl1_orders.uk_style_ref','tesco_thl1_orders.ce_style_ref','tesco_thl1_orders.po_number as BuyerPO','tesco_thl1_orders.zone as Zone',DB::raw("'".$thl1."'".' as "TemplateName"'),'tesco_thl1_orders.template_type as Template Type','customers.id as customer_id','customers.company_name as Company','tesco_thl1_orders.created_at as OrderDate',DB::raw('to_char(tesco_thl1_orders.delivery_date,'."'".$ddmmyy."'".') as "DeliveryDate"'),'tesco_thl1_orders.order_status as Artwork Status','tesco_thl1_orders.pi_status as PI Status','tesco_thl1_orders.po_status as PO Status','tesco_thl1_orders.purchase_order_no as PO Number',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'".$tesco_thl1_orders."'".' as "tableName"'),'tesco_thl1_orders.id as tableID','tesco_thl1_orders.po_file','customers.pi_required','customers.po_required','tesco_thl1_orders.template_id','tesco_thl1_orders.order_flag','customers.delivery_day_count','is_order_count_empty')
                                ->get();
			$thl3 = "THL 3";
			$tesco_thl3_orders = "tesco_thl3_orders";
            $data['thl3'] = DB::table('tesco_thl3_orders')
                            ->join('customers','tesco_thl3_orders.tesco_supplier_id','=','customers.id')
                            // ->where('tesco_thl3_orders.is_order_count_empty',0)
                            // ->where('tesco_thl3_orders.order_status', '!=', 'Saved')
                            ->whereNotIn('tesco_thl3_orders.order_status',['Saved','Order Cancelled'])
                            ->select(DB::raw("'".$actions."'".' as "StyleNo"'),'tesco_thl3_orders.uk_style_ref','tesco_thl3_orders.ce_style_ref','tesco_thl3_orders.po_number as BuyerPO','tesco_thl3_orders.zone as Zone',DB::raw("'".$thl3."'".' as "TemplateName"'),'tesco_thl3_orders.template_type as Template Type','customers.id as customer_id','customers.company_name as Company','tesco_thl3_orders.created_at as OrderDate',DB::raw('to_char(tesco_thl3_orders.delivery_date,'."'".$ddmmyy."'".') as "DeliveryDate"'),'tesco_thl3_orders.order_status as Artwork Status','tesco_thl3_orders.pi_status as PI Status','tesco_thl3_orders.po_status as PO Status','tesco_thl3_orders.purchase_order_no as PO Number',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'".$tesco_thl3_orders."'".' as "tableName"'),'tesco_thl3_orders.id as tableID','tesco_thl3_orders.po_file','customers.pi_required','customers.po_required','tesco_thl3_orders.template_id','tesco_thl3_orders.order_flag','customers.delivery_day_count','is_order_count_empty')
                            ->get();
			$tsl1 = "TSL 1";
			$tesco_tsl1_orders = "tesco_tsl1_orders";
            $data['tsl1'] = DB::table('tesco_tsl1_orders')
                            ->join('customers','tesco_tsl1_orders.tesco_supplier_id','=','customers.id')
                            // ->where('tesco_tsl1_orders.is_order_count_empty',0)
                            // ->where('tesco_tsl1_orders.order_status', '!=', 'Saved')
                            ->whereNotIn('tesco_tsl1_orders.order_status',['Saved','Order Cancelled'])
                            ->select(DB::raw("'".$actions."'".' as "StyleNo"'),'tesco_tsl1_orders.uk_style_ref','tesco_tsl1_orders.ce_style_ref','tesco_tsl1_orders.po_number as BuyerPO','tesco_tsl1_orders.zone as Zone',DB::raw("'".$tsl1."'".' as "TemplateName"'),'tesco_tsl1_orders.template_type as Template Type','customers.id as customer_id','customers.company_name as Company','tesco_tsl1_orders.created_at as OrderDate',DB::raw('to_char(tesco_tsl1_orders.delivery_date,'."'".$ddmmyy."'".') as "DeliveryDate"'),'tesco_tsl1_orders.order_status as Artwork Status','tesco_tsl1_orders.pi_status as PI Status','tesco_tsl1_orders.po_status as PO Status','tesco_tsl1_orders.purchase_order_no as PO Number',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'".$tesco_tsl1_orders."'".' as "tableName"'),'tesco_tsl1_orders.id as tableID','tesco_tsl1_orders.po_file','customers.pi_required','customers.po_required','tesco_tsl1_orders.template_id','tesco_tsl1_orders.order_flag','customers.delivery_day_count','is_order_count_empty')
                            ->get();
			$tsl3 = "TSL 3";
			$tesco_tsl3_orders = "tesco_tsl3_orders";
            $data['tsl3'] = DB::table('tesco_tsl3_orders')
                            ->join('customers','tesco_tsl3_orders.tesco_supplier_id','=','customers.id')
                            // ->where('tesco_tsl3_orders.is_order_count_empty',0)
                            // ->where('tesco_tsl3_orders.order_status', '!=', 'Saved')
                            ->whereNotIn('tesco_tsl3_orders.order_status',['Saved','Order Cancelled'])
                            ->select(DB::raw("'".$actions."'".' as "StyleNo"'),'tesco_tsl3_orders.uk_style_ref','tesco_tsl3_orders.ce_style_ref','tesco_tsl3_orders.po_number as BuyerPO','tesco_tsl3_orders.zone as Zone',DB::raw("'".$tsl3."'".' as "TemplateName"'),'tesco_tsl3_orders.template_type as Template Type','customers.id as customer_id','customers.company_name as Company','tesco_tsl3_orders.created_at as OrderDate',DB::raw('to_char(tesco_tsl3_orders.delivery_date,'."'".$ddmmyy."'".') as "DeliveryDate"'),'tesco_tsl3_orders.order_status as Artwork Status','tesco_tsl3_orders.pi_status as PI Status','tesco_tsl3_orders.po_status as PO Status','tesco_tsl3_orders.purchase_order_no as PO Number',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'".$tesco_tsl3_orders."'".' as "tableName"'),'tesco_tsl3_orders.id as tableID','tesco_tsl3_orders.po_file','customers.pi_required','customers.po_required','tesco_tsl3_orders.template_id','tesco_tsl3_orders.order_flag','customers.delivery_day_count','is_order_count_empty')
                            ->get();

            $admin_id = Auth::guard('web')->id();
            /***********REDIS***************/
            /** set order-status data into redis */ 
            Redis::set('admin-order-status:tesco-'.$admin_id, json_encode($data));

            /***********REDIS***************/
            /** get order-status data from redis */
            $data = Redis::get('admin-order-status:tesco-'.$admin_id);
            $data = json_decode($data);
	     	$result = response()->json(['error'=> false,'message' =>'success','data'=>$data],200);            
        }
        catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
    }
    public function tescoFilter(Request $request)
    {
        try
		{
            $tcl1 = "TCL 1";
			$tesco_tcl1_orders = "tesco_tcl1_orders";
			$actions="-";
            $ddmmyy="DD-MM-YYYY";
            $date_start = date("Y-m-d", strtotime(" -6 month"));
            $date_end = date("Y-m-d");

            $tcl1 = DB::table("tesco_tcl1_orders")
            ->whereBetween('tesco_tcl1_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                ->select("season_code");

            $tcl2 = DB::table("tesco_tcl2_orders")
            ->whereBetween('tesco_tcl2_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                ->select("season_code");
            
            $tcl4 = DB::table("tesco_tcl4_orders")
            ->whereBetween('tesco_tcl4_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                    ->select("season_code");

            $tsl1 = DB::table("tesco_tsl1_orders")
            ->whereBetween('tesco_tsl1_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                    ->select("season_code");

            $tsl3 = DB::table("tesco_tsl3_orders")
            ->whereBetween('tesco_tsl3_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                    ->select("season_code");

            $thl1 = DB::table("tesco_thl1_orders")
            ->whereBetween('tesco_thl1_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                    ->select("season_code");
                
            $data = DB::table("tesco_thl3_orders")
            ->whereBetween('tesco_thl3_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                ->select("season_code")
                ->groupBy('season_code')
                ->union($tcl1)
                ->union($tcl2)
                ->union($tcl4)
                ->union($tsl1)
                ->union($tsl3)
                ->union($thl1)
                ->get();

            $tcl1_style = DB::table("tesco_tcl1_orders")
            ->whereBetween('tesco_tcl1_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                    ->select("uk_style_ref","ce_style_ref","zone");

            $tcl2_style = DB::table("tesco_tcl2_orders")
            ->whereBetween('tesco_tcl2_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                    ->select("uk_style_ref","ce_style_ref","zone");
                    
            $tcl4_style = DB::table("tesco_tcl4_orders")
            ->whereBetween('tesco_tcl4_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                    ->select("uk_style_ref","ce_style_ref","zone");
            
            $tsl1_style = DB::table("tesco_tsl1_orders")
            ->whereBetween('tesco_tsl1_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                    ->select("uk_style_ref","ce_style_ref","zone");
            
            $tsl3_style = DB::table("tesco_tsl3_orders")
            ->whereBetween('tesco_tsl3_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                    ->select("uk_style_ref","ce_style_ref","zone");

            $thl1_style = DB::table("tesco_thl1_orders")
            ->whereBetween('tesco_thl1_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                    ->select("uk_style_ref","ce_style_ref","zone");

            $style_no = DB::table("tesco_thl3_orders")
                        ->whereBetween('tesco_thl3_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                        ->select("uk_style_ref","ce_style_ref","zone")
                        ->union($tcl1_style)
                        ->union($tcl2_style)
                        ->union($tcl4_style)
                        ->union($tsl1_style)
                        ->union($tsl3_style)
                        ->union($thl1_style)
                        ->get();

            //$data = json_decode($data);
            $result = response()->json(['error'=> false,'message' =>'success','data'=>$data,'style_no'=>$style_no],200);
            return $result;
        }
        catch (\Exception $e)
        {
            return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
    }
    public function tescoOrdersSubmitFilter(Request $request)
    {
        try
		{
            $date_start = $request->start_date;
            $date_end = $request->end_date;

            $tcl1 = DB::table("tesco_tcl1_orders")
            ->whereBetween('tesco_tcl1_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                ->select("season_code");

            $tcl2 = DB::table("tesco_tcl2_orders")
            ->whereBetween('tesco_tcl2_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                ->select("season_code");
            
            $tcl4 = DB::table("tesco_tcl4_orders")
            ->whereBetween('tesco_tcl4_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                    ->select("season_code");

            $tsl1 = DB::table("tesco_tsl1_orders")
            ->whereBetween('tesco_tsl1_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                    ->select("season_code");

            $tsl3 = DB::table("tesco_tsl3_orders")
            ->whereBetween('tesco_tsl3_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                    ->select("season_code");

            $thl1 = DB::table("tesco_thl1_orders")
            ->whereBetween('tesco_thl1_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                    ->select("season_code");
                
            $data = DB::table("tesco_thl3_orders")
            ->whereBetween('tesco_thl3_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                ->select("season_code")
                ->groupBy('season_code')
                ->union($tcl1)
                ->union($tcl2)
                ->union($tcl4)
                ->union($tsl1)
                ->union($tsl3)
                ->union($thl1)
                ->get();

            $tcl1_style = DB::table("tesco_tcl1_orders")
            ->whereBetween('tesco_tcl1_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                    ->select("uk_style_ref","ce_style_ref","zone");

            $tcl2_style = DB::table("tesco_tcl2_orders")
            ->whereBetween('tesco_tcl2_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                    ->select("uk_style_ref","ce_style_ref","zone");
                    
            $tcl4_style = DB::table("tesco_tcl4_orders")
            ->whereBetween('tesco_tcl4_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                    ->select("uk_style_ref","ce_style_ref","zone");
            
            $tsl1_style = DB::table("tesco_tsl1_orders")
            ->whereBetween('tesco_tsl1_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                    ->select("uk_style_ref","ce_style_ref","zone");
            
            $tsl3_style = DB::table("tesco_tsl3_orders")
            ->whereBetween('tesco_tsl3_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                    ->select("uk_style_ref","ce_style_ref","zone");

            $thl1_style = DB::table("tesco_thl1_orders")
            ->whereBetween('tesco_thl1_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                    ->select("uk_style_ref","ce_style_ref","zone");

            $style_no = DB::table("tesco_thl3_orders")
                        ->whereBetween('tesco_thl3_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                        ->select("uk_style_ref","ce_style_ref","zone")
                        ->union($tcl1_style)
                        ->union($tcl2_style)
                        ->union($tcl4_style)
                        ->union($tsl1_style)
                        ->union($tsl3_style)
                        ->union($thl1_style)
                        ->get();

            //$data = json_decode($data);
            $result = response()->json(['error'=> false,'message' =>'success','data'=>$data,'style_no'=>$style_no],200);
            return $result;
        }
        catch (\Exception $e)
        {
            return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
    }
    

    public function tescoSubmitOs(Request $request)
    {
        try
		{
            if($request->seasonos !== null)
            {
                $seasonos = $request->seasonos;
            }
            else
            {
                $seasonos = array();
            }

            if($request->orderstatusos !== null)
            {
                $orderstatusos = $request->orderstatusos;
            }
            else
            {
                $orderstatusos = [];
            }

            if($request->artworkstatusos !== null)
            {
                $artworkstatusos = $request->artworkstatusos;
            }
            else
            {
                $artworkstatusos = [];
            }


            if($request->postatusos !== null)
            {
                $postatusos = $request->postatusos;
            }
            else
            {
                $postatusos = [];
            }

            if($request->pistatusos !== null)
            {
                $pistatusos = $request->pistatusos;
            }
            else
            {
                $pistatusos = [];
            }

            if($request->stylenoos !== null)
            {
                $stylenoos = $request->stylenoos;
            }
            else
            {
                $stylenoos = [];
            }

            if($request->ukstylenoos !== null)
            {
                $ukstylenoos = $request->ukstylenoos;
            }
            else
            {
                $ukstylenoos = [];
            }

            if (in_array("Approval Pending", $artworkstatusos))
            {
                array_splice($artworkstatusos, array_search("Approval Pending", $artworkstatusos ), 1);
                array_push($artworkstatusos,"Order Placed","Saved","Order Pending");
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
           
            
            $tcl1 = "TCL 1";
			$tesco_tcl1_orders = "tesco_tcl1_orders";
			$actions="-";
			$ddmmyy="DD-MM-YYYY";
            $tesco_tcl1_orders = DB::table("tesco_tcl1_orders")
                            ->join('customers','tesco_tcl1_orders.tesco_supplier_id','=','customers.id')
                            ->whereBetween('tesco_tcl1_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                            ->orWhereIn('season_code', $seasonos)
                            ->orWhereIn('order_status', $orderstatusos)
                            ->orWhereIn('order_status', $artworkstatusos)
                            ->orWhereIn('ce_style_ref', $stylenoos)
                            ->orWhereIn('uk_style_ref', $ukstylenoos)
                            ->orWhereIn('po_status', $postatusos)
                            ->orWhereIn('pi_status', $pistatusos)
                            ->select(DB::raw("'".$actions."'".' as "StyleNo"'),'tesco_tcl1_orders.uk_style_ref','tesco_tcl1_orders.ce_style_ref','tesco_tcl1_orders.po_number as BuyerPO','tesco_tcl1_orders.zone as Zone',DB::raw("'".$tcl1."'".' as "TemplateName"'),'tesco_tcl1_orders.template_type as Template Type','customers.id as customer_id','customers.company_name as Company','tesco_tcl1_orders.created_at as OrderDate',DB::raw('to_char(tesco_tcl1_orders.delivery_date,'."'".$ddmmyy."'".') as "DeliveryDate"'),'tesco_tcl1_orders.order_status as Artwork Status','tesco_tcl1_orders.pi_status as PI Status','tesco_tcl1_orders.po_status as PO Status','tesco_tcl1_orders.purchase_order_no as PO Number',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'".$tesco_tcl1_orders."'".' as "tableName"'),'tesco_tcl1_orders.id as tableID','tesco_tcl1_orders.po_file','customers.pi_required','customers.po_required','tesco_tcl1_orders.template_id','tesco_tcl1_orders.order_flag','customers.delivery_day_count','is_order_count_empty');

            $tcl2 = "TCL 2";
            $tesco_tcl2_orders = "tesco_tcl2_orders";
            $tesco_tcl2_orders = DB::table("tesco_tcl2_orders")
            ->join('customers','tesco_tcl2_orders.tesco_supplier_id','=','customers.id')
                            ->whereBetween('tesco_tcl2_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                            ->orWhereIn('season_code', $seasonos)
                            ->orWhereIn('order_status', $orderstatusos)
                            ->orWhereIn('order_status', $artworkstatusos)
                            ->orWhereIn('ce_style_ref', $stylenoos)
                            ->orWhereIn('uk_style_ref', $ukstylenoos)
                            ->orWhereIn('po_status', $postatusos)
                            ->orWhereIn('pi_status', $pistatusos)
                            ->select(DB::raw("'".$actions."'".' as "StyleNo"'),'tesco_tcl2_orders.uk_style_ref','tesco_tcl2_orders.ce_style_ref','tesco_tcl2_orders.po_number as BuyerPO','tesco_tcl2_orders.zone as Zone',DB::raw("'".$tcl2."'".' as "TemplateName"'),'tesco_tcl2_orders.template_type as Template Type','customers.id as customer_id','customers.company_name as Company','tesco_tcl2_orders.created_at as OrderDate',DB::raw('to_char(tesco_tcl2_orders.delivery_date,'."'".$ddmmyy."'".') as "DeliveryDate"'),'tesco_tcl2_orders.order_status as Artwork Status','tesco_tcl2_orders.pi_status as PI Status','tesco_tcl2_orders.po_status as PO Status','tesco_tcl2_orders.purchase_order_no as PO Number',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'".$tesco_tcl2_orders."'".' as "tableName"'),'tesco_tcl2_orders.id as tableID','tesco_tcl2_orders.po_file','customers.pi_required','customers.po_required','tesco_tcl2_orders.template_id','tesco_tcl2_orders.order_flag','customers.delivery_day_count','is_order_count_empty');

            $tcl4 = "TCL 4";
            $tesco_tcl4_orders = "tesco_tcl4_orders";
            $tesco_tcl4_orders = DB::table("tesco_tcl4_orders")
            ->join('customers','tesco_tcl4_orders.tesco_supplier_id','=','customers.id')
                            ->whereBetween('tesco_tcl4_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                            ->orWhereIn('season_code', $seasonos)
                            ->orWhereIn('order_status', $orderstatusos)
                            ->orWhereIn('order_status', $artworkstatusos)
                            ->orWhereIn('ce_style_ref', $stylenoos)
                            ->orWhereIn('uk_style_ref', $ukstylenoos)
                            ->orWhereIn('po_status', $postatusos)
                            ->orWhereIn('pi_status', $pistatusos)
                            ->select(DB::raw("'".$actions."'".' as "StyleNo"'),'tesco_tcl4_orders.uk_style_ref','tesco_tcl4_orders.ce_style_ref','tesco_tcl4_orders.po_number as BuyerPO','tesco_tcl4_orders.zone as Zone',DB::raw("'".$tcl4."'".' as "TemplateName"'),'tesco_tcl4_orders.template_type as Template Type','customers.id as customer_id','customers.company_name as Company','tesco_tcl4_orders.created_at as OrderDate',DB::raw('to_char(tesco_tcl4_orders.delivery_date,'."'".$ddmmyy."'".') as "DeliveryDate"'),'tesco_tcl4_orders.order_status as Artwork Status','tesco_tcl4_orders.pi_status as PI Status','tesco_tcl4_orders.po_status as PO Status','tesco_tcl4_orders.purchase_order_no as PO Number',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'".$tesco_tcl4_orders."'".' as "tableName"'),'tesco_tcl4_orders.id as tableID','tesco_tcl4_orders.po_file','customers.pi_required','customers.po_required','tesco_tcl4_orders.template_id','tesco_tcl4_orders.order_flag','customers.delivery_day_count','is_order_count_empty');

            $tsl1 = "TSL 1";
            $tesco_tsl1_orders = "tesco_tsl1_orders";
            $tesco_tsl1_orders = DB::table("tesco_tsl1_orders")
            ->join('customers','tesco_tsl1_orders.tesco_supplier_id','=','customers.id')
                            ->whereBetween('tesco_tsl1_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                            ->orWhereIn('season_code', $seasonos)
                            ->orWhereIn('order_status', $orderstatusos)
                            ->orWhereIn('order_status', $artworkstatusos)
                            ->orWhereIn('ce_style_ref', $stylenoos)
                            ->orWhereIn('uk_style_ref', $ukstylenoos)
                            ->orWhereIn('po_status', $postatusos)
                            ->orWhereIn('pi_status', $pistatusos)
                            ->select(DB::raw("'".$actions."'".' as "StyleNo"'),'tesco_tsl1_orders.uk_style_ref','tesco_tsl1_orders.ce_style_ref','tesco_tsl1_orders.po_number as BuyerPO','tesco_tsl1_orders.zone as Zone',DB::raw("'".$tsl1."'".' as "TemplateName"'),'tesco_tsl1_orders.template_type as Template Type','customers.id as customer_id','customers.company_name as Company','tesco_tsl1_orders.created_at as OrderDate',DB::raw('to_char(tesco_tsl1_orders.delivery_date,'."'".$ddmmyy."'".') as "DeliveryDate"'),'tesco_tsl1_orders.order_status as Artwork Status','tesco_tsl1_orders.pi_status as PI Status','tesco_tsl1_orders.po_status as PO Status','tesco_tsl1_orders.purchase_order_no as PO Number',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'".$tesco_tsl1_orders."'".' as "tableName"'),'tesco_tsl1_orders.id as tableID','tesco_tsl1_orders.po_file','customers.pi_required','customers.po_required','tesco_tsl1_orders.template_id','tesco_tsl1_orders.order_flag','customers.delivery_day_count','is_order_count_empty');

			$tsl3 = "TSL 3";
			$tesco_tsl3_orders = "tesco_tsl3_orders";
            $tesco_tsl3_orders = DB::table("tesco_tsl3_orders")
            ->join('customers','tesco_tsl3_orders.tesco_supplier_id','=','customers.id')
                            ->whereBetween('tesco_tsl3_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                            ->orWhereIn('season_code', $seasonos)
                            ->orWhereIn('order_status', $orderstatusos)
                            ->orWhereIn('order_status', $artworkstatusos)
                            ->orWhereIn('ce_style_ref', $stylenoos)
                            ->orWhereIn('uk_style_ref', $ukstylenoos)
                            ->orWhereIn('po_status', $postatusos)
                            ->orWhereIn('pi_status', $pistatusos)
                            ->select(DB::raw("'".$actions."'".' as "StyleNo"'),'tesco_tsl3_orders.uk_style_ref','tesco_tsl3_orders.ce_style_ref','tesco_tsl3_orders.po_number as BuyerPO','tesco_tsl3_orders.zone as Zone',DB::raw("'".$tsl3."'".' as "TemplateName"'),'tesco_tsl3_orders.template_type as Template Type','customers.id as customer_id','customers.company_name as Company','tesco_tsl3_orders.created_at as OrderDate',DB::raw('to_char(tesco_tsl3_orders.delivery_date,'."'".$ddmmyy."'".') as "DeliveryDate"'),'tesco_tsl3_orders.order_status as Artwork Status','tesco_tsl3_orders.pi_status as PI Status','tesco_tsl3_orders.po_status as PO Status','tesco_tsl3_orders.purchase_order_no as PO Number',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'".$tesco_tsl3_orders."'".' as "tableName"'),'tesco_tsl3_orders.id as tableID','tesco_tsl3_orders.po_file','customers.pi_required','customers.po_required','tesco_tsl3_orders.template_id','tesco_tsl3_orders.order_flag','customers.delivery_day_count','is_order_count_empty');

            $thl1 = "THL 1";
            $tesco_thl1_orders = "tesco_thl1_orders";
            $tesco_thl1_orders = DB::table("tesco_thl1_orders")
            ->join('customers','tesco_thl1_orders.tesco_supplier_id','=','customers.id')
                            ->whereBetween('tesco_thl1_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                            ->orWhereIn('season_code', $seasonos)
                            ->orWhereIn('order_status', $orderstatusos)
                            ->orWhereIn('order_status', $artworkstatusos)
                            ->orWhereIn('ce_style_ref', $stylenoos)
                            ->orWhereIn('uk_style_ref', $ukstylenoos)
                            ->orWhereIn('po_status', $postatusos)
                            ->orWhereIn('pi_status', $pistatusos)
                            ->select(DB::raw("'".$actions."'".' as "StyleNo"'),'tesco_thl1_orders.uk_style_ref','tesco_thl1_orders.ce_style_ref','tesco_thl1_orders.po_number as BuyerPO','tesco_thl1_orders.zone as Zone',DB::raw("'".$thl1."'".' as "TemplateName"'),'tesco_thl1_orders.template_type as Template Type','customers.id as customer_id','customers.company_name as Company','tesco_thl1_orders.created_at as OrderDate',DB::raw('to_char(tesco_thl1_orders.delivery_date,'."'".$ddmmyy."'".') as "DeliveryDate"'),'tesco_thl1_orders.order_status as Artwork Status','tesco_thl1_orders.pi_status as PI Status','tesco_thl1_orders.po_status as PO Status','tesco_thl1_orders.purchase_order_no as PO Number',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'".$tesco_thl1_orders."'".' as "tableName"'),'tesco_thl1_orders.id as tableID','tesco_thl1_orders.po_file','customers.pi_required','customers.po_required','tesco_thl1_orders.template_id','tesco_thl1_orders.order_flag','customers.delivery_day_count','is_order_count_empty');

			$thl3 = "THL 3";
			$tesco_thl3_orders = "tesco_thl3_orders";
            $orders = DB::table("tesco_thl3_orders")
            ->join('customers','tesco_thl3_orders.tesco_supplier_id','=','customers.id')
                        ->whereBetween('tesco_thl3_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                        ->orWhereIn('season_code', $seasonos)
                        ->orWhereIn('order_status', $orderstatusos)
                        ->orWhereIn('order_status', $artworkstatusos)
                        ->orWhereIn('ce_style_ref', $stylenoos)
                        ->orWhereIn('uk_style_ref', $ukstylenoos)
                        ->orWhereIn('po_status', $postatusos)
                        ->orWhereIn('pi_status', $pistatusos)
                        ->select(DB::raw("'".$actions."'".' as "StyleNo"'),'tesco_thl3_orders.uk_style_ref','tesco_thl3_orders.ce_style_ref','tesco_thl3_orders.po_number as BuyerPO','tesco_thl3_orders.zone as Zone',DB::raw("'".$thl3."'".' as "TemplateName"'),'tesco_thl3_orders.template_type as Template Type','customers.id as customer_id','customers.company_name as Company','tesco_thl3_orders.created_at as OrderDate',DB::raw('to_char(tesco_thl3_orders.delivery_date,'."'".$ddmmyy."'".') as "DeliveryDate"'),'tesco_thl3_orders.order_status as Artwork Status','tesco_thl3_orders.pi_status as PI Status','tesco_thl3_orders.po_status as PO Status','tesco_thl3_orders.purchase_order_no as PO Number',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'".$tesco_thl3_orders."'".' as "tableName"'),'tesco_thl3_orders.id as tableID','tesco_thl3_orders.po_file','customers.pi_required','customers.po_required','tesco_thl3_orders.template_id','tesco_thl3_orders.order_flag','customers.delivery_day_count','is_order_count_empty')
                        ->union($tesco_tcl1_orders)
                        ->union($tesco_tcl2_orders)
                        ->union($tesco_tcl4_orders)
                        ->union($tesco_tsl1_orders)
                        ->union($tesco_tsl3_orders)
                        ->union($tesco_thl1_orders)
                        ->get();

            //$data = json_decode($data);
            $result = response()->json(['error'=> false,'message' =>'success','data'=>$orders],200);
            return $result;
        }
        catch (\Exception $e)
        {
            return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
    }
    
    /**
     * method to set the PO number for an order
     * @param Request $request
     */
	public function setPONumber(Request $request)
	{
		try
		{
            $po_number      = $request->po_number;
			$template_id    = $request->id;
            $table_name     = $request->tableName; 

            $po_details     = DB::table($table_name)->where('id',$template_id)
                                ->select('po_file','purchase_order_no')->first();
            
            // check order status in erp_oorders table.                    
            $data_array = [];
            $data_array['id'] = $template_id;
            $data_array['table_name'] = $table_name;
            $class_obj = new ERPOrderStatusCheck();
            $erp_order_details = $class_obj->ERPStatus($data_array);
            
            if($erp_order_details && $erp_order_details[0] != null)
            {
                if($erp_order_details[0]->status_name != 'Hold For JC Queue' &&  $erp_order_details[0]->status_name != 'Moved To ERP' )
                {
                    return response()->json(['error'=> false,'message' =>'Order status is '.$erp_order_details[0]->status_name. '. PO number edit is not allowed.'],403);
                }
                
            }
            
            if ($po_details->po_file === null || $po_details->po_file === "")
            {
                $po_status = "PO Pending";
            }
            else
            {
                $po_status = "PO Uploaded";
            }

            DB::table($table_name)->where('id',$template_id)
            ->update([
                'purchase_order_no' => $po_number,
                'po_status' => $po_status,
                'po_uploaded_at' => Carbon::now('Asia/Kolkata')->toDateTimeString()
                ]);

            if ($po_status === "PO Uploaded") {
                /** @var array [Pass array of values to updateTescoErpOrders function] */
                $data_array_2 = [];
                $data_array_2['id'] = $template_id;
                $data_array_2['tableName'] = $table_name;
                $data_array_2['buyer_id'] = $request->buyer_id;
                $data_array_2['created_by'] = Auth::guard('web')->id();

                $jsonResponse = $this->erp_obj->updateTescoErpOrders($data_array_2);
                $content = $jsonResponse->getContent();
                $this->updated_erp_table_array = json_decode($content, true);
                
                $this->class_status = array_key_exists('message', $this->updated_erp_table_array)?$this->updated_erp_table_array['message']:'error';

                if ($this->class_status === 'success' ) {
                    $buyer_id = $this->updated_erp_table_array['buyer_id'];
                    $customer_id = $this->updated_erp_table_array['customer_id'];
                    
                    $erp_previous_status = Erp::where('buyer_id',$buyer_id)->where('customer_id',$customer_id)->where('po_no',$po_details->purchase_order_no)->first();
                    
                    if($erp_previous_status)
                    {
                        $erp_moved_erp = ErpOrderDetails::where('erp_order_details.erp_order_id', $erp_previous_status->id)->where('status',1)->get();
                    
                        if(count($erp_moved_erp) == 0)
                        {
                            if($erp_previous_status->previous_status != "" && $erp_previous_status->previous_status != null)
                            {
                                Erp::where('id',$erp_previous_status->id)->update([
                                        'status' => $erp_previous_status->previous_status,
                                        'previous_status' => null,
                                ]);
                            }
                            
                        }
                    }
                }
            }

            if ($this->class_status === 'success' ) 
            {
                return response()->json(['error'=> false,'message' =>'Purchase Order Number added successfully'],200);
            }
            else {
                return response()->json(['error'=> true,'message' =>'Purchase Order Number updation failed'],400);
            }

            
		}
        catch (\Exception $e)
        {
            return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
	}

     /**
     * method to set the po number and pdf file for an order
     * @param po_number and pdf (pdf is optional)
     * @return \Illuminate\Http\Response
     */
    public function setCustomerPOFile(Request $request)
    {
        try 
        {
            $formartStr_obj   = new CleanString();
            $table_name = $request->tableName;
            $row_id     = $request->id;
            $po_file    = $request->file('file');
            $path       = $request->file('file')->getRealPath();
            $filename   = $request->file('file')->getClientOriginalName();
            $destination_path='/storage/app/public/po-files/';
            $path       ='storage/app/public/po-files/';
            $Orname     = $po_file->getClientOriginalName();
            $filename   = $formartStr_obj->cleanStr($Orname);
            $po_file->move($path,$filename);
            $destination_path_insert=$destination_path.$filename;

            $po_details = DB::table($table_name)->where('id',$row_id)->select('purchase_order_no')->first();

            $data_array = [];
            $data_array['id'] = $row_id;
            $data_array['table_name'] = $table_name;
            $data_array['created_by'] = Auth::guard('web')->id();
            $class_obj = new ERPOrderStatusCheck();
            $erp_order_details = $class_obj->ERPStatus($data_array);

            if($erp_order_details && $erp_order_details[0] != null)
            {
                if($erp_order_details[0]->status_name != 'Hold For JC Queue' &&  $erp_order_details[0]->status_name != 'Moved To ERP' )
                {
                    return response()->json(['error'=> false,'message' =>'Order status is '.$erp_order_details[0]->status_name. '. PO file upload is not allowed.'],403);
                }
                
            }
            
            if ($po_details->purchase_order_no === null || $po_details->purchase_order_no === "")
            {
                $po_status = "PO Pending";
            }
            else
            {
                $po_status = "PO Uploaded";
            }
            
            DB::table($table_name)->where('id',$row_id)
            ->update([
                'po_file' => $destination_path_insert,
                'po_status' => $po_status,
                'po_uploaded_at' => Carbon::now('Asia/Kolkata')->toDateTimeString()

            ]);
            
            if ($po_status == "PO Uploaded") {
                /** @var array [Pass array of values to updateTescoErpOrders function] */
                $data_array_2 = [];   
                $data_array_2['tableName'] = $table_name;
                $data_array_2['buyer_id'] = $request->buyer_id;
                $data_array_2['id'] = $row_id;
                $data_array_2['created_by'] = Auth::guard('web')->id();

                $jsonResponse = $this->erp_obj->updateTescoErpOrders($data_array_2);

                $content = $jsonResponse->getContent();

                $this->updated_erp_table_array = json_decode($content, true);

                $this->class_status = array_key_exists('message', $this->updated_erp_table_array)?$this->updated_erp_table_array['message']:'error';

                if ($this->class_status === 'success' ) {
                    $buyer_id = $this->updated_erp_table_array['buyer_id'];
                    $customer_id = $this->updated_erp_table_array['customer_id'];
                    
                    $erp_previous_status = Erp::where('buyer_id',$buyer_id)->where('customer_id',$customer_id)->where('po_no',$po_details->purchase_order_no)->first();
                    
                    if($erp_previous_status)
                    {
                        $erp_moved_erp = ErpOrderDetails::where('erp_order_details.erp_order_id', $erp_previous_status->id)->where('status',1)->get();
                    
                        if(count($erp_moved_erp) == 0)
                        {
                            if($erp_previous_status->previous_status != "" && $erp_previous_status->previous_status != null)
                            {
                                Erp::where('id',$erp_previous_status->id)->update([
                                        'status' => $erp_previous_status->previous_status,
                                        'previous_status' => null,
                                ]);
                            }
                            
                        }
                    }
                }
            }

            if ($this->class_status === 'success' ) 
            {
               return response()->json(['error'=> false,'message' =>'success','po_file'=>$destination_path_insert],200);
            }
            else
            {
                return response()->json(['error'=> true,'message' =>'Error'],400);
            }    
        }
        catch (Exception $e)
        {
            return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        } 
    }

    /**
     * method to get the proforma invoice details of an artwork
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getTescoPI(Request $request)
    {
        try
        {   
            $pi_obj    = new ProformaInvoice();
            $pi_status = $request->pi_status;

            if ($pi_status == "true")
            {
                $data      = $pi_obj->getApprovedTescoPI($request);
            }
            else
            {
                $data      = $pi_obj->getTescoPIDetails($request);
            }
            $result    = response()->json(['error'=> false,'message' =>'success','data'=>$data],200);
        }
        catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
    }
    /**
     * method to approve the proforma invoice
     * @param  Request $request [description]
     * @return \Illuminate\Http\Response
     */
    public function approveTescoPI(Request $request)
    {
        try
        {
            $row_id     =   $request->id;
            $table_name =   $request->tableName;
            $buyer_id   =   $request->buyer_id;
            $billamnt   =   $request->billamnt;
            $total_qnty =   0 ; 
            $sub_total  =   0 ;
            $pi_obj     =   new ProformaInvoice();
            $data       =   $pi_obj->getTescoPIDetails($request);
            unset($data['price_list']);
            $attachment =  $pi_obj->getTescoAttachment($data,$request);
            
            $erpOrders  =   [];

            if ($data)
            {
                if ($data['bank_details']->zone == "CE")
                {
                    $style_no = $data['bank_details']->ce_style_ref;
                }
                else
                {
                    $style_no = $data['bank_details']->uk_style_ref;
                }
                if ($data['item_details'][0]->order_flag=="reOrder" || $data['item_details'][0]->order_flag=="repeatOrder") {
                    $rep_order_id = str_replace(' ', '', $data['item_details'][0]->template_name)."_".$row_id;
                }
                else{
                    $rep_order_id = 0;
                }
                $pi_details = PI::create([
                    'buyer_id'         => $buyer_id,
                    'company_id'       => $data['company_details']->id,
                    'customer_id'      => $data['bank_details']->id,
                    // 'template_id'      => $data['bank_details']->template_id,
                    'buyer_po_no'      => $data['bank_details']->po_number,
                    'uvc_style_no'     => $style_no,
                    'ref_no'           => $data['bank_details']->ref_no,
                    'shipping_address' => $data['ship_to']->address,
                    'billing_address'  => $data['bill_to']->address,
                    'place_of_supply'  => $data['bank_details']->place_of_supply,
                    'template_type'    => $data['bank_details']->template_type,
                    'revision_no'      => $data['item_details'][0]->pi_rev_no,
                    'rep_order_id'     => $rep_order_id,
                     'pi_file'         => $attachment
                    
                ]);
                foreach ($data['item_details'] as $key => $value)
                {
                    $unit_cost = $value->price;
                    $tax = $value->tax;
                    $total_qnty+= $value->quantity;
                    $sub_total += $value->quantity *$unit_cost;
                    ProfomaInvoiceItemDetails::create([
                        'pi_id'       => $pi_details->id,
                        'material'    => $value->material_name,
                        'size'        => $value->size,
                        'template_id' => $value->item_id,
                        'ref_no'      => $value->ref_no,
                        'style'       => $style_no,
                        'quantity'    => $value->quantity,
                        'unit_cost'   => $unit_cost,
                        'tax'         => $tax
                    ]);
                }
                PI::where('id',$pi_details->id)
                            ->update([
                              'total_quantity'   => $total_qnty,
                              'sub_total'        => $sub_total,
                              // 'tax'              => $data['item_details'][0]->tax,
                              'bill_amount'      => $billamnt
                            ]);
            }

            if ($data['item_details'][0]->order_flag == "newOrder") {
                $tableID = 0;
               foreach ($data['tables'] as $table => $vals) {
                    if ($data['bank_details']->zone == "CE") {
                        
                        $tableID =  DB::table($table)
                                            ->where($table.'.is_order_count_empty',0)
                                            ->where('ce_style_ref',$style_no)
                                            ->where('order_flag',$data['item_details'][0]->order_flag)
                                            ->where('pi_status',$data['item_details'][0]->pi_status)
                                            ->where('po_number',$data['bank_details']->po_number)->value('id');
                        $approved_data  =   DB::table($table)
                                            ->where($table.'.is_order_count_empty',0)
                                            ->where('ce_style_ref',$style_no)
                                            ->where('order_flag',$data['item_details'][0]->order_flag)
                                            ->where('pi_status',$data['item_details'][0]->pi_status)
                                            ->where('po_number',$data['bank_details']->po_number)
                                            ->update([
                                               'pi_status' => "PI Approved",
                                               'pi_id' => $pi_details->id,
                                               'pi_file' => $attachment,
                                               'pi_approved_at' => Carbon::now('Asia/Kolkata')->toDateTimeString()
                                            ]);
                    }
                    else {
                        $tableID = DB::table($table)
                                            ->where($table.'.is_order_count_empty',0)
                                            ->where('uk_style_ref',$style_no)
                                            ->where('order_flag',$data['item_details'][0]->order_flag)
                                            ->where('pi_status',$data['item_details'][0]->pi_status)
                                            ->where('po_number',$data['bank_details']->po_number)->value('id');
                        $approved_data   = DB::table($table)
                                            ->where($table.'.is_order_count_empty',0)
                                            ->where('uk_style_ref',$style_no)
                                            ->where('order_flag',$data['item_details'][0]->order_flag)
                                            ->where('pi_status',$data['item_details'][0]->pi_status)
                                            ->where('po_number',$data['bank_details']->po_number)
                                            ->update([
                                               'pi_status' => "PI Approved",
                                               'pi_id' => $pi_details->id,
                                               'pi_file' => $attachment,   
                                               'pi_approved_at' => Carbon::now('Asia/Kolkata')->toDateTimeString()
                                            ]);
                    }
                    /** @var array [Pass array of values to updateTescoErpOrders function] */
                    $erpOrders['tableName'] = $table;
                    $erpOrders['id']        = $tableID;
                    $erpOrders['buyer_id']  = $buyer_id;
                    $erpOrders['created_by']= Auth::guard('web')->id();
                    $customer_data  = DB::table('customers')->where('id',$data['bank_details']->id)->first();
                    if ($customer_data->pi_required == 1 && $customer_data->po_required== 0) {
                        $this->erp_obj->updateTescoErpOrders($erpOrders);
                    } 
                }
            }
            else{
                    $approved_data   = DB::table($table_name)
                                    ->where('id',$row_id)
                                    ->update([
                                       'pi_status' => "PI Approved",
                                       'pi_id' => $pi_details->id,
                                       'pi_file' => $attachment, 
                                       'pi_approved_at' => Carbon::now('Asia/Kolkata')->toDateTimeString()
                                    ]);
                /** @var array [Pass array of values to updateTescoErpOrders function] */
                $erpOrders['tableName'] = $table_name;
                $erpOrders['id']        = $row_id;
                $erpOrders['buyer_id']  = $buyer_id;  
                $erpOrders['created_by']= Auth::guard('web')->id(); 
                $customer_data  = DB::table('customers')->where('id',$data['bank_details']->id)->first();

                if ($customer_data->pi_required == 1 && $customer_data->po_required== 0) {
                    $this->erp_obj->updateTescoErpOrders($erpOrders);
                }                                
            }
            if ($approved_data)
            {
               $result = response()->json(['error'=> false,'message' =>'success'    ],200);
            }
            else
            {
                $result = response()->json(['error'=> true,'message' =>'Error'],400);
            }
        }
        catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
    }
    
    /**
     * method to reject the proforma invoice
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function rejectTescoPI(Request $request)
    {
        try
        {
            $row_id     =   $request->id;
            $table_name =   $request->table_name;
            $table_data =   DB::table($table_name)->where('id',$row_id)->first();

            if ($table_data->order_flag =="newOrder") {
                if ($table_name == "tesco_tcl1_orders" || $table_name == "tesco_tcl2_orders" || $table_name == "tesco_tsl1_orders") {
                  $table_array = array('tesco_tcl1_orders','tesco_tcl2_orders','tesco_tsl1_orders');
                  $qnty_id_array = array('tesco_tcl1_orders_quanty','tesco_tcl2_orders_quanty','tesco_tsl1_orders_quanty');
                  $template_array = array_combine($table_array, $qnty_id_array);

                }
                if ($table_name == "tesco_tcl4_orders" || $table_name == "tesco_tsl3_orders") {
                  $table_array = array('tesco_tcl4_orders','tesco_tsl3_orders');
                  $qnty_id_array = array('tesco_tcl4_orders_quanty','tesco_tsl3_orders_quanty');
                  $template_array = array_combine($table_array, $qnty_id_array);
                }
            }
            // if ($table_name == "tesco_tcl1_orders" || $table_name == "tesco_tcl2_orders" || $table_name == "tesco_tsl1_orders") {
            //   $table_array = array('tesco_tcl1_orders','tesco_tcl2_orders','tesco_tsl1_orders');
            //   $qnty_id_array = array('tesco_tcl1_orders_quanty','tesco_tcl2_orders_quanty','tesco_tsl1_orders_quanty');
            //   $template_array = array_combine($table_array, $qnty_id_array);

            // }
            // if ($table_name == "tesco_tcl4_orders" || $table_name == "tesco_tsl3_orders") {
            //   $table_array = array('tesco_tcl4_orders','tesco_tsl3_orders');
            //   $qnty_id_array = array('tesco_tcl4_orders_quanty','tesco_tsl3_orders_quanty');
            //   $template_array = array_combine($table_array, $qnty_id_array);
            // }
            if (!empty($template_array)) {
                foreach ($template_array as $table => $value) {

                    if ($table_data->zone == "CE") {
                        DB::table($table)
                        ->where('ce_style_ref',$table_data->ce_style_ref)
                        ->where('po_number',$table_data->po_number)
                        ->where('order_flag',$table_data->order_flag)
                        ->update([
                          'pi_status' => "PI Rejected",
                          'pi_rev_no' => $table_data->pi_rev_no + 1
                        ]);
                    }
                    else{
                        DB::table($table)
                        ->where('uk_style_ref',$table_data->uk_style_ref)
                        ->where('po_number',$table_data->po_number)
                        ->where('order_flag',$table_data->order_flag)
                        ->update([
                          'pi_status' => "PI Rejected",
                          'pi_rev_no' => $table_data->pi_rev_no + 1
                        ]);
                    }   
                }
            }
            else{
                $data = DB::table($table_name)
                        ->where('id',$row_id)
                         ->update([
                          'pi_status' => "PI Rejected",
                          'pi_rev_no' => $table_data->pi_rev_no+1
                        ]);
            }

            $result = response()->json(['error'=> false,'message' =>'success'],200);
        }
        catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
    }
    
    /**
     * method to get the tesco artworks for a particular style number
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getApproveArtwork(Request $request)
    {
        try 
        {
            $style_num = $request->styleNum;
            $zone      = $request->zone;
            if ($zone=="CE") {
               $col = "ce_style_ref";
            }
            else{
                 $col = "uk_style_ref";
            }
			
			
			
			$tcl1 = "TCL 1";
			$tesco_tcl1_orders = "tesco_tcl1_orders";
			$tcl2 = "TCL 2";
			$tesco_tcl2_orders = "tesco_tcl2_orders";
			$tcl4 = "TCL 4";
			$tesco_tcl4_orders = "tesco_tcl4_orders";			
			$thl1 = "THL 1";
			$tesco_thl1_orders = "tesco_thl1_orders";					
			$thl3 = "THL 3";
			$tesco_thl3_orders = "tesco_thl3_orders";							
			$tsl1 = "TSL 1";
			$tesco_tsl1_orders = "tesco_tsl1_orders";							
			$tsl3 = "TSL 3";
			$tesco_tsl3_orders = "tesco_tsl3_orders";			
			
            $data['tcl1'] = DB::table('tesco_tcl1_orders')
                            ->join('tesco_item_master','tesco_tcl1_orders.template_id','=','tesco_item_master.id')
                            ->join('customers','tesco_tcl1_orders.tesco_supplier_id','=','customers.id')
                            ->where('customers.po_required',1) 
                            ->whereNotIn('tesco_tcl1_orders.order_status',['Saved','Order Cancelled'])
                            ->where('tesco_tcl1_orders.is_order_count_empty',0)
                            ->select('tesco_tcl1_orders.*',DB::raw("'".$tcl1."'".' as "TemplateName"'),DB::raw("'".$tesco_tcl1_orders."'".' as "tableName"'),'tesco_item_master.company_id')
                            ->where("zone",$zone)
                            ->where($col,$style_num)
                            ->get();
            $data['tcl2'] = DB::table('tesco_tcl2_orders')
                            ->where('tesco_tcl2_orders.is_order_count_empty',0)
                            ->join('tesco_item_master','tesco_tcl2_orders.template_id','=','tesco_item_master.id')
                            ->join('customers','tesco_tcl2_orders.tesco_supplier_id','=','customers.id')
                            ->where('customers.po_required',1)
							->whereNotIn('tesco_tcl2_orders.order_status',['Saved','Order Cancelled'])
                            ->select('tesco_tcl2_orders.*',DB::raw("'".$tcl2."'".' as "TemplateName"'),DB::raw("'".$tesco_tcl2_orders."'".' as "tableName"'),'tesco_item_master.company_id')
                            ->where("zone",$zone)
                            ->where($col,$style_num)
                            ->get();
            $data['tcl4'] = DB::table('tesco_tcl4_orders')
                            ->join('tesco_item_master','tesco_tcl4_orders.template_id','=','tesco_item_master.id')
                            ->join('customers','tesco_tcl4_orders.tesco_supplier_id','=','customers.id')
                            ->where('customers.po_required',1)
                            ->where('tesco_tcl4_orders.is_order_count_empty',0)
				            ->whereNotIn('tesco_tcl4_orders.order_status',['Saved','Order Cancelled'])
                            ->select('tesco_tcl4_orders.*',DB::raw("'".$tcl4."'".' as "TemplateName"'),DB::raw("'".$tesco_tcl4_orders."'".' as "tableName"'),'tesco_item_master.company_id')
                            ->where("zone",$zone)
                            ->where($col,$style_num)
                            ->get();
            $data['thl1'] = DB::table('tesco_thl1_orders')
                            ->join('tesco_item_master','tesco_thl1_orders.template_id','=','tesco_item_master.id')
                            ->join('customers','tesco_thl1_orders.tesco_supplier_id','=','customers.id')
                            ->where('customers.po_required',1)
                            ->where('tesco_thl1_orders.is_order_count_empty',0)
				            ->whereNotIn('tesco_thl1_orders.order_status',['Saved','Order Cancelled'])
                            ->select('tesco_thl1_orders.*',DB::raw("'".$thl1."'".' as "TemplateName"'),DB::raw("'".$tesco_thl1_orders."'".' as "tableName"'),'tesco_item_master.company_id')
                            ->where("zone",$zone)
                            ->where($col,$style_num)
                            ->get();
            $data['thl3'] = DB::table('tesco_thl3_orders')
                            ->join('tesco_item_master','tesco_thl3_orders.template_id','=','tesco_item_master.id')
                            ->join('customers','tesco_thl3_orders.tesco_supplier_id','=','customers.id')
                            ->where('customers.po_required',1)
                            ->where('tesco_thl3_orders.is_order_count_empty',0)
				            ->whereNotIn('tesco_thl3_orders.order_status',['Saved','Order Cancelled'])
                            ->select('tesco_thl3_orders.*',DB::raw("'".$thl3."'".' as "TemplateName"'),DB::raw("'".$tesco_thl3_orders."'".' as "tableName"'),'tesco_item_master.company_id')
                            ->where("zone",$zone)
                            ->where($col,$style_num)
                            ->get();
            $data['tsl1'] = DB::table('tesco_tsl1_orders')
                            ->join('tesco_item_master','tesco_tsl1_orders.template_id','=','tesco_item_master.id')
                            ->join('customers','tesco_tsl1_orders.tesco_supplier_id','=','customers.id')
                            ->where('customers.po_required',1)
                            ->where('tesco_tsl1_orders.is_order_count_empty',0)
				            ->whereNotIn('tesco_tsl1_orders.order_status',['Saved','Order Cancelled'])
                            ->select('tesco_tsl1_orders.*',DB::raw("'".$tsl1."'".' as "TemplateName"'),DB::raw("'".$tesco_tsl1_orders."'".' as "tableName"'),'tesco_item_master.company_id')
                            ->where("zone",$zone)
                            ->where($col,$style_num)
                            ->get();
            $data['tsl3'] = DB::table('tesco_tsl3_orders')
                            ->join('tesco_item_master','tesco_tsl3_orders.template_id','=','tesco_item_master.id')
                            ->join('customers','tesco_tsl3_orders.tesco_supplier_id','=','customers.id')
                            ->where('customers.po_required',1)
                            ->where('tesco_tsl3_orders.is_order_count_empty',0)
				            ->whereNotIn('tesco_tsl3_orders.order_status',['Saved','Order Cancelled'])
                            ->select('tesco_tsl3_orders.*',DB::raw("'".$tsl3."'".' as "TemplateName"'),DB::raw("'".$tesco_tsl3_orders."'".' as "tableName"'),'tesco_item_master.company_id')
                            ->where("zone",$zone)
                            ->where($col,$style_num)
                            ->get();
            $result = response()->json(['error'=> false,'message' =>'success','data'=>$data],200); 
        }
        catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
    }

    public function approveArtworkBulk(Request $request)
    {
        try 
        {
            $id  =  explode(',',$request->id);
            foreach($id as $val)
            {
                $value    =  explode('-',$val);
                
                DB::table($value[0])->where('id',$value[1])
                   ->update([
                        'order_status' => "Order Approved"
                  ]);
            }
            $result = response()->json(['error'=> false,'message' =>'success'],200); 
        }
        catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
    }

    /**
     * method to get the tesco artworks for a particular style number
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getArtwork(Request $request)
    {
        try 
        {
            $style_num = $request->styleNum;
            $zone      = $request->zone;
            if ($zone=="CE") {
               $col = "ce_style_ref";
            }
            else{
                 $col = "uk_style_ref";
            }
			
			
			
			$tcl1 = "TCL 1";
			$tesco_tcl1_orders = "tesco_tcl1_orders";
			$tcl2 = "TCL 2";
			$tesco_tcl2_orders = "tesco_tcl2_orders";
			$tcl4 = "TCL 4";
			$tesco_tcl4_orders = "tesco_tcl4_orders";			
			$thl1 = "THL 1";
			$tesco_thl1_orders = "tesco_thl1_orders";					
			$thl3 = "THL 3";
			$tesco_thl3_orders = "tesco_thl3_orders";							
			$tsl1 = "TSL 1";
			$tesco_tsl1_orders = "tesco_tsl1_orders";							
			$tsl3 = "TSL 3";
			$tesco_tsl3_orders = "tesco_tsl3_orders";			
			
            $data['tcl1'] = DB::table('tesco_tcl1_orders')
                            ->join('tesco_item_master','tesco_tcl1_orders.template_id','=','tesco_item_master.id')
                            ->join('customers','tesco_tcl1_orders.tesco_supplier_id','=','customers.id')
                            ->where('customers.po_required',1) 
                            ->whereNotIn('tesco_tcl1_orders.order_status',['Saved','Order Cancelled'])
                            ->where('tesco_tcl1_orders.is_order_count_empty',0)
                            ->select('tesco_tcl1_orders.*',DB::raw("'".$tcl1."'".' as "TemplateName"'),DB::raw("'".$tesco_tcl1_orders."'".' as "tableName"'),'tesco_item_master.company_id')
                            ->where("zone",$zone)
                            ->where($col,$style_num)
                            ->get();
            $data['tcl2'] = DB::table('tesco_tcl2_orders')
                            ->where('tesco_tcl2_orders.is_order_count_empty',0)
                            ->join('tesco_item_master','tesco_tcl2_orders.template_id','=','tesco_item_master.id')
                            ->join('customers','tesco_tcl2_orders.tesco_supplier_id','=','customers.id')
                            ->where('customers.po_required',1)
							->whereNotIn('tesco_tcl2_orders.order_status',['Saved','Order Cancelled'])
                            ->select('tesco_tcl2_orders.*',DB::raw("'".$tcl2."'".' as "TemplateName"'),DB::raw("'".$tesco_tcl2_orders."'".' as "tableName"'),'tesco_item_master.company_id')
                            ->where("zone",$zone)
                            ->where($col,$style_num)
                            ->get();
            $data['tcl4'] = DB::table('tesco_tcl4_orders')
                            ->join('tesco_item_master','tesco_tcl4_orders.template_id','=','tesco_item_master.id')
                            ->join('customers','tesco_tcl4_orders.tesco_supplier_id','=','customers.id')
                            ->where('customers.po_required',1)
                            ->where('tesco_tcl4_orders.is_order_count_empty',0)
				            ->whereNotIn('tesco_tcl4_orders.order_status',['Saved','Order Cancelled'])
                            ->select('tesco_tcl4_orders.*',DB::raw("'".$tcl4."'".' as "TemplateName"'),DB::raw("'".$tesco_tcl4_orders."'".' as "tableName"'),'tesco_item_master.company_id')
                            ->where("zone",$zone)
                            ->where($col,$style_num)
                            ->get();
            $data['thl1'] = DB::table('tesco_thl1_orders')
                            ->join('tesco_item_master','tesco_thl1_orders.template_id','=','tesco_item_master.id')
                            ->join('customers','tesco_thl1_orders.tesco_supplier_id','=','customers.id')
                            ->where('customers.po_required',1)
                            ->where('tesco_thl1_orders.is_order_count_empty',0)
				            ->whereNotIn('tesco_thl1_orders.order_status',['Saved','Order Cancelled'])
                            ->select('tesco_thl1_orders.*',DB::raw("'".$thl1."'".' as "TemplateName"'),DB::raw("'".$tesco_thl1_orders."'".' as "tableName"'),'tesco_item_master.company_id')
                            ->where("zone",$zone)
                            ->where($col,$style_num)
                            ->get();
            $data['thl3'] = DB::table('tesco_thl3_orders')
                            ->join('tesco_item_master','tesco_thl3_orders.template_id','=','tesco_item_master.id')
                            ->join('customers','tesco_thl3_orders.tesco_supplier_id','=','customers.id')
                            ->where('customers.po_required',1)
                            ->where('tesco_thl3_orders.is_order_count_empty',0)
				            ->whereNotIn('tesco_thl3_orders.order_status',['Saved','Order Cancelled'])
                            ->select('tesco_thl3_orders.*',DB::raw("'".$thl3."'".' as "TemplateName"'),DB::raw("'".$tesco_thl3_orders."'".' as "tableName"'),'tesco_item_master.company_id')
                            ->where("zone",$zone)
                            ->where($col,$style_num)
                            ->get();
            $data['tsl1'] = DB::table('tesco_tsl1_orders')
                            ->join('tesco_item_master','tesco_tsl1_orders.template_id','=','tesco_item_master.id')
                            ->join('customers','tesco_tsl1_orders.tesco_supplier_id','=','customers.id')
                            ->where('customers.po_required',1)
                            ->where('tesco_tsl1_orders.is_order_count_empty',0)
				            ->whereNotIn('tesco_tsl1_orders.order_status',['Saved','Order Cancelled'])
                            ->select('tesco_tsl1_orders.*',DB::raw("'".$tsl1."'".' as "TemplateName"'),DB::raw("'".$tesco_tsl1_orders."'".' as "tableName"'),'tesco_item_master.company_id')
                            ->where("zone",$zone)
                            ->where($col,$style_num)
                            ->get();
            $data['tsl3'] = DB::table('tesco_tsl3_orders')
                            ->join('tesco_item_master','tesco_tsl3_orders.template_id','=','tesco_item_master.id')
                            ->join('customers','tesco_tsl3_orders.tesco_supplier_id','=','customers.id')
                            ->where('customers.po_required',1)
                            ->where('tesco_tsl3_orders.is_order_count_empty',0)
				            ->whereNotIn('tesco_tsl3_orders.order_status',['Saved','Order Cancelled'])
                            ->select('tesco_tsl3_orders.*',DB::raw("'".$tsl3."'".' as "TemplateName"'),DB::raw("'".$tesco_tsl3_orders."'".' as "tableName"'),'tesco_item_master.company_id')
                            ->where("zone",$zone)
                            ->where($col,$style_num)
                            ->get();
            $result = response()->json(['error'=> false,'message' =>'success','data'=>$data],200); 
        }
        catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
    }

    /**
     * method to update the po number and po file for the selected artworks
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function updateArtworkPO(Request $request)
    {
        try
        {
            $formartStr_obj   = new CleanString();
            $id        =  explode(',',$request->id);
            $po        =  $request->po;          
            $po_file = $request->file('po_file');
            $filename = $request->file('po_file')->getClientOriginalName();
            $destination_path='/storage/app/public/po-files/';
            $path='storage/app/public/po-files/';
            $Orname = $po_file->getClientOriginalName();
            $filename   = $formartStr_obj->cleanStr($Orname);
            $po_file->move($path,$filename);
            $destination_path_insert=$destination_path.$filename;
            foreach($id as $val)
            {
                $value    =  explode('-',$val);
                
                DB::table($value[0])->where('id',$value[1])
                   ->update([
                        'purchase_order_no' => $po,
                        'po_file' => $destination_path_insert,
                        'po_status' => "PO Uploaded",
                        'po_uploaded_at' => Carbon::now('Asia/Kolkata')->toDateTimeString()
                  ]);

                /** @var array [Pass array of values to updateTescoErpOrders function] */
                $data_array = [];   
                $tesco_buyer_id = DB::table('buyers')->select('id')->where('buyer_name','=','Tesco')->first();
                $data_array['tableName'] = $value[0];
                $data_array['buyer_id'] = $tesco_buyer_id->id;
                $data_array['id'] = $value[1];
                $data_array['created_by']= Auth::guard('web')->id();
                $this->erp_obj->updateTescoErpOrders($data_array);
            }
            $result = response()->json(['error'=> false,'message' =>'success'],200); 
           
        }
        catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
    }
    
    /**
     * method to copy the existing PO number to another artwork
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function copyArtworkPO(Request $request)
    {
        try
        {
            $id        =   explode(',',$request->id);
            $po        =   $request->po;
            $erp_orders = DB::table('erp_orders')->where('po_no',$po)->select('po_file')->first();

            foreach($id as $val)
            {
                $value    =  explode('-',$val);
                $order_po_file = DB::table($value[0])->where('id',$value[1])->select('po_file')->first();

                // po file status and po file path
                if(sizeof($erp_orders) > 0)
                {
                    $status = "PO Uploaded";
                    $po_file = $erp_orders->po_file;
                }
                else 
                {
                    // if po is already uploaded, set status as po uploaded
                    if(sizeof($order_po_file) > 0)
                    {
                        $status = "PO Uploaded";
                        $po_file = $order_po_file->po_file;
                    }
                    else
                    {
                        $status = "PO Pending";
                        $po_file = $order_po_file;
                    }
                    
                }
                
                DB::table($value[0])->where('id',$value[1])
                   ->update([
                        'purchase_order_no' => $po,
                        'po_file' => $po_file,
                        'po_status' => $status,
                        'po_uploaded_at' => Carbon::now('Asia/Kolkata')->toDateTimeString()
                  ]);

                if($status === "PO Uploaded")
                {
                    /** @var array [Pass array of values to updateTescoErpOrders function] */
                    $data_array = [];   
                    $tesco_buyer_id = DB::table('buyers')->select('id')->where('buyer_name','=','Tesco')->first();
                    $data_array['tableName'] = $value[0];
                    $data_array['buyer_id'] = $tesco_buyer_id->id;
                    $data_array['id'] = $value[1];
                    $data_array['created_by']= Auth::guard('web')->id();
                    $this->erp_obj->updateTescoErpOrders($data_array);
                }
				
            }
            $result = response()->json(['error'=> false,'message' =>'success'],200);
        }
        catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
    }

    /**
     * method to get the po file
     * @param  table name & ID
     * @return [po file]
     */
    public function getPOFile(Request $request)
    {
        try 
        {
            $row_id       =   $request->id;
            $table_name   =   $request->tableName; 
            $po_file      =   DB::table($table_name)->where('id',$row_id)
               ->pluck('po_file')->first();
            if ($po_file === "null" || $po_file === "" || $po_file === null)
            {
               $po_file = "null";
            }
            $result = response()->json(['error'=> false,'message' =>'success','po_file'=>$po_file],200);
        }
        catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
    }

    /**
     * method to update the delivery date of a particular artwork
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function updateDeliveryDate(Request $request)
    {
        try
        {
            $row_id       =   $request->id;
            $table_name   =   $request->tableName; 
            $deliveryDate =   $request->deliveryDate; 
            $date = str_replace('/', '-', $deliveryDate);
            $deliveryDt =  date('Y-m-d', strtotime($deliveryDate));
            DB::table($table_name)->where('id',$row_id)
                   ->update([
                        'delivery_date' => $deliveryDt
                  ]);
            $result = response()->json(['error'=> false,'message' =>'success'],200);
        }
        catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
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
            $erp_order_details = array();
            $order_details  =   DB::table($table_name)->where('id',$row_id)
                            ->select('*')->first();
            $qnty_id         =  str_replace("_orders","_order_id",str_replace("tesco_","",$table_name));
            $quantity_details = DB::table($table_name.'_quanty')
                                ->where($qnty_id,$order_details->id)->orderBy('id')->get();
			$template_file=DB::table('tesco_template')->where('item_id',$order_details->template_id)->select('*')->get();
			
			$tesco_item_master=DB::table('tesco_item_master')->where('id',$order_details->template_id)->select('*')->get();

            $moq     =    DB::table('price_taxation')->where('template_id',$order_details->template_id)->where('customer_id',$order_details->tesco_supplier_id)->where('buyer_id',2)->value('moq');

            // fetch erp_order_details table status
            foreach($quantity_details as $quantity)
            {
               $quantity_id = $quantity->id;
               $erp_order_details[] = DB::table('erp_order_details')->where('order_qnty_table_name','ilike', $table_name.'_quanty')->join('erp_status', 'erp_status.id', 'erp_order_details.status')->where('order_qnty_table_id','=', $quantity_id)->select('erp_order_details.id','erp_status.status_name')->first();
            }

            if ($order_details && $quantity_details)
            {
                $result = response()->json(['error'=> false,'message' =>'success','order_details'=>$order_details,'quantity_details'=>$quantity_details,'template_file'=>$template_file,'tesco_item_master'=>$tesco_item_master,'moq' => $moq, 'erp_details' => $erp_order_details],200);
            }
            else
            {
                $result = response()->json(['error'=> true,'message'=>'Error'],400);
            }
        }
        catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
    }
    
    /**
     * Edit the tesco orders
     * ie, Update all references in the combination. While editing TSL1 details, Also update TCL1 and TCL2 details.
     *
     * @param Request $request
     * @return void
     */
    public function setTescoOrderStatus(Request $request)
    {
        $orderObj        = new TescoOrders();
        $edit_erp_obj    = new EditErpOrders();
        $tpnd         	 = explode(',', $request['tpnd']);
		$barcode         = explode(',', $request['barcode']);
		$case_size		 = explode(',', $request['size']);
		$count			 = explode(',', $request['count']);
		$template_id     = $request['edit_id'];
		$template_name   = $request['template_name'];
        $zone            = $request['zone'];
        $created_by      = Auth::guard('web')->id();
        $qr_code         = explode('|', $request['qr_code']);

		if($template_name == "TCL1")
		{
			$table_name = "tesco_tcl1_orders";
			$qty_table_name="tesco_tcl1_orders_quanty";
			$foreign_key="tcl1_order_id";
			$single_size	= $request['single_size'];
            $table_array = array('tesco_tcl2_orders','tesco_tsl1_orders');
		}
		else if($template_name == "TCL2")
		{
			$table_name="tesco_tcl2_orders";
			$qty_table_name="tesco_tcl2_orders_quanty";
			$foreign_key="tcl2_order_id";
			$single_size = $request['single_size'];
            $table_array = array('tesco_tcl1_orders','tesco_tsl1_orders');
            $qnty_table_array = array('tesco_tcl1_orders_quanty','tesco_tsl1_orders_quanty');
		}
		else if($template_name == "TCL4")
		{
			$table_name ="tesco_tcl4_orders";
			$qty_table_name ="tesco_tcl4_orders_quanty";
			$foreign_key ="tcl4_order_id";
			$single_size = explode(',',$request['single_size']);
            $table_array = array('tesco_tsl3_orders');
            $qnty_table_array = array('tesco_tsl3_orders_quanty');
		}
		else if($template_name == "TSL1")
		{
			$table_name ="tesco_tsl1_orders";
			$qty_table_name ="tesco_tsl1_orders_quanty";
			$foreign_key ="tsl1_order_id";
			$single_size = $request['single_size'];
            $table_array = array('tesco_tcl1_orders','tesco_tcl2_orders');
            $qnty_table_array = array('tesco_tcl1_orders_quanty','tesco_tcl2_orders_quanty');
		}
		else if($template_name == "TSL3")
		{
			$table_name ="tesco_tsl3_orders";
			$qty_table_name ="tesco_tsl3_orders_quanty";
			$foreign_key ="tsl3_order_id";
			$single_size = explode(',',$request['single_size']);
            $table_array = array('tesco_tcl4_orders');
            $qnty_table_array = array('tesco_tcl4_orders_quanty');
		}
		else if($template_name == "THL1")
		{
			$table_name="tesco_thl1_orders";
			$qty_table_name="tesco_thl1_orders_quanty";
			$foreign_key="thl1_order_id";
			$single_size = $request['single_size'];
		}
		else if($template_name == "THL3")
		{
			$table_name="tesco_thl3_orders";
			$qty_table_name="tesco_thl3_orders_quanty";
			$foreign_key="thl3_order_id";
			$single_size = explode(',',$request['single_size']);
        }
        
        // get quantity_table ids.
        $quantity_table_ids = DB::table($qty_table_name)->select('id')->where($foreign_key,$template_id)->get();
        
        $temp_data =  DB::table($table_name)->where('id',$template_id)->first();

        $customer_id = DB::table('customer_metadata')->where('customer_metadata_id',$request['supplier'])->select('*')->first();
		
        $order_qnty = (array_sum($count)>0)?0:1 ;
        
        // check for same po number and style combination already exists or not.
        if($zone == "CE")
        {
            $table_data = DB::table($table_name)->where('po_number', $request['po_number'])
                      ->where('ce_style_ref', $request['ce_style'])
                      ->where('id', '<>', $template_id)
                      ->where('order_status', '<>', 'Order Cancelled')
                      ->whereIn('order_flag', ['newOrder', 'repeatOrder'])
                      ->first();
        }
        else
        {
            $table_data = DB::table($table_name)->where('po_number', $request['po_number'])
                      ->where('uk_style_ref', $request['uk_style'])
                      ->where('id', '<>', $template_id)
                      ->where('order_status', '<>', 'Order Cancelled')
                      ->whereIn('order_flag', ['newOrder', 'repeatOrder'])
                      ->first();
        }
        
        // check order already exists , except for reOrder and TSL3 
        if($table_data && $temp_data->order_flag != "reOrder" && $template_name != "TSL3")
        {
            $result =  response()->json(['error'=> true,'message' =>'Order Already Exists.'],409);
        }
        else
        {
            if ($temp_data->order_flag === "newOrder" && $template_name != "THL3" && $template_name != "THL1") {
                $orderObj->updateTescoOrders($table_name, $temp_data, $request, $customer_id->customer_id, $created_by);
             } 
             DB::table($table_name)->where('id',$template_id)
                                   ->update([
                                       'zone'                 => $request['zone'],
                                       'template_type'        => $request['type'],
                                       'po_number'            => $request['po_number'],
                                       'uk_style_ref'         => $request['uk_style'],
                                       'ce_style_ref'         => $request['ce_style'],
                                       'season_code'          => $request['season_code'],
                                       'short_description'    => $request['description'],
                                       'eqos_site_code'       => $request['eqos'],
                                       'manufacture_date'     => $request['manufacture_date'],
                                       'tesco_department_id'  => $request['department'],
                                       'tesco_brand_id'       => $request['brand'],
                                       'tesco_supplier_id'    => $customer_id->customer_id,
                                       'tesco_metadata_supplier_id'    => $request['supplier'],
                                       'rfid_compliant'       => $request['rfid'],
                                       'tag_at_source'        => $request['tag_at_source'],
                                       'is_order_count_empty' => $order_qnty,
                                       'ocn_number'           => $request['tesco_ocn'],
                                     ]);	
            
             DB::table($qty_table_name)->where($foreign_key,$template_id)->delete();
              
             $i=0;
             foreach($tpnd as $value)
             {
                 if(($template_name == "TCL1" && $zone=="CE") ||($template_name == "TCL2" && $zone=="CE") || ($template_name == "TSL1" && $zone=="CE") || ($template_name == "THL1" && $zone=="CE"))
                 {					
                     DB::table($qty_table_name)->insert([
                         'orms_tpn'          => $value,
                         'itf_barcode'       => $barcode[$i],
                         $foreign_key        => $template_id,
                         'cases_per_carton'  => $case_size[$i],
                         'count'         	 => $count[$i],
                         'size'  			 => null,
                         'ratio_pack_type'   => $this->ratio_pack_type[$i],
                         'qr_code'           => $qr_code[$i]
                     ]);
                 }
                 else if(($template_name == "TCL4" && $zone=="CE") || ($template_name == "TSL3" && $zone=="CE") || ($template_name == "THL3" && $zone=="CE"))
                 {
                     DB::table($qty_table_name)->insert([
                         'orms_tpn'         => $value,
                         'ean_barcode'      => $barcode[$i],
                         $foreign_key       => $template_id,
                         'case_size'        => $case_size[$i],
                         'count'         	=> $count[$i],
                         'size'  			=> $single_size[$i],
                         'qr_code'          => $qr_code[$i]
                         ]);
                 }
                 else if(($template_name == "TCL4" && $zone=="UK") || ($template_name == "TSL3" && $zone=="UK") || ($template_name == "THL3" && $zone=="UK"))
                 {
                     DB::table($qty_table_name)->insert([
                     'tpnd'            => $value,
                     'ean_barcode'     => $barcode[$i],
                     $foreign_key      => $template_id,
                     'case_size'       => $case_size[$i],
                     'count'           => $count[$i],
                     'size'  		   => $single_size[$i]
                     ]);
                 }
                 else if(($template_name == "TCL1" && $zone=="UK") ||($template_name == "TCL2" && $zone=="UK") || ($template_name == "TSL1" && $zone=="UK") || ($template_name == "THL1" && $zone=="UK"))
                 {
                     DB::table($qty_table_name)->insert([
                         'tpnd'           	  => $value,
                         'itf_barcode'        => $barcode[$i],
                         $foreign_key         => $template_id,
                         'units_per_carton'   => $case_size[$i],
                         'count'         	  => $count[$i],
                         'size'  			  => null,
                         'ratio_pack_type'   => $this->ratio_pack_type[$i]
                     ]);
                 }
                 $i++;
             }

            // fetch erp_order_details table details for each quantity_table ids.
            foreach($quantity_table_ids as $quantity_table_id)
            {
                $quantity_id = $quantity_table_id->id;
                $erp_order_details[] = DB::table('erp_order_details')->where('order_qnty_table_name','ilike', $table_name.'_quanty')->join('erp_status', 'erp_status.id', 'erp_order_details.status')->where('order_qnty_table_id','=', $quantity_id)->select('erp_order_details.id','erp_status.status_name', 'erp_order_details.erp_order_id')->first();
            }
        
            if(!is_null($erp_order_details[0]))
            {
                $erp_details_status =  $erp_order_details[0]->status_name;
                $erp_order_id =  $erp_order_details[0]->erp_order_id;
                
                foreach($erp_order_details as $erp_order_detail){
                    // delete from history table and insert new rows.
                    $history_table_id =  DB::table('history')->where('erp_order_details_id',$erp_order_detail->id)->value('id');
                    DB::table('event_history')->where('history_id',$history_table_id)->delete();
                    DB::table('history')->where('id',$history_table_id)->delete();
                    // delete from erp_order_details table
                    DB::table('erp_order_details')->where('id',$erp_order_detail->id)->delete();
                }
                
                /** @var array [Pass array of values to updateTescoErpOrderDetails function] */
                $data_array = [];   
                $tesco_buyer_id = DB::table('buyers')->select('id')->where('buyer_name','=','Tesco')->first();
                $data_array['tableName'] = $table_name;
                $data_array['buyer_id'] = $tesco_buyer_id->id;
                $data_array['id'] = $template_id;
                $data_array['erp_details_status'] = $erp_details_status;
                $data_array['erp_order_id'] = $erp_order_id;
                $data_array['created_by'] = $created_by;

                // update erp_order_details and erp_orders table 
                $result = $edit_erp_obj->updateTescoErpOrderDetails($data_array);
            }
            else
            {
                $result = response()->json(['error'=> false,'message' =>'success'],200);
            }
        }
        
		return $result;	  
    }
	
	/**
     * This function is used to check exist Po exists or not
     * 
     * @return boolean values
     */
    public function editCheckExistPo(Request $request)
    {
		$template_id    = $request['edit_id'];
		$template_name  = $request['template_name'];
		$uk_style	    = $request['uk_style'];
		$po_number	    = $request['po_number'];
		$ce_style	    = $request['ce_style'];
		$zone =$request['zone'];
		
		if($template_name == "TCL1")
		{
			$table_name="tesco_tcl1_orders";
			$qty_table_name="tesco_tcl1_orders_quanty";
		}
		else if($template_name == "TCL2")
		{
			$table_name="tesco_tcl2_orders";
			$qty_table_name="tesco_tcl2_orders_quanty";
		}
		else if($template_name == "TCL4")
		{
			$table_name="tesco_tcl4_orders";
			$qty_table_name="tesco_tcl4_orders_quanty";
		}
		else if($template_name == "TSL1")
		{
			$table_name="tesco_tsl1_orders";
			$qty_table_name="tesco_tsl1_orders_quanty";
		}
		else if($template_name == "TSL3")
		{
			$table_name="tesco_tsl3_orders";
			$qty_table_name="tesco_tsl3_orders_quanty";
		}
		else if($template_name == "THL1")
		{
			$table_name="tesco_thl1_orders";
			$qty_table_name="tesco_thl1_orders_quanty";
		}
		else if($template_name == "THL3")
		{
			$table_name="tesco_thl3_orders";
			$qty_table_name="tesco_thl3_orders_quanty";
		}
		
		if($zone == "CE")
		{
			$template_exists=DB::table($table_name)->where('po_number',$po_number)->where('ce_style_ref',$ce_style)->whereNotIn('order_flag',['reOrder','newOrder'])->where('id','!=',$template_id)->select('*')->get();
		}
		else
		{
			$template_exists=DB::table($table_name)->where('po_number',$po_number)->where('uk_style_ref',$uk_style)->whereNotIn('order_flag',['reOrder','newOrder'])->where('id','!=',$template_id)->select('*')->get();
		}
		
		if(count($template_exists) > 0) 
		{
			echo "false";
		}
		else
		{
			echo "true";
		}
		
		
	}
	/**
     * This function is used to check exist Po exists or not
     * 
     * @return boolean values
     */
    public function editCheckExistPoRepeat(Request $request)
    {
		$template_name  = $request['template_name'];
		$uk_style	    = $request['uk_style'];
		$po_number	    = $request['po_number'];
		$ce_style	    = $request['ce_style'];
		$zone =$request['zone'];
		
		if($template_name == "TCL1")
		{
			$table_name="tesco_tcl1_orders";
			$qty_table_name="tesco_tcl1_orders_quanty";
		}
		else if($template_name == "TCL2")
		{
			$table_name="tesco_tcl2_orders";
			$qty_table_name="tesco_tcl2_orders_quanty";
		}
		else if($template_name == "TCL4")
		{
			$table_name="tesco_tcl4_orders";
			$qty_table_name="tesco_tcl4_orders_quanty";
		}
		else if($template_name == "TSL1")
		{
			$table_name="tesco_tsl1_orders";
			$qty_table_name="tesco_tsl1_orders_quanty";
		}
		else if($template_name == "TSL3")
		{
			$table_name="tesco_tsl3_orders";
			$qty_table_name="tesco_tsl3_orders_quanty";
		}
		else if($template_name == "THL1")
		{
			$table_name="tesco_thl1_orders";
			$qty_table_name="tesco_thl1_orders_quanty";
		}
		else if($template_name == "THL3")
		{
			$table_name="tesco_thl3_orders";
			$qty_table_name="tesco_thl3_orders_quanty";
		}
		
		if($zone == "CE")
		{
			$template_exists=DB::table($table_name)->where('po_number',$po_number)->where('ce_style_ref',$ce_style)->where('order_flag','!=','reOrder')->select('*')->get();
		}
		else
		{
			$template_exists=DB::table($table_name)->where('po_number',$po_number)->where('uk_style_ref',$uk_style)->where('order_flag','!=','reOrder')->select('*')->get();
		}
		
		if(count($template_exists) > 0) 
		{
			echo "false";
		}
		else
		{
			echo "true";
		}
		
		
	}
    /**
     * This function is used to enable the edit functionality to the user
     * 
     * @return boolean values
     */
    public function enableArtworkEdit(Request $request)
    {
       try {
           $tablename = $request->tablename;
           $rowid     = $request->rowid;
           $data        = DB::table($tablename)->where('id',$rowid)
                             ->update([
                                 'edit_permission' => 1
                               ]);            $result = response()->json(['error'=> false,'message' =>'success'],200);
       }
       catch (\Exception $e)
       {
           $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
       }
       return $result;
   }    

   /**
     * This function is used to disable the edit functionality to the user
     * 
     * @return boolean values
     */
   public function disableArtworkEdit(Request $request)
   {
       try {
           
           $tablename = $request->tablename;
           $rowid     = $request->rowid;
           $data    = DB::table($tablename)->where('id',$rowid)
                             ->update([
                                 'edit_permission' => 0
                               ]);               $result = response()->json(['error'=> false,'message' =>'success'],200);
               
       }
       catch (\Exception $e)
       {
           $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
       }
       return $result;
   }

   public function downloadTescoPI(Request $request)
   {
        try {
            $id         =  $request['id'];
            $table_name =  $request['tableName'];            
            $result     =  DB::table($table_name)->where('id',$id)->value('pi_file');   
        }  
        catch (\Exception $e)
        {
           $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
   }

   /**
     * method to get erp status of this order
     * @param  Request $request [description]
     * @return ERP_Status          [description]
     * @author Praveen <praveen@wizroots.com>
     */
    public function checkERPStatus(Request $request)
    {
        try
        {   
            $data_array = [];
            $data_array['id'] = $request->id;
            $data_array['table_name'] = $request->table_name;
            $class_obj = new ERPOrderStatusCheck();
            $erp_order_details = $class_obj->ERPStatus($data_array);
            
            $result = response()->json(['error'=> false,'message' =>'success','erp_details' => $erp_order_details],200);
        }
        catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
    }
}