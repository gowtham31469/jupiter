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
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Config;
use App\TescoStickerOrdersSizeDetails;
use App\TescoPolybagCartonStickerOrders;
use App\TescoStickerOrdersCount;
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
            $data['tcl1'] = DB::table('tesco_tcl1_orders')
                             ->join('customers','tesco_tcl1_orders.tesco_supplier_id','=','customers.id')
                            ->select(DB::raw('"-" as StyleNumber'),'tesco_tcl1_orders.uk_style_ref','tesco_tcl1_orders.ce_style_ref','tesco_tcl1_orders.po_number as Buyer PO Number','tesco_tcl1_orders.zone as Zone',DB::raw('"TCL 1" as TemplateName'),'tesco_tcl1_orders.template_type as Template Type','customers.company_name as Supplier Name','tesco_tcl1_orders.created_at as OrderDate','tesco_tcl1_orders.artwork_status as Artwork Status','tesco_tcl1_orders.pi_status as PI Status','tesco_tcl1_orders.po_status as PO Status','tesco_tcl1_orders.purchase_order_no as PO Number',DB::raw('"-" as Actions'),DB::raw('"tesco_tcl1_orders" as tableName'),'tesco_tcl1_orders.id as tableID','tesco_tcl1_orders.po_file','customers.pi_required','tesco_tcl1_orders.template_id')
                            ->get();
            $data['tcl2'] = DB::table('tesco_tcl2_orders')
                            ->join('customers','tesco_tcl2_orders.tesco_supplier_id','=','customers.id')
                            ->select(DB::raw('"-" as StyleNumber'),'tesco_tcl2_orders.uk_style_ref','tesco_tcl2_orders.ce_style_ref','tesco_tcl2_orders.po_number as Buyer PO Number','tesco_tcl2_orders.zone as Zone',DB::raw('"TCL 2" as TemplateName'),'tesco_tcl2_orders.template_type as Template Type','customers.company_name as Supplier Name','tesco_tcl2_orders.created_at as OrderDate','tesco_tcl2_orders.artwork_status as Artwork Status','tesco_tcl2_orders.pi_status as PI Status','tesco_tcl2_orders.po_status as PO Status','tesco_tcl2_orders.purchase_order_no as PO Number',DB::raw('"-" as Actions'),DB::raw('"tesco_tcl2_orders" as tableName'),'tesco_tcl2_orders.id as tableID','tesco_tcl2_orders.po_file','customers.pi_required','tesco_tcl2_orders.template_id')
                            ->get();
            $data['tcl4'] = DB::table('tesco_tcl4_orders')
                            ->join('customers','tesco_tcl4_orders.tesco_supplier_id','=','customers.id')
                            ->select(DB::raw('"-" as StyleNumber'),'tesco_tcl4_orders.uk_style_ref','tesco_tcl4_orders.ce_style_ref','tesco_tcl4_orders.po_number as Buyer PO Number','tesco_tcl4_orders.zone as Zone',DB::raw('"TCL 4" as TemplateName'),'tesco_tcl4_orders.template_type as Template Type','customers.company_name as Supplier Name','tesco_tcl4_orders.created_at as OrderDate','tesco_tcl4_orders.artwork_status as Artwork Status','tesco_tcl4_orders.pi_status as PI Status','tesco_tcl4_orders.po_status as PO Status','tesco_tcl4_orders.purchase_order_no as PO Number',DB::raw('"-" as Actions'),DB::raw('"tesco_tcl4_orders" as tableName'),'tesco_tcl4_orders.id as tableID','tesco_tcl4_orders.po_file','customers.pi_required','tesco_tcl4_orders.template_id')
                            ->get();
            $data['thl1'] = DB::table('tesco_thl1_orders')
                            ->join('customers','tesco_thl1_orders.tesco_supplier_id','=','customers.id')
                            ->select(DB::raw('"-" as StyleNumber'),'tesco_thl1_orders.uk_style_ref','tesco_thl1_orders.ce_style_ref','tesco_thl1_orders.po_number as Buyer PO Number','tesco_thl1_orders.zone as Zone',DB::raw('"THL 1" as TemplateName'),'tesco_thl1_orders.template_type as Template Type','customers.company_name as Supplier Name','tesco_thl1_orders.created_at as OrderDate','tesco_thl1_orders.artwork_status as Artwork Status','tesco_thl1_orders.pi_status as PI Status','tesco_thl1_orders.po_status as PO Status','tesco_thl1_orders.purchase_order_no as PO Number',DB::raw('"-" as Actions'),DB::raw('"tesco_thl1_orders" as tableName'),'tesco_thl1_orders.id as tableID','tesco_thl1_orders.po_file','customers.pi_required','tesco_thl1_orders.template_id')
                            ->get();
            $data['thl3'] = DB::table('tesco_thl3_orders')
                            ->join('customers','tesco_thl3_orders.tesco_supplier_id','=','customers.id')
                            ->select(DB::raw('"-" as StyleNumber'),'tesco_thl3_orders.uk_style_ref','tesco_thl3_orders.ce_style_ref','tesco_thl3_orders.po_number as Buyer PO Number','tesco_thl3_orders.zone as Zone',DB::raw('"THL 3" as TemplateName'),'tesco_thl3_orders.template_type as Template Type','customers.company_name as Supplier Name','tesco_thl3_orders.created_at as OrderDate','tesco_thl3_orders.artwork_status as Artwork Status','tesco_thl3_orders.pi_status as PI Status','tesco_thl3_orders.po_status as PO Status','tesco_thl3_orders.purchase_order_no as PO Number',DB::raw('"-" as Actions'),DB::raw('"tesco_thl3_orders" as tableName'),'tesco_thl3_orders.id as tableID','tesco_thl3_orders.po_file','customers.pi_required','tesco_thl3_orders.template_id')
                            ->get();
            $data['tsl1'] = DB::table('tesco_tsl1_orders')
                            ->join('customers','tesco_tsl1_orders.tesco_supplier_id','=','customers.id')
                            ->select(DB::raw('"-" as StyleNumber'),'tesco_tsl1_orders.uk_style_ref','tesco_tsl1_orders.ce_style_ref','tesco_tsl1_orders.po_number as Buyer PO Number','tesco_tsl1_orders.zone as Zone',DB::raw('"TSL 1" as TemplateName'),'tesco_tsl1_orders.template_type as Template Type','customers.company_name as Supplier Name','tesco_tsl1_orders.created_at as OrderDate','tesco_tsl1_orders.artwork_status as Artwork Status','tesco_tsl1_orders.pi_status as PI Status','tesco_tsl1_orders.po_status as PO Status','tesco_tsl1_orders.purchase_order_no as PO Number',DB::raw('"-" as Actions'),DB::raw('"tesco_tsl1_orders" as tableName'),'tesco_tsl1_orders.id as tableID','tesco_tsl1_orders.po_file','customers.pi_required','tesco_tsl1_orders.template_id')
                            ->get();
            $data['tsl3'] = DB::table('tesco_tsl3_orders')
                            ->join('customers','tesco_tsl3_orders.tesco_supplier_id','=','customers.id')
                            ->select(DB::raw('"-" as StyleNumber'),'tesco_tsl3_orders.uk_style_ref','tesco_tsl3_orders.ce_style_ref','tesco_tsl3_orders.po_number as BuyerPONo','tesco_tsl3_orders.zone as Zone',DB::raw('"TSL 3" as TemplateName'),'tesco_tsl3_orders.template_type as Template Type','customers.company_name as Supplier Name','tesco_tsl3_orders.created_at as OrderDate','tesco_tsl3_orders.artwork_status as Artwork Status','tesco_tsl3_orders.pi_status as PI Status','tesco_tsl3_orders.po_status as PO Status','tesco_tsl3_orders.purchase_order_no as PO Number',DB::raw('"-" as Actions'),DB::raw('"tesco_tsl3_orders" as tableName'),'tesco_tsl3_orders.id as tableID','tesco_tsl3_orders.po_file','customers.pi_required','tesco_tsl3_orders.template_id')
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
			$data 		 = DB::table($table_name)->where('id',$template_id)
						      ->update([
                                  'purchase_order_no' => $po_number,
                                  'po_status' => "PO Approved"
                                ]);
            if (count($data)>0)
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

    public function getTescoPI(Request $request)
    {
        try
        {
            $pi_obj = new ProfomaInvoice();
            $data   = $pi_obj->getTescoPIDetails($request);
            $result = response()->json(['error'=> false,'message' =>'success','data'=>$data],200);
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
            $data       =   $pi_obj->getTescoPIDetails($request);
            if (count($data)>0)
            {
                
                $pi_details = PI::create([
                    'buyer_id'  => $buyer_id,
                    'company_id' => $data['company_details']->id,
                    'customer_id'      => $data['bank_details']->id,
                    'shipping_address' => $data['ship_to']->address,
                    'billing_address'  => $data['bill_to']->address,
                    'place_of_supply'  => $data['bank_details']->place_of_supply
                    
                ]);
                foreach ($data['item_details'] as $key => $value)
                {
                    $total_qnty+= $value->quantity;
                    $sub_total += $value->quantity *$value->unit_price;
                    ProfomaInvoiceItemDetails::create([
                        'pi_id'     => $pi_details->id,
                        'material'  => $value->material_name,
                        'size'      => $value->size,
                        'style'     => $value->style,
                        'quantity'  => $value->quantity,
                        'unit_cost' => $value->unit_price
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
            
            $data       =   DB::table($table_name)
                            ->where('id',$row_id)
                            ->update([
                               'pi_status' => "PI Approved"
                            ]);
            if ($data)
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
            $data       =   DB::table($table_name)
                            ->where('id',$row_id)
                            ->update([
                              'pi_status' => "PI Rejected"
                            ]);
            if ($data)
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

}