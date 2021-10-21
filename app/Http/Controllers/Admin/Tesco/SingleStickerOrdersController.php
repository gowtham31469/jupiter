<?php

namespace App\Http\Controllers\Admin\Tesco;

use App\Classes\TescoOrders;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\TescoSingleStickerOrderRequest;
use DB;
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

            $orms_tpn    = explode(',',$request['orms_tpn']);
            $barcode     = explode(',',$request['barcode']);
            $case_size   = explode(',',$request['case_size']);
            $size        = explode(',',$request['size']);
            $qr_code     = explode('|',$request['qr_code']);
            $templates   = $request['templates'];
            $zone        = $request['zone'];
            $style_ref   = strtolower($request['zone']) . '_style_ref';
            $style       = strtolower($request['zone']) . '_style';
            $barcode_type= strlen($barcode[0]) === 13?'ean_barcode':'itf_barcode';

            $last_insertions  = [];
            $zero_quantity    = [];
            $already_exists   = [];
            $customer_id      = NULL;
            $delivery_date_count = 0; 
            $pi_required      = 0; 
            $po_required      = 0; 
            $day_count        = 0; 
            $created_by       = 0;

            $customer_details = DB::table('customer_metadata')
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
            $customer_profile_id = DB::table('customer_profile')
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
              $template_id    = $template['id'];
              $sticker_name   = str_replace(' ', '', $template['template_name']);
              $orders_model_name     = 'App\\' . "Tesco" . $sticker_name . "Orders";
              $order_qty_model_name     = 'App\\' . "Tesco" . $sticker_name . "OrdersQuantity";
              $sticker_order_id = strtolower($sticker_name).'_order_id';

              $quantity_array = $request['sticker_count'][$template['template_name']];

              /** [ Check if order already placed for this style ] */
              $is_exists = $orders_model_name::select('id')
              ->Where($style_ref, $request[$style])
              ->where('po_number', $request['po_number'])
              ->whereNotIn('order_status', ['Saved', 'Order Cancelled'])
              ->exists();

              if($is_exists)
              {
                return response()->json(['error'=> true,'message' =>'Order already exists'],409);
              }

              /** check all entries of array equal to FALSE(ie. 0) */
              $is_order_count_empty = (array_sum($quantity_array)>0)?0:1;
              $order_status  = $is_order_count_empty === 1?'Order Pending':'Order Placed';
              $edit_permission  = $is_order_count_empty === 1?1:0;

              /** [ Invoking createTescoOrder function using class instance ] */
              $tesco_order     = $this->storeorder->createTescoOrder($request,$template_id,$pi_required,$is_order_count_empty,$customer_id,$created_by,$po_required, $order_status, $edit_permission);
              
              /** Insert quantity details first time. If data already exists, delete old values and insert new values**/
              $tesco_order_id  = $tesco_order->id;
              
              $order_qty_model_name::where($sticker_order_id,$tesco_order_id)
                ->delete();
              $i = 0;

              if($zone === 'CE'){
                foreach ($orms_tpn as $value) 
                {
                  $order_qty_model_name::create([
                      $sticker_order_id  => $tesco_order_id,
                      'orms_tpn'         => $value,
                      $barcode_type      => $barcode[$i],
                      'size'             => $size[$i],
                      'case_size'        => $case_size[$i],
                      'count'            => $quantity_array[$i],
                      'qr_code'          => $qr_code[$i]
                    ]);
                  $i++;
                }
              } else {
                foreach ($orms_tpn as $value) 
                    {
                      $order_qty_model_name::create([
                          $sticker_order_id  => $tesco_order_id,
                          'tpnd'             => $value,
                          $barcode_type      => $barcode[$i],
                          'size'             => $size[$i],
                          'case_size'        => $case_size[$i],
                          'count'            => $quantity_array[$i]
                        ]);
                      $i++;
                    }
              }

              /** [ last inserted tesco order id ] */
              $templates_inserted = array($template['template_name'] => $tesco_order_id);
              
              /** [ Return array of inserted id ] */
              $last_insertions = array_merge($last_insertions,$templates_inserted);
              $quantity_empty  = $is_order_count_empty == 1?array($template['template_name']):[];
              $zero_quantity  = array_merge($zero_quantity,$quantity_empty);
            }

            return response()->json(
              [
                'error' => false,
                'message' => 'success',
                'last_insertions' => $last_insertions,
                'delivery_date_required' => $delivery_date_count,
                'ordered_date' => $ordered_date,
                'po_required' => $po_required,
                'pi_required' => $pi_required,
                'zero_quantity' => $zero_quantity,
                'already_exists' => $already_exists
              ],
              200
            );

        }
    }
}
