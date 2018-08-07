<?php

namespace App\Http\Controllers\Admin\Tesco;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use App\Classes\Modules;
use App\Classes\ProfomaInvoice;
use App\ProfomaInvoice as PI;
use App\ProfomaInvoiceItemDetails;
use Response;
use View;
use DB;
use Excel;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Config;
use App\Classes\TescoOrders;

class OrderStatusCntrl extends Controller
{

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
                            ->where('tesco_tcl1_orders.is_order_count_empty',0)
                            ->where('tesco_tcl1_orders.order_status', '!=', 'Saved')
                            ->select(DB::raw("'".$actions."'".' as "StyleNo"'),'tesco_tcl1_orders.uk_style_ref','tesco_tcl1_orders.ce_style_ref','tesco_tcl1_orders.po_number as BuyerPO','tesco_tcl1_orders.zone as Zone',DB::raw("'".$tcl1."'".' as "TemplateName"'),'tesco_tcl1_orders.template_type as Template Type','customers.id as customer_id','customers.company_name as Company','tesco_tcl1_orders.created_at as OrderDate',DB::raw('to_char(tesco_tcl1_orders.delivery_date,'."'".$ddmmyy."'".') as "DeliveryDate"'),'tesco_tcl1_orders.artwork_status as Artwork Status','tesco_tcl1_orders.pi_status as PI Status','tesco_tcl1_orders.po_status as PO Status','tesco_tcl1_orders.purchase_order_no as PO Number',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'".$tesco_tcl1_orders."'".' as "tableName"'),'tesco_tcl1_orders.id as tableID','tesco_tcl1_orders.po_file','customers.pi_required','tesco_tcl1_orders.template_id','tesco_tcl1_orders.order_flag','customers.delivery_day_count')
                            ->get();
			$tcl2 = "TCL 2";
			$tesco_tcl2_orders = "tesco_tcl2_orders";
            $data['tcl2'] = DB::table('tesco_tcl2_orders')
                            ->join('customers','tesco_tcl2_orders.tesco_supplier_id','=','customers.id')
                            ->where('tesco_tcl2_orders.is_order_count_empty',0)
                            ->where('tesco_tcl2_orders.order_status', '!=', 'Saved')
                            ->select(DB::raw("'".$actions."'".' as "StyleNo"'),'tesco_tcl2_orders.uk_style_ref','tesco_tcl2_orders.ce_style_ref','tesco_tcl2_orders.po_number as BuyerPO','tesco_tcl2_orders.zone as Zone',DB::raw("'".$tcl2."'".' as "TemplateName"'),'tesco_tcl2_orders.template_type as Template Type','customers.id as customer_id','customers.company_name as Company','tesco_tcl2_orders.created_at as OrderDate',DB::raw('to_char(tesco_tcl2_orders.delivery_date,'."'".$ddmmyy."'".') as "DeliveryDate"'),'tesco_tcl2_orders.artwork_status as Artwork Status','tesco_tcl2_orders.pi_status as PI Status','tesco_tcl2_orders.po_status as PO Status','tesco_tcl2_orders.purchase_order_no as PO Number',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'".$tesco_tcl2_orders."'".' as "tableName"'),'tesco_tcl2_orders.id as tableID','tesco_tcl2_orders.po_file','customers.pi_required','tesco_tcl2_orders.template_id','tesco_tcl2_orders.order_flag','customers.delivery_day_count')
                            ->get();
			$tcl4 = "TCL 4";
			$tesco_tcl4_orders = "tesco_tcl4_orders";
            $data['tcl4'] = DB::table('tesco_tcl4_orders')
                            ->join('customers','tesco_tcl4_orders.tesco_supplier_id','=','customers.id')
                            ->where('tesco_tcl4_orders.is_order_count_empty',0)
                            ->where('tesco_tcl4_orders.order_status', '!=', 'Saved')
                            ->select(DB::raw("'".$actions."'".' as "StyleNo"'),'tesco_tcl4_orders.uk_style_ref','tesco_tcl4_orders.ce_style_ref','tesco_tcl4_orders.po_number as BuyerPO','tesco_tcl4_orders.zone as Zone',DB::raw("'".$tcl4."'".' as "TemplateName"'),'tesco_tcl4_orders.template_type as Template Type','customers.id as customer_id','customers.company_name as Company','tesco_tcl4_orders.created_at as OrderDate',DB::raw('to_char(tesco_tcl4_orders.delivery_date,'."'".$ddmmyy."'".') as "DeliveryDate"'),'tesco_tcl4_orders.artwork_status as Artwork Status','tesco_tcl4_orders.pi_status as PI Status','tesco_tcl4_orders.po_status as PO Status','tesco_tcl4_orders.purchase_order_no as PO Number',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'".$tesco_tcl4_orders."'".' as "tableName"'),'tesco_tcl4_orders.id as tableID','tesco_tcl4_orders.po_file','customers.pi_required','tesco_tcl4_orders.template_id','tesco_tcl4_orders.order_flag','customers.delivery_day_count')
                            ->get();
			$thl1 = "THL 1";
			$tesco_thl1_orders = "tesco_thl1_orders";
            $data['thl1'] = DB::table('tesco_thl1_orders')
                            ->join('customers','tesco_thl1_orders.tesco_supplier_id','=','customers.id')
                            ->where('tesco_thl1_orders.is_order_count_empty',0)
                            ->where('tesco_thl1_orders.order_status', '!=', 'Saved')
                            ->select(DB::raw("'".$actions."'".' as "StyleNo"'),'tesco_thl1_orders.uk_style_ref','tesco_thl1_orders.ce_style_ref','tesco_thl1_orders.po_number as BuyerPO','tesco_thl1_orders.zone as Zone',DB::raw("'".$thl1."'".' as "TemplateName"'),'tesco_thl1_orders.template_type as Template Type','customers.id as customer_id','customers.company_name as Company','tesco_thl1_orders.created_at as OrderDate',DB::raw('to_char(tesco_thl1_orders.delivery_date,'."'".$ddmmyy."'".') as "DeliveryDate"'),'tesco_thl1_orders.artwork_status as Artwork Status','tesco_thl1_orders.pi_status as PI Status','tesco_thl1_orders.po_status as PO Status','tesco_thl1_orders.purchase_order_no as PO Number',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'".$tesco_thl1_orders."'".' as "tableName"'),'tesco_thl1_orders.id as tableID','tesco_thl1_orders.po_file','customers.pi_required','tesco_thl1_orders.template_id','tesco_thl1_orders.order_flag','customers.delivery_day_count')
                            ->get();
			$thl3 = "THL 3";
			$tesco_thl3_orders = "tesco_thl3_orders";
            $data['thl3'] = DB::table('tesco_thl3_orders')
                            ->join('customers','tesco_thl3_orders.tesco_supplier_id','=','customers.id')
                            ->where('tesco_thl3_orders.is_order_count_empty',0)
                            ->where('tesco_thl3_orders.order_status', '!=', 'Saved')
                            ->select(DB::raw("'".$actions."'".' as "StyleNo"'),'tesco_thl3_orders.uk_style_ref','tesco_thl3_orders.ce_style_ref','tesco_thl3_orders.po_number as BuyerPO','tesco_thl3_orders.zone as Zone',DB::raw("'".$thl3."'".' as "TemplateName"'),'tesco_thl3_orders.template_type as Template Type','customers.id as customer_id','customers.company_name as Company','tesco_thl3_orders.created_at as OrderDate',DB::raw('to_char(tesco_thl3_orders.delivery_date,'."'".$ddmmyy."'".') as "DeliveryDate"'),'tesco_thl3_orders.artwork_status as Artwork Status','tesco_thl3_orders.pi_status as PI Status','tesco_thl3_orders.po_status as PO Status','tesco_thl3_orders.purchase_order_no as PO Number',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'".$tesco_thl3_orders."'".' as "tableName"'),'tesco_thl3_orders.id as tableID','tesco_thl3_orders.po_file','customers.pi_required','tesco_thl3_orders.template_id','tesco_thl3_orders.order_flag','customers.delivery_day_count')
                            ->get();
			$tsl1 = "TSL 1";
			$tesco_tsl1_orders = "tesco_tsl1_orders";
            $data['tsl1'] = DB::table('tesco_tsl1_orders')
                            ->join('customers','tesco_tsl1_orders.tesco_supplier_id','=','customers.id')
                            ->where('tesco_tsl1_orders.is_order_count_empty',0)
                            ->where('tesco_tsl1_orders.order_status', '!=', 'Saved')
                            ->select(DB::raw("'".$actions."'".' as "StyleNo"'),'tesco_tsl1_orders.uk_style_ref','tesco_tsl1_orders.ce_style_ref','tesco_tsl1_orders.po_number as BuyerPO','tesco_tsl1_orders.zone as Zone',DB::raw("'".$tsl1."'".' as "TemplateName"'),'tesco_tsl1_orders.template_type as Template Type','customers.id as customer_id','customers.company_name as Company','tesco_tsl1_orders.created_at as OrderDate',DB::raw('to_char(tesco_tsl1_orders.delivery_date,'."'".$ddmmyy."'".') as "DeliveryDate"'),'tesco_tsl1_orders.artwork_status as Artwork Status','tesco_tsl1_orders.pi_status as PI Status','tesco_tsl1_orders.po_status as PO Status','tesco_tsl1_orders.purchase_order_no as PO Number',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'".$tesco_tsl1_orders."'".' as "tableName"'),'tesco_tsl1_orders.id as tableID','tesco_tsl1_orders.po_file','customers.pi_required','tesco_tsl1_orders.template_id','tesco_tsl1_orders.order_flag','customers.delivery_day_count')
                            ->get();
			$tsl3 = "TSL 3";
			$tesco_tsl3_orders = "tesco_tsl3_orders";
            $data['tsl3'] = DB::table('tesco_tsl3_orders')
                            ->join('customers','tesco_tsl3_orders.tesco_supplier_id','=','customers.id')
                            ->where('tesco_tsl3_orders.is_order_count_empty',0)
                            ->where('tesco_tsl3_orders.order_status', '!=', 'Saved')
                            ->select(DB::raw("'".$actions."'".' as "StyleNo"'),'tesco_tsl3_orders.uk_style_ref','tesco_tsl3_orders.ce_style_ref','tesco_tsl3_orders.po_number as BuyerPO','tesco_tsl3_orders.zone as Zone',DB::raw("'".$tsl3."'".' as "TemplateName"'),'tesco_tsl3_orders.template_type as Template Type','customers.id as customer_id','customers.company_name as Company','tesco_tsl3_orders.created_at as OrderDate',DB::raw('to_char(tesco_tsl3_orders.delivery_date,'."'".$ddmmyy."'".') as "DeliveryDate"'),'tesco_tsl3_orders.artwork_status as Artwork Status','tesco_tsl3_orders.pi_status as PI Status','tesco_tsl3_orders.po_status as PO Status','tesco_tsl3_orders.purchase_order_no as PO Number',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'".$tesco_tsl3_orders."'".' as "tableName"'),'tesco_tsl3_orders.id as tableID','tesco_tsl3_orders.po_file','customers.pi_required','tesco_tsl3_orders.template_id','tesco_tsl3_orders.order_flag','customers.delivery_day_count')
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
            $po_details = DB::table($table_name)->where('id',$template_id)->select('po_file')->first();
            
            if ($po_details->po_file =="" || $po_details->po_file=="null")
            {
                $po_status = "PO Pending";
            }
            else
            {
                $po_status = "PO Uploaded";
            }

			$data 		 = DB::table($table_name)->where('id',$template_id)
						      ->update([
                                  'purchase_order_no' => $po_number,
                                  'po_status' => $po_status
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
     * method to get the profoma invoice details of an artwork
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getTescoPI(Request $request)
    {
        try
        {   
            $pi_obj    = new ProfomaInvoice();
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
     * method to approve the profoma invoice
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function approveTescoPI(Request $request)
    {
        try
        {
            $row_id     =   $request->id;
            $table_name =   $request->table_name;
            $buyer_id   =   $request->buyer_id;
            $total_qnty =   0 ; 
            $sub_total  =   0 ;
            $pi_obj     =   new ProfomaInvoice();
            $data       =   $pi_obj->getTescoPIIDetails($request);
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
                if ($data['item_details'][0]->order_flag=="reOrder") {
                    $rep_order_id = str_replace(' ', '', $data['item_details'][0]->template_name)."_".$row_id;
                }
                else{
                    $rep_order_id = 0;
                }
                $pi_details = PI::create([
                    'buyer_id'         => $buyer_id,
                    'company_id'       => $data['company_details']->id,
                    'customer_id'      => $data['bank_details']->id,
                    'template_id'      => $data['bank_details']->template_id,
                    'buyer_po_no'      => $data['bank_details']->po_number,
                    'uvc_style_no'     => $style_no,
                    'ref_no'           => $data['bank_details']->ref_no,
                    'shipping_address' => $data['ship_to']->address,
                    'billing_address'  => $data['bill_to']->address,
                    'place_of_supply'  => $data['bank_details']->place_of_supply,
                    'template_type'    => $data['bank_details']->template_type,
                    'revision_no'      => $data['item_details'][0]->pi_rev_no,
                    'rep_order_id'     => $rep_order_id
                    
                ]);
                foreach ($data['item_details'] as $key => $value)
                {
                    $unit_cost = 0;
                    foreach ($data['price_list'] as $i => $val) {
                        if ($value->item_id == $val->item_id) {
                            if($value->quantity <= $val->volume_to) {
                                $unit_cost = $val->price;
                                break;
                            }
                        }
                    }
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
                        'unit_cost'   => $unit_cost
                    ]);
                }
                PI::where('id',$pi_details->id)
                            ->update([
                              'total_quantity'   => $total_qnty,
                              'sub_total'        => $sub_total,
                              'tax'              => $data['item_details'][0]->tax,
                              'bill_amount'      => $sub_total*$data['item_details'][0]->tax
                            ]);
            }

            if ($data['item_details'][0]->order_flag == "newOrder") {
               foreach ($data['tables'] as $table => $vals) {
                    if ($data['bank_details']->zone == "CE") {
                        $approved_data   =   DB::table($table)
                                            ->where($table.'.is_order_count_empty',0)
                                            ->where('ce_style_ref',$style_no)
                                            ->where('order_flag',$data['item_details'][0]->order_flag)
                                            ->where('po_number',$data['bank_details']->po_number)
                                            ->update([
                                               'pi_status' => "PI Approved"
                                            ]);
                    }
                    else {
                        $approved_data   = DB::table($table)
                                            ->where($table.'.is_order_count_empty',0)
                                            ->where('uk_style_ref',$style_no)
                                            ->where('order_flag',$data['item_details'][0]->order_flag)
                                            ->where('po_number',$data['bank_details']->po_number)
                                            ->update([
                                               'pi_status' => "PI Approved"
                                            ]);
                    }
                }
            }
            else{
                $approved_data   = DB::table($table_name)
                                    ->where('id',$row_id)
                                    ->update([
                                       'pi_status' => "PI Approved"
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
     * method to reject the profoma invoice
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
            if (!empty($template_array)) {
                foreach ($template_array as $table => $value) {
                    if ($table_data->zone == "CE") {
                        DB::table($table)
                        ->where('ce_style_ref',$table_data->ce_style_ref)
                        ->where('po_number',$table_data->po_number)
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
				 			->where('tesco_tcl1_orders.order_status', '!=', 'Saved')
                            ->where('tesco_tcl1_orders.is_order_count_empty',0)
                            ->select('tesco_tcl1_orders.*',DB::raw("'".$tcl1."'".' as "TemplateName"'),DB::raw("'".$tesco_tcl1_orders."'".' as "tableName"'),'tesco_item_master.company_id')
                            ->where("zone",$zone)
                            ->where($col,$style_num)
                            ->get();
            $data['tcl2'] = DB::table('tesco_tcl2_orders')
                            ->where('tesco_tcl2_orders.is_order_count_empty',0)
                            ->join('tesco_item_master','tesco_tcl2_orders.template_id','=','tesco_item_master.id')
							->where('tesco_tcl2_orders.order_status', '!=', 'Saved')
                            ->select('tesco_tcl2_orders.*',DB::raw("'".$tcl2."'".' as "TemplateName"'),DB::raw("'".$tesco_tcl2_orders."'".' as "tableName"'),'tesco_item_master.company_id')
                            ->where("zone",$zone)
                            ->where($col,$style_num)
                            ->get();
            $data['tcl4'] = DB::table('tesco_tcl4_orders')
                            ->join('tesco_item_master','tesco_tcl4_orders.template_id','=','tesco_item_master.id')
                            ->where('tesco_tcl4_orders.is_order_count_empty',0)
				->where('tesco_tcl4_orders.order_status', '!=', 'Saved')
                            ->select('tesco_tcl4_orders.*',DB::raw("'".$tcl4."'".' as "TemplateName"'),DB::raw("'".$tesco_tcl4_orders."'".' as "tableName"'),'tesco_item_master.company_id')
                            ->where("zone",$zone)
                            ->where($col,$style_num)
                            ->get();
            $data['thl1'] = DB::table('tesco_thl1_orders')
                            ->join('tesco_item_master','tesco_thl1_orders.template_id','=','tesco_item_master.id')
                            ->where('tesco_thl1_orders.is_order_count_empty',0)
				->where('tesco_thl1_orders.order_status', '!=', 'Saved')
                            ->select('tesco_thl1_orders.*',DB::raw("'".$thl1."'".' as "TemplateName"'),DB::raw("'".$tesco_thl1_orders."'".' as "tableName"'),'tesco_item_master.company_id')
                            ->where("zone",$zone)
                            ->where($col,$style_num)
                            ->get();
            $data['thl3'] = DB::table('tesco_thl3_orders')
                            ->join('tesco_item_master','tesco_thl3_orders.template_id','=','tesco_item_master.id')
                            ->where('tesco_thl3_orders.is_order_count_empty',0)
				->where('tesco_thl3_orders.order_status', '!=', 'Saved')
                            ->select('tesco_thl3_orders.*',DB::raw("'".$thl3."'".' as "TemplateName"'),DB::raw("'".$tesco_thl3_orders."'".' as "tableName"'),'tesco_item_master.company_id')
                            ->where("zone",$zone)
                            ->where($col,$style_num)
                            ->get();
            $data['tsl1'] = DB::table('tesco_tsl1_orders')
                            ->join('tesco_item_master','tesco_tsl1_orders.template_id','=','tesco_item_master.id')
                            ->where('tesco_tsl1_orders.is_order_count_empty',0)
				->where('tesco_tsl1_orders.order_status', '!=', 'Saved')
                            ->select('tesco_tsl1_orders.*',DB::raw("'".$tsl1."'".' as "TemplateName"'),DB::raw("'".$tesco_tsl1_orders."'".' as "tableName"'),'tesco_item_master.company_id')
                            ->where("zone",$zone)
                            ->where($col,$style_num)
                            ->get();
            $data['tsl3'] = DB::table('tesco_tsl3_orders')
                            ->join('tesco_item_master','tesco_tsl3_orders.template_id','=','tesco_item_master.id')
                            ->where('tesco_tsl3_orders.is_order_count_empty',0)
				->where('tesco_tsl3_orders.order_status', '!=', 'Saved')
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
            $id        =  explode(',',$request->id);
            $po        =  $request->po;          
            $po_file = $request->file('po_file');
            $filename = $request->file('po_file')->getClientOriginalName();
            $destination_path='/storage/app/public/po-files/';
            $path='storage/app/public/po-files/';
            $Orname = $po_file->getClientOriginalName();
            $filename= str_random(6).'_'.preg_replace('/\s+/', '-', $Orname);
            $po_file->move($path,$filename);
            $destination_path_insert=$destination_path.$filename;
            foreach($id as $val)
            {
                $value    =  explode('-',$val);
                $po_details = DB::table($value[0])->where('id',$value[1])->select('purchase_order_no')->first();
            
                if ($po_details->purchase_order_no =="" || $po_details->purchase_order_no=="null")
                {
                    $po_status = "PO Pending";
                }
                else
                {
                    $po_status = "PO Uploaded";
                }
                
                DB::table($value[0])->where('id',$value[1])
                   ->update([
                        'purchase_order_no' => $po,
                        'po_file' => $destination_path_insert,
                        'po_status' => $po_status
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
            
            foreach($id as $val)
            {
                $value    =  explode('-',$val);
            
                $po_details = DB::table($value[0])->where('id',$value[1])->select('purchase_order_no')->first();
            
                if ($po_details->purchase_order_no =="" || $po_details->purchase_order_no=="null")
                {
                    $po_status = "PO Pending";
                }
                else
                {
                    $po_status = "PO Uploaded";
                }
                DB::table($value[0])->where('id',$value[1])
                   ->update([
                        'purchase_order_no' => $po,
                        'po_status' => $po_status
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

    public function getPOFile(Request $request)
    {
        try 
        {
            $row_id       =   $request->id;
            $table_name   =   $request->tableName; 
            $po_file      =   DB::table($table_name)->where('id',$row_id)
               ->pluck('po_file')->first();
            if ($po_file=="null" || $po_file=="" || $po_file==null)
            {
               $po_file=="null";
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
            $order_details       =   DB::table($table_name)->where('id',$row_id)
                            ->select('*')->first();
            $qnty_id         =  str_replace("_orders","_order_id",str_replace("tesco_","",$table_name));
            $quantity_details = DB::table($table_name.'_quanty')
                                ->where($qnty_id,$order_details->id)->get();
			$template_file=DB::table('tesco_template')->where('item_id',$order_details->template_id)->select('*')->get();
            if ($order_details && $quantity_details)
            {
                $result = response()->json(['error'=> false,'message' =>'success','order_details'=>$order_details,'quantity_details'=>$quantity_details,'template_file'=>$template_file],200);
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
        $tpnd         	 = explode(',',$request['tpnd']);
		$barcode         = explode(',',$request['barcode']);
		$size			 = explode(',',$request['size']);
		$count			 = explode(',',$request['count']);
		$template_id     = $request['edit_id'];
		$template_name   = $request['template_name'];
		$zone            = $request['zone'];
        
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
		
			
		$customer_ids=DB::table('customer_metadata')->where('customer_metadata_id',$request['supplier'])->select('*')->get();
		
		$customer_name=DB::table('customers')->where('id',$customer_ids{0}->customer_id)->select('*')->get();
		$temp_data =  DB::table($table_name)->where('id',$template_id)->first();
        if ($temp_data->order_flag == "newOrder" && $template_name != "THL3" && $template_name != "THL1") {
            $orderObj->updateTescoAdminOrders($table_name,$temp_data,$request,$customer_ids{0}->customer_id);
        }
		DB::table($table_name)->where('id',$template_id)
						      ->update([
                                  'zone'                 => $request['zone'],
								  'template_type'        => $request['type'],
								 // 'po_number'            => $request['po_number'],
								 // 'uk_style_ref'         => $request['uk_style'],
								//  'ce_style_ref'         => $request['ce_style'],
								  'season_code'          => $request['season_code'],
								  'short_description'    => $request['description'],
								  'eqos_site_code'       => $request['eqos'],
								  'manufacture_date'     => $request['manufacture_date'],
								  'tesco_department_id'  => $request['department'],
								  'tesco_brand_id'       => $request['brand'],
								  'tesco_supplier_id'    => $customer_ids{0}->customer_id,
                                  'tesco_metadata_supplier_id'    => $request['supplier'],
								  'rfid_compliant'       => $request['rfid'],
								  'tag_at_source'        => $request['tag_at_source'],
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
					  'cases_per_carton'   => $size[$i],
					  'count'         	   => $count[$i],
					  'size'  			   => "0"
					]);
			}
			else if(($template_name == "TCL4" && $zone=="CE") || ($template_name == "TSL3" && $zone=="CE") || ($template_name == "THL3" && $zone=="CE"))
			{
					
				DB::table($qty_table_name)->insert([
					  'orms_tpn'         => $value,
					  'ean_barcode'      => $barcode[$i],
					  $foreign_key       => $template_id,
					  'case_size'        => $size[$i],
					  'count'         	 => $count[$i],
					  'size'  			 => $single_size[$i]
					]);
			}
			else if(($template_name == "TCL4" && $zone=="UK") || ($template_name == "TSL3" && $zone=="UK") || ($template_name == "THL3" && $zone=="UK"))
			{
				
				DB::table($qty_table_name)->insert([
				  'tpnd'            => $value,
				  'ean_barcode'     => $barcode[$i],
				  $foreign_key      => $template_id,
				  'case_size'       => $size[$i],
				  'count'           => $count[$i],
				  'size'  			=> $single_size[$i]
				]);
			}
			else if(($template_name == "TCL1" && $zone=="UK") ||($template_name == "TCL2" && $zone=="UK") || ($template_name == "TSL1" && $zone=="UK") || ($template_name == "THL1" && $zone=="UK"))
			{
				
				DB::table($qty_table_name)->insert([
					  'tpnd'           	   => $value,
					  'itf_barcode'        => $barcode[$i],
					   $foreign_key        => $template_id,
					  'units_per_carton'   => $size[$i],
					  'count'         	   => $count[$i],
					  'size'  			   => "0"
				]);
			}
			$i++;
		}
		
		
		echo $customer_name{0}->company_name;	
			  
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
			$template_exists=DB::table($table_name)->where('po_number',$po_number)->where('ce_style_ref',$ce_style)->where('order_flag','!=','reOrder')->where('id','!=',$template_id)->select('*')->get();
		}
		else
		{
			$template_exists=DB::table($table_name)->where('po_number',$po_number)->where('uk_style_ref',$uk_style)->where('order_flag','!=','reOrder')->where('id','!=',$template_id)->select('*')->get();
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
}