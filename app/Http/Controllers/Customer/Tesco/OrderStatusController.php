<?php

namespace App\Http\Controllers\Customer\Tesco;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use App\Classes\Modules;
use App\Classes\ProformaInvoice;
use App\Classes\ErpOrders;
use App\ProfomaInvoice as PI;
use App\ProfomaInvoiceItemDetails;
use Response;
use View;
use DB;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Config;
use App\Classes\TescoOrders;
use Auth;
use Carbon\Carbon;
use App\Classes\EditErpOrders;
use App\Classes\ERPOrderStatusCheck;
use Illuminate\Support\Facades\Redis;
use App\ErpOrders as Erp;
use App\ErpOrderDetails as ErpOrderDetails;
use App\Classes\CleanString;
use App\OrderManagementHistory;
use App\Classes\ApproveTescoOrders;
use PDF;

class OrderStatusController extends Controller
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

       $this->middleware('customer_auth');
       $this->ratio_pack_type = ['RatA', 'RatB', 'RatC', 'RatD', 'RatE', 'RatF', 'RatG', 'RatH', 'RatI', 'RatJ', 'RatK', 'RatL', 'RatM', 'RatN', 'RatO', 'RatP', 'RatQ', 'RatR', 'RatS', 'RatT', 'RatU', 'RatV', 'RatW', 'RatX', 'RatY', 'RatZ'];

       $this->updated_erp_table_array = [];
       $this->class_status = 'success';
       $this->erp_obj    = new ErpOrders();
    }

    /**
     * method to show the Customer-tesco order status page
     * @return [type] [description]
     */
    public function index()
    {
        try
        {
			$customer_id = Auth::guard('web_customer')->id();
		
		
            $customer_role_id = DB::table('customer_profile')->select('*')->where('id',$customer_id)->first();
                
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
            $actions="-";
            $ddmmyy="DD-MM-YYYY";
			
            if($customer_role_id->role_id == 1)
            {
                $data['tcl1'] = DB::table('tesco_tcl1_orders')
                                ->join('customers','tesco_tcl1_orders.tesco_supplier_id','=','customers.id')->join('tesco_template','tesco_tcl1_orders.template_id','=','tesco_template.item_id')
                                // ->where('tesco_tcl1_orders.is_order_count_empty',0)
                                // ->where('tesco_tcl1_orders.order_status', '!=', 'Saved')
                                ->whereNotIn('tesco_tcl1_orders.order_status',['Saved','Order Cancelled'])
                                ->where('tesco_tcl1_orders.tesco_supplier_id', $customer_role_id->company_id)
                                ->select(DB::raw("'".$actions."'".' as "StyleNo"'),'tesco_tcl1_orders.uk_style_ref','tesco_tcl1_orders.ce_style_ref','tesco_tcl1_orders.po_number as BuyerPO','tesco_tcl1_orders.zone as Zone',DB::raw("'".$tcl1."'".' as "TemplateName"'),'tesco_tcl1_orders.template_type as Template Type','customers.id as customer_id','tesco_tcl1_orders.created_at as OrderDate',DB::raw('to_char(tesco_tcl1_orders.delivery_date,'."'".$ddmmyy."'".') as "DeliveryDate"'),'tesco_tcl1_orders.order_status as Artwork Status','tesco_tcl1_orders.pi_status as PI Status','tesco_tcl1_orders.po_status as PO Status','tesco_tcl1_orders.purchase_order_no as PO Number',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'".$tesco_tcl1_orders."'".' as "tableName"'),'tesco_tcl1_orders.id as tableID','tesco_tcl1_orders.po_file','customers.pi_required','customers.po_required','tesco_tcl1_orders.template_id','tesco_tcl1_orders.order_flag','customers.delivery_day_count','is_order_count_empty','tesco_template.template_file')
                                ->orderBy('tesco_tcl1_orders.id','desc')
                                ->get();
                $data['tcl2'] = DB::table('tesco_tcl2_orders')
                                ->join('customers','tesco_tcl2_orders.tesco_supplier_id','=','customers.id')->join('tesco_template','tesco_tcl2_orders.template_id','=','tesco_template.item_id')
                                // ->where('tesco_tcl2_orders.is_order_count_empty',0)
                                // ->where('tesco_tcl2_orders.order_status', '!=', 'Saved')
                                ->whereNotIn('tesco_tcl2_orders.order_status',['Saved','Order Cancelled'])
                                ->where('tesco_tcl2_orders.tesco_supplier_id', $customer_role_id->company_id)
                                ->select(DB::raw("'".$actions."'".' as "StyleNo"'),'tesco_tcl2_orders.uk_style_ref','tesco_tcl2_orders.ce_style_ref','tesco_tcl2_orders.po_number as BuyerPO','tesco_tcl2_orders.zone as Zone',DB::raw("'".$tcl2."'".' as "TemplateName"'),'tesco_tcl2_orders.template_type as Template Type','customers.id as customer_id','tesco_tcl2_orders.created_at as OrderDate',DB::raw('to_char(tesco_tcl2_orders.delivery_date,'."'".$ddmmyy."'".') as "DeliveryDate"'),'tesco_tcl2_orders.order_status as Artwork Status','tesco_tcl2_orders.pi_status as PI Status','tesco_tcl2_orders.po_status as PO Status','tesco_tcl2_orders.purchase_order_no as PO Number',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'".$tesco_tcl2_orders."'".' as "tableName"'),'tesco_tcl2_orders.id as tableID','tesco_tcl2_orders.po_file','customers.pi_required','customers.po_required','tesco_tcl2_orders.template_id','tesco_tcl2_orders.order_flag','customers.delivery_day_count','is_order_count_empty','tesco_template.template_file')
                                ->orderBy('tesco_tcl2_orders.id','desc')
                                ->get();
                $data['tcl4'] = DB::table('tesco_tcl4_orders')
                                ->join('customers','tesco_tcl4_orders.tesco_supplier_id','=','customers.id')->join('tesco_template','tesco_tcl4_orders.template_id','=','tesco_template.item_id')
                                // ->where('tesco_tcl4_orders.is_order_count_empty',0)
                                // ->where('tesco_tcl4_orders.order_status', '!=', 'Saved')
                                ->whereNotIn('tesco_tcl4_orders.order_status',['Saved','Order Cancelled'])
                                ->where('tesco_tcl4_orders.tesco_supplier_id', $customer_role_id->company_id)
                                ->select(DB::raw("'".$actions."'".' as "StyleNo"'),'tesco_tcl4_orders.uk_style_ref','tesco_tcl4_orders.ce_style_ref','tesco_tcl4_orders.po_number as BuyerPO','tesco_tcl4_orders.zone as Zone',DB::raw("'".$tcl4."'".' as "TemplateName"'),'tesco_tcl4_orders.template_type as Template Type','customers.id as customer_id','tesco_tcl4_orders.created_at as OrderDate',DB::raw('to_char(tesco_tcl4_orders.delivery_date,'."'".$ddmmyy."'".') as "DeliveryDate"'),'tesco_tcl4_orders.order_status as Artwork Status','tesco_tcl4_orders.pi_status as PI Status','tesco_tcl4_orders.po_status as PO Status','tesco_tcl4_orders.purchase_order_no as PO Number',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'".$tesco_tcl4_orders."'".' as "tableName"'),'tesco_tcl4_orders.id as tableID','tesco_tcl4_orders.po_file','customers.pi_required','customers.po_required','tesco_tcl4_orders.template_id','tesco_tcl4_orders.order_flag','customers.delivery_day_count','is_order_count_empty','tesco_template.template_file')
                                ->orderBy('tesco_tcl4_orders.id','desc')
                                ->get();
                $data['thl1'] = DB::table('tesco_thl1_orders')
                                ->join('customers','tesco_thl1_orders.tesco_supplier_id','=','customers.id')->join('tesco_template','tesco_thl1_orders.template_id','=','tesco_template.item_id')
                                // ->where('tesco_thl1_orders.is_order_count_empty',0)
                                // ->where('tesco_thl1_orders.order_status', '!=', 'Saved')
                                ->whereNotIn('tesco_thl1_orders.order_status',['Saved','Order Cancelled'])
                                ->where('tesco_thl1_orders.tesco_supplier_id', $customer_role_id->company_id)
                                ->select(DB::raw("'".$actions."'".' as "StyleNo"'),'tesco_thl1_orders.uk_style_ref','tesco_thl1_orders.ce_style_ref','tesco_thl1_orders.po_number as BuyerPO','tesco_thl1_orders.zone as Zone',DB::raw("'".$thl1."'".' as "TemplateName"'),'tesco_thl1_orders.template_type as Template Type','customers.id as customer_id','tesco_thl1_orders.created_at as OrderDate',DB::raw('to_char(tesco_thl1_orders.delivery_date,'."'".$ddmmyy."'".') as "DeliveryDate"'),'tesco_thl1_orders.order_status as Artwork Status','tesco_thl1_orders.pi_status as PI Status','tesco_thl1_orders.po_status as PO Status','tesco_thl1_orders.purchase_order_no as PO Number',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'".$tesco_thl1_orders."'".' as "tableName"'),'tesco_thl1_orders.id as tableID','tesco_thl1_orders.po_file','customers.pi_required','customers.po_required','tesco_thl1_orders.template_id','tesco_thl1_orders.order_flag','customers.delivery_day_count','is_order_count_empty','tesco_template.template_file')
                                ->orderBy('tesco_thl1_orders.id','desc')
                                ->get();
                $data['thl3'] = DB::table('tesco_thl3_orders')
                                ->join('customers','tesco_thl3_orders.tesco_supplier_id','=','customers.id')->join('tesco_template','tesco_thl3_orders.template_id','=','tesco_template.item_id')
                                // ->where('tesco_thl3_orders.is_order_count_empty',0)
                                // ->where('tesco_thl3_orders.order_status', '!=', 'Saved')
                                ->whereNotIn('tesco_thl3_orders.order_status',['Saved','Order Cancelled'])
                                ->where('tesco_thl3_orders.tesco_supplier_id', $customer_role_id->company_id)
                                ->select(DB::raw("'".$actions."'".' as "StyleNo"'),'tesco_thl3_orders.uk_style_ref','tesco_thl3_orders.ce_style_ref','tesco_thl3_orders.po_number as BuyerPO','tesco_thl3_orders.zone as Zone',DB::raw("'".$thl3."'".' as "TemplateName"'),'tesco_thl3_orders.template_type as Template Type','customers.id as customer_id','tesco_thl3_orders.created_at as OrderDate',DB::raw('to_char(tesco_thl3_orders.delivery_date,'."'".$ddmmyy."'".') as "DeliveryDate"'),'tesco_thl3_orders.order_status as Artwork Status','tesco_thl3_orders.pi_status as PI Status','tesco_thl3_orders.po_status as PO Status','tesco_thl3_orders.purchase_order_no as PO Number',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'".$tesco_thl3_orders."'".' as "tableName"'),'tesco_thl3_orders.id as tableID','tesco_thl3_orders.po_file','customers.pi_required','customers.po_required','tesco_thl3_orders.template_id','tesco_thl3_orders.order_flag','customers.delivery_day_count','is_order_count_empty','tesco_template.template_file')
                                ->orderBy('tesco_thl3_orders.id','desc')
                                ->get();
                $data['tsl1'] = DB::table('tesco_tsl1_orders')
                                ->join('customers','tesco_tsl1_orders.tesco_supplier_id','=','customers.id')->join('tesco_template','tesco_tsl1_orders.template_id','=','tesco_template.item_id')
                                // ->where('tesco_tsl1_orders.is_order_count_empty',0)
                                // ->where('tesco_tsl1_orders.order_status', '!=', 'Saved')
                                ->whereNotIn('tesco_tsl1_orders.order_status',['Saved','Order Cancelled'])
                                ->where('tesco_tsl1_orders.tesco_supplier_id', $customer_role_id->company_id)
                                ->select(DB::raw("'".$actions."'".' as "StyleNo"'),'tesco_tsl1_orders.uk_style_ref','tesco_tsl1_orders.ce_style_ref','tesco_tsl1_orders.po_number as BuyerPO','tesco_tsl1_orders.zone as Zone',DB::raw("'".$tsl1."'".' as "TemplateName"'),'tesco_tsl1_orders.template_type as Template Type','customers.id as customer_id','tesco_tsl1_orders.created_at as OrderDate',DB::raw('to_char(tesco_tsl1_orders.delivery_date,'."'".$ddmmyy."'".') as "DeliveryDate"'),'tesco_tsl1_orders.order_status as Artwork Status','tesco_tsl1_orders.pi_status as PI Status','tesco_tsl1_orders.po_status as PO Status','tesco_tsl1_orders.purchase_order_no as PO Number',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'".$tesco_tsl1_orders."'".' as "tableName"'),'tesco_tsl1_orders.id as tableID','tesco_tsl1_orders.po_file','customers.pi_required','customers.po_required','tesco_tsl1_orders.template_id','tesco_tsl1_orders.order_flag','customers.delivery_day_count','is_order_count_empty','tesco_template.template_file')
                                ->orderBy('tesco_tsl1_orders.id','desc')
                                ->get();
                $data['tsl3'] = DB::table('tesco_tsl3_orders')
                                ->join('customers','tesco_tsl3_orders.tesco_supplier_id','=','customers.id')->join('tesco_template','tesco_tsl3_orders.template_id','=','tesco_template.item_id')
                                // ->where('tesco_tsl3_orders.is_order_count_empty',0)
                                // ->where('tesco_tsl3_orders.order_status', '!=', 'Saved')
                                ->whereNotIn('tesco_tsl3_orders.order_status',['Saved','Order Cancelled'])
                                ->where('tesco_tsl3_orders.tesco_supplier_id', $customer_role_id->company_id)
                                ->select(DB::raw("'".$actions."'".' as "StyleNo"'),'tesco_tsl3_orders.uk_style_ref','tesco_tsl3_orders.ce_style_ref','tesco_tsl3_orders.po_number as BuyerPO','tesco_tsl3_orders.zone as Zone',DB::raw("'".$tsl3."'".' as "TemplateName"'),'tesco_tsl3_orders.template_type as Template Type','customers.id as customer_id','tesco_tsl3_orders.created_at as OrderDate',DB::raw('to_char(tesco_tsl3_orders.delivery_date,'."'".$ddmmyy."'".') as "DeliveryDate"'),'tesco_tsl3_orders.order_status as Artwork Status','tesco_tsl3_orders.pi_status as PI Status','tesco_tsl3_orders.po_status as PO Status','tesco_tsl3_orders.purchase_order_no as PO Number',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'".$tesco_tsl3_orders."'".' as "tableName"'),'tesco_tsl3_orders.id as tableID','tesco_tsl3_orders.po_file','customers.pi_required','customers.po_required','tesco_tsl3_orders.template_id','tesco_tsl3_orders.order_flag','customers.delivery_day_count','is_order_count_empty','tesco_template.template_file')
                                ->orderBy('tesco_tsl3_orders.id','desc')
                                ->get();
            }
            else
            {
                $data['tcl1'] = DB::table('tesco_tcl1_orders')
                                ->join('customers','tesco_tcl1_orders.tesco_supplier_id','=','customers.id')->join('tesco_template','tesco_tcl1_orders.template_id','=','tesco_template.item_id')
                                // ->where('tesco_tcl1_orders.is_order_count_empty',0)
                                // ->where('tesco_tcl1_orders.order_status', '!=', 'Saved')
                                ->whereNotIn('tesco_tcl1_orders.order_status',['Saved','Order Cancelled'])
                                ->where('tesco_tcl1_orders.tesco_supplier_id', $customer_role_id->company_id)
                                ->where('tesco_tcl1_orders.order_placed_by','=',Auth::guard('web_customer')->id())
                                ->select(DB::raw("'".$actions."'".' as "StyleNo"'),'tesco_tcl1_orders.uk_style_ref','tesco_tcl1_orders.ce_style_ref','tesco_tcl1_orders.po_number as BuyerPO','tesco_tcl1_orders.zone as Zone',DB::raw("'".$tcl1."'".' as "TemplateName"'),'tesco_tcl1_orders.template_type as Template Type','customers.id as customer_id','tesco_tcl1_orders.created_at as OrderDate',DB::raw('to_char(tesco_tcl1_orders.delivery_date,'."'".$ddmmyy."'".') as "DeliveryDate"'),'tesco_tcl1_orders.order_status as Artwork Status','tesco_tcl1_orders.pi_status as PI Status','tesco_tcl1_orders.po_status as PO Status','tesco_tcl1_orders.purchase_order_no as PO Number',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'".$tesco_tcl1_orders."'".' as "tableName"'),'tesco_tcl1_orders.id as tableID','tesco_tcl1_orders.po_file','customers.pi_required','customers.po_required','tesco_tcl1_orders.template_id','tesco_tcl1_orders.order_flag','customers.delivery_day_count','is_order_count_empty','tesco_template.template_file')
                                ->orderBy('tesco_tcl1_orders.id','desc')
                                ->get();
                $data['tcl2'] = DB::table('tesco_tcl2_orders')
                                ->join('customers','tesco_tcl2_orders.tesco_supplier_id','=','customers.id')->join('tesco_template','tesco_tcl2_orders.template_id','=','tesco_template.item_id')
                                // ->where('tesco_tcl2_orders.is_order_count_empty',0)
                                // ->where('tesco_tcl2_orders.order_status', '!=', 'Saved')
                                ->whereNotIn('tesco_tcl2_orders.order_status',['Saved','Order Cancelled'])
                                ->where('tesco_tcl2_orders.tesco_supplier_id', $customer_role_id->company_id)
                                ->where('tesco_tcl2_orders.order_placed_by','=',Auth::guard('web_customer')->id())
                                ->select(DB::raw("'".$actions."'".' as "StyleNo"'),'tesco_tcl2_orders.uk_style_ref','tesco_tcl2_orders.ce_style_ref','tesco_tcl2_orders.po_number as BuyerPO','tesco_tcl2_orders.zone as Zone',DB::raw("'".$tcl2."'".' as "TemplateName"'),'tesco_tcl2_orders.template_type as Template Type','customers.id as customer_id','tesco_tcl2_orders.created_at as OrderDate',DB::raw('to_char(tesco_tcl2_orders.delivery_date,'."'".$ddmmyy."'".') as "DeliveryDate"'),'tesco_tcl2_orders.order_status as Artwork Status','tesco_tcl2_orders.pi_status as PI Status','tesco_tcl2_orders.po_status as PO Status','tesco_tcl2_orders.purchase_order_no as PO Number',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'".$tesco_tcl2_orders."'".' as "tableName"'),'tesco_tcl2_orders.id as tableID','tesco_tcl2_orders.po_file','customers.pi_required','customers.po_required','tesco_tcl2_orders.template_id','tesco_tcl2_orders.order_flag','customers.delivery_day_count','is_order_count_empty','tesco_template.template_file')
                                ->orderBy('tesco_tcl2_orders.id','desc')
                                ->get();
                $data['tcl4'] = DB::table('tesco_tcl4_orders')
                                ->join('customers','tesco_tcl4_orders.tesco_supplier_id','=','customers.id')->join('tesco_template','tesco_tcl4_orders.template_id','=','tesco_template.item_id')
                                // ->where('tesco_tcl4_orders.is_order_count_empty',0)
                                // ->where('tesco_tcl4_orders.order_status', '!=', 'Saved')
                                ->whereNotIn('tesco_tcl4_orders.order_status',['Saved','Order Cancelled'])
                                ->where('tesco_tcl4_orders.tesco_supplier_id', $customer_role_id->company_id)
                                ->where('tesco_tcl4_orders.order_placed_by','=',Auth::guard('web_customer')->id())
                                ->select(DB::raw("'".$actions."'".' as "StyleNo"'),'tesco_tcl4_orders.uk_style_ref','tesco_tcl4_orders.ce_style_ref','tesco_tcl4_orders.po_number as BuyerPO','tesco_tcl4_orders.zone as Zone',DB::raw("'".$tcl4."'".' as "TemplateName"'),'tesco_tcl4_orders.template_type as Template Type','customers.id as customer_id','tesco_tcl4_orders.created_at as OrderDate',DB::raw('to_char(tesco_tcl4_orders.delivery_date,'."'".$ddmmyy."'".') as "DeliveryDate"'),'tesco_tcl4_orders.order_status as Artwork Status','tesco_tcl4_orders.pi_status as PI Status','tesco_tcl4_orders.po_status as PO Status','tesco_tcl4_orders.purchase_order_no as PO Number',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'".$tesco_tcl4_orders."'".' as "tableName"'),'tesco_tcl4_orders.id as tableID','tesco_tcl4_orders.po_file','customers.pi_required','customers.po_required','tesco_tcl4_orders.template_id','tesco_tcl4_orders.order_flag','customers.delivery_day_count','is_order_count_empty','tesco_template.template_file')
                                ->orderBy('tesco_tcl4_orders.id','desc')
                                ->get();
                $data['thl1'] = DB::table('tesco_thl1_orders')
                                ->join('customers','tesco_thl1_orders.tesco_supplier_id','=','customers.id')->join('tesco_template','tesco_thl1_orders.template_id','=','tesco_template.item_id')
                                // ->where('tesco_thl1_orders.is_order_count_empty',0)
                                // ->where('tesco_thl1_orders.order_status', '!=', 'Saved')
                                ->whereNotIn('tesco_thl1_orders.order_status',['Saved','Order Cancelled'])
                                ->where('tesco_thl1_orders.tesco_supplier_id', $customer_role_id->company_id)
                                ->where('tesco_thl1_orders.order_placed_by','=',Auth::guard('web_customer')->id())
                                ->select(DB::raw("'".$actions."'".' as "StyleNo"'),'tesco_thl1_orders.uk_style_ref','tesco_thl1_orders.ce_style_ref','tesco_thl1_orders.po_number as BuyerPO','tesco_thl1_orders.zone as Zone',DB::raw("'".$thl1."'".' as "TemplateName"'),'tesco_thl1_orders.template_type as Template Type','customers.id as customer_id','tesco_thl1_orders.created_at as OrderDate',DB::raw('to_char(tesco_thl1_orders.delivery_date,'."'".$ddmmyy."'".') as "DeliveryDate"'),'tesco_thl1_orders.order_status as Artwork Status','tesco_thl1_orders.pi_status as PI Status','tesco_thl1_orders.po_status as PO Status','tesco_thl1_orders.purchase_order_no as PO Number',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'".$tesco_thl1_orders."'".' as "tableName"'),'tesco_thl1_orders.id as tableID','tesco_thl1_orders.po_file','customers.pi_required','customers.po_required','tesco_thl1_orders.template_id','tesco_thl1_orders.order_flag','customers.delivery_day_count','is_order_count_empty','tesco_template.template_file')
                                ->orderBy('tesco_thl1_orders.id','desc')
                                ->get();
                $data['thl3'] = DB::table('tesco_thl3_orders')
                                ->join('customers','tesco_thl3_orders.tesco_supplier_id','=','customers.id')->join('tesco_template','tesco_thl3_orders.template_id','=','tesco_template.item_id')
                                // ->where('tesco_thl3_orders.is_order_count_empty',0)
                                // ->where('tesco_thl3_orders.order_status', '!=', 'Saved')
                                ->whereNotIn('tesco_thl3_orders.order_status',['Saved','Order Cancelled'])
                                ->where('tesco_thl3_orders.tesco_supplier_id', $customer_role_id->company_id)
                                ->where('tesco_thl3_orders.order_placed_by','=',Auth::guard('web_customer')->id())
                                ->select(DB::raw("'".$actions."'".' as "StyleNo"'),'tesco_thl3_orders.uk_style_ref','tesco_thl3_orders.ce_style_ref','tesco_thl3_orders.po_number as BuyerPO','tesco_thl3_orders.zone as Zone',DB::raw("'".$thl3."'".' as "TemplateName"'),'tesco_thl3_orders.template_type as Template Type','customers.id as customer_id','tesco_thl3_orders.created_at as OrderDate',DB::raw('to_char(tesco_thl3_orders.delivery_date,'."'".$ddmmyy."'".') as "DeliveryDate"'),'tesco_thl3_orders.order_status as Artwork Status','tesco_thl3_orders.pi_status as PI Status','tesco_thl3_orders.po_status as PO Status','tesco_thl3_orders.purchase_order_no as PO Number',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'".$tesco_thl3_orders."'".' as "tableName"'),'tesco_thl3_orders.id as tableID','tesco_thl3_orders.po_file','customers.pi_required','customers.po_required','tesco_thl3_orders.template_id','tesco_thl3_orders.order_flag','customers.delivery_day_count','is_order_count_empty','tesco_template.template_file')
                                ->orderBy('tesco_thl3_orders.id','desc')
                                ->get();
                $data['tsl1'] = DB::table('tesco_tsl1_orders')
                                ->join('customers','tesco_tsl1_orders.tesco_supplier_id','=','customers.id')->join('tesco_template','tesco_tsl1_orders.template_id','=','tesco_template.item_id')
                                // ->where('tesco_tsl1_orders.is_order_count_empty',0)
                                // ->where('tesco_tsl1_orders.order_status', '!=', 'Saved')
                                ->whereNotIn('tesco_tsl1_orders.order_status',['Saved','Order Cancelled'])
                                ->where('tesco_tsl1_orders.tesco_supplier_id', $customer_role_id->company_id)
                                ->where('tesco_tsl1_orders.order_placed_by','=',Auth::guard('web_customer')->id())
                                ->select(DB::raw("'".$actions."'".' as "StyleNo"'),'tesco_tsl1_orders.uk_style_ref','tesco_tsl1_orders.ce_style_ref','tesco_tsl1_orders.po_number as BuyerPO','tesco_tsl1_orders.zone as Zone',DB::raw("'".$tsl1."'".' as "TemplateName"'),'tesco_tsl1_orders.template_type as Template Type','customers.id as customer_id','tesco_tsl1_orders.created_at as OrderDate',DB::raw('to_char(tesco_tsl1_orders.delivery_date,'."'".$ddmmyy."'".') as "DeliveryDate"'),'tesco_tsl1_orders.order_status as Artwork Status','tesco_tsl1_orders.pi_status as PI Status','tesco_tsl1_orders.po_status as PO Status','tesco_tsl1_orders.purchase_order_no as PO Number',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'".$tesco_tsl1_orders."'".' as "tableName"'),'tesco_tsl1_orders.id as tableID','tesco_tsl1_orders.po_file','customers.pi_required','customers.po_required','tesco_tsl1_orders.template_id','tesco_tsl1_orders.order_flag','customers.delivery_day_count','is_order_count_empty','tesco_template.template_file')
                                ->orderBy('tesco_tsl1_orders.id','desc')
                                ->get();
                $data['tsl3'] = DB::table('tesco_tsl3_orders')
                                ->join('customers','tesco_tsl3_orders.tesco_supplier_id','=','customers.id')->join('tesco_template','tesco_tsl3_orders.template_id','=','tesco_template.item_id')
                                // ->where('tesco_tsl3_orders.is_order_count_empty',0)
                                // ->where('tesco_tsl3_orders.order_status', '!=', 'Saved')
                                ->whereNotIn('tesco_tsl3_orders.order_status',['Saved','Order Cancelled'])
                                ->where('tesco_tsl3_orders.tesco_supplier_id', $customer_role_id->company_id)
                                ->where('tesco_tsl3_orders.order_placed_by','=',Auth::guard('web_customer')->id())
                                ->select(DB::raw("'".$actions."'".' as "StyleNo"'),'tesco_tsl3_orders.uk_style_ref','tesco_tsl3_orders.ce_style_ref','tesco_tsl3_orders.po_number as BuyerPO','tesco_tsl3_orders.zone as Zone',DB::raw("'".$tsl3."'".' as "TemplateName"'),'tesco_tsl3_orders.template_type as Template Type','customers.id as customer_id','tesco_tsl3_orders.created_at as OrderDate',DB::raw('to_char(tesco_tsl3_orders.delivery_date,'."'".$ddmmyy."'".') as "DeliveryDate"'),'tesco_tsl3_orders.order_status as Artwork Status','tesco_tsl3_orders.pi_status as PI Status','tesco_tsl3_orders.po_status as PO Status','tesco_tsl3_orders.purchase_order_no as PO Number',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'".$tesco_tsl3_orders."'".' as "tableName"'),'tesco_tsl3_orders.id as tableID','tesco_tsl3_orders.po_file','customers.pi_required','customers.po_required','tesco_tsl3_orders.template_id','tesco_tsl3_orders.order_flag','customers.delivery_day_count','is_order_count_empty','tesco_template.template_file')
                                ->orderBy('tesco_tsl3_orders.id','desc')
                                ->get();
            }
            
            /***********REDIS***************/
            /** set order-status data into redis */ 
            Redis::set('customer-order-status:tesco-'.$customer_id, json_encode($data));

            /***********REDIS***************/
            /** get order-status data from redis */
            $data = Redis::get('customer-order-status:tesco-'.$customer_id);
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
            $customer_id = Auth::guard('web_customer')->id();
		
		
            $customer_role_id = DB::table('customer_profile')->select('*')->where('id',$customer_id)->first();
            if($customer_role_id->role_id == 1)
            {
                $tcl1 = "TCL 1";
                $tesco_tcl1_orders = "tesco_tcl1_orders";
                $actions="-";
                $ddmmyy="DD-MM-YYYY";
                $date_start = date("Y-m-d", strtotime(" -6 month"));
                $date_end = date("Y-m-d");
    
                $tcl1 = DB::table("tesco_tcl1_orders")
                ->whereBetween('tesco_tcl1_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                ->where('tesco_tcl1_orders.tesco_supplier_id', $customer_role_id->company_id)
                    ->select("season_code");
    
                $tcl2 = DB::table("tesco_tcl2_orders")
                ->whereBetween('tesco_tcl2_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                ->where('tesco_tcl2_orders.tesco_supplier_id', $customer_role_id->company_id)
                    ->select("season_code");
                
                $tcl4 = DB::table("tesco_tcl4_orders")
                ->whereBetween('tesco_tcl4_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                ->where('tesco_tcl4_orders.tesco_supplier_id', $customer_role_id->company_id)
                        ->select("season_code");
    
                $tsl1 = DB::table("tesco_tsl1_orders")
                ->whereBetween('tesco_tsl1_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                ->where('tesco_tsl1_orders.tesco_supplier_id', $customer_role_id->company_id)
                        ->select("season_code");
    
                $tsl3 = DB::table("tesco_tsl3_orders")
                ->whereBetween('tesco_tsl3_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                ->where('tesco_tsl3_orders.tesco_supplier_id', $customer_role_id->company_id)
                        ->select("season_code");
    
                $thl1 = DB::table("tesco_thl1_orders")
                ->whereBetween('tesco_thl1_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                ->where('tesco_thl1_orders.tesco_supplier_id', $customer_role_id->company_id)
                        ->select("season_code");
                    
                $data = DB::table("tesco_thl3_orders")
                ->whereBetween('tesco_thl3_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                ->where('tesco_thl3_orders.tesco_supplier_id', $customer_role_id->company_id)
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
                ->where('tesco_tcl1_orders.tesco_supplier_id', $customer_role_id->company_id)
                        ->select("uk_style_ref","ce_style_ref","zone");
    
                $tcl2_style = DB::table("tesco_tcl2_orders")
                ->whereBetween('tesco_tcl2_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                ->where('tesco_tcl2_orders.tesco_supplier_id', $customer_role_id->company_id)
                        ->select("uk_style_ref","ce_style_ref","zone");
                        
                $tcl4_style = DB::table("tesco_tcl4_orders")
                ->whereBetween('tesco_tcl4_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                ->where('tesco_tcl4_orders.tesco_supplier_id', $customer_role_id->company_id)
                        ->select("uk_style_ref","ce_style_ref","zone");
                
                $tsl1_style = DB::table("tesco_tsl1_orders")
                ->whereBetween('tesco_tsl1_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                ->where('tesco_tsl1_orders.tesco_supplier_id', $customer_role_id->company_id)
                        ->select("uk_style_ref","ce_style_ref","zone");
                
                $tsl3_style = DB::table("tesco_tsl3_orders")
                ->whereBetween('tesco_tsl3_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                ->where('tesco_tsl3_orders.tesco_supplier_id', $customer_role_id->company_id)
                        ->select("uk_style_ref","ce_style_ref","zone");
    
                $thl1_style = DB::table("tesco_thl1_orders")
                ->whereBetween('tesco_thl1_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                ->where('tesco_thl1_orders.tesco_supplier_id', $customer_role_id->company_id)
                        ->select("uk_style_ref","ce_style_ref","zone");
    
                $style_no = DB::table("tesco_thl3_orders")
                            ->whereBetween('tesco_thl3_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                            ->where('tesco_thl3_orders.tesco_supplier_id', $customer_role_id->company_id)
                            ->select("uk_style_ref","ce_style_ref","zone")
                            ->union($tcl1_style)
                            ->union($tcl2_style)
                            ->union($tcl4_style)
                            ->union($tsl1_style)
                            ->union($tsl3_style)
                            ->union($thl1_style)
                            ->get();
                
            }
            else
            {
                $tcl1 = "TCL 1";
                $tesco_tcl1_orders = "tesco_tcl1_orders";
                $actions="-";
                $ddmmyy="DD-MM-YYYY";
                $date_start = date("Y-m-d", strtotime(" -6 month"));
                $date_end = date("Y-m-d");
    
                $tcl1 = DB::table("tesco_tcl1_orders")
                ->whereBetween('tesco_tcl1_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                ->where('tesco_tcl1_orders.tesco_supplier_id', $customer_role_id->company_id)
                ->where('tesco_tcl1_orders.order_placed_by','=',Auth::guard('web_customer')->id())
                    ->select("season_code");
    
                $tcl2 = DB::table("tesco_tcl2_orders")
                ->whereBetween('tesco_tcl2_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                ->where('tesco_tcl2_orders.tesco_supplier_id', $customer_role_id->company_id)
                ->where('tesco_tcl2_orders.order_placed_by','=',Auth::guard('web_customer')->id())
                    ->select("season_code");
                
                $tcl4 = DB::table("tesco_tcl4_orders")
                ->whereBetween('tesco_tcl4_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                ->where('tesco_tcl4_orders.tesco_supplier_id', $customer_role_id->company_id)
                ->where('tesco_tcl4_orders.order_placed_by','=',Auth::guard('web_customer')->id())
                        ->select("season_code");
    
                $tsl1 = DB::table("tesco_tsl1_orders")
                ->whereBetween('tesco_tsl1_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                ->where('tesco_tsl1_orders.tesco_supplier_id', $customer_role_id->company_id)
                ->where('tesco_tsl1_orders.order_placed_by','=',Auth::guard('web_customer')->id())
                        ->select("season_code");
    
                $tsl3 = DB::table("tesco_tsl3_orders")
                ->whereBetween('tesco_tsl3_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                ->where('tesco_tsl3_orders.tesco_supplier_id', $customer_role_id->company_id)
                ->where('tesco_tsl3_orders.order_placed_by','=',Auth::guard('web_customer')->id())
                        ->select("season_code");
    
                $thl1 = DB::table("tesco_thl1_orders")
                ->whereBetween('tesco_thl1_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                ->where('tesco_thl1_orders.tesco_supplier_id', $customer_role_id->company_id)
                ->where('tesco_thl1_orders.order_placed_by','=',Auth::guard('web_customer')->id())
                        ->select("season_code");
                    
                $data = DB::table("tesco_thl3_orders")
                ->whereBetween('tesco_thl3_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                ->where('tesco_thl3_orders.tesco_supplier_id', $customer_role_id->company_id)
                ->where('tesco_thl3_orders.order_placed_by','=',Auth::guard('web_customer')->id())
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
                ->where('tesco_tcl1_orders.tesco_supplier_id', $customer_role_id->company_id)
                ->where('tesco_tcl1_orders.order_placed_by','=',Auth::guard('web_customer')->id())
                        ->select("uk_style_ref","ce_style_ref","zone");
    
                $tcl2_style = DB::table("tesco_tcl2_orders")
                ->whereBetween('tesco_tcl2_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                ->where('tesco_tcl2_orders.tesco_supplier_id', $customer_role_id->company_id)
                ->where('tesco_tcl2_orders.order_placed_by','=',Auth::guard('web_customer')->id())
                        ->select("uk_style_ref","ce_style_ref","zone");
                        
                $tcl4_style = DB::table("tesco_tcl4_orders")
                ->whereBetween('tesco_tcl4_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                ->where('tesco_tcl4_orders.tesco_supplier_id', $customer_role_id->company_id)
                ->where('tesco_tcl4_orders.order_placed_by','=',Auth::guard('web_customer')->id())
                        ->select("uk_style_ref","ce_style_ref","zone");
                
                $tsl1_style = DB::table("tesco_tsl1_orders")
                ->whereBetween('tesco_tsl1_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                ->where('tesco_tsl1_orders.tesco_supplier_id', $customer_role_id->company_id)
                ->where('tesco_tsl1_orders.order_placed_by','=',Auth::guard('web_customer')->id())
                        ->select("uk_style_ref","ce_style_ref","zone");
                
                $tsl3_style = DB::table("tesco_tsl3_orders")
                ->whereBetween('tesco_tsl3_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                ->where('tesco_tsl3_orders.tesco_supplier_id', $customer_role_id->company_id)
                ->where('tesco_tsl3_orders.order_placed_by','=',Auth::guard('web_customer')->id())
                        ->select("uk_style_ref","ce_style_ref","zone");
    
                $thl1_style = DB::table("tesco_thl1_orders")
                ->whereBetween('tesco_thl1_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                ->where('tesco_thl1_orders.tesco_supplier_id', $customer_role_id->company_id)
                ->where('tesco_thl1_orders.order_placed_by','=',Auth::guard('web_customer')->id())
                        ->select("uk_style_ref","ce_style_ref","zone");
    
                $style_no = DB::table("tesco_thl3_orders")
                            ->whereBetween('tesco_thl3_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                            ->where('tesco_thl3_orders.tesco_supplier_id', $customer_role_id->company_id)
                            ->where('tesco_thl3_orders.order_placed_by','=',Auth::guard('web_customer')->id())
                            ->select("uk_style_ref","ce_style_ref","zone")
                            ->union($tcl1_style)
                            ->union($tcl2_style)
                            ->union($tcl4_style)
                            ->union($tsl1_style)
                            ->union($tsl3_style)
                            ->union($thl1_style)
                            ->get();

            }

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
            $customer_id = Auth::guard('web_customer')->id();
		
		
            $customer_role_id = DB::table('customer_profile')->select('*')->where('id',$customer_id)->first();
            if($customer_role_id->role_id == 1)
            {

                $date_start = $request->start_date;
                $date_end = $request->end_date;
    
                $tcl1 = DB::table("tesco_tcl1_orders")
                ->whereBetween('tesco_tcl1_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                ->where('tesco_tcl1_orders.tesco_supplier_id', $customer_role_id->company_id)
                    ->select("season_code");
    
                $tcl2 = DB::table("tesco_tcl2_orders")
                ->whereBetween('tesco_tcl2_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                ->where('tesco_tcl2_orders.tesco_supplier_id', $customer_role_id->company_id)
                    ->select("season_code");
                
                $tcl4 = DB::table("tesco_tcl4_orders")
                ->whereBetween('tesco_tcl4_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                ->where('tesco_tcl4_orders.tesco_supplier_id', $customer_role_id->company_id)
                        ->select("season_code");
    
                $tsl1 = DB::table("tesco_tsl1_orders")
                ->whereBetween('tesco_tsl1_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                ->where('tesco_tsl1_orders.tesco_supplier_id', $customer_role_id->company_id)
                        ->select("season_code");
    
                $tsl3 = DB::table("tesco_tsl3_orders")
                ->whereBetween('tesco_tsl3_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                ->where('tesco_tsl3_orders.tesco_supplier_id', $customer_role_id->company_id)
                        ->select("season_code");
    
                $thl1 = DB::table("tesco_thl1_orders")
                ->whereBetween('tesco_thl1_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                ->where('tesco_thl1_orders.tesco_supplier_id', $customer_role_id->company_id)
                        ->select("season_code");
                    
                $data = DB::table("tesco_thl3_orders")
                ->whereBetween('tesco_thl3_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                ->where('tesco_thl3_orders.tesco_supplier_id', $customer_role_id->company_id)
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
                ->where('tesco_tcl1_orders.tesco_supplier_id', $customer_role_id->company_id)
                        ->select("uk_style_ref","ce_style_ref","zone");
    
                $tcl2_style = DB::table("tesco_tcl2_orders")
                ->whereBetween('tesco_tcl2_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                ->where('tesco_tcl2_orders.tesco_supplier_id', $customer_role_id->company_id)
                        ->select("uk_style_ref","ce_style_ref","zone");
                        
                $tcl4_style = DB::table("tesco_tcl4_orders")
                ->whereBetween('tesco_tcl4_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                ->where('tesco_tcl4_orders.tesco_supplier_id', $customer_role_id->company_id)
                        ->select("uk_style_ref","ce_style_ref","zone");
                
                $tsl1_style = DB::table("tesco_tsl1_orders")
                ->whereBetween('tesco_tsl1_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                ->where('tesco_tsl1_orders.tesco_supplier_id', $customer_role_id->company_id)
                        ->select("uk_style_ref","ce_style_ref","zone");
                
                $tsl3_style = DB::table("tesco_tsl3_orders")
                ->whereBetween('tesco_tsl3_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                ->where('tesco_tsl3_orders.tesco_supplier_id', $customer_role_id->company_id)
                        ->select("uk_style_ref","ce_style_ref","zone");
    
                $thl1_style = DB::table("tesco_thl1_orders")
                ->whereBetween('tesco_thl1_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                ->where('tesco_thl1_orders.tesco_supplier_id', $customer_role_id->company_id)
                        ->select("uk_style_ref","ce_style_ref","zone");
    
                $style_no = DB::table("tesco_thl3_orders")
                            ->whereBetween('tesco_thl3_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                            ->where('tesco_thl3_orders.tesco_supplier_id', $customer_role_id->company_id)
                            ->select("uk_style_ref","ce_style_ref","zone")
                            ->union($tcl1_style)
                            ->union($tcl2_style)
                            ->union($tcl4_style)
                            ->union($tsl1_style)
                            ->union($tsl3_style)
                            ->union($thl1_style)
                            ->get();
            }
            else
            {

                $date_start = $request->start_date;
                $date_end = $request->end_date;
    
                $tcl1 = DB::table("tesco_tcl1_orders")
                ->whereBetween('tesco_tcl1_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                ->where('tesco_tcl1_orders.tesco_supplier_id', $customer_role_id->company_id)
                ->where('tesco_tcl1_orders.order_placed_by','=',Auth::guard('web_customer')->id())
                    ->select("season_code");
    
                $tcl2 = DB::table("tesco_tcl2_orders")
                ->whereBetween('tesco_tcl2_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                ->where('tesco_tcl2_orders.tesco_supplier_id', $customer_role_id->company_id)
                ->where('tesco_tcl2_orders.order_placed_by','=',Auth::guard('web_customer')->id())
                    ->select("season_code");
                
                $tcl4 = DB::table("tesco_tcl4_orders")
                ->whereBetween('tesco_tcl4_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                ->where('tesco_tcl4_orders.tesco_supplier_id', $customer_role_id->company_id)
                ->where('tesco_tcl4_orders.order_placed_by','=',Auth::guard('web_customer')->id())
                        ->select("season_code");
    
                $tsl1 = DB::table("tesco_tsl1_orders")
                ->whereBetween('tesco_tsl1_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                ->where('tesco_tsl1_orders.tesco_supplier_id', $customer_role_id->company_id)
                ->where('tesco_tsl1_orders.order_placed_by','=',Auth::guard('web_customer')->id())
                        ->select("season_code");
    
                $tsl3 = DB::table("tesco_tsl3_orders")
                ->whereBetween('tesco_tsl3_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                ->where('tesco_tsl3_orders.tesco_supplier_id', $customer_role_id->company_id)
                ->where('tesco_tsl3_orders.order_placed_by','=',Auth::guard('web_customer')->id())
                        ->select("season_code");
    
                $thl1 = DB::table("tesco_thl1_orders")
                ->whereBetween('tesco_thl1_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                ->where('tesco_thl1_orders.tesco_supplier_id', $customer_role_id->company_id)
                ->where('tesco_thl1_orders.order_placed_by','=',Auth::guard('web_customer')->id())
                        ->select("season_code");
                    
                $data = DB::table("tesco_thl3_orders")
                ->whereBetween('tesco_thl3_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                ->where('tesco_thl3_orders.tesco_supplier_id', $customer_role_id->company_id)
                ->where('tesco_thl3_orders.order_placed_by','=',Auth::guard('web_customer')->id())
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
                ->where('tesco_tcl1_orders.tesco_supplier_id', $customer_role_id->company_id)
                ->where('tesco_tcl1_orders.order_placed_by','=',Auth::guard('web_customer')->id())
                        ->select("uk_style_ref","ce_style_ref","zone");
    
                $tcl2_style = DB::table("tesco_tcl2_orders")
                ->whereBetween('tesco_tcl2_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                ->where('tesco_tcl2_orders.tesco_supplier_id', $customer_role_id->company_id)
                ->where('tesco_tcl2_orders.order_placed_by','=',Auth::guard('web_customer')->id())
                        ->select("uk_style_ref","ce_style_ref","zone");
                        
                $tcl4_style = DB::table("tesco_tcl4_orders")
                ->whereBetween('tesco_tcl4_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                ->where('tesco_tcl4_orders.tesco_supplier_id', $customer_role_id->company_id)
                ->where('tesco_tcl4_orders.order_placed_by','=',Auth::guard('web_customer')->id())
                        ->select("uk_style_ref","ce_style_ref","zone");
                
                $tsl1_style = DB::table("tesco_tsl1_orders")
                ->whereBetween('tesco_tsl1_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                ->where('tesco_tsl1_orders.tesco_supplier_id', $customer_role_id->company_id)
                ->where('tesco_tsl1_orders.order_placed_by','=',Auth::guard('web_customer')->id())
                        ->select("uk_style_ref","ce_style_ref","zone");
                
                $tsl3_style = DB::table("tesco_tsl3_orders")
                ->whereBetween('tesco_tsl3_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                ->where('tesco_tsl3_orders.tesco_supplier_id', $customer_role_id->company_id)
                ->where('tesco_tsl3_orders.order_placed_by','=',Auth::guard('web_customer')->id())
                        ->select("uk_style_ref","ce_style_ref","zone");
    
                $thl1_style = DB::table("tesco_thl1_orders")
                ->whereBetween('tesco_thl1_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                ->where('tesco_thl1_orders.tesco_supplier_id', $customer_role_id->company_id)
                ->where('tesco_thl1_orders.order_placed_by','=',Auth::guard('web_customer')->id())
                        ->select("uk_style_ref","ce_style_ref","zone");
    
                $style_no = DB::table("tesco_thl3_orders")
                            ->whereBetween('tesco_thl3_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                            ->where('tesco_thl3_orders.tesco_supplier_id', $customer_role_id->company_id)
                            ->where('tesco_thl3_orders.order_placed_by','=',Auth::guard('web_customer')->id())
                            ->select("uk_style_ref","ce_style_ref","zone")
                            ->union($tcl1_style)
                            ->union($tcl2_style)
                            ->union($tcl4_style)
                            ->union($tsl1_style)
                            ->union($tsl3_style)
                            ->union($thl1_style)
                            ->get();
            }

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
            $orderstatusosref = array_values(array_diff($orderstatusos, array("Pending")));
            
            $customer_id = Auth::guard('web_customer')->id();
		
		
            $customer_role_id = DB::table('customer_profile')->select('*')->where('id',$customer_id)->first();
            if($customer_role_id->role_id == 1)
            {
                $tcl1 = "TCL 1";
                $tesco_tcl1_orders = "tesco_tcl1_orders";
                $actions="-";
                $ddmmyy="DD-MM-YYYY";
                $tesco_tcl1_orders = DB::table("tesco_tcl1_orders")
                                ->join('customers','tesco_tcl1_orders.tesco_supplier_id','=','customers.id')
                                ->join('tesco_template','tesco_tcl1_orders.template_id','=','tesco_template.item_id')
                                ->whereBetween('tesco_tcl1_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                                ->where('tesco_tcl1_orders.tesco_supplier_id', $customer_role_id->company_id)
                                ->where(function($query) use ($seasonos){
                                    if(count($seasonos))
                                    {
                                        $query->orWhereIn('season_code', $seasonos);
                                    }
                                })
                                ->where(function($query) use ($stylenoos,$ukstylenoos){
                                    if(count($stylenoos))
                                    {
                                        $query->orWhereIn('ce_style_ref', $stylenoos);
                                        $query->where('zone', 'CE');
                                    }
                                    if(count($ukstylenoos))
                                    {
                                        $query->orWhereIn('uk_style_ref', $ukstylenoos);
                                        $query->where('zone', 'UK');
                                    }
                                })
                                ->where(function($query) use ($orderstatusos,$orderstatusosref,$artworkstatusos){
                                    if(count($orderstatusosref))
                                    {
                                        $query->orWhereIn('order_status', $orderstatusosref);
                                    }
                                    if(in_array("Pending", $orderstatusos))
                                    {
                                        $query->orWhere('is_order_count_empty', 1);
                                    }
                                    if(count($artworkstatusos))
                                    {
                                        $query->orWhereIn('order_status', $artworkstatusos);
                                    }
                                })
                                ->where(function($query) use ($postatusos){
                                    if(count($postatusos))
                                    {
                                        $query->orWhereIn('po_status', $postatusos);
                                    }
                                })
                                ->where(function($query) use ($pistatusos){
                                    if(count($pistatusos))
                                    {
                                        $query->orWhereIn('pi_status', $pistatusos);
                                    }
                                })
                                ->select(DB::raw("'".$actions."'".' as "StyleNo"'),'ocn_number','tesco_tcl1_orders.season_code','tesco_tcl1_orders.uk_style_ref','tesco_tcl1_orders.ce_style_ref','tesco_tcl1_orders.po_number as BuyerPO','tesco_tcl1_orders.zone as Zone',DB::raw("'".$tcl1."'".' as "TemplateName"'),'tesco_tcl1_orders.template_type as Template Type','customers.id as customer_id','tesco_tcl1_orders.created_at as OrderDate',DB::raw('to_char(tesco_tcl1_orders.delivery_date,'."'".$ddmmyy."'".') as "DeliveryDate"'),'tesco_tcl1_orders.order_status as order_status','tesco_template.template_file',DB::raw("'".$actions."'".' as "Artwork"'), 'tesco_tcl1_orders.order_status as artwork_status','tesco_tcl1_orders.pi_status as PI Status','tesco_tcl1_orders.po_status as PO Status','tesco_tcl1_orders.purchase_order_no as PO Number',DB::raw("'".$tesco_tcl1_orders."'".' as "tableName"'),'tesco_tcl1_orders.id as tableID','tesco_tcl1_orders.po_file','customers.pi_required','customers.po_required','tesco_tcl1_orders.template_id','tesco_tcl1_orders.order_flag','customers.delivery_day_count','is_order_count_empty');
    
                $tcl2 = "TCL 2";
                $tesco_tcl2_orders = "tesco_tcl2_orders";
                $tesco_tcl2_orders = DB::table("tesco_tcl2_orders")
                ->join('customers','tesco_tcl2_orders.tesco_supplier_id','=','customers.id')
                ->join('tesco_template','tesco_tcl2_orders.template_id','=','tesco_template.item_id')
                                ->whereBetween('tesco_tcl2_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                                ->where('tesco_tcl2_orders.tesco_supplier_id', $customer_role_id->company_id)
                                ->where(function($query) use ($seasonos){
                                    if(count($seasonos))
                                    {
                                        $query->orWhereIn('season_code', $seasonos);
                                    }
                                })
                                ->where(function($query) use ($stylenoos,$ukstylenoos){
                                    if(count($stylenoos))
                                    {
                                        $query->orWhereIn('ce_style_ref', $stylenoos);
                                        $query->where('zone', 'CE');
                                    }
                                    if(count($ukstylenoos))
                                    {
                                        $query->orWhereIn('uk_style_ref', $ukstylenoos);
                                        $query->where('zone', 'UK');
                                    }
                                })
                                ->where(function($query) use ($orderstatusos,$orderstatusosref,$artworkstatusos){
                                    if(count($orderstatusosref))
                                    {
                                        $query->orWhereIn('order_status', $orderstatusosref);
                                    }
                                    if(in_array("Pending", $orderstatusos))
                                    {
                                        $query->orWhere('is_order_count_empty', 1);
                                    }
                                    if(count($artworkstatusos))
                                    {
                                        $query->orWhereIn('order_status', $artworkstatusos);
                                    }
                                })
                                ->where(function($query) use ($postatusos){
                                    if(count($postatusos))
                                    {
                                        $query->orWhereIn('po_status', $postatusos);
                                    }
                                })
                                ->where(function($query) use ($pistatusos){
                                    if(count($pistatusos))
                                    {
                                        $query->orWhereIn('pi_status', $pistatusos);
                                    }
                                })
                                ->select(DB::raw("'".$actions."'".' as "StyleNo"'),'ocn_number','tesco_tcl2_orders.season_code','tesco_tcl2_orders.uk_style_ref','tesco_tcl2_orders.ce_style_ref','tesco_tcl2_orders.po_number as BuyerPO','tesco_tcl2_orders.zone as Zone',DB::raw("'".$tcl2."'".' as "TemplateName"'),'tesco_tcl2_orders.template_type as Template Type','customers.id as customer_id','tesco_tcl2_orders.created_at as OrderDate',DB::raw('to_char(tesco_tcl2_orders.delivery_date,'."'".$ddmmyy."'".') as "DeliveryDate"'),'tesco_tcl2_orders.order_status as order_status','tesco_template.template_file',DB::raw("'".$actions."'".' as "Artwork"'), 'tesco_tcl2_orders.order_status as artwork_status','tesco_tcl2_orders.pi_status as PI Status','tesco_tcl2_orders.po_status as PO Status','tesco_tcl2_orders.purchase_order_no as PO Number',DB::raw("'".$tesco_tcl2_orders."'".' as "tableName"'),'tesco_tcl2_orders.id as tableID','tesco_tcl2_orders.po_file','customers.pi_required','customers.po_required','tesco_tcl2_orders.template_id','tesco_tcl2_orders.order_flag','customers.delivery_day_count','is_order_count_empty');
    
                $tcl4 = "TCL 4";
                $tesco_tcl4_orders = "tesco_tcl4_orders";
                $tesco_tcl4_orders = DB::table("tesco_tcl4_orders")
                ->join('customers','tesco_tcl4_orders.tesco_supplier_id','=','customers.id')->join('tesco_template','tesco_tcl4_orders.template_id','=','tesco_template.item_id')
                                ->whereBetween('tesco_tcl4_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                                ->where('tesco_tcl4_orders.tesco_supplier_id', $customer_role_id->company_id)
                                ->where(function($query) use ($seasonos){
                                    if(count($seasonos))
                                    {
                                        $query->orWhereIn('season_code', $seasonos);
                                    }
                                })
                                ->where(function($query) use ($stylenoos,$ukstylenoos){
                                    if(count($stylenoos))
                                    {
                                        $query->orWhereIn('ce_style_ref', $stylenoos);
                                        $query->where('zone', 'CE');
                                    }
                                    if(count($ukstylenoos))
                                    {
                                        $query->orWhereIn('uk_style_ref', $ukstylenoos);
                                        $query->where('zone', 'UK');
                                    }
                                })
                                ->where(function($query) use ($orderstatusos,$orderstatusosref,$artworkstatusos){
                                    if(count($orderstatusosref))
                                    {
                                        $query->orWhereIn('order_status', $orderstatusosref);
                                    }
                                    if(in_array("Pending", $orderstatusos))
                                    {
                                        $query->orWhere('is_order_count_empty', 1);
                                    }
                                    if(count($artworkstatusos))
                                    {
                                        $query->orWhereIn('order_status', $artworkstatusos);
                                    }
                                })
                                ->where(function($query) use ($postatusos){
                                    if(count($postatusos))
                                    {
                                        $query->orWhereIn('po_status', $postatusos);
                                    }
                                })
                                ->where(function($query) use ($pistatusos){
                                    if(count($pistatusos))
                                    {
                                        $query->orWhereIn('pi_status', $pistatusos);
                                    }
                                })
                                ->select(DB::raw("'".$actions."'".' as "StyleNo"'),'ocn_number','tesco_tcl4_orders.season_code','tesco_tcl4_orders.uk_style_ref','tesco_tcl4_orders.ce_style_ref','tesco_tcl4_orders.po_number as BuyerPO','tesco_tcl4_orders.zone as Zone',DB::raw("'".$tcl4."'".' as "TemplateName"'),'tesco_tcl4_orders.template_type as Template Type','customers.id as customer_id','tesco_tcl4_orders.created_at as OrderDate',DB::raw('to_char(tesco_tcl4_orders.delivery_date,'."'".$ddmmyy."'".') as "DeliveryDate"'),'tesco_tcl4_orders.order_status as order_status','tesco_template.template_file',DB::raw("'".$actions."'".' as "Artwork"'), 'tesco_tcl4_orders.order_status as artwork_status','tesco_tcl4_orders.pi_status as PI Status','tesco_tcl4_orders.po_status as PO Status','tesco_tcl4_orders.purchase_order_no as PO Number',DB::raw("'".$tesco_tcl4_orders."'".' as "tableName"'),'tesco_tcl4_orders.id as tableID','tesco_tcl4_orders.po_file','customers.pi_required','customers.po_required','tesco_tcl4_orders.template_id','tesco_tcl4_orders.order_flag','customers.delivery_day_count','is_order_count_empty');
    
                $tsl1 = "TSL 1";
                $tesco_tsl1_orders = "tesco_tsl1_orders";
                $tesco_tsl1_orders = DB::table("tesco_tsl1_orders")
                ->join('customers','tesco_tsl1_orders.tesco_supplier_id','=','customers.id')->join('tesco_template','tesco_tsl1_orders.template_id','=','tesco_template.item_id')
                                ->whereBetween('tesco_tsl1_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                                ->where('tesco_tsl1_orders.tesco_supplier_id', $customer_role_id->company_id)
                                ->where(function($query) use ($seasonos){
                                    if(count($seasonos))
                                    {
                                        $query->orWhereIn('season_code', $seasonos);
                                    }
                                })
                                ->where(function($query) use ($stylenoos,$ukstylenoos){
                                    if(count($stylenoos))
                                    {
                                        $query->orWhereIn('ce_style_ref', $stylenoos);
                                        $query->where('zone', 'CE');
                                    }
                                    if(count($ukstylenoos))
                                    {
                                        $query->orWhereIn('uk_style_ref', $ukstylenoos);
                                        $query->where('zone', 'UK');
                                    }
                                })
                                ->where(function($query) use ($orderstatusos,$orderstatusosref,$artworkstatusos){
                                    if(count($orderstatusosref))
                                    {
                                        $query->orWhereIn('order_status', $orderstatusosref);
                                    }
                                    if(in_array("Pending", $orderstatusos))
                                    {
                                        $query->orWhere('is_order_count_empty', 1);
                                    }
                                    if(count($artworkstatusos))
                                    {
                                        $query->orWhereIn('order_status', $artworkstatusos);
                                    }
                                })
                                ->where(function($query) use ($postatusos){
                                    if(count($postatusos))
                                    {
                                        $query->orWhereIn('po_status', $postatusos);
                                    }
                                })
                                ->where(function($query) use ($pistatusos){
                                    if(count($pistatusos))
                                    {
                                        $query->orWhereIn('pi_status', $pistatusos);
                                    }
                                })
                                ->select(DB::raw("'".$actions."'".' as "StyleNo"'),'ocn_number','tesco_tsl1_orders.season_code','tesco_tsl1_orders.uk_style_ref','tesco_tsl1_orders.ce_style_ref','tesco_tsl1_orders.po_number as BuyerPO','tesco_tsl1_orders.zone as Zone',DB::raw("'".$tsl1."'".' as "TemplateName"'),'tesco_tsl1_orders.template_type as Template Type','customers.id as customer_id','tesco_tsl1_orders.created_at as OrderDate',DB::raw('to_char(tesco_tsl1_orders.delivery_date,'."'".$ddmmyy."'".') as "DeliveryDate"'),'tesco_tsl1_orders.order_status as order_status','tesco_template.template_file',DB::raw("'".$actions."'".' as "Artwork"'), 'tesco_tsl1_orders.order_status as artwork_status','tesco_tsl1_orders.pi_status as PI Status','tesco_tsl1_orders.po_status as PO Status','tesco_tsl1_orders.purchase_order_no as PO Number',DB::raw("'".$tesco_tsl1_orders."'".' as "tableName"'),'tesco_tsl1_orders.id as tableID','tesco_tsl1_orders.po_file','customers.pi_required','customers.po_required','tesco_tsl1_orders.template_id','tesco_tsl1_orders.order_flag','customers.delivery_day_count','is_order_count_empty');
    
                $tsl3 = "TSL 3";
                $tesco_tsl3_orders = "tesco_tsl3_orders";
                $tesco_tsl3_orders = DB::table("tesco_tsl3_orders")
                ->join('customers','tesco_tsl3_orders.tesco_supplier_id','=','customers.id')->join('tesco_template','tesco_tsl3_orders.template_id','=','tesco_template.item_id')
                                ->whereBetween('tesco_tsl3_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                                ->where('tesco_tsl3_orders.tesco_supplier_id', $customer_role_id->company_id)
                                ->where(function($query) use ($seasonos){
                                    if(count($seasonos))
                                    {
                                        $query->orWhereIn('season_code', $seasonos);
                                    }
                                })
                                ->where(function($query) use ($stylenoos,$ukstylenoos){
                                    if(count($stylenoos))
                                    {
                                        $query->orWhereIn('ce_style_ref', $stylenoos);
                                        $query->where('zone', 'CE');
                                    }
                                    if(count($ukstylenoos))
                                    {
                                        $query->orWhereIn('uk_style_ref', $ukstylenoos);
                                        $query->where('zone', 'UK');
                                    }
                                })
                                ->where(function($query) use ($orderstatusos,$orderstatusosref,$artworkstatusos){
                                    if(count($orderstatusosref))
                                    {
                                        $query->orWhereIn('order_status', $orderstatusosref);
                                    }
                                    if(in_array("Pending", $orderstatusos))
                                    {
                                        $query->orWhere('is_order_count_empty', 1);
                                    }
                                    if(count($artworkstatusos))
                                    {
                                        $query->orWhereIn('order_status', $artworkstatusos);
                                    }
                                })
                                ->where(function($query) use ($postatusos){
                                    if(count($postatusos))
                                    {
                                        $query->orWhereIn('po_status', $postatusos);
                                    }
                                })
                                ->where(function($query) use ($pistatusos){
                                    if(count($pistatusos))
                                    {
                                        $query->orWhereIn('pi_status', $pistatusos);
                                    }
                                })
                                ->select(DB::raw("'".$actions."'".' as "StyleNo"'),'ocn_number','tesco_tsl3_orders.season_code','tesco_tsl3_orders.uk_style_ref','tesco_tsl3_orders.ce_style_ref','tesco_tsl3_orders.po_number as BuyerPO','tesco_tsl3_orders.zone as Zone',DB::raw("'".$tsl3."'".' as "TemplateName"'),'tesco_tsl3_orders.template_type as Template Type','customers.id as customer_id','tesco_tsl3_orders.created_at as OrderDate',DB::raw('to_char(tesco_tsl3_orders.delivery_date,'."'".$ddmmyy."'".') as "DeliveryDate"'),'tesco_tsl3_orders.order_status as order_status','tesco_template.template_file',DB::raw("'".$actions."'".' as "Artwork"'), 'tesco_tsl3_orders.order_status as artwork_status','tesco_tsl3_orders.pi_status as PI Status','tesco_tsl3_orders.po_status as PO Status','tesco_tsl3_orders.purchase_order_no as PO Number',DB::raw("'".$tesco_tsl3_orders."'".' as "tableName"'),'tesco_tsl3_orders.id as tableID','tesco_tsl3_orders.po_file','customers.pi_required','customers.po_required','tesco_tsl3_orders.template_id','tesco_tsl3_orders.order_flag','customers.delivery_day_count','is_order_count_empty');
    
                $thl1 = "THL 1";
                $tesco_thl1_orders = "tesco_thl1_orders";
                $tesco_thl1_orders = DB::table("tesco_thl1_orders")
                ->join('customers','tesco_thl1_orders.tesco_supplier_id','=','customers.id')->join('tesco_template','tesco_thl1_orders.template_id','=','tesco_template.item_id')
                                ->whereBetween('tesco_thl1_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                                ->where('tesco_thl1_orders.tesco_supplier_id', $customer_role_id->company_id)
                                ->where(function($query) use ($seasonos){
                                    if(count($seasonos))
                                    {
                                        $query->orWhereIn('season_code', $seasonos);
                                    }
                                })
                                ->where(function($query) use ($stylenoos,$ukstylenoos){
                                    if(count($stylenoos))
                                    {
                                        $query->orWhereIn('ce_style_ref', $stylenoos);
                                        $query->where('zone', 'CE');
                                    }
                                    if(count($ukstylenoos))
                                    {
                                        $query->orWhereIn('uk_style_ref', $ukstylenoos);
                                        $query->where('zone', 'UK');
                                    }
                                })
                                ->where(function($query) use ($orderstatusos,$orderstatusosref,$artworkstatusos){
                                    if(count($orderstatusosref))
                                    {
                                        $query->orWhereIn('order_status', $orderstatusosref);
                                    }
                                    if(in_array("Pending", $orderstatusos))
                                    {
                                        $query->orWhere('is_order_count_empty', 1);
                                    }
                                    if(count($artworkstatusos))
                                    {
                                        $query->orWhereIn('order_status', $artworkstatusos);
                                    }
                                })
                                ->where(function($query) use ($postatusos){
                                    if(count($postatusos))
                                    {
                                        $query->orWhereIn('po_status', $postatusos);
                                    }
                                })
                                ->where(function($query) use ($pistatusos){
                                    if(count($pistatusos))
                                    {
                                        $query->orWhereIn('pi_status', $pistatusos);
                                    }
                                })
                                ->select(DB::raw("'".$actions."'".' as "StyleNo"'),'ocn_number','tesco_thl1_orders.season_code','tesco_thl1_orders.uk_style_ref','tesco_thl1_orders.ce_style_ref','tesco_thl1_orders.po_number as BuyerPO','tesco_thl1_orders.zone as Zone',DB::raw("'".$thl1."'".' as "TemplateName"'),'tesco_thl1_orders.template_type as Template Type','customers.id as customer_id','tesco_thl1_orders.created_at as OrderDate',DB::raw('to_char(tesco_thl1_orders.delivery_date,'."'".$ddmmyy."'".') as "DeliveryDate"'),'tesco_thl1_orders.order_status as order_status','tesco_template.template_file',DB::raw("'".$actions."'".' as "Artwork"'), 'tesco_thl1_orders.order_status as artwork_status','tesco_thl1_orders.pi_status as PI Status','tesco_thl1_orders.po_status as PO Status','tesco_thl1_orders.purchase_order_no as PO Number',DB::raw("'".$tesco_thl1_orders."'".' as "tableName"'),'tesco_thl1_orders.id as tableID','tesco_thl1_orders.po_file','customers.pi_required','customers.po_required','tesco_thl1_orders.template_id','tesco_thl1_orders.order_flag','customers.delivery_day_count','is_order_count_empty');
    
                $thl3 = "THL 3";
                $tesco_thl3_orders = "tesco_thl3_orders";
                $orders = DB::table("tesco_thl3_orders")
                ->join('customers','tesco_thl3_orders.tesco_supplier_id','=','customers.id')->join('tesco_template','tesco_thl3_orders.template_id','=','tesco_template.item_id')
                            ->whereBetween('tesco_thl3_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                            ->where('tesco_thl3_orders.tesco_supplier_id', $customer_role_id->company_id)
                            ->where(function($query) use ($seasonos){
                                if(count($seasonos))
                                {
                                    $query->orWhereIn('season_code', $seasonos);
                                }
                            })
                            ->where(function($query) use ($stylenoos,$ukstylenoos){
                                if(count($stylenoos))
                                {
                                    $query->orWhereIn('ce_style_ref', $stylenoos);
                                    $query->where('zone', 'CE');
                                }
                                if(count($ukstylenoos))
                                {
                                    $query->orWhereIn('uk_style_ref', $ukstylenoos);
                                    $query->where('zone', 'UK');
                                }
                            })
                            ->where(function($query) use ($orderstatusos,$orderstatusosref,$artworkstatusos){
                                if(count($orderstatusosref))
                                {
                                    $query->orWhereIn('order_status', $orderstatusosref);
                                }
                                if(in_array("Pending", $orderstatusos))
                                {
                                    $query->orWhere('is_order_count_empty', 1);
                                }
                                if(count($artworkstatusos))
                                {
                                    $query->orWhereIn('order_status', $artworkstatusos);
                                }
                            })
                            ->where(function($query) use ($postatusos){
                                if(count($postatusos))
                                {
                                    $query->orWhereIn('po_status', $postatusos);
                                }
                            })
                            ->where(function($query) use ($pistatusos){
                                if(count($pistatusos))
                                {
                                    $query->orWhereIn('pi_status', $pistatusos);
                                }
                            })
                            ->select(DB::raw("'".$actions."'".' as "StyleNo"'),'ocn_number','tesco_thl3_orders.season_code','tesco_thl3_orders.uk_style_ref','tesco_thl3_orders.ce_style_ref','tesco_thl3_orders.po_number as BuyerPO','tesco_thl3_orders.zone as Zone',DB::raw("'".$thl3."'".' as "TemplateName"'),'tesco_thl3_orders.template_type as Template Type','customers.id as customer_id','tesco_thl3_orders.created_at as OrderDate',DB::raw('to_char(tesco_thl3_orders.delivery_date,'."'".$ddmmyy."'".') as "DeliveryDate"'),'tesco_thl3_orders.order_status as order_status','tesco_template.template_file',DB::raw("'".$actions."'".' as "Artwork"'), 'tesco_thl3_orders.order_status as artwork_status','tesco_thl3_orders.pi_status as PI Status','tesco_thl3_orders.po_status as PO Status','tesco_thl3_orders.purchase_order_no as PO Number',DB::raw("'".$tesco_thl3_orders."'".' as "tableName"'),'tesco_thl3_orders.id as tableID','tesco_thl3_orders.po_file','customers.pi_required','customers.po_required','tesco_thl3_orders.template_id','tesco_thl3_orders.order_flag','customers.delivery_day_count','is_order_count_empty')
                            ->union($tesco_tcl1_orders)
                            ->union($tesco_tcl2_orders)
                            ->union($tesco_tcl4_orders)
                            ->union($tesco_tsl1_orders)
                            ->union($tesco_tsl3_orders)
                            ->union($tesco_thl1_orders)
                            ->get();
            }
            else
            {
                $tcl1 = "TCL 1";
                $tesco_tcl1_orders = "tesco_tcl1_orders";
                $actions="-";
                $ddmmyy="DD-MM-YYYY";
                $tesco_tcl1_orders = DB::table("tesco_tcl1_orders")
                                ->join('customers','tesco_tcl1_orders.tesco_supplier_id','=','customers.id')
                                ->join('tesco_template','tesco_tcl1_orders.template_id','=','tesco_template.item_id')
                                ->whereBetween('tesco_tcl1_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                                ->where('tesco_tcl1_orders.tesco_supplier_id', $customer_role_id->company_id)
                                ->where('tesco_tcl1_orders.order_placed_by','=',Auth::guard('web_customer')->id())
                                ->where(function($query) use ($seasonos){
                                    if(count($seasonos))
                                    {
                                        $query->orWhereIn('season_code', $seasonos);
                                    }
                                })
                                ->where(function($query) use ($stylenoos,$ukstylenoos){
                                    if(count($stylenoos))
                                    {
                                        $query->orWhereIn('ce_style_ref', $stylenoos);
                                        $query->where('zone', 'CE');
                                    }
                                    if(count($ukstylenoos))
                                    {
                                        $query->orWhereIn('uk_style_ref', $ukstylenoos);
                                        $query->where('zone', 'UK');
                                    }
                                })
                                ->where(function($query) use ($orderstatusos,$orderstatusosref,$artworkstatusos){
                                    if(count($orderstatusosref))
                                    {
                                        $query->orWhereIn('order_status', $orderstatusosref);
                                    }
                                    if(in_array("Pending", $orderstatusos))
                                    {
                                        $query->orWhere('is_order_count_empty', 1);
                                    }
                                    if(count($artworkstatusos))
                                    {
                                        $query->orWhereIn('order_status', $artworkstatusos);
                                    }
                                })
                                ->where(function($query) use ($postatusos){
                                    if(count($postatusos))
                                    {
                                        $query->orWhereIn('po_status', $postatusos);
                                    }
                                })
                                ->where(function($query) use ($pistatusos){
                                    if(count($pistatusos))
                                    {
                                        $query->orWhereIn('pi_status', $pistatusos);
                                    }
                                })
                                ->select(DB::raw("'".$actions."'".' as "StyleNo"'),'ocn_number','tesco_tcl1_orders.season_code','tesco_tcl1_orders.uk_style_ref','tesco_tcl1_orders.ce_style_ref','tesco_tcl1_orders.po_number as BuyerPO','tesco_tcl1_orders.zone as Zone',DB::raw("'".$tcl1."'".' as "TemplateName"'),'tesco_tcl1_orders.template_type as Template Type','customers.id as customer_id','tesco_tcl1_orders.created_at as OrderDate',DB::raw('to_char(tesco_tcl1_orders.delivery_date,'."'".$ddmmyy."'".') as "DeliveryDate"'),'tesco_tcl1_orders.order_status as order_status','tesco_template.template_file',DB::raw("'".$actions."'".' as "Artwork"'), 'tesco_tcl1_orders.order_status as artwork_status','tesco_tcl1_orders.pi_status as PI Status','tesco_tcl1_orders.po_status as PO Status','tesco_tcl1_orders.purchase_order_no as PO Number',DB::raw("'".$tesco_tcl1_orders."'".' as "tableName"'),'tesco_tcl1_orders.id as tableID','tesco_tcl1_orders.po_file','customers.pi_required','customers.po_required','tesco_tcl1_orders.template_id','tesco_tcl1_orders.order_flag','customers.delivery_day_count','is_order_count_empty');
    
                $tcl2 = "TCL 2";
                $tesco_tcl2_orders = "tesco_tcl2_orders";
                $tesco_tcl2_orders = DB::table("tesco_tcl2_orders")
                ->join('customers','tesco_tcl2_orders.tesco_supplier_id','=','customers.id')
                ->join('tesco_template','tesco_tcl2_orders.template_id','=','tesco_template.item_id')
                                ->whereBetween('tesco_tcl2_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                                ->where('tesco_tcl2_orders.tesco_supplier_id', $customer_role_id->company_id)
                                ->where('tesco_tcl2_orders.order_placed_by','=',Auth::guard('web_customer')->id())
                                ->where(function($query) use ($seasonos){
                                    if(count($seasonos))
                                    {
                                        $query->orWhereIn('season_code', $seasonos);
                                    }
                                })
                                ->where(function($query) use ($stylenoos,$ukstylenoos){
                                    if(count($stylenoos))
                                    {
                                        $query->orWhereIn('ce_style_ref', $stylenoos);
                                        $query->where('zone', 'CE');
                                    }
                                    if(count($ukstylenoos))
                                    {
                                        $query->orWhereIn('uk_style_ref', $ukstylenoos);
                                        $query->where('zone', 'UK');
                                    }
                                })
                                ->where(function($query) use ($orderstatusos,$orderstatusosref,$artworkstatusos){
                                    if(count($orderstatusosref))
                                    {
                                        $query->orWhereIn('order_status', $orderstatusosref);
                                    }
                                    if(in_array("Pending", $orderstatusos))
                                    {
                                        $query->orWhere('is_order_count_empty', 1);
                                    }
                                    if(count($artworkstatusos))
                                    {
                                        $query->orWhereIn('order_status', $artworkstatusos);
                                    }
                                })
                                ->where(function($query) use ($postatusos){
                                    if(count($postatusos))
                                    {
                                        $query->orWhereIn('po_status', $postatusos);
                                    }
                                })
                                ->where(function($query) use ($pistatusos){
                                    if(count($pistatusos))
                                    {
                                        $query->orWhereIn('pi_status', $pistatusos);
                                    }
                                })
                                ->select(DB::raw("'".$actions."'".' as "StyleNo"'),'ocn_number','tesco_tcl2_orders.season_code','tesco_tcl2_orders.uk_style_ref','tesco_tcl2_orders.ce_style_ref','tesco_tcl2_orders.po_number as BuyerPO','tesco_tcl2_orders.zone as Zone',DB::raw("'".$tcl2."'".' as "TemplateName"'),'tesco_tcl2_orders.template_type as Template Type','customers.id as customer_id','tesco_tcl2_orders.created_at as OrderDate',DB::raw('to_char(tesco_tcl2_orders.delivery_date,'."'".$ddmmyy."'".') as "DeliveryDate"'),'tesco_tcl2_orders.order_status as order_status','tesco_template.template_file',DB::raw("'".$actions."'".' as "Artwork"'), 'tesco_tcl2_orders.order_status as artwork_status','tesco_tcl2_orders.pi_status as PI Status','tesco_tcl2_orders.po_status as PO Status','tesco_tcl2_orders.purchase_order_no as PO Number',DB::raw("'".$tesco_tcl2_orders."'".' as "tableName"'),'tesco_tcl2_orders.id as tableID','tesco_tcl2_orders.po_file','customers.pi_required','customers.po_required','tesco_tcl2_orders.template_id','tesco_tcl2_orders.order_flag','customers.delivery_day_count','is_order_count_empty');
    
                $tcl4 = "TCL 4";
                $tesco_tcl4_orders = "tesco_tcl4_orders";
                $tesco_tcl4_orders = DB::table("tesco_tcl4_orders")
                ->join('customers','tesco_tcl4_orders.tesco_supplier_id','=','customers.id')->join('tesco_template','tesco_tcl4_orders.template_id','=','tesco_template.item_id')
                                ->whereBetween('tesco_tcl4_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                                ->where('tesco_tcl4_orders.tesco_supplier_id', $customer_role_id->company_id)
                                ->where('tesco_tcl4_orders.order_placed_by','=',Auth::guard('web_customer')->id())
                                ->where(function($query) use ($seasonos){
                                    if(count($seasonos))
                                    {
                                        $query->orWhereIn('season_code', $seasonos);
                                    }
                                })
                                ->where(function($query) use ($stylenoos,$ukstylenoos){
                                    if(count($stylenoos))
                                    {
                                        $query->orWhereIn('ce_style_ref', $stylenoos);
                                        $query->where('zone', 'CE');
                                    }
                                    if(count($ukstylenoos))
                                    {
                                        $query->orWhereIn('uk_style_ref', $ukstylenoos);
                                        $query->where('zone', 'UK');
                                    }
                                })
                                ->where(function($query) use ($orderstatusos,$orderstatusosref,$artworkstatusos){
                                    if(count($orderstatusosref))
                                    {
                                        $query->orWhereIn('order_status', $orderstatusosref);
                                    }
                                    if(in_array("Pending", $orderstatusos))
                                    {
                                        $query->orWhere('is_order_count_empty', 1);
                                    }
                                    if(count($artworkstatusos))
                                    {
                                        $query->orWhereIn('order_status', $artworkstatusos);
                                    }
                                })
                                ->where(function($query) use ($postatusos){
                                    if(count($postatusos))
                                    {
                                        $query->orWhereIn('po_status', $postatusos);
                                    }
                                })
                                ->where(function($query) use ($pistatusos){
                                    if(count($pistatusos))
                                    {
                                        $query->orWhereIn('pi_status', $pistatusos);
                                    }
                                })
                                ->select(DB::raw("'".$actions."'".' as "StyleNo"'),'ocn_number','tesco_tcl4_orders.season_code','tesco_tcl4_orders.uk_style_ref','tesco_tcl4_orders.ce_style_ref','tesco_tcl4_orders.po_number as BuyerPO','tesco_tcl4_orders.zone as Zone',DB::raw("'".$tcl4."'".' as "TemplateName"'),'tesco_tcl4_orders.template_type as Template Type','customers.id as customer_id','tesco_tcl4_orders.created_at as OrderDate',DB::raw('to_char(tesco_tcl4_orders.delivery_date,'."'".$ddmmyy."'".') as "DeliveryDate"'),'tesco_tcl4_orders.order_status as order_status','tesco_template.template_file',DB::raw("'".$actions."'".' as "Artwork"'), 'tesco_tcl4_orders.order_status as artwork_status','tesco_tcl4_orders.pi_status as PI Status','tesco_tcl4_orders.po_status as PO Status','tesco_tcl4_orders.purchase_order_no as PO Number',DB::raw("'".$tesco_tcl4_orders."'".' as "tableName"'),'tesco_tcl4_orders.id as tableID','tesco_tcl4_orders.po_file','customers.pi_required','customers.po_required','tesco_tcl4_orders.template_id','tesco_tcl4_orders.order_flag','customers.delivery_day_count','is_order_count_empty');
    
                $tsl1 = "TSL 1";
                $tesco_tsl1_orders = "tesco_tsl1_orders";
                $tesco_tsl1_orders = DB::table("tesco_tsl1_orders")
                ->join('customers','tesco_tsl1_orders.tesco_supplier_id','=','customers.id')->join('tesco_template','tesco_tsl1_orders.template_id','=','tesco_template.item_id')
                                ->whereBetween('tesco_tsl1_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                                ->where('tesco_tsl1_orders.tesco_supplier_id', $customer_role_id->company_id)
                                ->where('tesco_tsl1_orders.order_placed_by','=',Auth::guard('web_customer')->id())
                                ->where(function($query) use ($seasonos){
                                    if(count($seasonos))
                                    {
                                        $query->orWhereIn('season_code', $seasonos);
                                    }
                                })
                                ->where(function($query) use ($stylenoos,$ukstylenoos){
                                    if(count($stylenoos))
                                    {
                                        $query->orWhereIn('ce_style_ref', $stylenoos);
                                        $query->where('zone', 'CE');
                                    }
                                    if(count($ukstylenoos))
                                    {
                                        $query->orWhereIn('uk_style_ref', $ukstylenoos);
                                        $query->where('zone', 'UK');
                                    }
                                })
                                ->where(function($query) use ($orderstatusos,$orderstatusosref,$artworkstatusos){
                                    if(count($orderstatusosref))
                                    {
                                        $query->orWhereIn('order_status', $orderstatusosref);
                                    }
                                    if(in_array("Pending", $orderstatusos))
                                    {
                                        $query->orWhere('is_order_count_empty', 1);
                                    }
                                    if(count($artworkstatusos))
                                    {
                                        $query->orWhereIn('order_status', $artworkstatusos);
                                    }
                                })
                                ->where(function($query) use ($postatusos){
                                    if(count($postatusos))
                                    {
                                        $query->orWhereIn('po_status', $postatusos);
                                    }
                                })
                                ->where(function($query) use ($pistatusos){
                                    if(count($pistatusos))
                                    {
                                        $query->orWhereIn('pi_status', $pistatusos);
                                    }
                                })
                                ->select(DB::raw("'".$actions."'".' as "StyleNo"'),'ocn_number','tesco_tsl1_orders.season_code','tesco_tsl1_orders.uk_style_ref','tesco_tsl1_orders.ce_style_ref','tesco_tsl1_orders.po_number as BuyerPO','tesco_tsl1_orders.zone as Zone',DB::raw("'".$tsl1."'".' as "TemplateName"'),'tesco_tsl1_orders.template_type as Template Type','customers.id as customer_id','tesco_tsl1_orders.created_at as OrderDate',DB::raw('to_char(tesco_tsl1_orders.delivery_date,'."'".$ddmmyy."'".') as "DeliveryDate"'),'tesco_tsl1_orders.order_status as order_status','tesco_template.template_file',DB::raw("'".$actions."'".' as "Artwork"'), 'tesco_tsl1_orders.order_status as artwork_status','tesco_tsl1_orders.pi_status as PI Status','tesco_tsl1_orders.po_status as PO Status','tesco_tsl1_orders.purchase_order_no as PO Number',DB::raw("'".$tesco_tsl1_orders."'".' as "tableName"'),'tesco_tsl1_orders.id as tableID','tesco_tsl1_orders.po_file','customers.pi_required','customers.po_required','tesco_tsl1_orders.template_id','tesco_tsl1_orders.order_flag','customers.delivery_day_count','is_order_count_empty');
    
                $tsl3 = "TSL 3";
                $tesco_tsl3_orders = "tesco_tsl3_orders";
                $tesco_tsl3_orders = DB::table("tesco_tsl3_orders")
                ->join('customers','tesco_tsl3_orders.tesco_supplier_id','=','customers.id')->join('tesco_template','tesco_tsl3_orders.template_id','=','tesco_template.item_id')
                                ->whereBetween('tesco_tsl3_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                                ->where('tesco_tsl3_orders.tesco_supplier_id', $customer_role_id->company_id)
                                ->where('tesco_tsl3_orders.order_placed_by','=',Auth::guard('web_customer')->id())
                                ->where(function($query) use ($seasonos){
                                    if(count($seasonos))
                                    {
                                        $query->orWhereIn('season_code', $seasonos);
                                    }
                                })
                                ->where(function($query) use ($stylenoos,$ukstylenoos){
                                    if(count($stylenoos))
                                    {
                                        $query->orWhereIn('ce_style_ref', $stylenoos);
                                        $query->where('zone', 'CE');
                                    }
                                    if(count($ukstylenoos))
                                    {
                                        $query->orWhereIn('uk_style_ref', $ukstylenoos);
                                        $query->where('zone', 'UK');
                                    }
                                })
                                ->where(function($query) use ($orderstatusos,$orderstatusosref,$artworkstatusos){
                                    if(count($orderstatusosref))
                                    {
                                        $query->orWhereIn('order_status', $orderstatusosref);
                                    }
                                    if(in_array("Pending", $orderstatusos))
                                    {
                                        $query->orWhere('is_order_count_empty', 1);
                                    }
                                    if(count($artworkstatusos))
                                    {
                                        $query->orWhereIn('order_status', $artworkstatusos);
                                    }
                                })
                                ->where(function($query) use ($postatusos){
                                    if(count($postatusos))
                                    {
                                        $query->orWhereIn('po_status', $postatusos);
                                    }
                                })
                                ->where(function($query) use ($pistatusos){
                                    if(count($pistatusos))
                                    {
                                        $query->orWhereIn('pi_status', $pistatusos);
                                    }
                                })
                                ->select(DB::raw("'".$actions."'".' as "StyleNo"'),'ocn_number','tesco_tsl3_orders.season_code','tesco_tsl3_orders.uk_style_ref','tesco_tsl3_orders.ce_style_ref','tesco_tsl3_orders.po_number as BuyerPO','tesco_tsl3_orders.zone as Zone',DB::raw("'".$tsl3."'".' as "TemplateName"'),'tesco_tsl3_orders.template_type as Template Type','customers.id as customer_id','tesco_tsl3_orders.created_at as OrderDate',DB::raw('to_char(tesco_tsl3_orders.delivery_date,'."'".$ddmmyy."'".') as "DeliveryDate"'),'tesco_tsl3_orders.order_status as order_status','tesco_template.template_file',DB::raw("'".$actions."'".' as "Artwork"'), 'tesco_tsl3_orders.order_status as artwork_status','tesco_tsl3_orders.pi_status as PI Status','tesco_tsl3_orders.po_status as PO Status','tesco_tsl3_orders.purchase_order_no as PO Number',DB::raw("'".$tesco_tsl3_orders."'".' as "tableName"'),'tesco_tsl3_orders.id as tableID','tesco_tsl3_orders.po_file','customers.pi_required','customers.po_required','tesco_tsl3_orders.template_id','tesco_tsl3_orders.order_flag','customers.delivery_day_count','is_order_count_empty');
    
                $thl1 = "THL 1";
                $tesco_thl1_orders = "tesco_thl1_orders";
                $tesco_thl1_orders = DB::table("tesco_thl1_orders")
                ->join('customers','tesco_thl1_orders.tesco_supplier_id','=','customers.id')->join('tesco_template','tesco_thl1_orders.template_id','=','tesco_template.item_id')
                                ->whereBetween('tesco_thl1_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                                ->where('tesco_thl1_orders.tesco_supplier_id', $customer_role_id->company_id)
                                ->where('tesco_thl1_orders.order_placed_by','=',Auth::guard('web_customer')->id())
                                ->where(function($query) use ($seasonos){
                                    if(count($seasonos))
                                    {
                                        $query->orWhereIn('season_code', $seasonos);
                                    }
                                })
                                ->where(function($query) use ($stylenoos,$ukstylenoos){
                                    if(count($stylenoos))
                                    {
                                        $query->orWhereIn('ce_style_ref', $stylenoos);
                                        $query->where('zone', 'CE');
                                    }
                                    if(count($ukstylenoos))
                                    {
                                        $query->orWhereIn('uk_style_ref', $ukstylenoos);
                                        $query->where('zone', 'UK');
                                    }
                                })
                                ->where(function($query) use ($orderstatusos,$orderstatusosref,$artworkstatusos){
                                    if(count($orderstatusosref))
                                    {
                                        $query->orWhereIn('order_status', $orderstatusosref);
                                    }
                                    if(in_array("Pending", $orderstatusos))
                                    {
                                        $query->orWhere('is_order_count_empty', 1);
                                    }
                                    if(count($artworkstatusos))
                                    {
                                        $query->orWhereIn('order_status', $artworkstatusos);
                                    }
                                })
                                ->where(function($query) use ($postatusos){
                                    if(count($postatusos))
                                    {
                                        $query->orWhereIn('po_status', $postatusos);
                                    }
                                })
                                ->where(function($query) use ($pistatusos){
                                    if(count($pistatusos))
                                    {
                                        $query->orWhereIn('pi_status', $pistatusos);
                                    }
                                })
                                ->select(DB::raw("'".$actions."'".' as "StyleNo"'),'ocn_number','tesco_thl1_orders.season_code','tesco_thl1_orders.uk_style_ref','tesco_thl1_orders.ce_style_ref','tesco_thl1_orders.po_number as BuyerPO','tesco_thl1_orders.zone as Zone',DB::raw("'".$thl1."'".' as "TemplateName"'),'tesco_thl1_orders.template_type as Template Type','customers.id as customer_id','tesco_thl1_orders.created_at as OrderDate',DB::raw('to_char(tesco_thl1_orders.delivery_date,'."'".$ddmmyy."'".') as "DeliveryDate"'),'tesco_thl1_orders.order_status as order_status','tesco_template.template_file',DB::raw("'".$actions."'".' as "Artwork"'), 'tesco_thl1_orders.order_status as artwork_status','tesco_thl1_orders.pi_status as PI Status','tesco_thl1_orders.po_status as PO Status','tesco_thl1_orders.purchase_order_no as PO Number',DB::raw("'".$tesco_thl1_orders."'".' as "tableName"'),'tesco_thl1_orders.id as tableID','tesco_thl1_orders.po_file','customers.pi_required','customers.po_required','tesco_thl1_orders.template_id','tesco_thl1_orders.order_flag','customers.delivery_day_count','is_order_count_empty');
    
                $thl3 = "THL 3";
                $tesco_thl3_orders = "tesco_thl3_orders";
                $orders = DB::table("tesco_thl3_orders")
                ->join('customers','tesco_thl3_orders.tesco_supplier_id','=','customers.id')->join('tesco_template','tesco_thl3_orders.template_id','=','tesco_template.item_id')
                            ->whereBetween('tesco_thl3_orders.created_at', [$date_start.' 00:00:00', $date_end.' 23:59:59'])
                            ->where('tesco_thl3_orders.tesco_supplier_id', $customer_role_id->company_id)
                            ->where('tesco_thl3_orders.order_placed_by','=',Auth::guard('web_customer')->id())
                            ->where(function($query) use ($seasonos){
                                if(count($seasonos))
                                {
                                    $query->orWhereIn('season_code', $seasonos);
                                }
                            })
                            ->where(function($query) use ($stylenoos,$ukstylenoos){
                                if(count($stylenoos))
                                {
                                    $query->orWhereIn('ce_style_ref', $stylenoos);
                                    $query->where('zone', 'CE');
                                }
                                if(count($ukstylenoos))
                                {
                                    $query->orWhereIn('uk_style_ref', $ukstylenoos);
                                    $query->where('zone', 'UK');
                                }
                            })
                            ->where(function($query) use ($orderstatusos,$orderstatusosref,$artworkstatusos){
                                if(count($orderstatusosref))
                                {
                                    $query->orWhereIn('order_status', $orderstatusosref);
                                }
                                if(in_array("Pending", $orderstatusos))
                                {
                                    $query->orWhere('is_order_count_empty', 1);
                                }
                                if(count($artworkstatusos))
                                {
                                    $query->orWhereIn('order_status', $artworkstatusos);
                                }
                            })
                            ->where(function($query) use ($postatusos){
                                if(count($postatusos))
                                {
                                    $query->orWhereIn('po_status', $postatusos);
                                }
                            })
                            ->where(function($query) use ($pistatusos){
                                if(count($pistatusos))
                                {
                                    $query->orWhereIn('pi_status', $pistatusos);
                                }
                            })
                            ->select(DB::raw("'".$actions."'".' as "StyleNo"'),'ocn_number','tesco_thl3_orders.season_code','tesco_thl3_orders.uk_style_ref','tesco_thl3_orders.ce_style_ref','tesco_thl3_orders.po_number as BuyerPO','tesco_thl3_orders.zone as Zone',DB::raw("'".$thl3."'".' as "TemplateName"'),'tesco_thl3_orders.template_type as Template Type','customers.id as customer_id','tesco_thl3_orders.created_at as OrderDate',DB::raw('to_char(tesco_thl3_orders.delivery_date,'."'".$ddmmyy."'".') as "DeliveryDate"'),'tesco_thl3_orders.order_status as order_status','tesco_template.template_file',DB::raw("'".$actions."'".' as "Artwork"'), 'tesco_thl3_orders.order_status as artwork_status','tesco_thl3_orders.pi_status as PI Status','tesco_thl3_orders.po_status as PO Status','tesco_thl3_orders.purchase_order_no as PO Number',DB::raw("'".$tesco_thl3_orders."'".' as "tableName"'),'tesco_thl3_orders.id as tableID','tesco_thl3_orders.po_file','customers.pi_required','customers.po_required','tesco_thl3_orders.template_id','tesco_thl3_orders.order_flag','customers.delivery_day_count','is_order_count_empty')
                            ->union($tesco_tcl1_orders)
                            ->union($tesco_tcl2_orders)
                            ->union($tesco_tcl4_orders)
                            ->union($tesco_tsl1_orders)
                            ->union($tesco_tsl3_orders)
                            ->union($tesco_thl1_orders)
                            ->get();
            }
            

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
   * Print Preview For Stickers
   *
   * @return pdf
   */
  
	public function printDivTesco(Request $request)
	{
        $id = $request['id'];
		$name = $request['name'];
		
		if($name == "TCL1-sticker"){
			$table = "tesco_tcl1_orders";
			$qty_table = "tesco_tcl1_orders_quanty";
			$table_id = "tcl1_order_id";
		}
		else if($name == "TCL2-sticker"){
			
			$table = "tesco_tcl2_orders";
			$qty_table = "tesco_tcl2_orders_quanty";
			$table_id = "tcl2_order_id";
		}
		else if($name == "TCL4-sticker"){
			
			$table = "tesco_tcl4_orders";
			$qty_table = "tesco_tcl4_orders_quanty";
			$table_id = "tcl4_order_id";
		}
		else if($name == "THL1-sticker"){
			
			$table = "tesco_thl1_orders";
			$qty_table = "tesco_thl1_orders_quanty";
			$table_id = "thl1_order_id";
		}
		else if($name == "THL3-sticker"){
			
			$table = "tesco_thl3_orders";
			$qty_table = "tesco_thl3_orders_quanty";
			$table_id = "thl3_order_id";
		}
		else if($name == "TSL1-sticker"){
			
			$table = "tesco_tsl1_orders";
			$qty_table = "tesco_tsl1_orders_quanty";
			$table_id = "tsl1_order_id";
		}
		else if($name == "TSL3-sticker"){
			
			$table = "tesco_tsl3_orders";
			$qty_table = "tesco_tsl3_orders_quanty";
			$table_id = "tsl3_order_id";
		}
		
		
        $order_details  = 	DB::table($table)
                            ->join('customers',$table.'.tesco_supplier_id','=','customers.id')
                            ->where($table.'.id',$id)
                            ->select('*')
                            ->get();
                                    
		 $customer_name = $order_details[0]->short_name;
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
                            if($order_details{0}->template_id == 28)
                            {
                                $yy += 77;
                            }
                            else
                            {
                                $yy += 100; 
                            }
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
        $id = $request['id'];
		$name = $request['name'];
		
		if($name == "TCL1-sticker"){
			$table = "tesco_tcl1_orders";
			$qty_table = "tesco_tcl1_orders_quanty";
			$table_id = "tcl1_order_id";
		}
		else if($name == "TCL2-sticker"){
			
			$table = "tesco_tcl2_orders";
			$qty_table = "tesco_tcl2_orders_quanty";
			$table_id = "tcl2_order_id";
		}
		else if($name == "TCL4-sticker"){
			
			$table = "tesco_tcl4_orders";
			$qty_table = "tesco_tcl4_orders_quanty";
			$table_id = "tcl4_order_id";
		}
		else if($name == "THL1-sticker"){
			
			$table = "tesco_thl1_orders";
			$qty_table = "tesco_thl1_orders_quanty";
			$table_id = "thl1_order_id";
		}
		else if($name == "THL3-sticker"){
			
			$table = "tesco_thl3_orders";
			$qty_table = "tesco_thl3_orders_quanty";
			$table_id = "thl3_order_id";
		}
		else if($name == "TSL1-sticker"){
			
			$table = "tesco_tsl1_orders";
			$qty_table = "tesco_tsl1_orders_quanty";
			$table_id = "tsl1_order_id";
		}
		else if($name == "TSL3-sticker"){
			
			$table = "tesco_tsl3_orders";
			$qty_table = "tesco_tsl3_orders_quanty";
			$table_id = "tsl3_order_id";
		}
		
		
        $order_details  = 	DB::table($table)
                            ->join('customers',$table.'.tesco_supplier_id','=','customers.id')
                            ->where($table.'.id',$id)
                            ->select('*')
                            ->get();
                                    
		 $customer_name = $order_details[0]->short_name;
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
     * method to set the PO number for an order
     * @param Request $request [description]
     */
    public function setPONumber(Request $request)
    {
        try
        {
            $po_number   = $request->po_number;
            $template_id = $request->id;
            $table_name  = $request->tableName;
            $po_details = DB::table($table_name)->where('id',$template_id)->select('po_file','purchase_order_no')->first();
            
            $data_array = [];
            $data_array['id'] = $request->id;
            $data_array['table_name'] = $request->tableName;
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
            
            // update po upload status in order history table
            $po_upload_status = $po_status === "PO Pending"?0:1;
            OrderManagementHistory::where([['order_table_name', $table_name], ['order_table_id', $template_id]])->update([
                'po_status' => $po_upload_status,
                'customer_po' => $po_number,
            ]);

            if ($po_status == "PO Uploaded") {
                /** @var array [Pass array of values to updateTescoErpOrders function] */
                $data_array_2 = [];
                $data_array_2['id'] = $template_id;
                $data_array_2['tableName'] = $table_name;
                $data_array_2['buyer_id'] = $request->buyer_id;
                $data_array_2['created_by'] = Auth::guard('web_customer')->id();
				
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
                return response()->json(['error'=> false,'message' =>'Purchase Order Number added successfully','po_status' => $po_status],200);
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
     * @return [type]           [description]
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

            // call PI generation function
            $pi_number = $pi_obj->piNumberGenerator();

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
                    'template_type'  => $data['bank_details']->template_type,
                    'revision_no'  => $data['item_details'][0]->pi_rev_no,
                    'rep_order_id'     => $rep_order_id,
                    'pi_file'         => $attachment,
                    'pi_number'       => $pi_number
                    
                ]);
                foreach ($data['item_details'] as $key => $value)
                {
                    $unit_cost = $value->price;
                    $tax = $value->tax;
                    $total_qnty+= $value->quantity;
                    $sub_total += $value->quantity *$unit_cost;
                    ProfomaInvoiceItemDetails::create([
                        'pi_id'     => $pi_details->id,
                        'material'  => $value->material_name,
                        'size'      => $value->size,
                        'template_id' => $value->item_id,
                        'ref_no'      => $value->ref_no,
                        'style'     => $value->style,
                        'quantity'  => $value->quantity,
                        'unit_cost' => $unit_cost,
                        'tax'       => $tax
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
                        $approved_data   =   DB::table($table)
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
                    $erpOrders['created_by']= Auth::guard('web_customer')->id();
                    $customer_data  = DB::table('customers')->where('id',$data['bank_details']->id)->first();
                    if ($customer_data->pi_required == 1 && $customer_data->po_required== 0) {
                        $this->erp_obj->updateTescoErpOrders($erpOrders);
                    }

                    // update PI approve status as "PI Approved" in order history table
                    OrderManagementHistory::where([['order_table_name', $table], ['order_table_id', $tableID]])->update([
                    'pi_status'   => 1
                    ]);
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
                $erpOrders['created_by']= Auth::guard('web_customer')->id(); 
                $customer_data  = DB::table('customers')->where('id',$data['bank_details']->id)->first();

                if ($customer_data->pi_required == 1 && $customer_data->po_required== 0) {
                    $this->erp_obj->updateTescoErpOrders($erpOrders);
                }

                // update PI approve status as "PI Approved" in order history table
                OrderManagementHistory::where([['order_table_name', $table_name], ['order_table_id', $row_id]])->update([
                    'pi_status'   => 1
                ]);
            }
            if ($approved_data)
            {
               $result = response()->json(['error'=> false,'message' =>'success'],200);
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
                          'pi_rev_no' => $table_data->pi_rev_no + 1
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
			$customer_id = Auth::guard('web_customer')->user()->id;
			$customer_role_id = DB::table('customer_profile')->select('*')->where('id',$customer_id)->first();
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
	  		if($customer_role_id->role_id == 1)
		    {
				$data['tcl1'] = DB::table('tesco_tcl1_orders')
                                ->join('tesco_item_master','tesco_tcl1_orders.template_id','=','tesco_item_master.id')
                                ->join('customers','tesco_tcl1_orders.tesco_supplier_id','=','customers.id')
                                ->where('customers.po_required',1) 
    					        ->whereNotIn('tesco_tcl1_orders.order_status',['Saved','Order Cancelled'])
    							->where('tesco_tcl1_orders.tesco_supplier_id', $customer_role_id->company_id)
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
    							->where('tesco_tcl2_orders.tesco_supplier_id', $customer_role_id->company_id)
                                ->select('tesco_tcl2_orders.*',DB::raw("'".$tcl2."'".' as "TemplateName"'),DB::raw("'".$tesco_tcl2_orders."'".' as "tableName"'),'tesco_item_master.company_id')
                                ->where("zone",$zone)
                                ->where($col,$style_num)
                                ->get();
            $data['tcl4'] = DB::table('tesco_tcl4_orders')
                                ->join('tesco_item_master','tesco_tcl4_orders.template_id','=','tesco_item_master.id')
                                ->join('customers','tesco_tcl4_orders.tesco_supplier_id','=','customers.id')
                                ->where('customers.po_required',1) 
    				            ->whereNotIn('tesco_tcl4_orders.order_status',['Saved','Order Cancelled'])
    				            ->where('tesco_tcl4_orders.tesco_supplier_id', $customer_role_id->company_id)
                                ->where('tesco_tcl4_orders.is_order_count_empty',0)
                                ->select('tesco_tcl4_orders.*',DB::raw("'".$tcl4."'".' as "TemplateName"'),DB::raw("'".$tesco_tcl4_orders."'".' as "tableName"'),'tesco_item_master.company_id')
                                ->where("zone",$zone)
                                ->where($col,$style_num)
                                ->get();
            $data['thl1'] = DB::table('tesco_thl1_orders')
                                ->join('tesco_item_master','tesco_thl1_orders.template_id','=','tesco_item_master.id')
                                ->join('customers','tesco_thl1_orders.tesco_supplier_id','=','customers.id')
                                ->where('customers.po_required',1) 
    				            ->whereNotIn('tesco_thl1_orders.order_status',['Saved','Order Cancelled'])
    							 ->where('tesco_thl1_orders.tesco_supplier_id', $customer_role_id->company_id)
                                ->where('tesco_thl1_orders.is_order_count_empty',0)
                                ->select('tesco_thl1_orders.*',DB::raw("'".$thl1."'".' as "TemplateName"'),DB::raw("'".$tesco_thl1_orders."'".' as "tableName"'),'tesco_item_master.company_id')
                                ->where("zone",$zone)
                                ->where($col,$style_num)
                                ->get();
            $data['thl3'] = DB::table('tesco_thl3_orders')
                                ->join('tesco_item_master','tesco_thl3_orders.template_id','=','tesco_item_master.id')
                                ->join('customers','tesco_thl3_orders.tesco_supplier_id','=','customers.id')
                                ->where('customers.po_required',1) 
    				            ->whereNotIn('tesco_thl3_orders.order_status',['Saved','Order Cancelled'])
    				            ->where('tesco_thl3_orders.tesco_supplier_id', $customer_role_id->company_id)
                                ->where('tesco_thl3_orders.is_order_count_empty',0)
                                ->select('tesco_thl3_orders.*',DB::raw("'".$thl3."'".' as "TemplateName"'),DB::raw("'".$tesco_thl3_orders."'".' as "tableName"'),'tesco_item_master.company_id')
                                ->where("zone",$zone)
                                ->where($col,$style_num)
                                ->get();
            $data['tsl1'] = DB::table('tesco_tsl1_orders')
                                ->join('tesco_item_master','tesco_tsl1_orders.template_id','=','tesco_item_master.id')
                                ->join('customers','tesco_tsl1_orders.tesco_supplier_id','=','customers.id')
                                ->where('customers.po_required',1) 
    				            ->whereNotIn('tesco_tsl1_orders.order_status',['Saved','Order Cancelled'])
    				             ->where('tesco_tsl1_orders.tesco_supplier_id', $customer_role_id->company_id)
                                ->where('tesco_tsl1_orders.is_order_count_empty',0)
                                ->select('tesco_tsl1_orders.*',DB::raw("'".$tsl1."'".' as "TemplateName"'),DB::raw("'".$tesco_tsl1_orders."'".' as "tableName"'),'tesco_item_master.company_id')
                                ->where("zone",$zone)
                                ->where($col,$style_num)
                                ->get();
            $data['tsl3'] = DB::table('tesco_tsl3_orders')
                                ->join('tesco_item_master','tesco_tsl3_orders.template_id','=','tesco_item_master.id')
                                ->join('customers','tesco_tsl3_orders.tesco_supplier_id','=','customers.id')
                                ->where('customers.po_required',1)                            
    				            ->whereNotIn('tesco_tsl3_orders.order_status',['Saved','Order Cancelled'])
    							->where('tesco_tsl3_orders.tesco_supplier_id', $customer_role_id->company_id)
                                ->where('tesco_tsl3_orders.is_order_count_empty',0)
                                ->select('tesco_tsl3_orders.*',DB::raw("'".$tsl3."'".' as "TemplateName"'),DB::raw("'".$tesco_tsl3_orders."'".' as "tableName"'),'tesco_item_master.company_id')
                                ->where("zone",$zone)
                                ->where($col,$style_num)
                                ->get();
			}
			else
			{
				$data['tcl1'] = DB::table('tesco_tcl1_orders')
                            ->join('tesco_item_master','tesco_tcl1_orders.template_id','=','tesco_item_master.id')
                            ->join('customers','tesco_tcl1_orders.tesco_supplier_id','=','customers.id')
                            ->where('customers.po_required',1)
					        ->where('tesco_tcl1_orders.order_status', '!=', 'Saved')
							->where('tesco_tcl1_orders.tesco_supplier_id', $customer_role_id->company_id)
							->where('tesco_tcl1_orders.order_placed_by','=',Auth::guard('web_customer')->id())
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
				            ->where('tesco_tcl2_orders.order_status', '!=', 'Saved')
							->where('tesco_tcl2_orders.tesco_supplier_id', $customer_role_id->company_id)
							->where('tesco_tcl2_orders.order_placed_by','=',Auth::guard('web_customer')->id())
                            ->select('tesco_tcl2_orders.*',DB::raw("'".$tcl2."'".' as "TemplateName"'),DB::raw("'".$tesco_tcl2_orders."'".' as "tableName"'),'tesco_item_master.company_id')
                            ->where("zone",$zone)
                            ->where($col,$style_num)
                            ->get();
            $data['tcl4'] = DB::table('tesco_tcl4_orders')
                            ->join('tesco_item_master','tesco_tcl4_orders.template_id','=','tesco_item_master.id')
                            ->join('customers','tesco_tcl4_orders.tesco_supplier_id','=','customers.id')
                            ->where('customers.po_required',1)
				            ->where('tesco_tcl4_orders.order_status', '!=', 'Saved')
				            ->where('tesco_tcl4_orders.tesco_supplier_id', $customer_role_id->company_id)
							->where('tesco_tcl4_orders.order_placed_by','=',Auth::guard('web_customer')->id())
                            ->where('tesco_tcl4_orders.is_order_count_empty',0)
                            ->select('tesco_tcl4_orders.*',DB::raw("'".$tcl4."'".' as "TemplateName"'),DB::raw("'".$tesco_tcl4_orders."'".' as "tableName"'),'tesco_item_master.company_id')
                            ->where("zone",$zone)
                            ->where($col,$style_num)
                            ->get();
            $data['thl1'] = DB::table('tesco_thl1_orders')
                            ->join('tesco_item_master','tesco_thl1_orders.template_id','=','tesco_item_master.id')
                            ->join('customers','tesco_thl1_orders.tesco_supplier_id','=','customers.id')
                            ->where('customers.po_required',1)
				            ->where('tesco_thl1_orders.order_status', '!=', 'Saved')
							->where('tesco_thl1_orders.tesco_supplier_id', $customer_role_id->company_id)
							->where('tesco_thl1_orders.order_placed_by','=',Auth::guard('web_customer')->id())
                            ->where('tesco_thl1_orders.is_order_count_empty',0)
                            ->select('tesco_thl1_orders.*',DB::raw("'".$thl1."'".' as "TemplateName"'),DB::raw("'".$tesco_thl1_orders."'".' as "tableName"'),'tesco_item_master.company_id')
                            ->where("zone",$zone)
                            ->where($col,$style_num)
                            ->get();
            $data['thl3'] = DB::table('tesco_thl3_orders')
                            ->join('tesco_item_master','tesco_thl3_orders.template_id','=','tesco_item_master.id')
                            ->join('customers','tesco_thl3_orders.tesco_supplier_id','=','customers.id')
                            ->where('customers.po_required',1)
				            ->where('tesco_thl3_orders.order_status', '!=', 'Saved')
				            ->where('tesco_thl3_orders.tesco_supplier_id', $customer_role_id->company_id)
							->where('tesco_thl3_orders.order_placed_by','=',Auth::guard('web_customer')->id())
                            ->where('tesco_thl3_orders.is_order_count_empty',0)
                            ->select('tesco_thl3_orders.*',DB::raw("'".$thl3."'".' as "TemplateName"'),DB::raw("'".$tesco_thl3_orders."'".' as "tableName"'),'tesco_item_master.company_id')
                            ->where("zone",$zone)
                            ->where($col,$style_num)
                            ->get();
            $data['tsl1'] = DB::table('tesco_tsl1_orders')
                            ->join('tesco_item_master','tesco_tsl1_orders.template_id','=','tesco_item_master.id')
                            ->join('customers','tesco_tsl1_orders.tesco_supplier_id','=','customers.id')
                            ->where('customers.po_required',1)
				            ->where('tesco_tsl1_orders.order_status', '!=', 'Saved')
				            ->where('tesco_tsl1_orders.tesco_supplier_id', $customer_role_id->company_id)
							->where('tesco_tsl1_orders.order_placed_by','=',Auth::guard('web_customer')->id())
                            ->where('tesco_tsl1_orders.is_order_count_empty',0)
                            ->select('tesco_tsl1_orders.*',DB::raw("'".$tsl1."'".' as "TemplateName"'),DB::raw("'".$tesco_tsl1_orders."'".' as "tableName"'),'tesco_item_master.company_id')
                            ->where("zone",$zone)
                            ->where($col,$style_num)
                            ->get();
            $data['tsl3'] = DB::table('tesco_tsl3_orders')
                            ->join('tesco_item_master','tesco_tsl3_orders.template_id','=','tesco_item_master.id')
                            ->join('customers','tesco_tsl3_orders.tesco_supplier_id','=','customers.id')
                            ->where('customers.po_required',1)
				            ->where('tesco_tsl3_orders.order_status', '!=', 'Saved')
				            ->where('tesco_tsl3_orders.tesco_supplier_id', $customer_role_id->company_id)
							->where('tesco_tsl3_orders.order_placed_by','=',Auth::guard('web_customer')->id())
                            ->where('tesco_tsl3_orders.is_order_count_empty',0)
                            ->select('tesco_tsl3_orders.*',DB::raw("'".$tsl3."'".' as "TemplateName"'),DB::raw("'".$tesco_tsl3_orders."'".' as "tableName"'),'tesco_item_master.company_id')
                            ->where("zone",$zone)
                            ->where($col,$style_num)
                            ->get();
			}
            
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
            /** [ Creating new object for ApproveTescoOrders() class ] */
            $approve_order_obj = new ApproveTescoOrders();
            $buyer_id = DB::table("buyers")->where('buyer_name','ilike','Tesco')->pluck('id')->first();
            $id  =  explode(',',$request->id);
            $data_array = [];
            
            foreach($id as $val)
            {
                $value    =  explode('-',$val);
                $data_array['table_name'] = $value[0];
                $data_array['table_id'] = $value[1];
                
                /** [ Invoking approveTescoOrderMethod function using class instance ] */
               $approved_order = $approve_order_obj->approveTescoOrderMethod($data_array, $buyer_id);
            }
           
            if($approved_order == "approved")
            {
                $result = response()->json(['error'=> false,'message' =>'success'],200);
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
            
            $customer_id = Auth::guard('web_customer')->user()->id;
            $customer_role_id = DB::table('customer_profile')->select('*')->where('id',$customer_id)->first();
            if($customer_role_id->role_id == 1)
		    {

                $data['tcl1'] = DB::table('tesco_tcl1_orders')
                                ->join('tesco_item_master','tesco_tcl1_orders.template_id','=','tesco_item_master.id')
                                ->join('customers','tesco_tcl1_orders.tesco_supplier_id','=','customers.id')
                                ->where('customers.po_required',1) 
                                ->whereNotIn('tesco_tcl1_orders.order_status',['Saved','Order Cancelled'])
    							->where('tesco_tcl1_orders.tesco_supplier_id', $customer_role_id->company_id)
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
    							->where('tesco_tcl2_orders.tesco_supplier_id', $customer_role_id->company_id)
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
    							->where('tesco_tcl4_orders.tesco_supplier_id', $customer_role_id->company_id)
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
    							->where('tesco_thl1_orders.tesco_supplier_id', $customer_role_id->company_id)
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
    							->where('tesco_thl3_orders.tesco_supplier_id', $customer_role_id->company_id)
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
    							->where('tesco_tsl1_orders.tesco_supplier_id', $customer_role_id->company_id)
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
    							->where('tesco_tsl3_orders.tesco_supplier_id', $customer_role_id->company_id)
                                ->select('tesco_tsl3_orders.*',DB::raw("'".$tsl3."'".' as "TemplateName"'),DB::raw("'".$tesco_tsl3_orders."'".' as "tableName"'),'tesco_item_master.company_id')
                                ->where("zone",$zone)
                                ->where($col,$style_num)
                                ->get();
            }
            else
            {

                $data['tcl1'] = DB::table('tesco_tcl1_orders')
                                ->join('tesco_item_master','tesco_tcl1_orders.template_id','=','tesco_item_master.id')
                                ->join('customers','tesco_tcl1_orders.tesco_supplier_id','=','customers.id')
                                ->where('customers.po_required',1) 
                                ->whereNotIn('tesco_tcl1_orders.order_status',['Saved','Order Cancelled'])
                                ->where('tesco_tcl1_orders.tesco_supplier_id', $customer_role_id->company_id)
							    ->where('tesco_tcl1_orders.order_placed_by','=',Auth::guard('web_customer')->id())
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
                                ->where('tesco_tcl2_orders.tesco_supplier_id', $customer_role_id->company_id)
							    ->where('tesco_tcl2_orders.order_placed_by','=',Auth::guard('web_customer')->id())
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
                                ->where('tesco_tcl4_orders.tesco_supplier_id', $customer_role_id->company_id)
							    ->where('tesco_tcl4_orders.order_placed_by','=',Auth::guard('web_customer')->id())
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
                                ->where('tesco_thl1_orders.tesco_supplier_id', $customer_role_id->company_id)
							    ->where('tesco_thl1_orders.order_placed_by','=',Auth::guard('web_customer')->id())
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
                                ->where('tesco_thl3_orders.tesco_supplier_id', $customer_role_id->company_id)
							    ->where('tesco_thl3_orders.order_placed_by','=',Auth::guard('web_customer')->id())
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
                                ->where('tesco_tsl1_orders.tesco_supplier_id', $customer_role_id->company_id)
							    ->where('tesco_tsl1_orders.order_placed_by','=',Auth::guard('web_customer')->id())
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
                                ->where('tesco_tsl3_orders.tesco_supplier_id', $customer_role_id->company_id)
							    ->where('tesco_tsl3_orders.order_placed_by','=',Auth::guard('web_customer')->id())
                                ->select('tesco_tsl3_orders.*',DB::raw("'".$tsl3."'".' as "TemplateName"'),DB::raw("'".$tesco_tsl3_orders."'".' as "tableName"'),'tesco_item_master.company_id')
                                ->where("zone",$zone)
                                ->where($col,$style_num)
                                ->get();
            }
			
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

                // update po upload status in order history table
                OrderManagementHistory::where([['order_table_name', $value[0]], ['order_table_id', $value[1]]])->update([
                    'po_status' => 1,
                    'customer_po' => $po,
                ]);

                /** @var array [Pass array of values to updateTescoErpOrders function] */
                $data_array = [];   
                $tesco_buyer_id = DB::table('buyers')->select('id')->where('buyer_name','=','Tesco')->first();
                $data_array['tableName'] = $value[0];
                $data_array['buyer_id'] = $tesco_buyer_id->id;
                $data_array['id'] = $value[1];
                $data_array['created_by']= Auth::guard('web_customer')->id(); 
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
                
                // update po upload status in order history table
                $po_upload_status = $status === "PO Pending"?0:1;
                OrderManagementHistory::where([['order_table_name', $value[0]], ['order_table_id', $value[1]]])->update([
                    'po_status' => $po_upload_status,
                    'customer_po' => $po,
                ]);
                
                  if($status === "PO Uploaded")
                  {
                        /** @var array [Pass array of values to updateTescoErpOrders function] */
                        $data_array = [];   
                        $tesco_buyer_id = DB::table('buyers')->select('id')->where('buyer_name','=','Tesco')->first();
                        $data_array['tableName'] = $value[0];
                        $data_array['buyer_id'] = $tesco_buyer_id->id;
                        $data_array['id'] = $value[1];
                        $data_array['created_by']= Auth::guard('web_customer')->id(); 
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
            $order_details       =   DB::table($table_name)->where('id',$row_id)
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
     * 
     * @return boolean values
     */
    public function setTescoOrderStatus(Request $request)
    {
        $orderObj        = new TescoOrders();
        $edit_erp_obj    = new EditErpOrders();
        $tpnd            = explode(',',$request['tpnd']);
        $barcode         = explode(',',$request['barcode']);
        $case_size       = explode(',',$request['size']);
        $count           = explode(',',$request['count']);
        $template_id     = $request['edit_id'];
        $template_name   = $request['template_name'];
        $zone            = $request['zone'];
        $created_by      = Auth::guard('web_customer')->id();
        $qr_code         = explode('|', $request['qr_code']);

        if($template_name == "TCL1")
        {
            $table_name="tesco_tcl1_orders";
            $qty_table_name="tesco_tcl1_orders_quanty";
            $foreign_key="tcl1_order_id";
            $single_size    = $request['single_size'];
        }
        else if($template_name == "TCL2")
        {
            $table_name="tesco_tcl2_orders";
            $qty_table_name="tesco_tcl2_orders_quanty";
            $foreign_key="tcl2_order_id";
            $single_size = $request['single_size'];
        }
        else if($template_name == "TCL4")
        {
            $table_name="tesco_tcl4_orders";
            $qty_table_name="tesco_tcl4_orders_quanty";
            $foreign_key="tcl4_order_id";
            $single_size = explode(',',$request['single_size']);
        }
        else if($template_name == "TSL1")
        {
            $table_name="tesco_tsl1_orders";
            $qty_table_name="tesco_tsl1_orders_quanty";
            $foreign_key="tsl1_order_id";
            $single_size = $request['single_size'];
        }
        else if($template_name == "TSL3")
        {
            $table_name="tesco_tsl3_orders";
            $qty_table_name="tesco_tsl3_orders_quanty";
            $foreign_key="tsl3_order_id";
            $single_size = explode(',',$request['single_size']);
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

        $tesco_main_customer_id = DB::table("customer_metadata")->where('customer_metadata_id','=',$request['supplier'])->select('customer_id')->first();

        if($tesco_main_customer_id)
        {
            $customer_id = $tesco_main_customer_id->customer_id;
        }

        $order_qnty = (array_sum($count)>0)?0:1 ;
        $order_status  = $order_qnty === 1?'Order Pending':'Order Placed';
        $edit_permission  = $order_qnty === 1?1:0;

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
            if ($temp_data->order_flag == "newOrder" && $template_name != "THL3" && $template_name != "THL1") {
                $orderObj->updateTescoOrders($table_name, $temp_data, $request, $customer_id, $created_by);
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
                                'tesco_metadata_supplier_id' => $request['supplier'],
                                'tesco_supplier_id'    => $customer_id,
                                'rfid_compliant'       => $request['rfid'],
                                'tag_at_source'        => $request['tag_at_source'],
                                'is_order_count_empty' => $order_qnty,
                                'ocn_number'           => $request['tesco_ocn'],
                                'order_status'         => $order_status,
                                'edit_permission'      => $edit_permission,
                                'total_qty'            => array_sum($count)
                            ]);
            $order_status_id  = $order_status === 'Order Pending'?6:2;
            // update order data in order management history table 
            OrderManagementHistory::where([['order_table_name', $table_name], ['order_table_id', $template_id]])->update([
                'season'                    => $request['season_code'],
                'customer_id'               => $customer_id,
                'style'                     => $request[strtolower($request['zone']).'_style'],
                'buyer_po'                  => $request['po_number'],
                'ocn_number'                => $request['tesco_ocn'],
                'order_status'              => $order_status_id,
                'order_edit_enabled'        => $edit_permission
            ]);

            DB::table($qty_table_name)->where($foreign_key,$template_id)->delete();
            
            $i=0;
            foreach($tpnd as $value)
            {
                if(($template_name == "TCL1" && $zone=="CE") ||($template_name == "TCL2" && $zone=="CE") || ($template_name == "TSL1" && $zone=="CE") || ($template_name == "THL1" && $zone=="CE"))
                {
                        DB::table($qty_table_name)->insert([
                        'orms_tpn'           => $value,
                        'itf_barcode'        => $barcode[$i],
                        $foreign_key         => $template_id,
                        'cases_per_carton'   => $case_size[$i],
                        'count'              => $count[$i],
                        'size'               => null,
                        'ratio_pack_type'    => $this->ratio_pack_type[$i],
                        'qr_code'           => $qr_code[$i]
                        ]);
                }
                else if(($template_name == "TCL4" && $zone=="CE") || ($template_name == "TSL3" && $zone=="CE") || ($template_name == "THL3" && $zone=="CE"))
                {
                    DB::table($qty_table_name)->insert([
                        'orms_tpn'           => $value,
                        'ean_barcode'        => $barcode[$i],
                        $foreign_key         => $template_id,
                        'case_size'          => $case_size[$i],
                        'count'              => $count[$i],
                        'size'               => $single_size[$i],
                        'qr_code'           => $qr_code[$i]
                        ]);
                }
                else if(($template_name == "TCL4" && $zone=="UK") || ($template_name == "TSL3" && $zone=="UK") || ($template_name == "THL3" && $zone=="UK"))
                {
                    DB::table($qty_table_name)->insert([
                    'tpnd'             => $value,
                    'ean_barcode'      => $barcode[$i],
                    $foreign_key       => $template_id,
                    'case_size'        => $case_size[$i],
                    'count'            => $count[$i],
                    'size'             => $single_size[$i]
                    ]);
                }
                else if(($template_name == "TCL1" && $zone=="UK") ||($template_name == "TCL2" && $zone=="UK") || ($template_name == "TSL1" && $zone=="UK") || ($template_name == "THL1" && $zone=="UK"))
                {
                    DB::table($qty_table_name)->insert([
                        'tpnd'               => $value,
                        'itf_barcode'        => $barcode[$i],
                        $foreign_key        => $template_id,
                        'units_per_carton'   => $case_size[$i],
                        'count'              => $count[$i],
                        'size'               => null,
                        'ratio_pack_type'    => $this->ratio_pack_type[$i]
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

                // pass $table_name, $template_id, $erp_details_status and $erp_order_id
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
        $uk_style       = $request['uk_style'];
        $po_number      = $request['po_number'];
        $ce_style       = $request['ce_style'];
        $zone           = $request['zone'];
        
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
        $template_exists=DB::table($table_name)->where('po_number',$po_number)->where('order_flag','newOrder')->where('id','!=',$template_id)->select('*')->get();

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
            $po_file = $request->file('file');
            $path = $request->file('file')->getRealPath();
            $filename = $request->file('file')->getClientOriginalName();
            $destination_path='/storage/app/public/po-files/';
            $path='storage/app/public/po-files/';
            $Orname = $po_file->getClientOriginalName();
            $filename   = $formartStr_obj->cleanStr($Orname);
            $po_file->move($path,$filename);
            $destination_path_insert=$destination_path.$filename;

            $po_details = DB::table($table_name)->where('id',$row_id)->select('purchase_order_no')->first();

            $data_array = [];
            $data_array['id'] = $request->id;
            $data_array['table_name'] = $request->tableName;
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
            
            // update po upload status in order history table
            $po_upload_status = $po_status === "PO Pending"?0:1;
            OrderManagementHistory::where([['order_table_name', $table_name], ['order_table_id', $row_id]])->update([
                'po_status' => $po_upload_status,
            ]);

            if ($po_status == "PO Uploaded") {
                /** @var array [Pass array of values to updateTescoErpOrders function] */
                $data_array_2 = [];   
                $data_array_2['tableName'] = $table_name;
                $data_array_2['buyer_id'] = $request->buyer_id;
                $data_array_2['id'] = $row_id;
                $data_array_2['created_by']= Auth::guard('web_customer')->id(); 

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
               return response()->json(['error'=> false,'message' =>'success','po_file'=>$destination_path_insert,'po_status' => $po_status],200);
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
     * Get order deatils.
     * @param order details id
     * @author Gowtham <gowtham@wizroots.com>
     * @return \Illuminate\Http\Response
     */
	public function getOrderDetails(Request $request)
	{
		$id = $request['id'];
		$name = $request['name'];
		
		if($name == "TCL1-sticker"){
			$table = "tesco_tcl1_orders";
			$qty_table = "tesco_tcl1_orders_quanty";
			$table_id = "tcl1_order_id";
		}
		else if($name == "TCL2-sticker"){
			
			$table = "tesco_tcl2_orders";
			$qty_table = "tesco_tcl2_orders_quanty";
			$table_id = "tcl2_order_id";
		}
		else if($name == "TCL4-sticker"){
			
			$table = "tesco_tcl4_orders";
			$qty_table = "tesco_tcl4_orders_quanty";
			$table_id = "tcl4_order_id";
		}
		else if($name == "THL1-sticker"){
			
			$table = "tesco_thl1_orders";
			$qty_table = "tesco_thl1_orders_quanty";
			$table_id = "thl1_order_id";
		}
		else if($name == "THL3-sticker"){
			
			$table = "tesco_thl3_orders";
			$qty_table = "tesco_thl3_orders_quanty";
			$table_id = "thl3_order_id";
		}
		else if($name == "TSL1-sticker"){
			
			$table = "tesco_tsl1_orders";
			$qty_table = "tesco_tsl1_orders_quanty";
			$table_id = "tsl1_order_id";
		}
		else if($name == "TSL3-sticker"){
			
			$table = "tesco_tsl3_orders";
			$qty_table = "tesco_tsl3_orders_quanty";
			$table_id = "tsl3_order_id";
		}
		
		
		$order_details  = 	DB::table($qty_table)
									->where($table_id,$id)
									->select('*')
									->get();
		
		foreach($order_details as $value)
		{
            $odetails  = 	DB::table($table)
									->where('id',$id)
									->select('*')
									->get();
			$tesco_item_master=DB::table('tesco_item_master')->where('id',$odetails[0]->template_id)->select('*')->get();
			
			$order_omp  = 	DB::table($table)
				->join('tesco_department',$table.'.tesco_department_id', 'tesco_department.id')
				->join('tesco_brand',$table.'.tesco_brand_id', 'tesco_brand.id')
				->join('tesco_suppliers',$table.'.tesco_metadata_supplier_id', 'tesco_suppliers.id')
									->where($table.'.id',$value->$table_id)
									->select($table.'.*','tesco_department.department','tesco_brand.brand','tesco_suppliers.supplier_name')
									->get();
			
			
			$value->zone=$order_omp{0}->zone;
			$value->po_number=$order_omp{0}->po_number;
			$value->uk_style_ref=$order_omp{0}->uk_style_ref;
			$value->ce_style_ref=$order_omp{0}->ce_style_ref;
			$value->season_code=$order_omp{0}->season_code;
			$value->short_description=$order_omp{0}->short_description;
			$value->eqos_site_code=$order_omp{0}->eqos_site_code;
			$value->manufacture_date=$order_omp{0}->manufacture_date;
			$value->department=$order_omp{0}->department;
			$value->brand=$order_omp{0}->brand;
			$value->supplier_name=$order_omp{0}->supplier_name;
			$value->rfid_compliant=$order_omp{0}->rfid_compliant;
			$value->tag_at_source=$order_omp{0}->tag_at_source;
			
		}
		
		return response()->json(['error'=> true,'order_details' =>$order_details,'tesco_item_master' => $tesco_item_master],200);
    }

    public function downloadTescoPI(Request $request)
   {
        try {
            // $pi_obj     =   new ProfomaInvoice();
            // $data       =   $pi_obj->getTescoPIDetails($request);
            // $attachment =  $pi_obj->getAttachment($data,$request);
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

    /**
     * Approve tesco artwork
     * @param \Illuminate\Http\Request
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function approveTescoArtwork(Request $request)
    {
      try {
              /** [ Creating new object for ApproveTescoOrders() class ] */
              $approve_order_obj = new ApproveTescoOrders();
              $buyer_id = DB::table("buyers")->where('buyer_name','ilike','Tesco')->pluck('id')->first();

              if(sizeof($request) > 0)
              {
                /** [ Invoking approveTescoOrderMethod function using class instance ] */
                $approved_order = $approve_order_obj->approveTescoOrderMethod($request, $buyer_id);
              }
            
              if($approved_order == "approved")
              {
                $result = response()->json(['error'=> false,'message' =>'success'],200);
              }
              else
              {
                $result = response()->json(['error'=> true,'message' =>'Error'],400);
              }

        } catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
    }

}
