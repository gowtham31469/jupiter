<?php

namespace App\Http\Controllers\Admin\Tesco;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use App\Classes\Modules;
use Response;
use View;
use DB;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Config;
use App\TescoStickerOrdersSizeDetails;
use App\TescoPolybagCartonStickerOrders;
use App\TescoStickerOrdersCount;
use App\Classes\TescoOrders;

class OrderStatusCntrl extends Controller
{

	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       $this->middleware('auth');
    }
    /**
     * mthod to show the tesco order status page
     * @return [type] [description]
     */
	public function index()
	{
		try
		{
			$data = DB::table('tesco_polybag_carton_sticker_orders')
									->join('tesco_sticker_orders_size_details','tesco_polybag_carton_sticker_orders.id','=','tesco_sticker_orders_size_details.tesco_order_id')
									->join('tesco_sticker_orders_count','tesco_sticker_orders_size_details.id','=','tesco_sticker_orders_count.tesco_order_id')
									->select('tesco_polybag_carton_sticker_orders.id','tesco_polybag_carton_sticker_orders.order_id as Order ID','tesco_sticker_orders_count.template_name as Template Name','tesco_polybag_carton_sticker_orders.zone as Zone','tesco_sticker_orders_count.count as Quantity','tesco_sticker_orders_count.artwork_status as Artwork Status','tesco_sticker_orders_count.po_number as PO Number','tesco_sticker_orders_count.po_status as PO Status','tesco_sticker_orders_count.pi_status as PI Status','tesco_sticker_orders_count.id as count_table_id',DB::raw('"-" as Actions'),'tesco_sticker_orders_count.po_file')
									->get();
            $data['tcl1'] = DB::table('tesco_polybag_carton_sticker_orders')
                            
	     	$result = response()->json(['error'=> false,'message' =>'success','data'=>$data],200);            
        }
        catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
	}

	public function setPONumber(Request $request)
	{
		try
		{
			$po_number   = $request->po_number;
			$template_id = $request->id;
			$data 		 = TescoStickerOrdersCount::where('id',$template_id)
						->update([
                          'po_number' => $po_number,
                          'po_status' => "PO Approved"
                        ]);
            if (count($data)>0)
            {
                $result = response()->json(['error'=> false,'message' =>'success'],200);
            }
            else
            {
                $result = response()->json(['error'=> true,'message' =>'Error'],400);
            }

		}
        catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
	}
}