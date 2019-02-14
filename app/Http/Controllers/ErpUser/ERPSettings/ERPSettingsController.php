<?php

namespace App\Http\Controllers\ErpUser\ERPSettings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Response;
use View;
use DB;
use Auth;
use App\Erpuser;
use App\ErpuserBuyerMapping;
use App\ErpuserCompanyMapping;

class ERPSettingsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
		$role = "ADMIN";
        $this->middleware('erpuser');
        $this->middleware('role_permission:'.$role)->except('index');
   
    }
    /**
     * Show the users list.
     * @author [Gba] <[guberakannan@wizroots.com]>
     * @return \Illuminate\Http\Response
     */
    public function home()
    {
		return view('erp.Settings.home');
	}
}
