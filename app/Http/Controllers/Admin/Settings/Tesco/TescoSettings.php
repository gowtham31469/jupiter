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
use App\TescoBrand;
use App\TescoDepartment;

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
     * Tesco metadata Brands list show using ajax.
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function tescoBrands()
    {
      try {
            $tesco_brand = DB::table('tesco_brand')->select('id','brand','is_enabled')->get();
            return response()->json(['error'=>'false','message' =>'success', 'tesco_brands' => $tesco_brand]);
          } catch (\Exception $e)
          {
                 return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
          }
    }

    /**
     * Add Tesco Brands.
     * @param \Illuminate\Http\Request
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function addTescoBrands(Request $request)
    {
        try {
            $inserted = TescoBrand::create([
                        'brand' => $request['name']
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
     * Get Tesco Brands details by ID for editing.
     * @param Tesco brand id
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function getTescoBrandsById(Request $request)
    {
    try {
          $id = $request['id'];
          $tesco_brand = DB::table('tesco_brand')->select('id','brand','is_enabled')->where('tesco_brand.id',$id)->get();
          return response()->json(['error'=>'false','message' =>'success', 'tesco_brands' => $tesco_brand]);
        } catch (\Exception $e)
        {
          return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
    }
    /**
    * Update Brands by id.
    * @param brand id
    * @author <Praveen>
    * @return \Illuminate\Http\Response
    */
    public function updateTescoBrandsById(Request $request)
    {
    try {
          $id        = $request['id'];
          $name      = $request['name'];
          
          TescoBrand::where('id',$id)
           ->update([
             'brand' => $name
          ]);

          $result = response()->json(['error'=> false,'message' =>'Success'],200);
        
        } catch (\Exception $e)
        {
               $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
    }
/**
     * function to delete brand by id.
     * @param brand id
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function deleteTescoBrandsById(Request $request)
    {
      try {
            $id        = $request['id'];
            $deleted = TescoBrand::where('id', '=', $id)
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

    /**
       * Make Brand enabled
       * @param brand id
       * @author <Praveen>
       * @return boolean true or false
       */
      public function makeBrandEnabled(Request $request)
      {
        try {
              $id      = $request['id'];
              
              TescoBrand::where('id',$id)
               ->update([
                 'is_enabled'  => 1
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
       * Make Brand diabled
       * @author <Praveen>
       * @return boolean true or false
       */
      public function makeBrandDisabled(Request $request)
      {
        try {
              $id      = $request['id'];
              
              TescoBrand::where('id',$id)
               ->update([
                 'is_enabled'  => 0
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
     * Tesco metadata Department list show using ajax.
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function tescoDepartments()
    {
      try {
            $tesco_department = DB::table('tesco_department')->select('id','department','pantone','rbg')->get();
            return response()->json(['error'=>'false','message' =>'success', 'tesco_department' => $tesco_department]);
          } catch (\Exception $e)
          {
                 return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
          }
    }

    /**
     * Add Tesco Department.
     * @param \Illuminate\Http\Request
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function addTescoDepartment(Request $request)
    {
        try {
            $inserted = TescoDepartment::create([
                        'department' => $request['department'],
                        'pantone' => $request['pantone'],
                        'rbg' => $request['rbg']
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
}
