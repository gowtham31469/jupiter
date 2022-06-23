<?php

namespace App\Http\Controllers\ErpUser\NonArtworkJobmanager;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DB;

class UpdateNATJobcardController extends Controller
{
    public function getNonArtworkJobcardList()
    {
        try {
            $erpuser_id = Auth::guard('erpuser')->id();
            
            $companies   = 	DB::table('erpuser_company_mapping')
                                    ->join('company','erpuser_company_mapping.company','company.id')
                                    ->where('erpuser_company_mapping.erp_user',$erpuser_id)
                                    ->select('erpuser_company_mapping.*','company.name', 'company.logo','company.id')
                                    ->get();
            if($companies)
            {
                $company_id   = DB::table('company')
                                ->select('company.*')
                                ->where('company.id', '=', $companies[0]->company)
                                ->first();
            }
            else
            {
                return view('erp.home');
            }
            
            
            if(session()->get('company') == "" && session()->get('company') == null)
            {
                session()->put('company', $company_id->id);
                session()->put('company_name', strtolower( str_replace(' ','',$company_id->name)) );
            }
            /**
             * Dropdown values
             */
            $buyers  = 	DB::table('buyers')
                            ->join('jobcard_main_table', 'jobcard_main_table.buyer_id', 'buyers.id')
                            ->where('jobcard_main_table.company_id', '=', session()->get('company'))
                            ->pluck('buyers.buyer_name','buyers.id')
                            ->all();
            
            return view('erp.Non-Artwork-Jobmanager.non-artwork-jobcards-list', ['buyers' => $buyers, 'companies' => $companies]);	
        } catch (\Exception $e) {
            return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
    }
}
