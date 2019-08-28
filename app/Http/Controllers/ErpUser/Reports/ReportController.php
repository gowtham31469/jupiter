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
use App\ErpOrdersInvoice; 
use App\Invoices;
use App\InvoiceDetails;
use App\InvExtraCharges;
use App\Classes\Invoice;
use App\InvoiceAddress;
use App\TescoTaxCode;
use Carbon\Carbon;
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

    $tax=array_unique(TescoTaxCode::all()->pluck('tax')->toArray());
    array_multisort($tax);
    array_walk($tax, function(&$value, $key) { $value .= '% tax'; } );

    // foreach ($tax as $key => $value) {
    //     # code...
    // }
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
    $invoice=$invoice+$tax;
    $templates = array_unique(History::all()->sortBy('template')->pluck('template')->toArray());
    $seasons = array_unique(History::all()->sortBy('season')->pluck('season')->toArray());
    $countries = array_unique(History::all()->sortBy('country')->pluck('country')->toArray());
    return view('erp.Report.reports',compact('buyers','actions','po','jc','jc','order','invoice','allActions','action_as_id','countries','seasons','templates'));

}

public function level1Filter(Request $request)
{

    $result = History::whereIn('buyer_id',$request->buyer)
    ->leftJoin("event_history",'history.id', '=', 'event_history.history_id')
    ->whereIn('season',$request->season)
    ->whereIn('template',$request->template)
    ->whereIn('country',$request->country)
    ->whereBetween('event_history.created_at', [$request->date_start.' 00:00:00', $request->date_end.' 23:59:59'])
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
    $result = History::whereIn('buyer_id',$request->buyer)
    ->leftJoin("event_history",'history.id', '=', 'event_history.history_id')
    ->whereIn('season',$request->season)
    ->whereIn('template',$request->template)
    ->whereIn('country',$request->country)
    ->whereIn('customer_id',$request->customers)
    ->whereIn('material',$request->materials)
    ->whereIn('style',$request->styles)
    ->whereIn('mat_sub',$request->mat_subs)
    ->whereBetween('event_history.created_at', [$request->date_start.' 00:00:00', $request->date_end.' 23:59:59'])
    ->select('reference','po','buyer_po','ocn')
    ->get();
    return response()->json($result);
}

