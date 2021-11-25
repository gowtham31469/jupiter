<?php

namespace App\Http\Controllers\Customer\Tesco;

use DB;
use Carbon\Carbon;
use App\Classes\TescoOrders;
use Illuminate\Http\Request;
use App\Classes\SaveTescoOrders;
use App\Http\Controllers\Controller;
use App\Http\Requests\TescoSaveSingleStickerRequest;
use App\Http\Requests\TescoSingleStickerOrderRequest;

class SingleStickerOrdersCustomerController extends Controller
{
    private $storeorder;
    private $saveorder;

    public function __construct(TescoOrders $storeorder, SaveTescoOrders $saveorder)
    {
        $this->storeorder = $storeorder;
        $this->saveorder = $saveorder;

    }

    public function submitSingleStickerOrder(TescoSingleStickerOrderRequest $request)
    {
        if ($request->ajax()) {

            $request->validated();

            $orms_tpn    = explode(',',$request['orms_tpn']);
            $ean_barcode = explode(',',$request['ean_barcode']);
            $itf_barcode = explode(',',$request['itf_barcode']);
            $case_size   = explode(',',$request['case_size']);
            $size        = explode(',',$request['size']);
            $templates   = $request['templates'];
            $zone        = $request['zone'];
            $style_ref   = strtolower($request['zone']) . '_style_ref';
            $style       = strtolower($request['zone']) . '_style';
            $qty_data_array = [];
            $tpnd_type   = $zone === 'CE'?'orms_tpn':'tpnd';

            $last_insertions  = [];
            $zero_quantity    = [];
            $already_exists   = [];
            $customer_id      = NULL;
            $delivery_date_count = 0; 
            $pi_required      = 0; 
            $po_required      = 0; 
            $day_count        = 0; 
            $created_by       = $request['created_by'];

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
            
            foreach ($templates as $template) 
            {
              $template_id    = $template['id'];
              $sticker_name   = str_replace(' ', '', $template['template_name']);
              $orders_model_name     = 'App\\' . "Tesco" . $sticker_name . "Orders";
              $order_qty_model_name     = 'App\\' . "Tesco" . $sticker_name . "OrdersQuantity";
              $order_qty_table_name     = 'tesco_' . strtolower($sticker_name) . '_orders_quanty';
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

              foreach ($orms_tpn as $value) 
              {
                // qr code format : {Buyer PO No,Barcode/last 3 digits of tpnd no,tpnd no}
                $qr_code = $request['po_number'] . ',' . $ean_barcode[$i] . '/' . (substr($value, -3)) . ',' . $value;
                  if (!empty($request['ean_barcode']) && !empty($request['itf_barcode'])) {
                  $qr_code = $sticker_name === 'TSL2'?$request['po_number'] . ',' . $itf_barcode[$i] . '/' . (substr($value, -3)) . ',' . $value:$qr_code;
                  
                  $qty_data_array = [
                    $sticker_order_id  => $tesco_order_id,
                    $tpnd_type         => $value,
                    'ean_barcode'      => $ean_barcode[$i],
                    'itf_barcode'      => $itf_barcode[$i],
                    'size'             => $size[$i],
                    'case_size'        => $case_size[$i],
                    'count'            => $quantity_array[$i],
                    'qr_code'          => $qr_code
                  ];
                } else if (!empty($request['ean_barcode'])) {
                  $qty_data_array = [
                    $sticker_order_id  => $tesco_order_id,
                    $tpnd_type         => $value,
                    'ean_barcode'      => $ean_barcode[$i],
                    'size'             => $size[$i],
                    'case_size'        => $case_size[$i],
                    'count'            => $quantity_array[$i],
                    'qr_code'          => $qr_code
                  ];
                } else if (!empty($request['itf_barcode'])) {
                  $qty_data_array = [
                    $sticker_order_id  => $tesco_order_id,
                    $tpnd_type         => $value,
                    'itf_barcode'      => $itf_barcode[$i],
                    'size'             => $size[$i],
                    'case_size'        => $case_size[$i],
                    'count'            => $quantity_array[$i],
                    'qr_code'          => $qr_code
                  ];
                }
                $i++;
              }

              DB::table($order_qty_table_name)->insert($qty_data_array);

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

    public function saveSingleStickerOrder(TescoSaveSingleStickerRequest $request)
    {
      $request->validated();
      $orms_tpn         = explode(',',$request['orms_tpn']);
      $ean_barcode      = explode(',',$request['ean_barcode']);
      $itf_barcode      = explode(',',$request['itf_barcode']);
      $case_size        = explode(',',$request['case_size']);
      $size             = explode(',',$request['size']);
      $qr_code          = explode('|',$request['qr_code']);
      $templates        = $request['templates'];
      $zone             = $request['zone'];
      $style_ref        = strtolower($request['zone']) . '_style_ref';
      $style            = strtolower($request['zone']) . '_style';
      $tpnd_type        = $zone === 'CE'?'orms_tpn':'tpnd';
      $qty_data_array   = [];
      $last_insertions  = [];
      $already_exists   = [];
      $created_by       = $request['created_by'];

      foreach ($templates as $template) 
      {
          $template_id      = $template['id'];

          $sticker_name   = str_replace(' ', '', $template['template_name']);
          $orders_model_name     = 'App\\' . "Tesco" . $sticker_name . "Orders";
          $order_qty_model_name     = 'App\\' . "Tesco" . $sticker_name . "OrdersQuantity";
          $order_qty_table_name     = 'tesco_' . strtolower($sticker_name) . '_orders_quanty';
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
          
          /** [ Invoking saveTescoOrder function using class instance ] */
          $tesco_order     = $this->saveorder->saveTescoOrder($request,$template_id,$created_by);

          /** Insert quantity details first time. If data already exits, delete old values and insert new values**/
          $tesco_order_id  = $tesco_order->id;
          $order_qty_model_name::where($sticker_order_id,$tesco_order_id)
              ->delete();

          $templates_inserted = array($template['template_name'] => $tesco_order_id);

          $i = 0;
          foreach ($orms_tpn as $value) 
          {
              $qty_data_array = [
                $sticker_order_id  => $tesco_order_id,
                $tpnd_type         => $value,
                'ean_barcode'      => $ean_barcode[$i],
                'itf_barcode'      => $itf_barcode[$i],
                'size'             => $size[$i],
                'case_size'        => $case_size[$i],
                'count'            => $quantity_array[$i],
                'qr_code'          => $qr_code[$i]
              ];
            $i++;
          }
        
          DB::table($order_qty_table_name)->insert($qty_data_array);

          /** [ Return array of inserted id ] */
          $last_insertions = array_merge($last_insertions,$templates_inserted);
      }
      
      return response()->json(['error'=> false,'message' =>'success','last_insertions' => $last_insertions,'already_exists' => $already_exists],200);
    }
}
