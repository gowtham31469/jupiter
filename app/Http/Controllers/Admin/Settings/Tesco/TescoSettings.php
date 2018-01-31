<?php

namespace App\Http\Controllers\Admin\Settings\Tesco;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classes\Modules;
use Response;
use View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use DB;

class TescoSettings extends Controller
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
     * Tesco metadata garments list show using ajax.
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function tescoBrands()
    {
      try {
            $tesco_brand = DB::table('tesco_brand')->select('id','brnad','is_enabled')->get();
            return response()->json(['error'=>'false','message' =>'success', 'tesco_brands' => $tesco_brand]);
          } catch (\Exception $e)
          {
                 return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
          }
    }
}
