<?php
namespace App\Classes;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Response;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use App\Customer;
use App\CustomerBillAddress;
use DB;
use Auth;
use App\RolePermissionMdl;
use Carbon\Carbon;
use App\ErpOrders;
use App\ErpOrderDetails;

class ErpOrders
{

	public function updateErpOrders($buyer_id,$po_number,$orders,$order_details)
	{
		try
		{
			ErpOrders::create([
					'buyer_id'         => $buyer_id,
                    'customer_id'      => $data['company_details']->id,
                    'customer_id'      => $data['bank_details']->id,
				]);
		}
        catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
	}
}