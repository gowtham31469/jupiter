<?php

namespace App\Http\Controllers\Admin\NonArtworkTemplate;

use DB;
use View;
use Validator;
use App\Buyer;
use App\Customer;
use App\NonArtworkOrder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classes\Modules;
// 
class NonArtworkTemplateController extends Controller
{

    public function nonArtworkOrders()
    {   
            $module_var = new Modules();
            $get_main_module = $module_var->getMainModules();
            $get_sub_module = $module_var->getSubModules(9);
            
            View::composer('layouts.Admin.navbar', function($view) use ($get_main_module,$get_sub_module) {
                return $view->with(['modules' => $get_main_module,'sub_modules' => $get_sub_module]);
            });
            
            $buyers = Buyer::where('artwork_flag','=',1)->where('is_active','=',1)->pluck('buyer_name','id');
            $customers = Customer::where('is_active',1)->pluck('company_name','id');
            $orders = DB::table('non_artwork_orders')->select('*')->get();
            
            $buyer_count = count($buyers);

            $select_item_data = DB::table('reports_template_level_name')->pluck('name','id');
            
            return view('Admin/Non Artwork Template/order_management',['buyers' => $buyers,'buyer_count' => $buyer_count,'orders' => $orders,'select_item_data'=>$select_item_data,'customers'=>$customers]);
    }

    public function getItem(Request $request)
    {
        $buyer = Buyer::where('id',$request->buyer_id)->first()->buyer_name;
        $template_table = strtolower($buyer)."_template";

        $data = DB::table($template_table)
                        ->leftJoin('reports_template_level_name','name_in_reports','reports_template_level_name.id')    
                        ->select($template_table.'.id','template_name','name_in_reports','name')
                        ->get()
                        ->groupBy('name_in_reports');
        
        $arr = [];
        $opt = "";
        foreach ($data as $key => $value) {
           $opt.='<option value='.$value[0]->name_in_reports.'>'.$value[0]->name.'</option>';
        }
        $item_select = '<select data-live-search="true" name="item[]" class="selectpicker item_select">'.$opt.'</select>';

        return response()->json(['items'=>$item_select]);

    }


    public function getReference(Request $request)
    {
        // dd($request->all());

        $buyer = Buyer::where('id',$request->buyer_id)->first()->buyer_name;
        $template_table = strtolower($buyer)."_template";

        $data = DB::table($template_table)
                        ->where('name_in_reports',$request->item_id)
                        ->leftJoin('reports_template_level_name','name_in_reports','reports_template_level_name.id')    
                        ->pluck('template_name',$template_table.'.id')
                        ->toArray();
        $arr = [];
        $opt = "";
        foreach (array_unique($data) as $key => $value) {
     
           $opt.='<option value='.$key.'>'.$value.'</option>';
        }
        $reference_select = '<select data-live-search="true" name="reference[]" class="selectpicker reference_select">'.$opt.'</select>';

        return response()->json(['reference'=>$reference_select]);

    }

