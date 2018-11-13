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
use App\ErpOrders as Erp;
use App\ErpOrderDetails;

class Invoice
{
	public function setCompany()
	{
		$customer_id = Auth::guard('erpuser')->id();
            
        $companies   =  DB::table('erpuser_company_mapping')
                                ->join('company','erpuser_company_mapping.company','company.id')
                                ->where('erpuser_company_mapping.erp_user',$customer_id)
                                ->select('erpuser_company_mapping.*','company.name')
                                ->get();
        
        $company_id = DB::table('company')
                                ->select('company.*')
                                ->first();
        
        
        if(session()->get('company') == "" && session()->get('company') == null)
        {
            session()->put('company', $company_id->id);
            session()->put('company_name', strtolower( str_replace(' ','',$company_id->name)) );
        }
        return $companies;
	}
}