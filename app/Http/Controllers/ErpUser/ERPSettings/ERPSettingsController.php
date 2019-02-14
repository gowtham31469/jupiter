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
     * Show the erp setings home.
     * @author [Praveen] <[praveen@wizroots.com]>
     * @return \Illuminate\Http\Response
     */
    public function home()
    {
		return view('erp.Settings.home');
    }
    
    /**
     * Show the get dispatch mode list.
     * @author [Praveen] <[praveen@wizroots.com]>
     * @return \Illuminate\Http\Response
     */
    public function getDispatchMode()
    {
     try {
            $erp_dispatch_mode = DB::table('erp_dispatch_mode')->select('*')->get();
            return response()->json(['error'=>'false','message' =>'success', 'erp_dispatch_mode' => $erp_dispatch_mode]);
         } catch (\Exception $e)
         {
            return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
         }
    }
    
    /**
     * To add dispatch mode.
     * @author [Praveen] <[praveen@wizroots.com]>
     * @return \Illuminate\Http\Response
     */
    public function addDispatchMode(Request $request)
    {
        try {
                /** @var array [Database Unique field validation] */
                $is_exists = DB::table('erp_dispatch_mode')->where('disp_mode','ilike',$request['disp_mode'])->where('description','ilike',$request['description'])->select('id')->get();
                if(sizeof($is_exists) > 0)
                {
                    $result = response()->json(['error'=> true,'message' =>'Dispatch Mode Already Exists'],208);
                }
                else
                {
                    $inserted = DB::table('erp_dispatch_mode')->insert([
                            'disp_mode'   => $request['disp_mode'],
                            'description' => $request['description'],
                            'is_enabled'  => true
                            ]);
                    $result = $inserted != ''?response()->json(['error'=> false,'message' =>'success'],200):response()->json(['error'=> true,'message' =>'Error'],400);
                }
            } catch (\Exception $e)
            {
                $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
            }
            return $result;
    }
    
    /**
     * Get dispatch mode details by ID for editing.
     * @param id dispatch mode
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function getDispatchModeById(Request $request)
    {
    try {
          $id = $request['id'];
          $erp_dispatch_mode = DB::table('erp_dispatch_mode')->select('*')->where('erp_dispatch_mode.id',$id)->get();
          return response()->json(['error'=>'false','message' =>'success', 'erp_dispatch_mode' => $erp_dispatch_mode]);
        } catch (\Exception $e)
        {
          return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
    }

    /**
    * Update dispatch mode by id.
    * @param $request
    * @author <Praveen>
    * @return \Illuminate\Http\Response
    */
    public function updateDispatchModeById(Request $request)
    {
    try {
          /** @var array [Database Unique field validation] */
          $is_exists = DB::table('erp_dispatch_mode')->where('disp_mode','ilike',$request['dispatch_mode'])->where('description','ilike',$request['description'])->where('id','<>',$request['id'])->select('id')->get();
          if(sizeof($is_exists) > 0)
          {
              $result = response()->json(['error'=> true,'message' =>'Dispatch Mode Already Exists'],208);
          }
          else
          {
            DB::table('erp_dispatch_mode')->where('id',$request['id'])
             ->update([
                'disp_mode'   => $request['dispatch_mode'],
                'description' => $request['description']
            ]);

            $result = response()->json(['error'=> false,'message' =>'Success'],200);
          }

        } catch (\Exception $e)
        {
          $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
    }
    /**
     * Make Dispatch Mode enabled
     * @param id
     * @author <Praveen>
     * @return boolean true or false
     */
    public function makeDispatchModeEnabled(Request $request)
    {
    try {
            DB::table('erp_dispatch_mode')->where('id',$request['id'])
            ->update([
                'is_enabled'  => true
            ]);
            $result = response()->json(['error'=> false,'message' =>'Success'],200);
        } 
        catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
    }
      /**
       * Make Dispatch Mode disabled
       * @author <Praveen>
       * @return boolean true or false
       */
      public function makeDispatchModeDisabled(Request $request)
      {
        try {
              $id      = $request['id'];
              // Check if this dispatch mode is in use or not 
              $dispatch_mode = DB::table('jobcard_main_table')->where('dispatch_mode','=',$id)->pluck('id')->all();

             if((sizeof($dispatch_mode) > 0))
              {
                $result = response()->json(['error'=> false,'message' =>'Dispatch mode is in use.'],208);
              }
              else
              {
                DB::table('erp_dispatch_mode')->where('id',$request['id'])
                ->update([
                    'is_enabled'  => false
                ]);
                $result = response()->json(['error'=> false,'message' =>'Success'],200);
              }
            } 
            catch (\Exception $e)
            {
              $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
            }
            return $result;
        }
}
