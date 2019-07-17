<?php

namespace App\Http\Controllers\ErpUser\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Buyer;
use App\Customer;
use App\History;
use App\Action;
use App\EventHistory;
use App\ErpOrderDetails;
use DB;
use Auth;
class ReportController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('erpuser');
    }

    public function index()
    {
        $allActions = array();
        $action_as_id = array();
        if(Auth::guard('erpuser')->user()->is_admin)
        {
        $roles  = \App\ErpRoles::pluck('name')->all();
        $allActions = array_values(array_unique(Action::all()->pluck('type')->toArray()));
        $action_as_id=["_poMgr","_jcMgr","_order","_invoice"];
        }
        else
        {
        $id = Auth::guard('erpuser')->user()->id;

        $roles  = \App\ErpUserRolesMapping::where('erp_user', $id)
        ->join('erp_roles', 'erpusers_roles_mapping.role', 'erp_roles.id')
        ->pluck('name')->all();

        foreach ($roles as $key => $value) {
            switch ($value) 
        {

                case 'PO MANAGER' :
                    array_push($allActions, 'PO Manager');
                    array_push($action_as_id, '_poMgr');
                break;

                case 'JOB CARD MANAGER' :
                    array_push($allActions, 'JC Manager');
                    array_push($action_as_id, '_jcMgr');
                break;

                case 'INVOICE MANAGER' :
                    array_push($allActions, 'Invoice');
                    array_push($action_as_id, '_invoice');
                break;
                
                default:
                   
                    break;
            }
            
        }
        array_push($allActions, 'Order');
        array_push($action_as_id, '_order');
        }
    $buyerId = History::all()->pluck('buyer_id');
    $buyers=Buyer::whereIn('id',$buyerId)->orderBy('buyer_name')->pluck('buyer_name','id')->toArray();
    $actions= Action::select('id','action','type')->get()->groupBy('type')->toArray();
    $po = Action::where('type','PO Manager')->pluck('action','id')->toArray();
    $jc = Action::where('type','JC Manager')->pluck('action','id')->toArray();
    $order = Action::where('type','Order')->pluck('action','id')->toArray();
    $invoice = Action::where('type','Invoice')->pluck('action','id')->toArray();

 
    return view('erp.Report.reports',compact('buyers','actions','po','jc','jc','order','invoice','allActions','action_as_id'));

    }

    public function level1Filter(Request $request)
    {

        $result = History::whereIn('buyer_id',$request->buyer)
                           ->whereIn('season',$request->season)
                           ->whereIn('template',$request->template)
                           ->whereIn('country',$request->country)
                           ->whereBetween('created_at', [$request->date_start, $request->date_end])
                           ->select('material','customer_id','style','mat_sub')
                           ->get();

        $customer_id = $result->map(function ($result) {
              return $result['customer_id'];
        });

        $customers=Customer::whereIn('id',$customer_id)->orderBy('company_name')
                             ->pluck('company_name','id')->toArray();    

        return response()->json(['customers'=>$customers,'result'=>$result]);
    }

    public function level2Filter(Request $request)
    {  
     // dd($request->all());
        $result = History::whereIn('buyer_id',$request->buyer)
                           ->whereIn('season',$request->season)
                           ->whereIn('template',$request->template)
                           ->whereIn('country',$request->country)
                           ->whereIn('customer_id',$request->customers)
                           ->whereIn('material',$request->materials)
                           ->whereIn('style',$request->styles)
                           ->whereIn('mat_sub',$request->mat_subs)
                           ->whereBetween('created_at', [$request->date_start, $request->date_end])
                           ->select('reference','po','buyer_po','ocn')
                           ->get();

        return response()->json($result);
    }

    public function getHistory(Request $request)
    {   

        $def_po1 = ["No","Order.Dt","ErpId","Customer","Material","Style","P.O","OCN","Reference"];
        $ext_po1 = ["A/w Date", "PO.dt","JC.No","JC.Dt","Inv.No","Inv.Dt","Buyer","Season","Country","ByrPo","Quantity","Reason","Reapprove.Dt","Disp.Mode","Awb","Status"];

        $def_po2 = ["No","Order.Dt","ErpId","Customer","Material","Style","P.O","OCN","Reference"];
        $ext_po2 = ["A/w Date", "PO.dt","JC.No","JC.Dt","Inv.No","Inv.Dt","Buyer","Season","Country","ByrPo","Quantity","Reason","Reapprove.Dt","Disp.Mode","Awb","Status"];

        $def_po3 = ["No","Order.Dt","ErpId","Customer","Material","Style","P.O","OCN","Reference","Reason"];
        $ext_po3 = ["A/w Date", "PO.dt","JC.No","JC.Dt","Inv.No","Inv.Dt","Buyer","Season","Country","ByrPo","Quantity","Reapprove.Dt","Disp.Mode","Awb","Status"];

        $def_po4 = ["No","Order.Dt","ErpId","Customer","Material","Style","P.O","OCN","Reference","Reapprove.Dt"];
        $ext_po4 = ["A/w Date", "PO.dt","JC.No","JC.Dt","Inv.No","Inv.Dt","Buyer","Season","Country","ByrPo","Quantity","Disp.Mode","Awb","Status","Reason"];

        $def_jc1 = ["No","Order.Dt","ErpId","JC.No","Customer","Material","Style","P.O","OCN","Reference"];
        $ext_jc1 = ["A/w Date", "PO.dt","JC.Dt","Inv.No","Inv.Dt","Buyer","Season","Country","ByrPo","Quantity","Reapprove.Dt","Disp.Mode","Awb","Status","Reason"];

        $def_jc2 = ["No","Order.Dt","ErpId","JC.No","Customer","Material","Style","P.O","OCN","Reference"];
        $ext_jc2 = ["A/w Date", "PO.dt","JC.Dt","Inv.No","Inv.Dt","Buyer","Season","Country","ByrPo","Quantity","Reapprove.Dt","Disp.Mode","Awb","Status","Reason"];

        $def_order1 = ["No","Order.Dt","ErpId","JC.No","Inv.No","Inv.Dt","Customer","Material","Style","P.O","OCN","Reference","Quantity"];
        $ext_order1 = ["A/w Date", "PO.dt","JC.Dt","Buyer","Season","Country","ByrPo","Reapprove.Dt","Disp.Mode","Awb","Reason","Status"];

        $def_order2 = ["No","Order.Dt","ErpId","JC.No","Customer","Material","Style","P.O","OCN","Reference","Quantity"];
        $ext_order2 = ["A/w Date", "PO.dt","Inv.No","Inv.Dt","JC.Dt","Buyer","Season","Country","ByrPo","Reapprove.Dt","Disp.Mode","Awb","Reason","Status"];

        $def_order3 = ["No","Order.Dt","ErpId","JC.No","Customer","Material","Style","P.O","OCN","Reference","Quantity","Reason"];
        $ext_order3 = ["A/w Date", "PO.dt","Inv.No","Inv.Dt","JC.Dt","Buyer","Season","Country","ByrPo","Reapprove.Dt","Disp.Mode","Awb","Status"];

         $def_order4 = ["No","Order.Dt","ErpId","JC.No","Customer","Material","Style","P.O","OCN","Reference","Quantity","Reason"];
        $ext_order4 = ["A/w Date", "PO.dt","Inv.No","Inv.Dt","JC.Dt","Buyer","Season","Country","ByrPo","Reapprove.Dt","Disp.Mode","Awb","Status"];

         $def_order5 = ["No","Order.Dt","ErpId","JC.No","Inv.No","Inv.Dt","Customer","Material","Style","P.O","OCN","Reference","Quantity"];
        $ext_order5 = ["A/w Date", "PO.dt","JC.Dt","Buyer","Season","Country","ByrPo","Reapprove.Dt","Disp.Mode","Awb","Reason","Status"];

         $def_order6 = ["No","Order.Dt","ErpId","JC.No","Inv.No","Inv.Dt","Customer","Material","Style","P.O","OCN","Reference","Quantity"];
        $ext_order6 = ["A/w Date", "PO.dt","JC.Dt","Buyer","Season","Country","ByrPo","Reapprove.Dt","Disp.Mode","Awb","Reason","Status"];

        // $def_inv1 =["No","Inv.No",]


        $defaultValues =[];
        $extraValues =[];

        switch ($request->role) {
            case 'PO Manager':
            foreach ($request->status as $key => $value) 
            {
                switch ($value)
                 {
                    case 1:
                    foreach ($def_po1 as $key => $value)
                    {
                        array_push($defaultValues,$value);
                    }
                    foreach ($ext_po1 as $key => $value) 
                    {
                        array_push($extraValues,$value);
                    }
                    break;

                    case 2:
                    foreach ($def_po2 as $key => $value) 
                    {
                        array_push($defaultValues,$value);
                    }
                    foreach ($ext_po2 as $key => $value) 
                    {
                        array_push($extraValues,$value);
                    } 
                    break;

                    case 3:
                    foreach ($def_po3 as $key => $value)
                    {
                        array_push($defaultValues,$value);
                    }
                    foreach ($ext_po3 as $key => $value) 
                    {
                        array_push($extraValues,$value);
                    }
                    break;

                    case 4:
                    foreach ($def_po4 as $key => $value) 
                    {
                        array_push($defaultValues,$value);
                    }
                    foreach ($ext_po4 as $key => $value) 
                    {
                        array_push($extraValues,$value);
                    }
                    break;

                    default:
                    break;
                }
            }
            break;

            case 'JC Manager':
            foreach ($request->status as $key => $value) 
            {
                        switch ($value) 
                        {
                            case 5:
                            foreach ($def_jc1 as $key => $value)
                            {
                                array_push($defaultValues,$value);
                            }
                            foreach ($ext_jc1 as $key => $value) 
                            {
                                array_push($extraValues,$value);
                            }
                            break;

                            case 6:
                            foreach ($def_jc2 as $key => $value) 
                            {
                                array_push($defaultValues,$value);
                            }
                            foreach ($ext_jc2 as $key => $value) 
                            {
                                array_push($extraValues,$value);
                            } 
                            break;

                            default:
                            break;
                        }
            }
            break;

            case 'Order':
            foreach ($request->status as $key => $value) 
            {
                switch ($value) 
                {
                    case 7:
                    foreach ($def_order1 as $key => $value)
                    {
                        array_push($defaultValues,$value);
                    }
                    foreach ($ext_order1 as $key => $value) 
                    {
                        array_push($extraValues,$value);
                    }
                    break;

                    case 8:
                    foreach ($def_order2 as $key => $value)
                    {
                        array_push($defaultValues,$value);
                    }
                    foreach ($ext_order2 as $key => $value) 
                    {
                        array_push($extraValues,$value);
                    }
                    break;

                    case 9:
                    foreach ($def_order3 as $key => $value)
                    {
                        array_push($defaultValues,$value);
                    }
                    foreach ($ext_order3 as $key => $value) 
                    {
                        array_push($extraValues,$value);
                    }
                    break;

                    case 10:
                    foreach ($def_order4 as $key => $value)
                    {
                        array_push($defaultValues,$value);
                    }
                    foreach ($ext_order4 as $key => $value) 
                    {
                        array_push($extraValues,$value);
                    }
                    break;

                    case 11:
                    foreach ($def_order5 as $key => $value)
                    {
                        array_push($defaultValues,$value);
                    }
                    foreach ($ext_order5 as $key => $value) 
                    {
                        array_push($extraValues,$value);
                    }
                    break;

                    case 12:
                    foreach ($def_order6 as $key => $value)
                    {
                        array_push($defaultValues,$value);
                    }
                    foreach ($ext_order6 as $key => $value) 
                    {
                        array_push($extraValues,$value);
                    }
                    break;

                    default:
                    break;
                }
            }
            break;
            default:
            break;
        }


  
        // return response()->json(['reportData'=>array_values($queryRes),'hist_id'=>$eventHistory,'Default'=>array_values(array_unique($defaultValues)),'Extra'=>array_values(array_diff(array_values(array_unique($extraValues)),array_values(array_unique($defaultValues))))]);

          return response()->json(['Default'=>array_values(array_unique($defaultValues)),'Extra'=>array_values(array_diff(array_values(array_unique($extraValues)),array_values(array_unique($defaultValues))))]);
    }

    public function setUpDatatable(Request $request)
    {
              $result = History::whereIn('buyer_id',$request->buyer)
                           ->whereIn('season',$request->season)
                           ->whereIn('template',$request->template)
                           ->whereIn('country',$request->country)
                           ->whereIn('customer_id',$request->customers)
                           ->whereIn('material',$request->materials)
                           ->whereIn('style',$request->styles)
                           ->whereIn('mat_sub',$request->mat_subs)
                           ->whereIn('reference',$request->references)
                           ->whereIn('po',$request->pos)
                           ->whereIn('buyer_po',$request->buyer_pos)
                           ->whereIn('ocn',$request->ocns)
                           ->whereBetween('created_at', [$request->date_start, $request->date_end])
                           ->pluck('id')
                           ->toArray();


        $eventHistory = EventHistory::whereIn('history_id',$result)
                        ->whereIn('action_id',$request->status)
                        ->pluck('history_id')
                        ->toArray();

        $queryRes = History::whereIn('history.id',$eventHistory)
                    ->leftJoin("erp_order_details",'erp_order_details.id', '=', 'history.id')
                    ->leftJoin("customers",'customers.id', '=', 'history.buyer_id')
                    ->select(['erp_order_details.created_at as Order.Dt', 'erp_order_details_id as ErpId','company_name as Customer','material as Material','style as Style','history.po as P.O','ocn as OCN','reference as Reference','po_reapproved_date as Reapprove.Dt','customer_id as A/w Date','po_reapproved_date as PO.dt','jc_number as JC.No','jc_created_date as JC.Dt','invoice_number as Inv.No','invoice_created_date as Inv.Dt','company_name as Buyer','season as Season','country as Country','buyer_po as ByrPo','order_quantity as Quantity','company_id as Disp.Mode','company_id as Awb','status as Status','reason as Reason'])
                    ->get();

        $queryRes->map(function ($queryRes,$key) {
            $queryRes->No =$key+1;
        });
        return Datatables::of($queryRes)->make();
    }

}
