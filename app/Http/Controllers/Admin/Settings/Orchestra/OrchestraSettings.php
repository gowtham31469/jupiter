<?php

namespace App\Http\Controllers\Admin\Settings\Orchestra;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classes\Modules;
use Response;
use View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use DB;
use App\OrchestraGarments;
/**
 * Orchestra Age Group controller
 * @author <Praveen>
 * @date(03/01/2018)
 */
class OrchestraSettings extends Controller
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
     * Show the orchestra age group table view.
     *
     * @return \Illuminate\Http\Response
     * @author <Praveen>
     */
    public function index()
    {
        $module_var = new Modules();
        $is_allowed = $module_var->permittedModules();
        if($is_allowed)
        {
            $get_main_module = $module_var->getMainModules();
            $get_sub_module = $module_var->getSubModules(40);
            View::composer('layouts.Admin.navbar', function($view) use ($get_main_module,$get_sub_module) {
                return $view->with(['modules' => $get_main_module,'sub_modules' => $get_sub_module]);
            });
            $buyers = DB::table('buyers')->select('*')->get();
            return view('Admin/Settings/Orchestra/home')->with(['buyers' => $buyers]);
        }
        else
        {
             return redirect('/admin/dashboard');
        }
    }
    /**
     * Orchestra metadata garments list show using ajax.
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function orchestraGarments()
    {
      try {
            $orchestra_garments = DB::table('orchestra_garments')->select('id','garment_name')->get();
            return response()->json(['error'=>'false','message' =>'success', 'orchestra_garments' => $orchestra_garments]);
          } catch (\Exception $e)
          {
                 return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
          }
    }
    /**
     * Add orchestra garments.
     * @param \Illuminate\Http\Request
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function addOrchestraGarments(Request $request)
    {
        try {
            $inserted = OrchestraGarments::create([
                        'garment_name' => $request['name']
                      ]);
            if($inserted != '')
            {
              $result = response()->json(['error'=> false,'message' =>'success'],200);
            }
            else
            {
              $result = response()->json(['error'=> true,'message' =>'Error'],400);
            }

        } catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
    }
    /**
     * Get orchestra garment details by ID for editing.
     * @param orchestra garment id
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function getOrchestraGarmentsById(Request $request)
    {
    try {
          $id = $request['id'];
          $orchestra_garments = DB::table('orchestra_garments')->select('id','garment_name')->where('orchestra_garments.id',$id)->get();
          return response()->json(['error'=>'false','message' =>'success', 'orchestra_garments' => $orchestra_garments]);
        } catch (\Exception $e)
        {
          return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
    }
    /**
    * Update garment by id.
    * @param garment id
    * @author <Praveen>
    * @return \Illuminate\Http\Response
    */
    public function updateOrchestraGarmentsById(Request $request)
    {
    try {
          $id        = $request['id'];
          $name      = $request['name'];
          
          OrchestraGarments::where('id',$id)
           ->update([
             'garment_name' => $name
          ]);

          $result = response()->json(['error'=> false,'message' =>'Success'],200);
        
        } catch (\Exception $e)
        {
               $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
    }
    /**
     * function to delete buyer by id.
     * @param buyer id
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function deleteOrchestraGarmentsById(Request $request)
    {
      try {
                $id        = $request['id'];
                $deleted = OrchestraGarments::where('id', '=', $id)
                            ->delete();
                if($deleted != '')
                {
                  $result = response()->json(['error'=> false,'message' =>'success'],200);
                }
                else
                {
                  $result = response()->json(['error'=> true,'message' =>'Error'],400);
                }
          } catch (\Exception $e)
          {
                $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
          }
          return $result;
    }
}
