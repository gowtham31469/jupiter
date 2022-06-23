<?php

namespace App\Http\Controllers\ErpUser\NonArtworkJobmanager;

use DB;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classes\ErpUserMappedTemplates;
use Illuminate\Database\Eloquent\Collection;

class UpdateNATJobcardController extends Controller
{
    public function getNonArtworkJobcardList()
    {

        $companies   = 	DB::table('erpuser_company_mapping')
									->join('company','erpuser_company_mapping.company','company.id')
									->where('erpuser_company_mapping.erp_user',Auth::guard('erpuser')->id())
									->select('erpuser_company_mapping.*','company.name', 'company.logo')
									->get();
        if(!$companies){
            return view('erp.home');
        }
                      
        if(session()->get('company') == "" && session()->get('company') == null){
            $company_data   = DB::table('company')
                            ->where('company.id', $companies[0]->company)
                            ->select('company.id', 'company.name')
                            ->first();

            session()->put('company', $company_data->id);
            session()->put('company_name', strtolower( str_replace(' ','',$company_data->name)) );
        }
        
		$erp_user_mapped_template_obj = new ErpUserMappedTemplates();
        $new_orders = $erp_user_mapped_template_obj->getPermittedTemplates();
        $buyers_array = array();

        foreach ($new_orders as $key => $values) {
            $split_key = explode('-', $key);
            $buyer_id = $split_key[0];
            $this->buyer_name = DB::table('buyers')->where('id', $buyer_id)->value('buyer_name');
            $buyers_array[$buyer_id] = $this->buyer_name;
        }

        return view('erp.Non-Artwork-Jobmanager.non-artwork-jobcards-list', ['buyers' => $buyers_array, 'companies' => $companies]);	
    }

    	/**
	 * Function to get jobcard id list by  buyer id and customer id.
	 * @author <Praveen>
	 * @param  $buyer_id, $customer_id
	 * @return equivalent jobcard ids list
	 */  
	public function getNatJobcardIdListByBuyerCustomerId(Request $request)
	{
        try {
            if(session()->get('company') == "" || session()->get('company') == null){
                return view('erp.home');
            }

            if($request['selected_buyer_id'] == null || $request['selected_customer_id'] == null){
                return response()->json(['error'=> true,'message' =>'Parameter Missing'], 400);
            }

            $jobcard_id_list  = DB::table('jobcard_main_table')
                                    ->where('jobcard_main_table.company_id', session()->get('company'))
                                    ->where('jobcard_main_table.buyer_id', $request['selected_buyer_id'])
                                    ->where('jobcard_main_table.customer_id', $request['selected_customer_id'])
                                    ->pluck('jobcard_main_table.work_order_id','jobcard_main_table.id')
                                    ->all();
            return response()->json(['error'=> false,'message' =>'Success', 'jobcard_id_list' => $jobcard_id_list], 200);
                

        } catch (\Exception $e) {
            return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
	}

    /**
	 * Function to get jobcards list by  buyer id, customer id and jobcard id.
	 * @author <Praveen>
	 * @param  $buyer_id, $customer_id, $jobcard_id
	 * @return equivalent jobcard list
	 */  
	public function getNatJobcardsListByBuyerCustomerJobcardId(Request $request)
	{
		try {
            if(session()->get('company') == "" || session()->get('company') == null){
                return view('erp.home');
            }

            if($request['selected_buyer_id'] == null || $request['selected_customer_id'] == null || $request['selected_jobcard_id'] == null){
                return response()->json(['error'=> true,'message' =>'Parameter Missing'], 400);
            }

            $jobcards_list  = DB::table('jobcard_main_table')
                                    ->join('customers', 'jobcard_main_table.customer_id', 'customers.id')
                                    ->join('buyers', 'jobcard_main_table.buyer_id', 'buyers.id')
                                    ->where('jobcard_main_table.company_id', '=', session()->get('company'))
                                    ->where('jobcard_main_table.buyer_id', '=', $request['selected_buyer_id'])
                                    ->where('jobcard_main_table.customer_id', '=', $request['selected_customer_id'])
                                    ->where('jobcard_main_table.id', '=', $request['selected_jobcard_id'])
                                    ->select('jobcard_main_table.id as jobcard_id', 'jobcard_main_table.work_order_id', 'customers.company_name', 'buyers.buyer_name')
                                    ->first();
            return response()->json(['error'=> false,'message' =>'Success', 'jobcards_list' => $jobcards_list], 200);
                

        } catch (\Exception $e) {
            return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
	}
}