public function getTableFields(Request $request)
{   

    $def_po1 = ["No","Order_Dt","ErpId","Customer","Material","Style","P.O","OCN","Reference"];
    $ext_po1 = ["AW_Dt", "PO_Dt","JC.No","JC_Dt","Inv.No","Inv_Dt","Buyer","Season","Country","ByrPo","Quantity","Reason","Reapproved_Dt","DispMode","Awb","Status"];

    $def_po2 = ["No","Order_Dt","ErpId","Customer","Material","Style","P.O","OCN","Reference"];
    $ext_po2 = ["AW_Dt", "PO_Dt","JC.No","JC_Dt","Inv.No","Inv_Dt","Buyer","Season","Country","ByrPo","Quantity","Reason","Reapproved_Dt","DispMode","Awb","Status"];

    $def_po3 = ["No","Order_Dt","ErpId","Customer","Material","Style","P.O","OCN","Reference","Reason"];
    $ext_po3 = ["AW_Dt", "PO_Dt","JC.No","JC_Dt","Inv.No","Inv_Dt","Buyer","Season","Country","ByrPo","Quantity","Reapproved_Dt","DispMode","Awb","Status"];

    $def_po4 = ["No","Order_Dt","ErpId","Customer","Material","Style","P.O","OCN","Reference","Reapproved_Dt"];
    $ext_po4 = ["AW_Dt", "PO_Dt","JC.No","JC_Dt","Inv.No","Inv_Dt","Buyer","Season","Country","ByrPo","Quantity","DispMode","Awb","Status","Reason"];

    $def_jc1 = ["No","Order_Dt","ErpId","JC.No","Customer","Material","Style","P.O","OCN","Reference"];
    $ext_jc1 = ["AW_Dt", "PO_Dt","JC_Dt","Inv.No","Inv_Dt","Buyer","Season","Country","ByrPo","Quantity","Reapproved_Dt","DispMode","Awb","Status","Reason"];

    $def_jc2 = ["No","Order_Dt","ErpId","JC.No","Customer","Material","Style","P.O","OCN","Reference"];
    $ext_jc2 = ["AW_Dt", "PO_Dt","JC_Dt","Inv.No","Inv_Dt","Buyer","Season","Country","ByrPo","Quantity","Reapproved_Dt","DispMode","Awb","Status","Reason"];

    $def_order1 = ["No","Order_Dt","ErpId","JC.No","Inv.No","Inv_Dt","Customer","Material","Style","P.O","OCN","Reference","Quantity"];
    $ext_order1 = ["AW_Dt", "PO_Dt","JC_Dt","Buyer","Season","Country","ByrPo","Reapproved_Dt","DispMode","Awb","Reason","Status"];

    $def_order2 = ["No","Order_Dt","ErpId","JC.No","Customer","Material","Style","P.O","OCN","Reference","Quantity"];
    $ext_order2 = ["AW_Dt", "PO_Dt","Inv.No","Inv_Dt","JC_Dt","Buyer","Season","Country","ByrPo","Reapproved_Dt","DispMode","Awb","Reason","Status"];

    $def_order3 = ["No","Order_Dt","ErpId","JC.No","Customer","Material","Style","P.O","OCN","Reference","Quantity","Reason"];
    $ext_order3 = ["AW_Dt", "PO_Dt","Inv.No","Inv_Dt","JC_Dt","Buyer","Season","Country","ByrPo","Reapproved_Dt","DispMode","Awb","Status"];

    $def_order4 = ["No","Order_Dt","ErpId","JC.No","Customer","Material","Style","P.O","OCN","Reference","Quantity","Reason"];
    $ext_order4 = ["AW_Dt", "PO_Dt","Inv.No","Inv_Dt","JC_Dt","Buyer","Season","Country","ByrPo","Reapproved_Dt","DispMode","Awb","Status"];

    $def_order5 = ["No","Order_Dt","ErpId","JC.No","Inv.No","Inv_Dt","Customer","Material","Style","P.O","OCN","Reference","Quantity"];
    $ext_order5 = ["AW_Dt", "PO_Dt","JC_Dt","Buyer","Season","Country","ByrPo","Reapproved_Dt","DispMode","Awb","Reason","Status"];

    $def_order6 = ["No","Order_Dt","ErpId","JC.No","Inv.No","Inv_Dt","Customer","Material","Style","P.O","OCN","Reference","Quantity"];
    $ext_order6 = ["AW_Dt", "PO_Dt","JC_Dt","Buyer","Season","Country","ByrPo","Reapproved_Dt","DispMode","Awb","Reason","Status"];

    $def_invoice1 =["No","Inv.No","Date","Customer","Base_Val","CGST","SGST","Inv_Val"];
    $ext_invoice1 = ["IGST"];

    $def_invoice2 =["No","Inv.No","Date","Customer","Base_Val","IGST","Inv_Val"];
    $ext_invoice2 = ["CGST","SGST"];

    // $def_invoice3 =["No","Inv.No","Date","Customer","Ass.Val","CGST","SGST","IGST","Inv_Val"];
    // $ext_invoice3 = [];

    // $def_invoice4 =["No","Inv.No","Date","Customer","Ass.Val","CGST","SGST","IGST","Inv_Val"];
    // $ext_invoice4 = [];

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

        case 'Invoice':
        // foreach ($request->status) 
        // {
            switch ($request->status) {
                case 14:
                foreach ($def_invoice1 as $key => $value)
                {
                    array_push($defaultValues,$value);
                }
                foreach ($ext_invoice1 as $key => $value) 
                {
                    array_push($extraValues,$value);
                }
                break;

                case 15:
                foreach ($def_invoice2 as $key => $value)
                {
                    array_push($defaultValues,$value);
                }
                foreach ($ext_invoice2 as $key => $value) 
                {
                    array_push($extraValues,$value);
                }
                break;

                // case 16:
                // foreach ($def_invoice3 as $key => $value)
                // {
                //     array_push($defaultValues,$value);
                // }
                // foreach ($ext_invoice3 as $key => $value) 
                // {
                //     array_push($extraValues,$value);
                // }
                // break;

                // case 17:
                // foreach ($def_invoice4 as $key => $value)
                // {
                //     array_push($defaultValues,$value);
                // }
                // foreach ($ext_invoice4 as $key => $value) 
                // {
                //     array_push($extraValues,$value);
                // }
                // break;

                default:
                        
                $defaultValues =["No","Inv.No","Date","Customer","Base_Val","CGST","SGST","IGST","Inv_Val"];
                $extraValues = [];
                break;
            }
        // }
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
        ->leftJoin("event_history",'history.id', '=', 'event_history.history_id')
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
        ->whereBetween('event_history.created_at', [$request->date_start.' 00:00:00', $request->date_end.' 23:59:59'])
        ->pluck('history.id')
        ->toArray();

     

        switch ($request->role) {
            case 'Invoice':

            $eventHistory = EventHistory::whereIn('history_id',$result)
                    ->where('action_id',$request->status)
                    ->pluck('history_id')
                    ->toArray();

                $invIds1 = [];
                $invIds2 = [];
                     

                if($request->status < 14)
                {  
                    $tax_arr=array_unique(TescoTaxCode::all()->pluck('tax')->toArray());
                    array_multisort($tax_arr);
                    $tax = $tax_arr[$request->status];

                    $queryRes   =InvoiceDetails::where('tax',$tax)
                                    ->join('invoice','invoice.id','invoice_details.invoice_id')
                                         // Invoices::whereIn('invoice.id',$invoiceIds)
                                    ->join('customers','invoice.customer_id','customers.id')
                                    // ->join('invoice_details','invoice_details.invoice_id','invoice.id')
                                    ->join('buyers','invoice.buyer_id','buyers.id')
                                    ->whereBetween('invoice.created_at', [$request->date_start.' 00:00:00', $request->date_end.' 23:59:59'])
                                    ->select('invoice.invoice_id as Inv.No','invoice.created_at as Date',
                                             'company_name as Customer','grand_total as Inv_Val','tax',
                                             'invoice.place_of_supply','base_amnt as Base_Val')
                                    ->distinct('invoice.id')
                                    ->get();
                }
                else{
                   
                    switch ($request->status) {
                        case 14:
                           $invoiceIds = Invoices::where('place_of_supply',1)->pluck('id')->toArray();
                            break;

                        case 15:
                           $invoiceIds= Invoices::where('place_of_supply',2)->pluck('id')->toArray();
                            break;
                        default:
                            # code...
                            break;
                    }
                

                  $queryRes   = Invoices::whereIn('invoice.id',$invoiceIds)
                                    ->join('customers','invoice.customer_id','customers.id')
                                    ->join('invoice_details','invoice_details.invoice_id','invoice.id')
                                    ->join('buyers','invoice.buyer_id','buyers.id')
                                    ->whereBetween('invoice.created_at', [$request->date_start.' 00:00:00', $request->date_end.' 23:59:59'])
                                    ->select('invoice.invoice_id as Inv.No','invoice.created_at as Date',
                                             'company_name as Customer','grand_total as Inv_Val','tax','invoice.place_of_supply','base_amnt as Base_Val')
                                    ->distinct('invoice.id')
                                    ->get();
                    }
                    $queryRes->map(function ($queryRes,$key) {
                    $queryRes->No =$key+1;
                    $num=($queryRes->tax/2)/100 * $queryRes->Base_Val;    
                    $val=number_format($num, 2, '.', '');
                    $queryRes->Date=(new Carbon($queryRes->Date))->format('d-m-Y');
                    $queryRes->Inv_Val = number_format($queryRes->Inv_Val, 2, '.', '');
                    $queryRes->Base_Val = number_format($queryRes->Base_Val, 2, '.', '');
                    
                    if($queryRes->place_of_supply==1)
                    {  
                    $queryRes->CGST = $val;
                    $queryRes->SGST = $val;
                    $queryRes->IGST = "-";
                    }
                    else{
                    $queryRes->CGST = "-";
                    $queryRes->SGST = "-";
                    $queryRes->IGST = $val;
                    }
                    });


                    

                break;
           
            default:
                    $statusId = $request->status;

                    if($request->role == "JC Manager")
                    {
                      
                    if (count($statusId) == 1 && ($key = array_search(5, $statusId)) !== false)
                     {
                    unset($statusId[$key]);
                    array_push($statusId, "2");
                    array_push($statusId, "4");

                    // $Jcverified = EventHistory::where('action_id',6)->pluck('history_id')->toArray();

                    // $allJc = EventHistory::whereIn('history_id',$result)
                    // ->whereIn('action_id',$statusId)
                    // ->pluck('history_id')
                    // ->toArray();

                    // $eventHistory = array_diff($allJc, $Jcverified);
                    }
                    }

                    $eventHistory = EventHistory::whereIn('history_id',$result)
                    ->whereIn('action_id',$statusId)
                    ->pluck('history_id')
                    ->toArray(); 

                    // if($request->role == "PO Manager")
                    // {
                    //  if (($key = array_search(2, $statusId)) !== false)
                    //     {
                    //         array_push($statusId, "4");

                    //         $eventHistory = EventHistory::whereIn('history_id',$result)
                    //         ->whereIn('action_id',$statusId)
                    //         ->pluck('history_id')
                    //         ->toArray(); 
                    //     }
                    // }

                    $queryRes = History::whereIn('history.id',$eventHistory)
                    ->leftJoin("erp_order_details",'erp_order_details.id', '=', 
                                'history.erp_order_details_id')
                    ->leftJoin("customers",'customers.id', '=', 'history.customer_id')
                    ->leftJoin("buyers",'buyers.id', '=', 'history.buyer_id')
                    ->leftJoin("event_history",'history.id', '=', 'event_history.history_id')
                    ->leftJoin("actions",'actions.id', '=', 'event_history.action_id')
                    ->whereIn('actions.id',$statusId)
                    ->select(['erp_order_details.order_date as Order_Dt', 'erp_order_id as ErpId',
                        'company_name as Customer','material as Material','style as Style','history.po as P.O',
                        'ocn as OCN','reference as Reference','po_reapproved_date as Reapproved_Dt',
                        'erp_order_details.aw_date as AW_Dt','erp_order_details.created_at as PO_Dt',
                        'jc_number as JC.No','jc_created_date as JC_Dt','invoice_number as Inv.No',
                        'invoice_created_date as Inv_Dt','buyer_name as Buyer','season as Season',
                        'country as Country','buyer_po as ByrPo','order_quantity as Quantity','actions.action as Status','reason as Reason'])
                        ->get();
                       
                    $queryRes->map(function ($queryRes,$key) {
                    $queryRes->Order_Dt=(new Carbon($queryRes->Order_Dt))->format('d-m-Y');
                    $queryRes->JC_Dt=(new Carbon($queryRes->JC_Dt))->format('d-m-Y');
                    $queryRes->JC_Dt=(new Carbon($queryRes->JC_Dt))->format('d-m-Y');
                    $queryRes->AW_Dt=(new Carbon($queryRes->AW_Dt))->format('d-m-Y');
                    $queryRes->PO_Dt=(new Carbon($queryRes->PO_Dt))->format('d-m-Y');
                    $queryRes->Inv_Dt=(new Carbon($queryRes->Inv_Dt))->format('d-m-Y');
                    $queryRes->Reapproved_Dt=(new Carbon($queryRes->Reapproved_Dt))->format('d-m-Y');

                    $queryRes->No =$key+1;
                    $queryRes->Awb ='-';
                    $queryRes->DispMode ='-';
                    });
            break;
        }


        
        return Datatables::of($queryRes)->make();
}

}
