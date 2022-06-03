<?php

namespace App\Http\Controllers\Admin\NonArtworkTemplate;

use DB;
use View;
use Validator;
use App\Buyer;
use App\NonArtWorkOrder;
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
            
            $orders = DB::table('non_artwork_orders')->select('*')->get();
            
            $buyer_count = count($buyers);
            
            return view('Admin/Non Artwork Template/order_management',['buyers' => $buyers,'buyer_count' => $buyer_count,'orders' => $orders]);
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

        if ($validator->passes()) 
        {

            $order_id = DB::table('non_artwork_orders')->insertGetId(
                                [ 'buyer_id' => $request->buyer, 
                                  'color' => "test",
                                  'color' => "test",
                                  'order_flag' => "newOrder",
                                ]
                                );

            foreach ($request->ocn as $key => $value) 
            {
                $order_quantity = DB::table('non_artwork_orders_quanty')->insert([
                        [   'non_artwork_order_id' => $order_id,
                            'quantity' => $request->quantity[$key],
                        ]
                     ]);
            }
            
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
                                    <select class="form-control input-sm label-ean-field" name="list[]"><option value="1">Orchestra</option><option value="2">Tesco</option></select>
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
                                    <input type="text" name="quantity[]" class="form-control input-sm label-ean-field quantity" placeholder="Quantity" value="'.$value->quantity.'">
                                </td>
          </tr>';
            }

            $html.='<tr id="addr'.$last.'" class="clonable"></tr>';

            return response()->json(['order'=>$order,'order_details'=>$html]);        
        }
    }
}
