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
use App\FinancialYearResetDate;

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
     * Show the get Machine list.
     * @author [gowtham] <[gowtham@wizroots.com]>
     * @return \Illuminate\Http\Response
     */
    public function getMachine()
    {
     try {
            $erp_machines = DB::table('erp_machines')->select('*')->get();
            return response()->json(['error'=>'false','message' =>'success', 'erp_machines' => $erp_machines]);
         } catch (\Exception $e)
         {
            return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
         }
    }
    /**
     * Show the get Teeth Number list.
     * @author [gowtham] <[gowtham@wizroots.com]>
     * @return \Illuminate\Http\Response
     */
    public function getTeethNo()
    {
     try {
            $erp_teeth_no = DB::table('erp_teeth_no')->select('*')->get();
            return response()->json(['error'=>'false','message' =>'success', 'erp_teeth_no' => $erp_teeth_no]);
         } catch (\Exception $e)
         {
            return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
         }
    }  
    
    /**
     * Show the get Finish Type list.
     * @author [gowtham] <[gowtham@wizroots.com]>
     * @return \Illuminate\Http\Response
     */
    public function getFinishtype()
    {
     try {
            $erp_finish_type = DB::table('erp_finish_type')->select('*')->get();
            return response()->json(['error'=>'false','message' =>'success', 'erp_finish_type' => $erp_finish_type]);
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
     * To add Machine.
     * @author [gowtham] <[gowtham@wizroots.com]>
     * @return \Illuminate\Http\Response
     */
    public function addMachine(Request $request)
    {
        try {
                /** @var array [Database Unique field validation] */
                $is_exists = DB::table('erp_machines')->where('machine','ilike',$request['machine'])->where('description','ilike',$request['description'])->select('id')->get();
                if(sizeof($is_exists) > 0)
                {
                    $result = response()->json(['error'=> true,'message' =>'Machine Already Exists'],208);
                }
                else
                {
                    $inserted = DB::table('erp_machines')->insert([
                            'machine'   => $request['machine'],
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
     * To add Teeth No.
     * @author [gowtham] <[gowtham@wizroots.com]>
     * @return \Illuminate\Http\Response
     */
    public function addTeethNo(Request $request)
    {
        try {
                /** @var array [Database Unique field validation] */
                $is_exists = DB::table('erp_teeth_no')->where('teeth_no','ilike',$request['teeth_no'])->where('description','ilike',$request['description'])->select('id')->get();
                if(sizeof($is_exists) > 0)
                {
                    $result = response()->json(['error'=> true,'message' =>'Teeth Number Already Exists'],208);
                }
                else
                {
                    $inserted = DB::table('erp_teeth_no')->insert([
                            'teeth_no'   => $request['teeth_no'],
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
     * To add Finish Type.
     * @author [gowtham] <[gowtham@wizroots.com]>
     * @return \Illuminate\Http\Response
     */
    public function addFinishType(Request $request)
    {
        try {
                /** @var array [Database Unique field validation] */
                $is_exists = DB::table('erp_finish_type')->where('finish_type','ilike',$request['finish_type'])->where('description','ilike',$request['description'])->select('id')->get();
                if(sizeof($is_exists) > 0)
                {
                    $result = response()->json(['error'=> true,'message' =>'Finish Type Already Exists'],208);
                }
                else
                {
                    $inserted = DB::table('erp_finish_type')->insert([
                            'finish_type'   => $request['finish_type'],
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
     * Get Machine details by ID for editing.
     * @param id machine
     * @author <gowtham>
     * @return \Illuminate\Http\Response
     */
    public function getMachineById(Request $request)
    {
    try {
          $id = $request['id'];
          $erp_machines = DB::table('erp_machines')->select('*')->where('erp_machines.id',$id)->get();
          return response()->json(['error'=>'false','message' =>'success', 'erp_machines' => $erp_machines]);
        } catch (\Exception $e)
        {
          return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
    }
    /**
     * Get TeethNo details by ID for editing.
     * @param id TeethNo
     * @author <gowtham>
     * @return \Illuminate\Http\Response
     */
    public function getTeethNoById(Request $request)
    {
    try {
          $id = $request['id'];
          $erp_teeth_no = DB::table('erp_teeth_no')->select('*')->where('erp_teeth_no.id',$id)->get();
          return response()->json(['error'=>'false','message' =>'success', 'erp_teeth_no' => $erp_teeth_no]);
        } catch (\Exception $e)
        {
          return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
    } 
    /**
     * Get Finish Type details by ID for editing.
     * @param id TeethNo
     * @author <gowtham>
     * @return \Illuminate\Http\Response
     */
    public function getFinishTypeById(Request $request)
    {
    try {
          $id = $request['id'];
          $erp_finish_type = DB::table('erp_finish_type')->select('*')->where('erp_finish_type.id',$id)->get();
          return response()->json(['error'=>'false','message' =>'success', 'erp_finish_type' => $erp_finish_type]);
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
    * Update machine by id.
    * @param $request
    * @author <gowtham>
    * @return \Illuminate\Http\Response
    */
    public function updateMachineById(Request $request)
    {
    try {
          /** @var array [Database Unique field validation] */
          $is_exists = DB::table('erp_machines')->where('machine','ilike',$request['machine'])->where('description','ilike',$request['description'])->where('id','<>',$request['id'])->select('id')->get();
          if(sizeof($is_exists) > 0)
          {
              $result = response()->json(['error'=> true,'message' =>'Machine Already Exists'],208);
          }
          else
          {
            DB::table('erp_machines')->where('id',$request['id'])
             ->update([
                'machine'   => $request['machine'],
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
    * Update teeth no by id.
    * @param $request
    * @author <gowtham>
    * @return \Illuminate\Http\Response
    */
    public function updateTeethNoById(Request $request)
    {
    try {
          /** @var array [Database Unique field validation] */
          $is_exists = DB::table('erp_teeth_no')->where('teeth_no','ilike',$request['teeth_no'])->where('description','ilike',$request['description'])->where('id','<>',$request['id'])->select('id')->get();
          if(sizeof($is_exists) > 0)
          {
              $result = response()->json(['error'=> true,'message' =>'Teeth No Already Exists'],208);
          }
          else
          {
            DB::table('erp_teeth_no')->where('id',$request['id'])
             ->update([
                'teeth_no'   => $request['teeth_no'],
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
    * Update finish type by id.
    * @param $request
    * @author <gowtham>
    * @return \Illuminate\Http\Response
    */
    public function updateFinishTypeById(Request $request)
    {
    try {
          /** @var array [Database Unique field validation] */
          $is_exists = DB::table('erp_finish_type')->where('finish_type','ilike',$request['finish_type'])->where('description','ilike',$request['description'])->where('id','<>',$request['id'])->select('id')->get();
          if(sizeof($is_exists) > 0)
          {
              $result = response()->json(['error'=> true,'message' =>'Finish Type Already Exists'],208);
          }
          else
          {
            DB::table('erp_finish_type')->where('id',$request['id'])
             ->update([
                'finish_type'   => $request['finish_type'],
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
     * Make Machine enabled
     * @param id
     * @author <gowtham>
     * @return boolean true or false
     */
    public function makeMachineEnabled(Request $request)
    {
    try {
            DB::table('erp_machines')->where('id',$request['id'])
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
     * Make Teeth No enabled
     * @param id
     * @author <gowtham>
     * @return boolean true or false
     */
    public function makeTeethNoEnabled(Request $request)
    {
    try {
            DB::table('erp_teeth_no')->where('id',$request['id'])
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
     * Make Finish Type enabled
     * @param id
     * @author <gowtham>
     * @return boolean true or false
     */
    public function makeFinishTypeEnabled(Request $request)
    {
    try {
            DB::table('erp_finish_type')->where('id',$request['id'])
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
    
    /**
       * Make Machine disabled
       * @author <gowtham>
       * @return boolean true or false
       */
      public function makeMachineDisabled(Request $request)
      {
        try {
              $id      = $request['id'];
              // Check if this dispatch mode is in use or not 
              $dispatch_mode = DB::table('jobcard_main_table')->where('machine','=',$id)->pluck('id')->all();

             if((sizeof($dispatch_mode) > 0))
              {
                $result = response()->json(['error'=> false,'message' =>'Machine is in use.'],208);
              }
              else
              {
                DB::table('erp_machines')->where('id',$request['id'])
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
    /**
       * Make Teeth No disabled
       * @author <gowtham>
       * @return boolean true or false
       */
      public function makeTeethNoDisabled(Request $request)
      {
        try {
              $id      = $request['id'];
              // Check if this dispatch mode is in use or not 
              $dispatch_mode = DB::table('jobcard_main_table')->where('teeth_no','=',$id)->pluck('id')->all();

             if((sizeof($dispatch_mode) > 0))
              {
                $result = response()->json(['error'=> false,'message' =>'Teeth No is in use.'],208);
              }
              else
              {
                DB::table('erp_teeth_no')->where('id',$request['id'])
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
    
    /**
       * Make Finish Type disabled
       * @author <gowtham>
       * @return boolean true or false
       */
      public function makeFinishTypeDisabled(Request $request)
      {
        try {
              $id      = $request['id'];
              // Check if this dispatch mode is in use or not 
              $dispatch_mode = DB::table('jobcard_main_table')->where('finish_type','=',$id)->pluck('id')->all();

             if((sizeof($dispatch_mode) > 0))
              {
                $result = response()->json(['error'=> false,'message' =>'Finish Type is in use.'],208);
              }
              else
              {
                DB::table('erp_finish_type')->where('id',$request['id'])
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

    /**
     * Show the financial year data.
     * @author [praveen] <[praveen@wizroots.com]>
     * @return \Illuminate\Http\Response
     */
    public function getFinancialYear()
    {
     try {
            $erp_financial_year = FinancialYearResetDate::select('id', 'financial_year_date')->get();
            return response()->json(['error'=>'false','message' =>'success', 'erp_financial_year' => $erp_financial_year]);
         } catch (\Exception $e)
         {
            return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
         }
    }

    /**
     * Update the financial year date.
     * @author [praveen] <[praveen@wizroots.com]>
     * @return \Illuminate\Http\Response
     */
    public function updateFinancialYearDateById(Request $request)
    {
     try {
            FinancialYearResetDate::find($request['id'])
                  ->update([
                      'financial_year_date'  => $request['date']
                  ]);
            return response()->json(['error'=> false,'message' =>'Success'],200);
         } catch (\Exception $e)
         {
            return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
         }
    }
}
