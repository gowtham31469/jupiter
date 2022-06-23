<?php
namespace App\Classes;
use DB;
use App\ErpOrders as Erp;
use Carbon\Carbon;
use App\FinancialYearResetDate;

class JobcardMetadata
{
	public function erpJobcardMetaData()
	{
		$data['erp_dispatch_mode'] = DB::table('erp_dispatch_mode')
        ->where('is_enabled', true)
        ->pluck('disp_mode', 'id')
        ->all();
        $data['erp_finish_type'] = DB::table('erp_finish_type')
        ->where('is_enabled', true)
        ->pluck('finish_type', 'id')
        ->all();
        $data['erp_machines'] = DB::table('erp_machines')
        ->where('is_enabled', true)
        ->pluck('machine', 'id')
        ->all();
        $data['erp_teeth_no'] = DB::table('erp_teeth_no')
        ->where('is_enabled', true)
        ->pluck('teeth_no', 'id')
        ->all();

        return $data;
    }
    
}