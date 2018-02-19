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
			$data = DB::table('tesco_polybag_carton_sticker_orders as order')
									->join('tesco_sticker_orders_size_details as size','order.id','=','size.tesco_order_id')
									->join('tesco_sticker_orders_count as count','size.id','=','count.tesco_order_id')
									->select('count.template_name','order.order_id','order.zone')
									->get();
	     	$result = response()->json(['error'=> false,'message' =>'success','data'=>$data],200);            
        }
        catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
	}
}