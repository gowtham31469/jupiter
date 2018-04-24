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
use App\TescoSupplier;
use App\TescoSizeChart;

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
            $tesco_brand = DB::table('tesco_brand')->select('id','brand','is_enabled')->orderBy('is_enabled','desc')->get();
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
       * Make Brand disabled
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
            $tesco_department = DB::table('tesco_department')->select('id','department','pantone','rbg','is_enabled')->orderBy('is_enabled','desc')->get();
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

   /**
     * Get Tesco Departments details by ID for editing.
     * @param Tesco Department id
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function getTescoDepartmentsById(Request $request)
    {
    try {
          $id = $request['id'];
          $tesco_department = DB::table('tesco_department')->select('id','department','pantone','rbg')->where('tesco_department.id',$id)->get();
          return response()->json(['error'=>'false','message' =>'success', 'tesco_departments' => $tesco_department]);
        } catch (\Exception $e)
        {
          return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
    }
   /**
    * Update departments by id.
    * @param department id
    * @author <Praveen>
    * @return \Illuminate\Http\Response
    */
    public function updateTescoDepartmentsById(Request $request)
    {
    try {
          $id        = $request['id'];
          $department      = $request['department'];
          $pantone = $request['pantone'];
          $rbg = $request['rbg'];

          
          TescoDepartment::where('id',$id)
           ->update([
             'department' => $department,
             'pantone' => $pantone,
             'rbg' => $rbg
          ]);

          $result = response()->json(['error'=> false,'message' =>'Success'],200);
        
        } catch (\Exception $e)
        {
               $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
    }
     /**
       * Make Department enabled
       * @param Department id
       * @author <Praveen>
       * @return boolean true or false
       */
      public function makeDepartmentEnabled(Request $request)
      {
        try {
              $id      = $request['id'];
              
              TescoDepartment::where('id',$id)
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
       * Make Department disabled
       * @author <Praveen>
       * @return boolean true or false
       */
      public function makeDepartmentDisabled(Request $request)
      {
        try {
              $id      = $request['id'];
              
              TescoDepartment::where('id',$id)
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
     * Tesco metadata supplier list show using ajax.
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function tescoSuppliers()
    {
      try {
            $tesco_suppliers = DB::table('tesco_suppliers')->select('id','supplier_name','supplier_code','is_enabled')->orderBy('is_enabled','desc')->get();
            return response()->json(['error'=>'false','message' =>'success', 'tesco_suppliers' => $tesco_suppliers]);
          } catch (\Exception $e)
          {
                 return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
          }
    }

   /**
     * Add Tesco Suppliers.
     * @param \Illuminate\Http\Request
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function addTescoSuppliers(Request $request)
    {
        try {
            $inserted = TescoSupplier::create([
                        'supplier_name' => $request['supplier_name'],
                        'supplier_code' => $request['supplier_code']
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
     * Get Tesco Suppliers details by ID for editing.
     * @param Tesco Suppliers id
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function getTescoSuppliersById(Request $request)
    {
    try {
          $id = $request['id'];
          $tesco_suppliers = DB::table('tesco_suppliers')->select('id','supplier_name','supplier_code')->where('tesco_suppliers.id',$id)->get();
          return response()->json(['error'=>'false','message' =>'success', 'tesco_suppliers' => $tesco_suppliers]);
        } catch (\Exception $e)
        {
          return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
    }
   /**
    * Update suppliers by id.
    * @param supplier id
    * @author <Praveen>
    * @return \Illuminate\Http\Response
    */
    public function updateTescoSuppliersById(Request $request)
    {
    try {
          $id        = $request['id'];
          $supplier_name      = $request['supplier_name'];
          $supplier_code = $request['supplier_code'];
        
          
          TescoSupplier::where('id',$id)
           ->update([
             'supplier_name' => $supplier_name,
             'supplier_code' => $supplier_code
          ]);

          $result = response()->json(['error'=> false,'message' =>'Success'],200);
        
        } catch (\Exception $e)
        {
               $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
    }
   /**
     * Make supplier enabled
     * @param supplier id
     * @author <Praveen>
     * @return boolean true or false
     */
    public function makeSupplierEnabled(Request $request)
    {
      try {
            $id      = $request['id'];
            
            TescoSupplier::where('id',$id)
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
       * Make Supplier disabled
       * @author <Praveen>
       * @return boolean true or false
       */
      public function makeSupplierDisabled(Request $request)
      {
        try {
              $id      = $request['id'];
              
              TescoSupplier::where('id',$id)
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
       * Tesco metadata Size Chart list show using ajax.
       * @author <Praveen>
       * @return \Illuminate\Http\Response
       */
      public function tescoSizeChart()
      {
        try {
              $tesco_size_chart = DB::table('tesco_size_chart')->select('id','po_size','uk','ce','is_enabled')->orderBy('is_enabled','desc')->get();
              return response()->json(['error'=>'false','message' =>'success', 'tesco_sizecharts' => $tesco_size_chart]);
            } catch (\Exception $e)
            {
                   return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
            }
      }

   /**
     * Add Tesco Size chart.
     * @param \Illuminate\Http\Request
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function addTescoSizeCharts(Request $request)
    {
        try {
            $inserted = TescoSizeChart::create([
                        'po_size' => $request['po_size'],
                        'uk' => $request['uk'],
                        'ce' => $request['ce']
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
     * Get Tesco size chart details by ID for editing.
     * @param Tesco size chart id
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function getTescoSizeChartsById(Request $request)
    {
    try {
          $id = $request['id'];
          $tesco_size_chart = DB::table('tesco_size_chart')->select('id','po_size','uk','ce')->where('tesco_size_chart.id',$id)->get();
          return response()->json(['error'=>'false','message' =>'success', 'tesco_sizecharts' => $tesco_size_chart]);
        } catch (\Exception $e)
        {
          return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
    }
   /**
    * Update size chart by id.
    * @param sizechart id
    * @author <Praveen>
    * @return \Illuminate\Http\Response
    */
    public function updateTescoSizechartsById(Request $request)
    {
    try {
          $id        = $request['id'];
          $po_size      = $request['po_size'];
          $uk = $request['uk'];
          $ce = $request['ce'];
        
          
          TescoSizeChart::where('id',$id)
           ->update([
             'po_size' => $po_size,
             'uk' => $uk,
             'ce' => $ce
          ]);

          $result = response()->json(['error'=> false,'message' =>'Success'],200);
        
        } catch (\Exception $e)
        {
               $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
    }
    /**
       * Make SizeChart enabled
       * @param SizeChart id
       * @author <Praveen>
       * @return boolean true or false
       */
      public function makeSizeChartEnabled(Request $request)
      {
        try {
              $id      = $request['id'];
              
              TescoSizeChart::where('id',$id)
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
       * Make SizeChart disabled
       * @author <Praveen>
       * @return boolean true or false
       */
      public function makeSizeChartDisabled(Request $request)
      {
        try {
              $id      = $request['id'];
              
              TescoSizeChart::where('id',$id)
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
    
}

