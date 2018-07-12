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
use App\TescoTCL1Orders;
use App\TescoTCL1OrdersQuantity;
use App\TescoTCL2Orders;
use App\TescoTCL2OrdersQuantity;
use App\TescoTCL4Orders;
use App\TescoTCL4OrdersQuantity;
use App\TescoTHL1Orders;
use App\TescoTHL1OrdersQuantity;
use App\TescoTHL3Orders;
use App\TescoTHL3OrdersQuantity;
use App\TescoTSL1Orders;
use App\TescoTSL1OrdersQuantity;
use App\TescoTSL3Orders;
use App\TescoTSL3OrdersQuantity;

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

             /** @var array [Database Unique field validation] */
             $is_exists = array(
                 'name' => 'required|unique:tesco_brand,brand',
             );
             $validator = Validator::make($request->all() ,$is_exists);
             if($validator->fails())
             {
               $result = response()->json(['error'=> true,'message' =>'Brand Already Exists'],208);
             }
             else
             {
                $inserted = TescoBrand::create([
                        'brand' => $request['name']
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
          
          /** @var array [Database Unique field validation] */
          $rules     = array('name' => 'unique:tesco_brand,brand,'.$id);
          $Validator  = Validator::make($request->all(),$rules);

          if($Validator->fails())
          {
              return response()->json(['error'=> true,'message' =>'Brand Already Exists'],208);
          }
          else
          {
            TescoBrand::where('id',$id)
             ->update([
               'brand' => $name
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
              // Check if this brand is in use or not  while placing the order. 
              // Checking for 1 template order table from each combination.
              // (ie, TCL1 from TCL1, TCL2 and TSL1 combination)
              $tcl1_table_brands = TescoTCL1Orders::where('tesco_brand_id','=',$id)->pluck('id')->all();
              $tcl4_table_brands = TescoTCL4Orders::where('tesco_brand_id','=',$id)->pluck('id')->all();
              $thl1_table_brands = TescoTHL1Orders::where('tesco_brand_id','=',$id)->pluck('id')->all();
              $thl3_table_brands = TescoTHL3Orders::where('tesco_brand_id','=',$id)->pluck('id')->all();
              array_merge($tcl1_table_brands,$tcl4_table_brands,$thl1_table_brands,$thl3_table_brands);
              if(empty($tcl1_table_brands) != '')
              {
                TescoBrand::where('id',$id)
                 ->update([
                   'is_enabled'  => 0
                ]);
                $result = response()->json(['error'=> false,'message' =>'Success'],200);
              }
              else
              {
                $result = response()->json(['error'=> false,'message' =>'Brand is in use.'],208);
              }
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
             /** @var array [Database Unique field validation] */
             $is_exists = array(
                 'department' => 'required|unique:tesco_department,department',
             );
             $validator = Validator::make($request->all() ,$is_exists);
             if($validator->fails())
             {
               $result = response()->json(['error'=> true,'message' =>'Department Already Exists'],208);
             }
             else
             {
                $inserted = TescoDepartment::create([
                        'department' => $request['department'],
                        'pantone' => $request['pantone'],
                        'rbg' => $request['rbg']
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
          
          /** @var array [Database Unique field validation] */
          $rules     = array('department' => 'unique:tesco_department,department,'.$id);
          $Validator  = Validator::make($request->all(),$rules);

          if($Validator->fails())
          {
              return response()->json(['error'=> true,'message' =>'Department Already Exists'],208);
          }
          else
          {
            TescoDepartment::where('id',$id)
             ->update([
               'department' => $department,
               'pantone' => $pantone,
               'rbg' => $rbg
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
              // Check if this department is in use or not while placing the order. 
              // Checking for 1 template order table from each combination.
              // (ie, TCL1 from TCL1, TCL2 and TSL1 combination)
              $tcl1_table_departments = TescoTCL1Orders::where('tesco_department_id','=',$id)->pluck('id')->all();
              $tcl4_table_departments = TescoTCL4Orders::where('tesco_department_id','=',$id)->pluck('id')->all();
              $thl1_table_departments = TescoTHL1Orders::where('tesco_department_id','=',$id)->pluck('id')->all();
              $thl3_table_departments = TescoTHL3Orders::where('tesco_department_id','=',$id)->pluck('id')->all();
              array_merge($tcl1_table_departments,$tcl4_table_departments,$thl1_table_departments,$thl3_table_departments);

              if(empty($tcl1_table_departments) != '')
              {
                TescoDepartment::where('id',$id)
                 ->update([
                   'is_enabled'  => 0
                ]);
                $result = response()->json(['error'=> false,'message' =>'Success'],200);
              }
              else
              {
                $result = response()->json(['error'=> false,'message' =>'Department is in use.'],208);
              }
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
              /** @var array [Database Unique field validation] */
              $is_exists = TescoSupplier::where(['supplier_name' => $request['supplier_name'],'supplier_code' => $request['supplier_code']])->select('id')->get();
              if(count($is_exists) > 0)
              {
                $result = response()->json(['error'=> true,'message' =>'Supplier Already Exists'],208);
              }
              else
              {
                $inserted = TescoSupplier::create([
                        'supplier_name' => $request['supplier_name'],
                        'supplier_code' => $request['supplier_code']
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
          $id            = $request['id'];
          $supplier_name = $request['supplier_name'];
          $supplier_code = $request['supplier_code'];

          /** @var array [Database Unique field validation] */
          $is_exists = TescoSupplier::select('id')
                       ->where('supplier_name', '=', $request['supplier_name'])
                       ->where('supplier_code', '=', $request['supplier_code'])
                       ->where('id','<>',$id)
                       ->get();
          if(count($is_exists) > 0)
          {
              return response()->json(['error'=> true,'message' =>'Supplier Already Exists.'],208);
          }
          else
          {
              TescoSupplier::where('id',$id)
               ->update([
                 'supplier_name' => $supplier_name,
                 'supplier_code' => $supplier_code
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
              /** Check if this supplier linked with tesco company  **/
              $company_id = DB::table('customer_metadata')->select('customer_metadata.id')
                            ->join('customers','customers.id','customer_metadata.customer_id')
                            ->where('customer_metadata.customer_metadata_id','=',$id)
                            ->where('customers.is_active','=',1)
                            ->first();
              if($company_id)
              {
                $result = response()->json(['error'=> false,'message' =>'Linked to company'],208);
              }
              else
              {
                TescoSupplier::where('id',$id)
                 ->update([
                   'is_enabled'  => 0
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
             $is_exists = TescoSizeChart::where('po_size', '=', $request['po_size'])->select('id')->get();
             $is_exists = array(
                 'po_size' => 'required|unique:tesco_size_chart,po_size',
             );
             $validator = Validator::make($request->all() ,$is_exists);
             if($validator->fails())
             {
               $result = response()->json(['error'=> true,'message' =>'Size Chart Already Exists'],208);
             }
             else
             {
                $inserted = TescoSizeChart::create([
                        'po_size' => $request['po_size'],
                        'uk' => $request['uk'],
                        'ce' => $request['ce']
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

          $rules     = array('po_size' => 'unique:tesco_size_chart,po_size,'.$id);
          $Validator  = Validator::make($request->all(),$rules);

          if($Validator->fails())
          {
              return response()->json(['error'=> true,'message' =>'Size Chart Already Exists'],208);
          }
          else
          {
             TescoSizeChart::where('id',$id)
              ->update([
                'po_size' => $po_size,
                'uk' => $uk,
                'ce' => $ce
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
              // get sizechart for this id.
              $size = DB::table('tesco_size_chart')->select('po_size')->where('id','=',$id)->first();
              // Check if this department is in use or not while placing the order. 
              // Checking for 1 template order table from each combination.
              // (ie, TCL4 from TCL4 and TSL3 combination)
              // size only for tesco single templates
              $tcl4_sizes = TescoTCL4OrdersQuantity::where('size','=',$size->po_size)->pluck('id')->all();
              $thl3_sizes = TescoTHL3OrdersQuantity::where('size','=',$size->po_size)->pluck('id')->all();
              array_merge($tcl4_sizes,$thl3_sizes);

              if(empty($tcl4_sizes) != '')
              {
                TescoSizeChart::where('id',$id)
                 ->update([
                   'is_enabled'  => 0
                ]);
                $result = response()->json(['error'=> false,'message' =>'Success'],200);
              }
              else
              {
                $result = response()->json(['error'=> false,'message' =>'Size Chart is in use.'],208);
              }

            } 
            catch (\Exception $e)
            {
              $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
            }
            return $result;
      }
    
}

