<?php
namespace App\Classes;

use DB,Auth;

class ErpCompanySession
{
    public function getErpCompanyMapping()
    {
        return 	DB::table('erpuser_company_mapping')
                ->join('company','erpuser_company_mapping.company','company.id')
                ->where('erpuser_company_mapping.erp_user',Auth::guard('erpuser')->id())
                ->select('erpuser_company_mapping.*','company.name', 'company.logo')
                ->get();
    }

    public function setCompanySession()
    {
        $companies = $this->getErpCompanyMapping();

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

        return $companies;
    }
    
}