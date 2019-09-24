<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\OrchestraSolidPolySticker;
use App\OrchestraSolidPolyStickerQty;
use App\OrchestraSolidCartonSticker;
use App\OrchestraSolidCartonStickerQty;
use App\OrchestraParcelDetails;
use App\OrchestraStdCartonStickerQty;
use App\OrchestraStdCartonSticker;
use App\OrchestraStdPolyStickerQty;
use App\OrchestraStdPolySticker;
use App\OrchestraHangTagQty;
use App\OrchestraHangTag;
use App\OrchestraWashCare;
use App\OrchestraWashCareQty;
use App\OrchestraAgeGroup;
use Response;
use View;
use Auth;
use DB;
use Session;
use App\ErpOrders as Erp;
use App\ErpOrderDetails as ErpOrderDetails;
use Carbon\Carbon;
use PDF;
use App\Classes\JobcardPDFHeaderFooter;
use App\Classes\FPDF_Merge;
use File;
use Config;
use App\History;

class ApiTest extends Controller
{
   public function testApi(Request $request)
   {
$composition = DB::table('orchestra_wash_care_composition')
                  ->where('owcl_id',65)
                  ->select('parts_id','composition_id')
                  ->get()->toArray();
dd($composition[0]->parts_id);
      // $p= OrchestraWashCare::with(['washcarelabelQty', function ($q) 
      //          {
      //           $q->where('orchestra_wash_care_label_orders_qty.owcl_id',65);
      //          }])->get();
      // return $p;


   $t = OrchestraWashCare::with(['washcarelabelQty' => function($type){
                  $type->where('orchestra_wash_care_label_orders_qty.owcl_id',65);
               }])->get();

   return $t;
   $res = OrchestraWashCare::with(array('washcarelabelQty' => function($query) {
   $query->where('orchestra_wash_care_label_orders_qty.owcl_id',65);
   }))
   ->get();

   // return $res;


      $r= DB::table('orchestra_hang_tag_orders_qty')
               ->leftJoin('orchestra_parcel_quantity_details','orchestra_hang_tag_orders_qty.uvc_tco','orchestra_parcel_quantity_details.UVC_TCO')
               // ->leftJoin('orchestra_parcel_details','orchestra_parcel_details.uvc_tco','orchestra_parcel_quantity_details.UVC_TCO')
               ->where('orchestra_hang_tag_orders_qty.oht_id',27)
               ->get();

               return $r;
       $a = DB::table('orchestra_parcel_quantity_details')
               ->leftJoin('orchestra_parcel_details','orchestra_parcel_details.SKU_Code','orchestra_parcel_quantity_details.UVC_TCO')
               ->leftJoin('orchestra_hang_tag_orders_qty','orchestra_hang_tag_orders_qty.uvc_tco','orchestra_parcel_quantity_details.UVC_TCO')
               ->where('orchestra_hang_tag_orders_qty.oht_id',27)
               ->select('Size_Range_Label')
               ->get();
      return($a);



// $q=  OrchestraHangTag::leftJoin('orchestra_parcel_quantity_details', function ($join) {
// $join->on('orchestra_hang_tag_orders.uvc', '=', 'orchestra_parcel_quantity_details.UVC')
// ->select('Price','Club_Price')
// ->whereIn('Currency', ['DZD'])
// ->where('Size', '2 ANS');
// })->where('orchestra_hang_tag_orders.id', '=', 27)
// ->select('Price','Club_Price')
// ->get();



$res = OrchestraHangTag::with(array('hangtagqty' => function($query) {
$query->leftJoin('orchestra_size_chart as p', 'p.age', 'orchestra_hang_tag_orders_qty.size')
->leftJoin('orchestra_parcel_details as kl', 'kl.SKU_Code', 'orchestra_hang_tag_orders_qty.uvc_tco')
->select('orchestra_hang_tag_orders_qty.*','p.*','Size_Range_Label');
}))
->where('id', '=', 27)
// ->select('Size_Range_Label')
->get();

// return($res);
   $res->map(function ($res) {
         foreach($res->hangtagqty as $key => $value) 
         {
         $CountryCodes =['DZD','CAD','CHF','SAR','MAD','MUR','USD'];
         $PriceArr = array();
         foreach($CountryCodes as $country)
         { 
        $q= OrchestraHangTag::leftJoin('orchestra_parcel_quantity_details', function ($join) 
               use($value,$country)
               {
               $join->on('orchestra_hang_tag_orders.uvc', '=', 'orchestra_parcel_quantity_details.UVC')
               ->select('Price','Club_Price')
               ->where('Currency',$country)
               ->where('Size', $value->size);
               })
        // ->leftJoin('orchestra_parcel_details', function ($join2) 
        //        {
        //        $join2->on('orchestra_parcel_details.SKU_Code', '=', 'orchestra_parcel_quantity_details.UVC_TCO')
        //        ->select('Size_Range_Label');
           
        //        })
        ->where('orchestra_hang_tag_orders.id', '=', 27)
               ->select('Price','Club_Price')
               ->get();

         array_push($PriceArr, array("Price"=>$q->first()->Price, "ClubPrice" => $q->first()->Club_Price));

         }
         $value->prices =  $PriceArr;
         $PriceArr = array();
         }
      });

return($res->toArray());


   // $value->Price=$q->first()->Price;
   // $value->ClubPrice=$q->first()->Club_Price;














// return($e[0]['hangtagqty']);
foreach ($e[0]['hangtagqty'] as $key => $value) {
   return $value;
   // $e[0]['hangtagqty']['p']="ll";
}
return $e;
   // $res  = OrchestraHangTag::find(27)
   //                         ->hangtagqty
   //                         ->leftJoin('orchestra_size_chart as os', 'os.age', '=', 'orchestra_hang_tag_orders_qty.size');
   // dd($res->toArray());

   //                $res['count'] = count($res);  
   //                $res['reference_name'] = "Orchestra HangTag";   


// $e=OrchestraHangTag::with('hangtagqty',function($q)
// {
// });

// 

//dd($e[0]['hangtagqty']);
// $e[0]['hangtagqty']->map(function ($re) {
 
   // $row=DB::table('orchestra_parcel_quantity_details')
   //                ->where('UVC','HGAKQJ')
   //                ->where('Size','$re->size')
   //                ->whereIn('Currency',['DZD'])
   //                ->select('Price','Club_Price')
   //                ->get();
   //                dd($row);
                  // $re->price =
                  // $res->reference_name = "Solid Polybag Stickers";
                  // $res->age_group = OrchestraAgeGroup::where('id',$res->group_id)->first()->color;
                  // });


// return($e);


      // $f=DB::table('orchestra_parcel_quantity_details')
      //             ->where('UVC','HGAKQJ')
      //             ->whereIn('Currency',['DZD','CAD','CHF','SAR','MAD','MUR','USD'])
      //             ->select('Price','Club_Price')
      //             ->get();

// dd ($f[0]->hangtagqty);
// $res = OrchestraHangTag::with(['hangtagqty' => 
//                   function($query)
//                   {  
//                      $query->groupBy('size')
//                           ->groupBy('orchestra_hang_tag_orders_qty.id')
//                           $join->on('users.id', '=', 'TotalCatches.user_id');;
//                   }])->get();

   	
   
}
}