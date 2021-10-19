<?php

namespace App\Http\Controllers\Admin\Tesco;

use App\Classes\TescoOrders;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\TescoSingleStickerOrderRequest;
use DB;

use App\TescoTCL4Orders;
use App\TescoTCL4OrdersQuantity;

use App\TescoTHL3Orders;
use App\TescoTHL3OrdersQuantity;

use App\TescoTSL3Orders;
use App\TescoTSL3OrdersQuantity;

use Carbon\Carbon;

class SingleStickerOrdersController extends Controller
{

    private $storeorder;

    public function __construct(TescoOrders $storeorder)
    {
        $this->storeorder = $storeorder;

    }

    public function submitSingleStickerOrder(TescoSingleStickerOrderRequest $request)
    {
        if ($request->ajax()) {

            $request->validated();

            $orms_tpn         = explode(',',$request['orms_tpn']);
            $barcode          = explode(',',$request['barcode']);
            $case_size        = explode(',',$request['case_size']);
            $size             = explode(',',$request['size']);
            $qr_code          = explode('|',$request['qr_code']);
            $templates        = $request['templates'];

            $last_insertions  = [];
            $already_exists   = [];
            $zero_quantity    = [];
            $customer_id      = NULL;
            $delivery_date_count = 0; 
            $pi_required      = 0; 
            $po_required      = 0; 
            $day_count        = 0; 
            $created_by       = 0;

            $customer_details = DB::table("customer_metadata")
                          ->join('customers', 'customers.id', 'customer_metadata.customer_id')
                          ->where('customer_metadata.customer_metadata_id', $request['supplier'])
                          ->select('customers.pi_required','customers.delivery_date_required','customers.delivery_day_count','customers.po_required','customer_metadata.customer_id')
                          ->first();
            if($customer_details) {
              $delivery_date_count = $customer_details->delivery_date_required; 
              $pi_required         = $customer_details->pi_required; 
              $po_required         = $customer_details->po_required; 
              $day_count           = $customer_details->delivery_day_count; 
              $customer_id         = $customer_details->customer_id; 
            } 

            $ordered_date     = Carbon::now('Asia/Kolkata')->addDay($day_count);
            
            /** [Get comapanies super admin id as created by customer id] */
            $customer_profile_id = DB::table("customer_profile")
                                  ->where('company_id', $customer_id)
                                  ->where('role_id', 1)
                                  ->select('id')
                                  ->first();
            
            /** Check if super admin exists or not  */
            if ($customer_profile_id) {
              $created_by = $customer_profile_id->id;
            }

            foreach ($templates as $template) 
            {
              $template_id      = $template['id'];
              if($template['template_name'] != "THL 3")
              {
                $count_TCL4       = explode(',',$request['count_TCL4']);
                $count_TSL3       = explode(',',$request['count_TSL3']);
                
                if($template['template_name'] == "TCL 4")
                {
                  /** check all entries of array equal to FALSE(ie. 0) */
                    $is_order_count_empty = (array_sum($count_TCL4)>0)?0:1;

                    /** [ Check if order already placed for this CE style ] */
                    $is_exists = TescoTCL4Orders::select('id')->Where('ce_style_ref','=',$request['ce_style'])->where('po_number','=',$request['po_number'])->whereNotIn('order_status', ['Saved', 'Order Cancelled'])->first();
                    if($is_exists)
                    {
                      /** [ Create array of already exists artworks ] */
                       array_push($already_exists,'TCL 4');
                    }
                    else
                    {
                         $order_status  = $is_order_count_empty === 1?'Order Pending':'Order Placed';
                         $edit_permission  = $is_order_count_empty === 1?1:0;
                         /** [ Invoking createTescoOrder function using class instance ] */
                         $tesco_order     = $tesco_order_obj->createTescoOrder($request,$template_id,$pi_required,$is_order_count_empty,$customer_id,$created_by,$po_required, $order_status, $edit_permission);
                         
                         /** Insert quantity details first time. If data already exits, delete old values and insert new values**/
                         $tesco_order_id  = $tesco_order->id;
                          TescoTCL4OrdersQuantity::where('tcl4_order_id',$tesco_order_id)
                            ->delete();
                          $i = 0;
                          foreach ($orms_tpn as $tpn) 
                          {
                            TescoTCL4OrdersQuantity::create([
                                'tcl4_order_id'    => $tesco_order_id,
                                'orms_tpn'         => $tpn,
                                'ean_barcode'      => $barcode[$i],
                                'size'             => $size[$i],
                                'case_size'        => $case_size[$i],
                                'count'            => $count_TCL4[$i],
                                'qr_code'          => $qr_code[$i]
                              ]);
                            $i++;
                          }

                          /** [ last inserted tesco order id ] */
                          $templates_inserted = array('TCL 4' => $tesco_order_id);

                         /** [ Return array of inserted id ] */
                         $last_insertions = array_merge($last_insertions,$templates_inserted);
                         $quantity_empty  = $is_order_count_empty == 1?array('TCL 4'):[];
                         $zero_quantity  = array_merge($zero_quantity,$quantity_empty);
                    }
                }
                else
                {
                   /** check all entries of array equal to FALSE(ie. 0) */
                    $is_order_count_empty = (array_sum($count_TSL3)>0)?0:1;

                    /** [ Check if order already placed for this CE style ] */
                    $is_exists = TescoTSL3Orders::select('id')->Where('ce_style_ref','=',$request['ce_style'])->where('po_number','=',$request['po_number'])->whereNotIn('order_status', ['Saved', 'Order Cancelled'])->first();
                    if($is_exists)
                    {
                      /** [ Create array of already exists artworks ] */
                       array_push($already_exists,'TSL 3');
                    }
                    else
                    {
                         $order_status  = $is_order_count_empty === 1?'Order Pending':'Order Placed';
                         $edit_permission  = $is_order_count_empty === 1?1:0;
                         /** [ Invoking createTescoOrder function using class instance ] */
                         $tesco_order     = $tesco_order_obj->createTescoOrder($request,$template_id,$pi_required,$is_order_count_empty,$customer_id,$created_by,$po_required, $order_status, $edit_permission);
                         
                         /** Insert quantity details first time. If data already exits, delete old values and insert new values**/
                         $tesco_order_id  = $tesco_order->id;
                          TescoTSL3OrdersQuantity::where('tsl3_order_id',$tesco_order_id)
                            ->delete();
                          $i = 0;
                          foreach ($orms_tpn as $tpn) 
                          {
                            TescoTSL3OrdersQuantity::create([
                                'tsl3_order_id'    => $tesco_order_id,
                                'orms_tpn'         => $tpn,
                                'ean_barcode'      => $barcode[$i],
                                'size'             => $size[$i],
                                'case_size'        => $case_size[$i],
                                'count'            => $count_TSL3[$i],
                                'qr_code'          => $qr_code[$i]
                              ]);
                            $i++;
                          }

                          /** [ last inserted tesco order id ] */
                          $templates_inserted = array('TSL 3' => $tesco_order_id);

                         /** [ Return array of inserted id ] */
                         $last_insertions = array_merge($last_insertions,$templates_inserted);
                         $quantity_empty  = $is_order_count_empty == 1?array('TSL 3'):[];
                         $zero_quantity  = array_merge($zero_quantity,$quantity_empty);
                    }
                }
              }
              else
              {
                 $count_THL3       = explode(',',$request['count_THL3']);

                 /** check all entries of array equal to FALSE(ie. 0) */
                    $is_order_count_empty = (array_sum($count_THL3)>0)?0:1;

                    /** [ Check if order already placed for this CE style ] */
                    $is_exists = TescoTHL3Orders::select('id')->Where('ce_style_ref','=',$request['ce_style'])->where('po_number','=',$request['po_number'])->whereNotIn('order_status', ['Saved', 'Order Cancelled'])->first();
                    if($is_exists)
                    {
                      /** [ Create array of already exists artworks ] */
                       array_push($already_exists,'THL 3');
                    }
                    else
                    {
                         $order_status  = $is_order_count_empty === 1?'Order Pending':'Order Placed';
                         $edit_permission  = $is_order_count_empty === 1?1:0;
                         /** [ Invoking createTescoOrder function using class instance ] */
                         $tesco_order     = $tesco_order_obj->createTescoOrder($request,$template_id,$pi_required,$is_order_count_empty,$customer_id,$created_by,$po_required, $order_status, $edit_permission);
                         
                         /** Insert quantity details first time. If data already exits, delete old values and insert new values**/
                         $tesco_order_id  = $tesco_order->id;
                          TescoTHL3OrdersQuantity::where('thl3_order_id',$tesco_order_id)
                            ->delete();
                          $i = 0;
                          foreach ($orms_tpn as $tpn) 
                          {
                            TescoTHL3OrdersQuantity::create([
                                'thl3_order_id'    => $tesco_order_id,
                                'orms_tpn'         => $tpn,
                                'ean_barcode'      => $barcode[$i],
                                'size'             => $size[$i],
                                'case_size'        => $case_size[$i],
                                'count'            => $count_THL3[$i],
                                'qr_code'          => $qr_code[$i]
                              ]);
                            $i++;
                          }

                          /** [ last inserted tesco order id ] */
                          $templates_inserted = array('THL 3' => $tesco_order_id);
                         
                         /** [ Return array of inserted id ] */
                         $last_insertions = array_merge($last_insertions,$templates_inserted);
                         $quantity_empty  = $is_order_count_empty == 1?array('THL 3'):[];
                         $zero_quantity  = array_merge($zero_quantity,$quantity_empty);
                    }
              }
            }
            
           return response()->json(['error'=> false,'message' =>'success','last_insertions' => $last_insertions,'delivery_date_required' => $delivery_date_count,'ordered_date' => $ordered_date,'already_exists' => $already_exists,'po_required' => $po_required,'pi_required'=>$pi_required, 'zero_quantity' => $zero_quantity],200);

            // $response = $this->storeorder->createTescoOrder($request, 'admin');

            // return $response;
        }
    }
}