    public function saveNonArtworkOrders(Request $request)
    {

        // dd($request->all());
        $rules = [
            'current_date' => 'required',
            'buyer' => 'required',
            'customer.*' => 'required',
            'po_number' => 'required',
            'due_date' => 'required',
            'ocn.*' => 'required',
            'bpo.*' => 'required',
            'style.*' => 'required',
            'list.*' => 'required',
            'quantity.*' => 'required',
            'approve_date.*' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        $created_rows = [];

        if ($validator->passes()) 
        {
            foreach ($request->ocn as $key => $value) 
            {
                $already_created_nat = NonArtworkOrder::where('ocn_number',$value)
                                      ->where('style_ref', $request->quantity[$key])  
                                      ->where('color', $request->color[$key])  
                                      ->where('composition', $request->composition[$key])  
                                      ->first();

                if($already_created_nat)
                {
                    $order_quantity = DB::table('non_artwork_orders_quanty')
                        ->insert([
                        [   'non_artwork_order_id' => $already_created_nat->id,
                            'quantity' => $request->quantity[$key],
                        ]
                     ]);
                }
                else
                {
                    $newly_created_nat_id = NonArtworkOrder::insertGetId(
                                [ 'buyer_id' => $request->buyer, 
                                  'color' => "test",
                                  'color' => "test",
                                  'order_flag' => "newOrder",
                                  'ocn_number' => $value,
                                  'tesco_supplier_id' => $request->customer,
                                  'po_number' => $request->po_number,
                                  'color' => $request->color[$key],
                                  'composition' => $request->composition[$key],
                                  'item_id' => $request->item[$key],
                                  'style_ref' => $request->reference[$key],
                                  'delivery_date' => $request->due_date,
                                ]
                                );

                    array_push($created_rows, $newly_created_nat_id);

                    

                    $newly_created_nat_qty = DB::table('non_artwork_orders_quanty')
                        ->insert([
                        [   'non_artwork_order_id' => $newly_created_nat_id,
                            'quantity' => $request->quantity[$key],
                            'size' => $request->size[$key],
                            'rate' => $request->rate[$key],
                            'rate_value' => $request->rate_value[$key],
                        ]
                     ]);
                }
            }

           $max_batch_id = NonArtworkOrder::max('order_id')+1;

           NonArtworkOrder::whereIn('id', $created_rows)
                                    ->update(['order_id' => $max_batch_id]);
           return response()->json(['success'=>'done']);
        }

        return response()->json(['error'=>$validator->errors()->all()]);
    }


    public function getNonArtworkOrders(Request $request)
    {
        // dd($request->all());
        $html='';

        if($request->id > 0)
        {
            $opt = '';
            $order = DB::table('non_artwork_orders')->where('id',$request->id)->first();

            $select_values = Buyer::where('artwork_flag','=',1)
                                  ->where('is_active','=',1)
                                  ->pluck('buyer_name','id');

            $order_details = DB::table('non_artwork_orders_quanty')
                               ->where('non_artwork_order_id',$request->id)
                                ->get();

            foreach ($order_details as $key => $value) {
               $last = $key+1;
               $html.='<tr id="addr'.$key.'" class="clonable">
            <td>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input select_row_to_clone" name="customCheck1">
                                        <label class="custom-control-label">&nbsp;'.$last.'</label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group has-feedback label-ean-feedback">
                                        <p>32123414</p>
                                    </div>
                                </td>
                                <td>
                                    <input type="text" name="ocn[]" class="form-control input-sm label-ean-field" placeholder="OCN Number">
                                </td>
                                <td>
                                    <input type="text" name="bpo[]" class="form-control input-sm label-ean-field" placeholder="BPO Number">
                                </td>
                                <td>
                                    <input type="text" name="style[]" class="form-control input-sm label-ean-field" placeholder="Style/UVC">
                                </td>
                                <td>
                                    <select class="form-control input-sm label-ean-field" name="item[]"><option value="1">Orchestra</option><option value="2">Tesco</option></select>
                                </td>
                                <td>
                                    <select class="form-control input-sm label-ean-field" name="reference[]"><option value="1">Orchestra</option><option value="2">Tesco</option></select>
                                </td>
                                <td>
                                    <input type="text" name="color[]" class="form-control input-sm label-ean-field" placeholder="Color">
                                </td>
                                <td>
                                    <input type="text" name="composition[]" class="form-control input-sm label-ean-field" placeholder="Composition">
                                </td>
                                <td>
                                    <div class="input-group tesco_manufacture_date date" data-provide="datepicker" id="datePicker1">
                                        <input value="" id="" name="approve_date[]" type="text" class="form-control" placeholder="Select Due Date">
                                        <div class="input-group-addon">
                                            <span class="glyphicon glyphicon-th"> </span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <select class="form-control input-sm label-ean-field" name="size[]"><option value="1">Orchestra</option><option value="2">Tesco</option></select>
                                </td>
                                <td>
                                    <input type="text" name="quantity[]" class="form-control input-sm label-ean-field quantity" placeholder="Quantity" value="'.$value->quantity.'">
                                </td>
                                <td>
                                    <select class="form-control input-sm label-ean-field" name="uom[]"><option value="1">Orchestra</option><option value="2">Tesco</option></select>
                                </td>
                                <td>
                                    <select class="form-control input-sm label-ean-field" name="rate[]"><option value="1">Orchestra</option><option value="2">Tesco</option></select>
                                </td>
                                <td>
                                    <input type="number" name="rate_value[]" class="form-control input-sm label-ean-field" placeholder="Value">
                                </td>
          </tr>';
            }

            $html.='<tr id="addr'.$last.'" class="clonable"></tr>';

            return response()->json(['order'=>$order,'order_details'=>$html]);        
        }
    }
}
