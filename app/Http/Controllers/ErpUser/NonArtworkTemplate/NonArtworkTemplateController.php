<?php

namespace App\Http\Controllers\ErpUser\NonArtworkTemplate;

use DB;
use View;
use Validator;
use App\Buyer;
use App\History;
use App\Customer;
use Carbon\Carbon;
use App\NonArtworkOrder;
use App\Classes\Modules;
use App\ErpOrderDetails;
use App\ErpOrders as Erp;
use Illuminate\Http\Request;
use App\NonArtworkOrderQuanty;
use App\FinancialYearResetDate;
use App\Classes\ErpIDGenerator;
use App\Classes\NATMaterialQuery;
use App\Classes\TescoMaterialQuery;
use App\Http\Controllers\Controller;


// 
class NonArtworkTemplateController extends Controller
{

    private $current_date;
    private $year;
    private $month;
    private $financial_year_date;
    private $today_date;
    private $financial_year_start;
    private $financial_year_end;
    private $financial_year;
    private $tesco_material_obj;
    private $erp_id_gen_obj;



    public function __construct()
    {   
        $this->middleware('erpuser');

        $role = "NON TEMPLATE ORDERS";
        $this->middleware('role_permission:'.$role);
        
        // get the current time
        $this->current_date = Carbon::now('Asia/Kolkata')->toDateTimeString();

        // get year as "2020"
        $this->year = Carbon::parse($this->current_date)->format('Y');

        // get month as "07"
        $this->month = Carbon::parse($this->current_date)->format('m');

        $this->financial_year_date = FinancialYearResetDate::value('financial_year_date');

        // format financial year date as "yyyy-dd-mm" 
        $this->financial_year_date_format = $this->year . '-' . $this->financial_year_date;

        // get the current date in "dd-mm-yyyy" format
        $this->today_date = Carbon::now('Asia/Kolkata')->format('Y-m-d');

        // store financial year start and end date and
        // financial year identification
        if ($this->today_date < $this->financial_year_date_format) {
            $this->financial_year_start = Carbon::parse($this->financial_year_date_format)->subYear()->toDateString();
            $this->financial_year_end = Carbon::parse($this->financial_year_date_format)->subDay()->toDateString();
            $this->financial_year = Carbon::parse($this->financial_year_date_format)->subYear()->format('y');
        } else {
            $this->financial_year_start = $this->financial_year_date_format;
            $this->financial_year_end = Carbon::parse($this->financial_year_date_format)->addYear()->subDay()->toDateString();
            $this->financial_year = Carbon::parse($this->financial_year_date_format)->format('y');
        }

        $this->tesco_material_obj = new TescoMaterialQuery();
        $this->erp_id_gen_obj = new ErpIDGenerator();

    }

    public function nonArtworkOrders()
    {   
            $buyers = Buyer::where('is_active','=',1)->pluck('buyer_name','id');
            $customers = Customer::where('is_active',1)->pluck('company_name','id');
            $orders = DB::table('non_artwork_orders')->select('*')->get();
            
            $buyer_count = count($buyers);

            $select_item_data = DB::table('reports_template_level_name')->pluck('name','id');
            $uom = DB::table('non_artwork_uom')->pluck('uom','id');
            $size_chart = DB::table('non_artwork_size_chart')->pluck('size','id');
            return view('erp/Non Artwork Template/order_management',['buyers' => $buyers,'buyer_count' => $buyer_count,'orders' => $orders,'select_item_data'=>$select_item_data,'customers'=>$customers,'uom'=>$uom,'sizes'=>$size_chart]);
    }

