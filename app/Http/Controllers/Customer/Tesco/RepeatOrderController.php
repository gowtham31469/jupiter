<?php

namespace App\Http\Controllers\Customer\Tesco;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use App\Classes\Modules;
use Response;
use View;
use DB;
use Auth;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Config;
use App\TescoTCL1Orders;
use App\TescoTCL1OrdersQuantity;
use App\TescoTCL2Orders;
use App\TescoTCL2OrdersQuantity;
use App\TescoTCL4Orders;
use App\TescoTCL4OrdersQuantity;
use App\TescoTHL1Orders;
use App\TescoTHL1OrdersQuantity;
use App\TescoTHL3Orders;
use App\TescoTHL3OrdersQuantity;
use App\TescoTSL1Orders;
use App\TescoTSL1OrdersQuantity;
use App\TescoTSL3Orders;
use App\TescoTSL3OrdersQuantity;
use App\TescoWashcareOrders;
use App\TescoWashcareOrdersQuantity;
use App\TescoWashcareOrdersComponentFibreMapping;
use App\TescoWashcareOrdersComponentMapping;
use App\Classes\TescoCombinedPOUpload;
use App\Classes\TescoPOUploadTemplatesSwitch;
use App\Classes\TescoOrders;
use App\Classes\SaveTescoOrders;
use App\TescoPrimarySize;
use PDF;
use Carbon\Carbon;
use App\OrderManagementHistory;
use App\Classes\StoreOrderManagementHistory;
use App\FinancialYearResetDate;
use Session;

class RepeatOrderController extends Controller
{
    
    public function extraQuantityLabelTescoRepeatOrder(Request $request)
    {
      // dd($request->all());

      // dd($caremeta->getCareMetadata()['symbols']);

      $type         =   $request->type;
      $id           =   $request->id;
      $type_id      =   explode('_',$type);
      
      $data['qty']=DB::table($type.'_quanty')->where($type_id[1].'_order_id',$id)->select('*')->get();

      $data['orders']=DB::table('tesco_washcare_orders')->where('id',$id)->select('*')->get();
    
      $data['template']=DB::table($type)
                        ->join('tesco_suppliers','tesco_suppliers.id','=',$type.'.tesco_metadata_supplier_id')
                        ->where($type.'.id',$id)
                        ->select($type.'.*','tesco_suppliers.supplier_name')->get();
    
      $data['template_file']=DB::table('tesco_template')->where('item_id',$data['template']{0}->template_id)->select('*')->get();     
    
      $data['tesco_item_master']=DB::table('tesco_item_master')->where('id',$data['template']{0}->template_id)->select('*')->get(); 
      
      $data['moq']=DB::table('price_taxation')->where('template_id',$data['template']{0}->template_id)->where('customer_id',$data['template']{0}->tesco_supplier_id)->where('buyer_id',2)->select('*')->get();

      $size_category_json = json_decode($data['qty']{0}->selected_secondary_sizes);

      $size_category =array();
      $data['size_category_text'] =array();
      foreach($size_category_json as $sizes)
      {
        if($sizes->id != "" && $sizes->id != null)
        {
          array_push($size_category,$sizes->id);
          array_push($data['size_category_text'],$sizes->secondary_size);
        }
        
      }


      $data['tesco_sizelists'] = TescoPrimarySize::with(['secSizeChart' => function ($query) use ($size_category) {
          $query->whereIn('tesco_secondary_size_chart.tesco_secondary_size_category_id', $size_category);
      }])
        ->where('tesco_primary_size.tesco_size_department_id', $data['orders']{0}->primary_size_id)
        ->orderBy('tesco_primary_size.id')
        ->get();

        // dd($size_category,$data['tesco_sizelists']);
    
      $data['composition'] =  TescoWashcareOrdersComponentMapping::with('fibers')
            ->where('washcare_order_id',$request->id)
            ->get();
            // ->with('quantities')
            // ->with('garment_components')
            // ->get();
            dd($data);
      echo json_encode($data);
    }
}