    public function getItem(Request $request)
    {
        $buyer = Buyer::where('id',$request->buyer_id)->first()->buyer_name;
        $template_table = strtolower($buyer)."_template";

        $data = DB::table($template_table)
                        ->leftJoin('reports_template_level_name','name_in_reports','reports_template_level_name.id')    
                        ->select($template_table.'.id','template_name','name_in_reports','name')
                        ->pluck('name','name_in_reports');
        $arr = [];
        $opt = "";

        foreach ($data as $key => $value) {
           if($value)
           {
                $opt.='<option value='.$key.'>'.$value.'</option>';
           }
           
        }
        $item_select = '<select data-live-search="true" name="item[]" class="selectpicker item_select form-control input-group" title="Select item">'.$opt.'</select>';

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
                        ->pluck('template_name','item_id')
                        ->toArray();
        $arr = [];
        $opt = "";
        foreach ($data as $key => $value) {
     
           $opt.='<option value='.$key.'>'.$value.'</option>';
        }
        $reference_select = '<select data-live-search="true" name="reference[]" class="selectpicker form-control input-group reference_select" title="Select reference" data-size="5">'.$opt.'</select>';

        return response()->json(['reference'=>$reference_select]);

    }

    public function saveNonArtworkOrders(Request $request)
    {   
        // dd($request->all());
        // $rules = [
        //     'current_date' => 'required',
        //     'buyer' => 'required',
        //     'customer.*' => 'required',
        //     'po_number' => 'required',
        //     'due_date' => 'required',
        //     'ocn.*' => 'required',
        //     'uom.*' => 'required',
        //     'bpo.*' => 'required',
        //     'style.*' => 'required',
        //     'list.*' => 'required',
        //     'item.*' => 'required',
        //     'reference.*' => 'required',
        //     'color.*' => 'required',
        //     'size.*' => 'required',
        //     'composition.*' => 'required',
        //     'A_W_Status.*' => 'required',
        //     'quantity.*' => 'required',
        // ];


        $erp_id = $this->erp_id_gen_obj->generateErpID(
                $this->financial_year_start,
                $this->today_date,
                $this->financial_year,
                $this->month
            );

        $buyer = Buyer::where('id',$request->buyer)->first()->buyer_name;
        $template_table = strtolower($buyer)."_template";
        $master_table = strtolower($buyer)."_item_master";

        $validator = Validator::make($request->all(), 
           [
            'current_date' => 'required',
            'buyer' => 'required',
            'customer.*' => 'required',
            'po_number' => 'required',
            'due_date' => 'required',
            'ocn.*' => 'required',
            'uom.*' => 'required',
            'bpo.*' => 'required',
            'style.*' => 'required',
            'list.*' => 'required',
            'item.*' => 'required',
            'reference.*' => 'required',
            'color.*' => 'required',
            'size.*' => 'required',
            'composition.*' => 'required',
            'A_W_Status.*' => 'required',
            'quantity.*' => 'required'
            ],
            [
                'ocn.*'=>'Please fill up ocn values on all the rows',
                'uom.*'=>'Please select uom on all the rows',
                'bpo.*'=>'Please fill up bpo values on all the rows',
                'style.*'=>'Please fill up style values on all the rows',
                'item.*'=>'Please select item on all the rows',
                'approve_date.*'=>'Please select appr on all the rows',
                'quantity.*'=>'Please fill up quantity values on all the rows',
                'reference.*'=>'Please select reference item on all the rows',
                'color.*'=>'Please fill up color values on all the rows',
                'composition.*'=>'Please fill up composition values on all the rows',
            ]);

        $created_rows = [];

        if ($validator->passes()) 
        {
            if($request->action == "update")
            {  

                $nat_orders = NonArtworkOrder::where('order_id',$request->order_id)->get();
                $nat_ids = $nat_orders->pluck('id');
                $erp_ids = [];

                Erp::where('erp_id',$nat_orders[0]->order_id)->first()->delete();
                foreach ($nat_orders as $key => $value) {
                    array_push($erp_ids,$value->order_id);
                    $value->delete();
                }

                DB::table('non_artwork_orders_quanty')->whereIn('non_artwork_order_id',$nat_ids)->delete();
            }

            $new_erp_order = Erp::create([
                        'buyer_id'         => $request->buyer,
                        'customer_id'      => $request->customer,
                        'po_no'            => $request->po_number,
                        'po_file'          => "Null",
                        'status'           => 3,
                        'created_at'       => $this->current_date,
                        'erp_id'           => $erp_id
                    ]);

            foreach ($request->ocn as $key => $value) 
            {

                $custom_ref_name = DB::table('price_taxation')
                        ->where([
                        'customer_id' => $request->customer,
                        'buyer_id' => $request->buyer,
                        'template_id' => $request->reference[$key]
                        ])->value('custom_name');
                     // dd($request->customer,$request->buyer,$request->reference[$key]);
                if($custom_ref_name == null)
                {   
                    return response()->json(['error'=>[0=>"Price taxation entry not found !!!"]]);
                }


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
                    // dd($erp_ids[$key]);
                    $erp_id = $request->action == "update" ? $erp_ids[0]:$erp_id;
                    $newly_created_nat_id = NonArtworkOrder::insertGetId(
                                [ 'buyer_id' => $request->buyer, 
                                  'order_id' => $erp_id,
                                  'order_flag' => "newOrder",
                                  'order_status' => $request->approve_date[$key] == null ? 2 : 4,
                                  'ocn_number' => $value,
                                  'supplier_id' => $request->customer,
                                  'purchase_order_no' => $request->po_number,
                                  'color' => $request->color[$key],
                                  'po_number' => $request->bpo[$key],
                                  'composition' => $request->composition[$key],
                                  'item_id' => $request->item[$key],
                                  'template_id' => $request->reference[$key],
                                  'style_ref' => $request->style[$key],
                                  'total_qty' => $request->qty_total,
                                  'delivery_date' => Carbon::parse($request->due_date)->toDateString(),
                                  'order_created_at' => Carbon::parse($request->current_date)->toDateTimeString(),
                                  'order_approved_at' => $request->approve_date[$key]!=null ? Carbon::parse($request->approve_date[$key])->toDateTimeString():null,
                                ]
                                );

                    array_push($created_rows, $newly_created_nat_id);
                    // dd($request->all());

                    $reference_name = DB::table($template_table)
                                        ->where('item_id',$request->reference[$key])
                                        ->first()
                                        ->template_name;

                    $company_id = DB::table($master_table)->where('id',$request->item[$key])->first()->company_id; 

                    $size = DB::table('non_artwork_size_chart')->where('id',$request->size[$key])->first()->size;
                    $uom = DB::table('non_artwork_uom')->where('id',$request->uom[$key])->first()->uom;

                    $nat_qty = new NonArtworkOrderQuanty;
                    $nat_qty->non_artwork_order_id = $newly_created_nat_id;
                    $nat_qty->quantity = $request->quantity[$key];
                    $nat_qty->size = $size;
                    $nat_qty->size_id = $request->size[$key];
                    $nat_qty->rate = $request->rate[$key];
                    $nat_qty->uom = $uom;
                    $nat_qty->uom_id = $request->uom[$key];
                    $nat_qty->rate_value = $request->rate_value[$key];
                    $nat_qty->save();

                    $_reference = preg_replace('#[ -]+#', '-', $reference_name);

                    $unique_id = 'ERP-'.$_reference.'-'.$erp_id.'-'.$request->style[$key].'-'.$request->bpo[$key].'-'."newOrder".'-'.$request->item[$key].'-'.$request->color[$key].'-'.$request->composition[$key].'-'.$value;

                    
                    DB::table('erp_order_details')
                    ->where([
                    'order_qnty_table_name' => "non_artwork_orders_quanty",
                    'order_qnty_table_id' => $nat_qty->id
                    ])
                    ->delete();

                    $erp_order_details_id = ErpOrderDetails::create([
                        'erp_order_id'          => $new_erp_order->id,
                        'style_no'              => $request->style[$key],
                        'order_quantity'        => $request->quantity[$key],
                        'pending_orders'        => $request->quantity[$key],
                        'size'                  => $size,
                        'delivery_date'         => Carbon::parse($request->due_date)->toDateString(),
                        'order_qnty_table_name' => "non_artwork_orders_quanty",
                        'order_qnty_table_id'   => $nat_qty->id,
                        'template_id'           => $request->reference[$key],
                        'company_id'            => $company_id,
                        'reference_name'        => $reference_name,
                        'ocn_number'            => $value,
                        'status'                => 3,
                        'template_type'         => "single",
                        'custom_ref_name'       => $custom_ref_name,
                        'buyer_po_number'       => $request->bpo[$key],
                        'zone'                  => null,
                        'order_type'            => "newOrder",
                        'created_at'            => Carbon::parse($request->current_date)
                                                    ->toDateTimeString(),
                        'order_status_id'       => 4,
                        'order_date'            => Carbon::parse($request->current_date)
                                                    ->toDateTimeString(),
                        'aw_date'               => $request->approve_date[$key]!=null ?
                                                     Carbon::parse($request->approve_date[$key])->toDateTimeString():null,
                        'uuid'                  => $unique_id,
                        'fiscal_year_start'     => $this->financial_year_start,
                        'fiscal_year_end'       => $this->financial_year_end
                    ])->id;


                    $nat_material_obj = new NATMaterialQuery();
                    $template_level = $nat_material_obj->nonArtworkMaterialDetails($request->reference[$key], strtolower($buyer));
                    if($template_level)
                    {
                        $material_name  = $template_level->material;
                        $material_sub   = $template_level->material_sub;
                        $material_ref   = $template_level->ref;
                    }
                    else
                    {
                        return response()->json(['error'=>[0=>"Template details not found !!!"] ]);
                    }
                    

                    $history_id = History::create([
                        'buyer_id'             => $request->buyer,
                        'erp_order_details_id' => $erp_order_details_id,
                        'template'             => $request->reference[$key],
                        'season'               => null,
                        'country'              => null,
                        'customer_id'          => $request->customer,
                        'material'             => $material_name,
                        'mat_sub'              => $material_sub,
                        'reference'            => $request->reference[$key],
                        'style'                => $request->style[$key],
                        'po'                   => $request->po_number,
                        'buyer_po'             => $request->bpo[$key],
                        'ocn'                  => $value,
                        'created_at'           => $this->current_date,
                        'updated_at'           => $this->current_date,
                    ])->id;

                    DB::table('event_history')
                    ->insert([
                        'history_id'  => $history_id,
                        'action_id'   => 2,
                        'created_at'  => $this->current_date,
                        'updated_at'  => $this->current_date,
                    ]);


                }
            }

           // $max_batch_id = NonArtworkOrder::max('order_id')+1;

           // NonArtworkOrder::whereIn('id', $created_rows)
           //                          ->update(['order_id' => $max_batch_id]);
           return response()->json(['success'=>'done','action'=>$request->action]);
        }
        return response()->json(['error'=>$validator->errors()->all()]);
    }


    public function updateNonArtworkOrders(Request $request)
    {

    }

    public function getNonArtworkOrders(Request $request)
    {
        $html='';
        $is_editable = 1;

        if($request->id > 0)
        {   
           $order_table_data = NonArtworkOrder::where('order_id',$request->id)->get();
           if(count($order_table_data)){
           $order = $order_table_data->take(1)[0];
           $order_ids = $order_table_data->pluck('id');
         
           $quantity_table_data = DB::table('non_artwork_orders_quanty')->whereIn('non_artwork_order_id',$order_ids)->get();

           $erp_data = ErpOrderDetails::where('order_qnty_table_name','non_artwork_orders_quanty')
            ->whereIn('order_qnty_table_id',$quantity_table_data->pluck('id'))
            ->pluck('status');

            if (in_array(8, $erp_data->toArray())){
                $is_editable = 0;
            }
           
           $approved_dates = $order_table_data->pluck('order_approved_at');

           $uom = DB::table('non_artwork_uom')->pluck('uom','id');
           $size_chart = DB::table('non_artwork_size_chart')->pluck('size','id');

           foreach($order_table_data as $key => $value)
           {
            $index = $key+1;
            $uom_options='';

            foreach ($uom as $j => $q) 
            {  
                if($quantity_table_data[$key]->uom_id == $j)
                {
                    $uom_options.='<option value="'.$j.'" selected="selected">'.$q.'</option>';
                }
                else
                {
                    $uom_options.='<option value="'.$j.'">'.$q.'</option>';
                }
                
            }

            $uom_select = '<select class="selectpicker item_select form-control input-group" data-live-search="true" title="Select uom" name="uom[]">"'.$uom_options.'"</select>';

            $size_options='';
            foreach ($size_chart as $j => $q) 
            {  
                if($quantity_table_data[$key]->size_id == $j)
                {
                    $size_options.='<option value="'.$j.'" selected="selected">'.$q.'</option>';
                }
                else
                {
                    $size_options.='<option value="'.$j.'">'.$q.'</option>';
                }
                
            }

            $size_select = '<select class="form-control input-sm label-ean-field" name="size[]">"'.$size_options.'"</select>';

            $buyer = Buyer::where('id',$value->buyer_id)->first()->buyer_name;
            $template_table = strtolower($buyer)."_template";

            $hsn_tax = DB::table($template_table)
                    ->where('item_id',$value->template_id)
                    ->first();

            $data = DB::table($template_table)
            ->where('name_in_reports',$value->item_id)
            ->leftJoin('reports_template_level_name','name_in_reports','reports_template_level_name.id')    
            ->pluck('template_name','item_id')
            ->toArray();


            $select_item_data = DB::table($template_table)
                        ->leftJoin('reports_template_level_name','name_in_reports','reports_template_level_name.id')    
                        ->select($template_table.'.id','template_name','name_in_reports','name')
                        ->pluck('name','name_in_reports');
            $item_options='';
            foreach ($select_item_data as $j => $q) 
            {  
                if($value->item_id == $j)
                {
                    $item_options.='<option value="'.$j.'" selected="selected">'.$q.'</option>';
                }
                else
                {
                    $item_options.='<option value="'.$j.'">'.$q.'</option>';
                }
                
            }

            $item_select = '<select class="selectpicker item_select form-control input-group" data-live-search="true" title="Select item" name="item[]">"'.$item_options.'"</select>';

            $arr = [];
            $opt = "";
            $reference_select = "";
            foreach ($data as $k => $v) 
            {

                if($k == $value->template_id)
                {
                    $opt.='<option value="'.$k.'" selected="selected">'.$v.'</option>';
                }
                else
                {
                    $opt.='<option value='.$k.'>'.$v.'</option>';
                }
            
            }

            $reference_select = '<select data-live-search="true" name="reference[]" class="selectpicker reference_select" title="Select reference" data-size="5">'.$opt.'</select>';

            $approved_flag = $value->order_approved_at == null ? "" : 'selected="selected"';
            $html.='<tr id="addr'.$key.'" class="clonable">
                                    <td>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input select_row_to_clone" name="customCheck1">
                                            <label class="custom-control-label">&nbsp;'.$index.'</label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group has-feedback label-ean-feedback">
                                            <p>32123414</p>
                                        </div>
                                    </td>
                                    <td width="150">
                                        <input type="number" name="ocn[]" class="form-control input-sm label-ean-field " value = "'.$value->ocn_number.'" placeholder="OCN Number">
                                    </td>
                                    <td width="150">
                                        <input type="text" name="bpo[]" class="form-control input-sm label-ean-field" value="'.$value->po_number.'" placeholder="BPO Number">
                                    </td>
                                    <td width="150">
                                        <input type="text" name="style[]" class="form-control input-sm label-ean-field" value = "'.$value->style_ref.'" placeholder="Style/UVC">
                                    </td>   
                                    <td width="100" class="selectpicker_td item_td">'.$item_select.'</td>
                                    <td width="150" class="reference_td selectpicker_td">'.$reference_select.'</td>
                                    <td width="150">
                                        <input type="text" name="color[]" class="form-control input-sm label-ean-field" value="'.$value->color.'" placeholder="Color">
                                    </td>
                                    <td width="150">
                                        <input type="text" name="composition[]" class="form-control input-sm label-ean-field" value="'.$value->composition.'"placeholder="Composition">
                                    </td>
                                    <td width="150" class="selectpicker_td">
                                        <select class="selectpicker aw_status" name="A_W_Status[]">
                                            <option value="0" '.$approved_flag.' >Not Approved</option>
                                            <option value="1" '.$approved_flag.'>Approved</option>
                                        </select>
                                    </td>
                                    <td width="170" class="aw_td">
                                        <div class="input-group tesco_manufacture_date date" data-provide="datepicker" id="datePicker1">
                                            <input value="" id="" name="approve_date[]" type="text" class="form-control aw_date" placeholder="Select Due Date">
                                            <div class="input-group-addon">
                                                <span class="glyphicon glyphicon-th"> </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td width="150">'.$size_select.'</td>
                                    <td width="150" class="quantity_td">
                                        <input type="number" name="quantity[]" class="form-control input-sm label-ean-field quantity" value="'.$quantity_table_data[$key]->quantity.'"placeholder="Quantity">
                                    </td>
                                    <td width="150" class="selectpicker_td" title="Select uom">'.$uom_select.'</td>
                                    <td width="150" class="rate_td">
                                    <input type="number" name="rate[]" class="form-control input-sm label-ean-field" placeholder="Rate" value="'.$quantity_table_data[$key]->rate.'" readonly>
                                    </td>
                                    <td width="150" class="value_td">
                                         <input type="number" name="rate_value[]" class="form-control input-sm label-ean-field rate_value" value="'.$quantity_table_data[$key]->rate_value.'" placeholder="qty*rate" readonly>
                                    </td>
                                </tr>';

           $last = $key+1;
           }

           // $html= $html.'<tr id="addr'.$last.'" class="clonable"></tr>';
           return response()->json(['result'=>1,'row'=>$order,'td'=>$html,'approved_dates'=>$approved_dates,'last_row_index' =>$last,'hsn'=>$hsn_tax->hsn_code,'tax'=>$hsn_tax->tax,'is_editable'=>$is_editable]);
       }
       else{
        return response()->json(['result'=>0]);
       }
    }
    }

    public function calculateRate(Request $request)
    {
       $row = DB::table('price_taxation')->where('customer_id',$request->customer_id)
                                   ->where('buyer_id',$request->buyer_id)
                                   ->where('template_id',$request->template_id)
                                   ->first();
       if($row)
       {
        $price_range = DB::table('price_range')
                        ->where('price_taxation_id',$row->id)
                        ->where('volume_from','<=',$request->quantity)
                        ->where('volume_to','>=',$request->quantity)
                        ->get();
        if($price_range->count())
        {
            return response()->json(['rate'=>$price_range[0]->price,'moq'=>$row->moq]);
        }
        else
        {
            return response()->json(['rate'=>0,'moq'=>0]);
        }
        
       }                            
       return response()->json(['rate'=>0,'moq'=>0]);
    }


    public function getHsn(Request $request)
    {
        $buyer = Buyer::where('id',$request->buyer_id)->first()->buyer_name;
        $template_table = strtolower($buyer)."_template";
        $row = DB::table($template_table)
                    ->where('item_id',$request->item_id)
                    ->first();
        return response()->json(['hsn'=>$row->hsn_code,'tax'=>$row->tax]);
    }

}
