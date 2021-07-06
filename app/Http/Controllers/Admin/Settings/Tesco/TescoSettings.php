<?php

namespace App\Http\Controllers\Admin\Settings\Tesco;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\TescoBrand;
use App\TescoDepartment;
use App\TescoSupplier;
use App\TescoSizeChart;
use App\TescoTCL1Orders;
use App\TescoTCL4Orders;
use App\TescoTCL4OrdersQuantity;
use App\TescoTHL1Orders;
use App\TescoTHL3Orders;
use App\TescoTHL3OrdersQuantity;
use App\TescoTaxCode;
use App\RawMaterialMaster;
use App\TescoErrorReportEmails;
use App\TescoCareSymbols;
use App\TescoCountries;
use App\TescoComponents;
use App\TescoFabricLists;
use App\TescoKidsWarningStatements;
use App\TescoTranslation;
use App\TescoPrimarySize;
use App\TescoSecondarySizeChart;

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
            $tesco_brand = TescoBrand::select('id','brand','is_enabled')->orderBy('is_enabled','desc')->get();

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
             if(TescoBrand::where('brand','ilike',$request['name'])->exists())
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
          $tesco_brand = TescoBrand::select('id','brand','is_enabled')->where('tesco_brand.id', $request['id'])->get();
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
          if(TescoBrand::where([['brand','ilike',$request['name']], ['id','<>', $request['id']]])->exists())
          {
              $result = response()->json(['error'=> true,'message' =>'Brand Already Exists'],208);
          }
          else
          {
            TescoBrand::find($request['id'])
             ->update([
               'brand' => $request['name']
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
              TescoBrand::find($request['id'])
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
              $tcl1_table_brands = TescoTCL1Orders::where('tesco_brand_id','=',$id)->exists();
              $tcl4_table_brands = TescoTCL4Orders::where('tesco_brand_id','=',$id)->exists();
              $thl1_table_brands = TescoTHL1Orders::where('tesco_brand_id','=',$id)->exists();
              $thl3_table_brands = TescoTHL3Orders::where('tesco_brand_id','=',$id)->exists();

             if($tcl1_table_brands || $tcl4_table_brands || $thl1_table_brands || $thl3_table_brands)
              {
                $result = response()->json(['error'=> false,'message' =>'Brand is in use.'],208);
              }
              else
              {
                TescoBrand::find($id)
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
     * Tesco metadata Department list show using ajax.
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function tescoDepartments()
    {
      try {
            $tesco_department = TescoDepartment::select('id','department','pantone','rbg','is_enabled')->orderBy('is_enabled','desc')->get();
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
              if(TescoDepartment::where('department','ilike',$request['department'])->exists())
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
          $tesco_department = TescoDepartment::select('id','department','pantone','rbg')->where('tesco_department.id', $request['id'])->get();
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
          
          if(TescoDepartment::where([['department','ilike', $department], ['id','<>',$id]])->exists())
          {
              $result = response()->json(['error'=> true,'message' =>'Department Already Exists'],208);
          }
          else
          {
            TescoDepartment::find($id)
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
              TescoDepartment::find($request['id'])
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
              $tcl1_table_departments = TescoTCL1Orders::where('tesco_department_id','=',$id)->exists();
              $tcl4_table_departments = TescoTCL4Orders::where('tesco_department_id','=',$id)->exists();
              $thl1_table_departments = TescoTHL1Orders::where('tesco_department_id','=',$id)->exists();
              $thl3_table_departments = TescoTHL3Orders::where('tesco_department_id','=',$id)->exists();

              if($tcl1_table_departments || $tcl4_table_departments || $thl1_table_departments || $thl3_table_departments)
              {
                $result = response()->json(['error'=> false,'message' =>'Department is in use.'],208);
              }
              else
              {
                TescoDepartment::find($id)
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
     * Tesco metadata supplier list show using ajax.
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function tescoSuppliers()
    {
      try {
            $tesco_suppliers = TescoSupplier::select('id','supplier_name','supplier_code','is_enabled')->orderBy('is_enabled','desc')->get();
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
              if(TescoSupplier::where([['supplier_code', '=', $request['supplier_code']], ['supplier_name','ilike',$request['supplier_name']]])->exists())
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
          $tesco_suppliers = TescoSupplier::select('id','supplier_name','supplier_code')->where('tesco_suppliers.id', $request['id'])->get();
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

          $is_exists = TescoSupplier::where([
            ['supplier_name', 'ilike', $request['supplier_name']],
            ['supplier_code', '=', $request['supplier_code']],
            ['id','<>',$id],
          ])->exists();

          if($is_exists)
          {
              $result = response()->json(['error'=> true,'message' =>'Supplier Already Exists.'],208);
          }
          else
          {
              TescoSupplier::find($id)
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
            TescoSupplier::find($request['id'])
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
              $company_id = DB::table('customer_metadata')
                            ->join('customers','customers.id','customer_metadata.customer_id')
                            ->where([
                              ['customer_metadata.customer_metadata_id','=',$id],
                              ['customers.is_active','=',1],
                            ])
                            ->exists();
              if($company_id)
              {
                $result = response()->json(['error'=> false,'message' =>'Linked to company'],208);
              }
              else
              {
                TescoSupplier::find($id)
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
              $tesco_size_chart = TescoSizeChart::select('id','po_size','uk','ce','is_enabled')->orderBy('is_enabled','desc')->get();
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
             if(TescoSizeChart::where('po_size', 'ilike', $request['po_size'])->exists())
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
          $tesco_size_chart = TescoSizeChart::select('id','po_size','uk','ce')->where('tesco_size_chart.id', $request['id'])->get();
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
          $po_size   = $request['po_size'];
          $uk        = $request['uk'];
          $ce        = $request['ce'];

          if(TescoSizeChart::where([['po_size', 'ilike', $po_size], ['id','<>',$id]])->exists())
          {
              $result = response()->json(['error'=> true,'message' =>'Size Chart Already Exists'],208);
          }
          else
          {
             TescoSizeChart::find($id)
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
              TescoSizeChart::find($request['id'])
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
              $size = TescoSizeChart::select('po_size')->where('id','=',$id)->first();
              // Check if this department is in use or not while placing the order. 
              // Checking for 1 template order table from each combination.
              // (ie, TCL4 from TCL4 and TSL3 combination)
              // size only for tesco single templates
              $tcl4_sizes = TescoTCL4OrdersQuantity::where('size','ilike',$size->po_size)->exists();
              $thl3_sizes = TescoTHL3OrdersQuantity::where('size','ilike',$size->po_size)->exists();

              if($tcl4_sizes || $thl3_sizes)
              {
                $result = response()->json(['error'=> false,'message' =>'Size Chart is in use.'],208);
              }
              else
              {
                TescoSizeChart::find($id)
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
          * Tesco/Orchestra metadata tax code list show using ajax.
          * @author <Praveen>
          * @return \Illuminate\Http\Response
          */
         public function taxCode()
         {
           try {
                 $taxcode = TescoTaxCode::select('id','tax_code','tax_type','is_enabled', 'tax')->orderBy('is_enabled','desc')->get();
                 return response()->json(['error'=>'false','message' =>'success', 'taxcodes' => $taxcode]);
               } catch (\Exception $e)
               {
                 return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
               }
         }

      /**
        * Add Tesco/Orchestra tax code.
        * @param \Illuminate\Http\Request
        * @author <Praveen>
        * @return \Illuminate\Http\Response
        */
       public function addTaxCodes(Request $request)
       {
           try {
                $is_exists = TescoTaxCode::where([['tax_type', 'ilike', $request['tax_type']], ['tax_code', 'ilike', $request['tax_code']], ['tax', '=', $request['tax']]])->exists();

                if($is_exists)
                {
                  $result = response()->json(['error'=> true,'message' =>'Tax Code Already Exists'],208);
                }
                else
                {
                   $inserted = TescoTaxCode::create([
                           'tax_type'   => $request['tax_type'],
                           'tax_code'   => $request['tax_code'],
                           'tax'        => $request['tax'],
                           'is_enabled' => 1
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
        * Get Tesco/Orchestra tax code details by ID for editing.
        * @param Tesco tax code id
        * @author <Praveen>
        * @return \Illuminate\Http\Response
        */
       public function getTaxCodesById(Request $request)
       {
       try {
             $tax_code = TescoTaxCode::select('id','tax_code','tax_type','is_enabled', 'tax')->where('tax_code.id', $request['id'])->get();
             return response()->json(['error'=>'false','message' =>'success', 'tax_codes' => $tax_code]);
           } catch (\Exception $e)
           {
             return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
           }
       }
      /**
       * Update Tesco/Orchestra tax code by id.
       * @param taxcode id
       * @author <Praveen>
       * @return \Illuminate\Http\Response
       */
       public function updateTaxCodesById(Request $request)
       {
       try {
             $id        = $request['id'];
             $tax_code  = $request['tax_code'];
             $tax_type  = $request['tax_type'];
             $tax       = $request['tax'];

             $is_exists = TescoTaxCode::where([
               ['tax_type', 'ilike', $tax_type],
               ['tax_code', 'ilike', $tax_code],
               ['tax', '=', $tax],
               ['id','<>',$id]
             ])->exists();

             if($is_exists)
             {
                 $result = response()->json(['error'=> true,'message' =>'Tax Code Already Exists'],208);
             }
             else
             {
                TescoTaxCode::find($id)
                 ->update([
                   'tax_code' => $tax_code,
                   'tax_type' => $tax_type,
                   'tax'      => $tax,

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
         * Raw material Metadata list show using ajax.
         * @author <Praveen>
         * @return \Illuminate\Http\Response
         */
        public function rawMaterials()
        {
          try {
                $raw_materials = RawMaterialMaster::select('id','material_name_employee','material_name_production','is_enabled', 'material_name_purchase')->orderBy('is_enabled','desc')->get();
                return response()->json(['error'=>'false','message' =>'success', 'raw_materials' => $raw_materials]);
              } catch (\Exception $e)
              {
                return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
              }
        }

       /**
         * Add Raw material Metadata.
         * @param \Illuminate\Http\Request
         * @author <Praveen>
         * @return \Illuminate\Http\Response
         */
        public function addRawMaterial(Request $request)
        {
            try {
                 $is_exists = RawMaterialMaster::where([
                   ['material_name_employee', 'ilike', $request['emp_material']],
                   ['material_name_production', 'ilike', $request['production_material']],
                   ['material_name_purchase', 'ilike', $request['purchase_material']]
                 ])->exists();

                 if($is_exists)
                 {
                   $result = response()->json(['error'=> true,'message' =>'Material Already Exists'],208);
                 }
                 else
                 {
                    $inserted = RawMaterialMaster::create([
                            'material_name_employee'    => $request['emp_material'],
                            'material_name_production'  => $request['production_material'],
                            'material_name_purchase'    => $request['purchase_material'],
                            'is_enabled'                => false
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
         * Get Raw material Metadata details by ID for editing.
         * @param Raw material id
         * @author <Praveen>
         * @return \Illuminate\Http\Response
         */
        public function getRawMaterialById(Request $request)
        {
        try {
              $raw_material = RawMaterialMaster::select('id','material_name_employee','material_name_production','is_enabled', 'material_name_purchase')->where('raw_material_master.id', $request['id'])->get();
              return response()->json(['error'=>'false','message' =>'success', 'raw_material' => $raw_material]);
            } catch (\Exception $e)
            {
              return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
            }
        }
       /**
        * Update Raw material Metadata by id.
        * @param material id
        * @author <Praveen>
        * @return \Illuminate\Http\Response
        */
        public function updateRawMaterialById(Request $request)
        {
        try {
              $id                  = $request['id'];
              $emp_material        = $request['emp_material'];
              $production_material = $request['production_material'];
              $purchase_material   = $request['purchase_material'];

              $is_exists = RawMaterialMaster::where([
                             ['material_name_employee', 'ilike', $emp_material],
                             ['material_name_production', 'ilike', $production_material],
                             ['material_name_purchase', 'ilike', $purchase_material],
                             ['id','<>',$id]
                           ])->exists();

              if($is_exists)
              {
                  $result = response()->json(['error'=> true,'message' =>'Material Already Exists'],208);
              }
              else
              {
                 RawMaterialMaster::find($id)
                  ->update([
                    'material_name_employee'    => $emp_material,
                    'material_name_production'  => $production_material,
                    'material_name_purchase'    => $purchase_material,
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
       * Make Material enabled
       * @param Material id
       * @author <Praveen>
       * @return boolean true or false
       */
      public function makeMaterialEnabled(Request $request)
      {
        try {
              RawMaterialMaster::find($request['id'])
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
       * Make Material disabled
       * @author <Praveen>
       * @return boolean true or false
       */
      public function makeMaterialDisabled(Request $request)
      {
        try {
              if(DB::table('tesco_template')->where('material_id','=', $request['id'])->exists())
              {
                $result = response()->json(['error'=> true,'message' =>'Material is in use.'],208);
              }
              else
              {
                  $inserted = RawMaterialMaster::find($request['id'])
                                ->update([
                                  'is_enabled'  => false
                              ]);
                  $result = $inserted != ''?response()->json(['error'=> false,'message' =>'success'],200):response()->json(['error'=> true,'message' =>'Error'],400);
              }
            } 
            catch (\Exception $e)
            {
              $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
            }
            return $result;
      }
      /**
        * Tesco error report emails list show using ajax.
        * @author <Praveen>
        * @return \Illuminate\Http\Response
        */
       public function tescoErrorReportEmails()
       {
         try {
               $error_report_data = TescoErrorReportEmails::select('tesco_error_report_emails.id','company.name as company','error_case','contact_person','email_address','is_enabled')->join('company','company.id','tesco_error_report_emails.company_id')->orderBy('tesco_error_report_emails.is_enabled','desc')->get();
               
               return response()->json(['error'=>'false','message' =>'success', 'error_report_data' => $error_report_data]);
             } catch (\Exception $e)
             {
               return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
             }
       }
       /**
        * Add Tesco PO error reporting emails.
        * @param \Illuminate\Http\Request
        * @author <Praveen>
        * @return \Illuminate\Http\Response
        */
       public function addTescoErrorReportEmail(Request $request)
       {
           try {
                $is_exists = TescoErrorReportEmails::where([['error_case', 'ilike', $request['error_case']], ['email_address', 'ilike', $request['email']], ['company_id', '=', $request['company']]])->exists();

                if($is_exists)
                {
                  $result = response()->json(['error'=> true,'message' =>'Email Details Already Exists'],208);
                }
                else
                {
                   $inserted = TescoErrorReportEmails::create([
                           'company_id'     => $request['company'],
                           'error_case'     => $request['error_case'],
                           'contact_person' => $request['contact_person'],
                           'email_address'  => $request['email'],
                           'is_enabled'     => false
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
         * Get Tesco Error Report Email details by ID for editing.
         * @param Tesco Error Report Email id
         * @author <Praveen>
         * @return \Illuminate\Http\Response
         */
        public function getTescoErrorReportEmailById(Request $request)
        {
        try {
              $error_report_data = TescoErrorReportEmails::select('*')->where('id', $request['id'])->get();
              return response()->json(['error'=>'false','message' =>'success', 'error_report_data' => $error_report_data]);
            } catch (\Exception $e)
            {
              return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
            }
        }
        /**
         * Edit Tesco PO error reporting emails.
         * @param \Illuminate\Http\Request
         * @author <Praveen>
         * @return \Illuminate\Http\Response
         */
        public function updateTescoErrorReportEmailById(Request $request)
        {
            try {
                 $is_exists = TescoErrorReportEmails::where([
                   ['error_case', 'ilike', $request['error_case']],
                   ['email_address', 'ilike', $request['email']],
                   ['company_id', '=', $request['company']],
                   ['id','<>',$request['id']]
                 ])->exists();

                 if($is_exists)
                 {
                   $result = response()->json(['error'=> true,'message' =>'Email Details Already Exists'],208);
                 }
                 else
                 {
                    TescoErrorReportEmails::find($request['id'])
                     ->update([
                        'company_id'     => $request['company'],
                        'error_case'     => $request['error_case'],
                        'contact_person' => $request['contact_person'],
                        'email_address'  => $request['email']
                    ]);
                    $result = response()->json(['error'=> false,'message' =>'success'],200);
                 }

            } catch (\Exception $e)
            {
                $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
            }
            return $result;
        }
      /**
       * Make Tesco PO error reporting email  enabled
       * @param Tesco PO error reporting email  id
       * @author <Praveen>
       * @return boolean true or false
       */
      public function makeErrorReportEmailEnabled(Request $request)
      {
        try {
              
              TescoErrorReportEmails::find($request['id'])
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
       * Make Tesco PO error reporting email disabled
       * @author <Praveen>
       * @return boolean true or false
       */
      public function makeErrorReportEmailDisabled(Request $request)
      {
        try {
              TescoErrorReportEmails::find($request['id'])
               ->update([
                 'is_enabled'  => false
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
     * Tesco metadata Symbols list show using ajax.
     * @return \Illuminate\Http\Response
    */
    public function tescoSymbols()
    {
      try {
            $tesco_care_symbols = DB::table('tesco_care_symbols')->select('*')->orderBy('is_enabled','desc')->get();
       
            return response()->json(['error'=>'false','message' =>'success', 'tesco_care_symbols' => $tesco_care_symbols]);
          } catch (\Exception $e)
          {
                 return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
          }
    }

    /**
     * Tesco metadata add Care Symbols with ajax.
     * @return \Illuminate\Http\Response
    */
    public function addTescoSymbols(Request $request){
      try {
        $destination_path='images/WashCareSymbols/tesco/';
        $destination_path_db='/images/WashCareSymbols/tesco/';
        $iso_symbols= $request->file('iso_symbols');
        $astms_symbol= $request->file('astms_symbol');
        $care_degree = $request->input('care_degree');

        $iso_symbols_orname = $iso_symbols->getClientOriginalName();
        $astms_symbol_orname = $astms_symbol->getClientOriginalName();

        $iso_extension = $iso_symbols->getClientOriginalExtension();
        $astms_extension = $astms_symbol->getClientOriginalExtension();

        if($iso_extension === 'png' && $astms_extension === 'png')
        {
          $iso_symbols_filename= str_random(6).'_'.preg_replace('/#[^\s]+/','',preg_replace('/\s+/', '-', $iso_symbols_orname));
          $astms_symbol_filename= str_random(6).'_'.preg_replace('/#[^\s]+/','',preg_replace('/\s+/', '-', $astms_symbol_orname));

            switch($request->category_id){
              case 1:
                $upload_path = 'Washing/';
                break;
              case 2:
                $upload_path = 'Whitening/';
                break;
              case 3:
                $upload_path = 'Dryer/';
                break;
              case 4:
                $upload_path = 'Ironing/';
                break;
              case 5:
                $upload_path = 'Cleaning/';
                break;
              default:
                $upload_path = 'Washing/';
                break;
            }

            $iso_symbols->move($destination_path.$upload_path,$iso_symbols_filename);
            $astms_symbol->move($destination_path.$upload_path,$astms_symbol_filename);
  
            $iso_path_insert=$destination_path_db.$upload_path.$iso_symbols_filename;
            $astms_path_insert=$destination_path_db.$upload_path.$astms_symbol_filename;

            $inserted = DB::table('tesco_care_symbols')->insert([
                'category_id'     =>  $request->category_id,
                'iso_symbols'     =>  $iso_path_insert,
                'astms_symbol'    =>  $astms_path_insert,
                'care_instruction'=>  $request->care_instruction,
                'degree'          =>  $care_degree
            ]);
        }
        else   
        {
          $inserted = '';
        }

        $result = $inserted != ''?response()->json(['error'=> false,'message' =>'success'],200):response()->json(['error'=> true,'message' =>'Unprocessable Format'],422);

      } catch (\Exception $e)
      {
          $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
      }
      return $result;
    }


  /**
   * Make tesco Symbol enabled
   * @param Symbol id
   * @return boolean true or false
   */
  public function enableTescoSymbol(Request $request)
  {
    try {
          $id = $request->get('id');
          
          TescoCareSymbols::where('id',$id)
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
     * Make Symbol disabled
     * @return boolean true or false
     */
    public function disableTescoSymbol(Request $request)
    {
      try {
        $id = $request['id'];

        TescoCareSymbols::where('id',$id)
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
     * Orchestra metadata countries list show using ajax.
     * @return \Illuminate\Http\Response
     */
    public function tescoCountries(){
      try {
        $tesco_countries = DB::table('tesco_countries')->select('id', 'english','polish', 'czech', 'slovak', 'hungarian','is_enabled')->orderBy('is_enabled','desc')->get();
        return response()->json(['error'=>'false','message' =>'success', 'tesco_countries' => $tesco_countries]);
      } catch (\Exception $e){
        return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
      }
    }

     /**
     * Add orchestra country.
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function addTescoCountry(Request $request){
        try {
         if(TescoCountries::where('english','ilike',$request['english'])->exists())
          {
              $result = response()->json(['error'=> true,'message' =>'Country Name[English] Already Exists'],208);
          }else{
             $inserted = TescoCountries::create([
                        'english'   => $request['english'],
                        'polish'    => $request['polish'],
                        'czech'     => $request['czech'],
                        'slovak'    => $request['slovak'],
                        'hungarian' => $request['hungarian']
                      ]);

            $result = $inserted != ''?response()->json(['error'=> false,'message' =>'success'],200):response()->json(['error'=> true,'message' =>'Error'],400);
          }
        } catch (\Exception $e){
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
    }

     /**
     * Get tesco country details by ID for editing.
     * @param tesco country id
     * @return \Illuminate\Http\Response
     */
    public function getTescoCountryById(Request $request){
      try {
          $id = $request['id'];
          $tesco_countries = DB::table('tesco_countries')->select('*')->where('tesco_countries.id',$id)->get();
          return response()->json(['error'=>'false','message' =>'success', 'tesco_countries' => $tesco_countries]);
      } catch (\Exception $e){
          return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
      }
    }

    /**
    * Update tesco country by id.
    * @param tesco country id
    * @return \Illuminate\Http\Response
    */
    public function updateTescoCountryById(Request $request){
      try {
          $id = $request['id'];
          
          if(TescoCountries::where('english','ilike',$request['english'])->where('id','!=',$id)->exists()){
              $result = response()->json(['error'=> true,'message' =>'Country Name[English] Already Exists'],208);
          }else{
              $updated = TescoCountries::where('id',$id)->update([
                  'english'   => $request['english'],
                  'polish'    => $request['polish'],
                  'czech'     => $request['czech'],
                  'slovak'    => $request['slovak'],
                  'hungarian' => $request['hungarian']
                ]);
              $result = $updated != ''?response()->json(['error'=> false,'message' =>'success'],200):response()->json(['error'=> true,'message' =>'Error'],400);
          }
        } catch (\Exception $e){
               $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
    }

    /**
       * enable Tesco Country
       * @param Country id
       * @return boolean true or false
    */
    public function enableTescoCountry(Request $request){
        try {
          $id = $request['id'];
          TescoCountries::where('id',$id)->update([
                 'is_enabled'  => 1
          ]);
          $result = response()->json(['error'=> false,'message' =>'Success'],200);
        }
        catch (\Exception $e){
          $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
    }
    /**
     * disable Tesco Country
     * @return boolean true or false
     */
    public function disableTescoCountry(Request $request){
      try {
        $id = $request['id'];  
        TescoCountries::where('id',$id)->update([
                 'is_enabled'  => 0
        ]);
        $result = response()->json(['error'=> false,'message' =>'Success'],200);
      } 
      catch (\Exception $e){
        $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
      }
      return $result;
    }


    /**
     * Tesco Components list show using ajax.
     * @return \Illuminate\Http\Response
     */
    public function tescoComponents(){
      try {
          $tesco_components = DB::table('tesco_components')->select('id', 'english','polish', 'czech', 'slovak', 'hungarian','is_enabled')->orderBy('is_enabled','desc')->get();
          return response()->json(['error'=>'false','message' =>'success', 'tesco_components' => $tesco_components]);
      }
      catch (\Exception $e){
          return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
      }
    }

    /**
     * Add tesco Components.
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function addTescoComponents(Request $request){
        try {
            if(TescoComponents::where('english','ilike',$request['english'])->exists()){
                $result = response()->json(['error'=> true,'message' =>'Components[English] Already Exists'],208);
            }else{
                $inserted = TescoComponents::create([
                        'english'   => $request['english'],
                        'polish'    => $request['polish'],
                        'czech'     => $request['czech'],
                        'slovak'    => $request['slovak'],
                        'hungarian' => $request['hungarian']
                ]);
                $result = $inserted != '' ? response()->json(['error'=> false,'message' =>'success'],200):response()->json(['error'=> true,'message' =>'Error'],400);
            }

        } catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
    }

    /**
     * Get tesco Components details by ID for editing.
     * @param tesco Components id
     * @return \Illuminate\Http\Response
     */
    public function getTescoComponentsById(Request $request){
      try {
            $id = $request['id'];
            $tesco_components = DB::table('tesco_components')->select('*')->where('tesco_components.id',$id)->get();
            return response()->json(['error'=>'false','message' =>'success', 'tesco_components' => $tesco_components]);
      }
      catch (\Exception $e){
            return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
      }
    }

    /**
    * Update tesco Components by id.
    * @param tesco Components id
    * @return \Illuminate\Http\Response
    */
    public function updateTescoComponentsById(Request $request){
      try {
          $id = $request['id'];
          if(TescoComponents::where('english','ilike',$request['english'])->where('id','!=',$id)->exists()){
                $result = response()->json(['error'=> true,'message' =>'Components[English] Already Exists'],208);
          } else {
              $updated = TescoComponents::where('id',$id)->update([
                                        'english'   => $request['english'],
                                        'polish'    => $request['polish'],
                                        'czech'     => $request['czech'],
                                        'slovak'    => $request['slovak'],
                                        'hungarian' => $request['hungarian']
                        ]);
            $result = $updated != ''?response()->json(['error'=> false,'message' =>'success'],200):response()->json(['error'=> true,'message' =>'Error'],400);
          }        
      } catch (\Exception $e) {
                $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
      }
      return $result;
    }

    /**
       * Make Components enabled
       * @param Components id
       * @return boolean true or false
       */
      public function enableTescoComponents(Request $request){
          try {
                $id = $request['id'];
                TescoComponents::where('id',$id)->update([
                        'is_enabled'  => 1
                ]);
                $result = response()->json(['error'=> false,'message' =>'Success'],200);
          } catch (\Exception $e) {
                    $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
          }
          return $result;
      }

      /**
       * Make Components disabled
       * @return boolean true or false
       */
      public function disableTescoComponents(Request $request){
          try {
              $id = $request['id'];
              TescoComponents::where('id',$id)->update([
                'is_enabled'  => 0
              ]);
              $result = response()->json(['error'=> false,'message' =>'Success'],200);
          } catch (\Exception $e) {
              $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
          }
              return $result;
      }


    /**
     * tescoFabricList list show using ajax.
     * @return \Illuminate\Http\Response
     */
    public function tescoFabricList(){
      try {
          $tesco_fabriclists = DB::table('tesco_fabriclists')->select('id', 'english','polish', 'czech', 'slovak', 'hungarian','is_enabled')->orderBy('is_enabled','desc')->get();
          return response()->json(['error'=>'false','message' =>'success', 'tesco_fabriclists' => $tesco_fabriclists]);
      }
      catch (\Exception $e){
          return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
      }
    }

    /**
     * Add tesco Fabric.
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function addTescoFabric(Request $request){
      try {
          if(TescoFabricLists::where('english','ilike',$request['english'])->exists()){
              $result = response()->json(['error'=> true,'message' =>'Fabric[English] Already Exists'],208);
          }else{
              $inserted = TescoFabricLists::create([
                      'english'   => $request['english'],
                      'polish'    => $request['polish'],
                      'czech'     => $request['czech'],
                      'slovak'    => $request['slovak'],
                      'hungarian' => $request['hungarian']
              ]);
              $result = $inserted != '' ? response()->json(['error'=> false,'message' =>'success'],200):response()->json(['error'=> true,'message' =>'Error'],400);
          }

      } catch (\Exception $e)
      {
          $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
      }
      return $result;
    }

  /**
     * Get tesco fabric details by ID for editing.
     * @param tesco fabric id
     * @return \Illuminate\Http\Response
     */
    public function getTescoFabricById(Request $request){
      try {
            $id = $request['id'];
            $tesco_fabriclists = DB::table('tesco_fabriclists')->select('*')->where('tesco_fabriclists.id',$id)->get();
            return response()->json(['error'=>'false','message' =>'success', 'tesco_fabriclists' => $tesco_fabriclists]);
      }
      catch (\Exception $e){
            return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
      }
    }

    /**
    * Update tesco fabric by id.
    * @param tesco fabric id
    * @return \Illuminate\Http\Response
    */
    public function updateTescoFabricById(Request $request){
      try {
          $id = $request['id'];
          if(TescoFabricLists::where('english','ilike',$request['english'])->where('id','!=',$id)->exists()){
                $result = response()->json(['error'=> true,'message' =>'Fabric[English] Already Exists'],208);
          } else {
              $updated = TescoFabricLists::where('id',$id)->update([
                                        'english'   => $request['english'],
                                        'polish'    => $request['polish'],
                                        'czech'     => $request['czech'],
                                        'slovak'    => $request['slovak'],
                                        'hungarian' => $request['hungarian']
                        ]);
            $result = $updated != ''?response()->json(['error'=> false,'message' =>'success'],200):response()->json(['error'=> true,'message' =>'Error'],400);
          }        
      } catch (\Exception $e) {
                $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
      }
      return $result;
    }
    
    /**
       * Make Fabric enabled
       * @param Fabric id
       * @return boolean true or false
       */
    public function enableTescoFabric(Request $request){
        try {
              $id = $request['id'];
              TescoFabricLists::where('id',$id)->update([
                      'is_enabled'  => 1
              ]);
              $result = response()->json(['error'=> false,'message' =>'Success'],200);
        } catch (\Exception $e) {
                  $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
    }

    /**
     * Make Fabric disabled
     * @return boolean true or false
     */
    public function disableTescoFabric(Request $request){
        try {
            $id = $request['id'];
            TescoFabricLists::where('id',$id)->update([
              'is_enabled'  => 0
            ]);
            $result = response()->json(['error'=> false,'message' =>'Success'],200);
        } catch (\Exception $e) {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
            return $result;
    }


    /**
     * tescoKidsWarnLists list show using ajax.
     * @return \Illuminate\Http\Response
     */
    public function tescoKidsWarnLists(){
      try {
          $tesco_warninglists = DB::table('tesco_kids_warning_statements')->select('id', 'english','polish', 'czech', 'slovak', 'hungarian','is_enabled')->orderBy('is_enabled','desc')->get();
          return response()->json(['error'=>'false','message' =>'success', 'tesco_warninglists' => $tesco_warninglists]);
      }
      catch (\Exception $e){
          return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
      }
    }

    /**
     * Add Tesco Warning Stmt.
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function addTescoWarnStmt(Request $request){
      try {
          if(TescoKidsWarningStatements::where('english','ilike',$request['english'])->exists()){
              $result = response()->json(['error'=> true,'message' =>'Statement [English] Already Exists'],208);
          }else{
              $inserted = TescoKidsWarningStatements::create([
                      'english'   => $request['english'],
                      'polish'    => $request['polish'],
                      'czech'     => $request['czech'],
                      'slovak'    => $request['slovak'],
                      'hungarian' => $request['hungarian']
              ]);
              $result = $inserted != '' ? response()->json(['error'=> false,'message' =>'success'],200):response()->json(['error'=> true,'message' =>'Error'],400);
          }

      } catch (\Exception $e)
      {
          $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
      }
      return $result;
    }

  /**
     * Get tesco Warning Stmt details by ID for editing.
     * @param tesco Warning Stmt id
     * @return \Illuminate\Http\Response
     */
    public function getTescoWarningStmtById(Request $request){
      try {
            $id = $request['id'];
            $tesco_warninglists = DB::table('tesco_kids_warning_statements')->select('*')->where('tesco_kids_warning_statements.id',$id)->get();
            return response()->json(['error'=>'false','message' =>'success', 'tesco_warninglists' => $tesco_warninglists]);
      }
      catch (\Exception $e){
            return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
      }
    }

    /**
    * Update tesco Warning Stmt by id.
    * @param tesco Warning Stmt id
    * @return \Illuminate\Http\Response
    */
    public function updateTescoWarningStmtById(Request $request){
      try {
          $id = $request['id'];
          if(TescoKidsWarningStatements::where('english','ilike',$request['english'])->where('id','!=',$id)->exists()){
                $result = response()->json(['error'=> true,'message' =>'Statement [English] Already Exists'],208);
          } else {
              $updated = TescoKidsWarningStatements::where('id',$id)->update([
                                        'english'   => $request['english'],
                                        'polish'    => $request['polish'],
                                        'czech'     => $request['czech'],
                                        'slovak'    => $request['slovak'],
                                        'hungarian' => $request['hungarian']
                        ]);
            $result = $updated != ''?response()->json(['error'=> false,'message' =>'success'],200):response()->json(['error'=> true,'message' =>'Error'],400);
          }        
      } catch (\Exception $e) {
                $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
      }
      return $result;
    }
    
    /**
       * Make Warning Stmt enabled
       * @param Fabric id
       * @return boolean true or false
       */
    public function enableTescoWarningStmt(Request $request){
        try {
              $id = $request['id'];
              TescoKidsWarningStatements::where('id',$id)->update([
                      'is_enabled'  => 1
              ]);
              $result = response()->json(['error'=> false,'message' =>'Success'],200);
        } catch (\Exception $e) {
                  $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
    }

    /**
     * Make Warning Stmt disabled
     * @return boolean true or false
     */
    public function disableTescoWarningStmt(Request $request){
        try {
            $id = $request['id'];
            TescoKidsWarningStatements::where('id',$id)->update([
              'is_enabled'  => 0
            ]);
            $result = response()->json(['error'=> false,'message' =>'Success'],200);
        } catch (\Exception $e) {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
            return $result;
    }


    /**
     * tescoTranslationLists list show using ajax.
     * @return \Illuminate\Http\Response
     */
    public function tescoTranslationLists(){
      try {
          $tesco_translationlists = DB::table('tesco_translation')->select('id', 'english','polish', 'czech', 'slovak', 'hungarian','is_enabled')->orderBy('is_enabled','desc')->get();
          return response()->json(['error'=>'false','message' =>'success', 'tesco_translationlist' => $tesco_translationlists]);
      }
      catch (\Exception $e){
          return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
      }
    }

    /**
     * Add Tesco Translation
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function addTescoTranslation(Request $request){
      try {
          if(TescoTranslation::where('english','ilike',$request['english'])->exists()){
              $result = response()->json(['error'=> true,'message' =>'Statement [English] Already Exists'],208);
          }else{
              $inserted = TescoTranslation::create([
                      'english'   => $request['english'],
                      'polish'    => $request['polish'],
                      'czech'     => $request['czech'],
                      'slovak'    => $request['slovak'],
                      'hungarian' => $request['hungarian']
              ]);
              $result = $inserted != '' ? response()->json(['error'=> false,'message' =>'success'],200):response()->json(['error'=> true,'message' =>'Error'],400);
          }

      } catch (\Exception $e)
      {
          $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
      }
      return $result;
    }

      /**
     * Get tesco translation details by ID for editing.
     * @param tesco translation id
     * @return \Illuminate\Http\Response
     */
    public function getTescoTranslationById(Request $request){
      try {
            $id = $request['id'];
            $tesco_translationlists = DB::table('tesco_translation')->select('*')->where('tesco_translation.id',$id)->get();
            return response()->json(['error'=>'false','message' =>'success', 'tesco_translationlists' => $tesco_translationlists]);
      }
      catch (\Exception $e){
            return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
      }
    }

    /**
    * Update tesco translation
    * @param tesco translation id
    * @return \Illuminate\Http\Response
    */
    public function updateTescoTranslationById(Request $request){
      try {
          $id = $request['id'];
          if(TescoTranslation::where('english','ilike',$request['english'])->where('id','!=',$id)->exists()){
                $result = response()->json(['error'=> true,'message' =>'Statement [English] Already Exists'],208);
          } else {
              $updated = TescoTranslation::where('id',$id)->update([
                                        'english'   => $request['english'],
                                        'polish'    => $request['polish'],
                                        'czech'     => $request['czech'],
                                        'slovak'    => $request['slovak'],
                                        'hungarian' => $request['hungarian']
                        ]);
            $result = $updated != ''?response()->json(['error'=> false,'message' =>'success'],200):response()->json(['error'=> true,'message' =>'Error'],400);
          }        
      } catch (\Exception $e) {
                $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
      }
      return $result;
    }


    /**
       * Make Translation enabled
       * @param Translation id
       * @return boolean true or false
       */
      public function enableTescoTranslation(Request $request){
        try {
              $id = $request['id'];
              TescoTranslation::where('id',$id)->update([
                      'is_enabled'  => 1
              ]);
              $result = response()->json(['error'=> false,'message' =>'Success'],200);
        } catch (\Exception $e) {
                  $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
    }

    /**
     * Make Translation disabled
     * @return boolean true or false
     */
    public function disableTescoTranslation(Request $request){
        try {
            $id = $request['id'];
            TescoTranslation::where('id',$id)->update([
              'is_enabled'  => 0
            ]);
            $result = response()->json(['error'=> false,'message' =>'Success'],200);
        } catch (\Exception $e) {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
            return $result;
    }


    /**
     * tescoLabelSizeChartLists list show using ajax.
     * @return \Illuminate\Http\Response
     */
    public function tescoLabelSizeChartLists(){
      try {
         
          $size_chart = TescoPrimarySize::with('secSizeChart')->get();
          $categories = DB::table('tesco_secondary_size_category')->pluck('category_name')->toArray();

          foreach ($size_chart as $key => $row) {
            foreach ($row->secSizeChart as $k => $v) {
              $temp[$v->category_name]=$v->secondary_size;
            }            
          }


          $size_chart->map(function ($size_chart,$key) use($categories) 
            {
              $dump=[];
               foreach ($categories as $category) {
                      $size_chart->$category="-";
                    }

              foreach ($size_chart->secSizeChart as $value) 
              {
                    $temp = $value->category_name;
                    $size_chart->$temp=$value->secondary_size;
              }
              $size_chart->No =$key+1;
            });
        
            // $sl_no = $key+1;
            $actions = '';
            $status = '';
            $td='';
            foreach ($size_chart as $k => $data) 
             {
              // dd($data);
              if ($data->is_flag) 
              {
                  $actions = '<a href=""><i class="fa fa-ban filterbtn options fa-button status update_status" aria-hidden="true" title="" id="disableTescoSizeChart" data-id= "'.$data->id.'"data-status=1 data-placement="left" data-original-title="Make Disabled"></i></a><a href="#"><i class="fa fa-pencil fa-button edit-tesco-size-chart filterbtn options" title="Edit" data-placement="left" data-id="'.$data->id.'"></i></a>';

                  $status = '<span class="label label-success">Enabled</span>';
              }
              else 
              {
                  $actions = '<a href=""><i class="fa fa-check filterbtn options fa-button status update_status" aria-hidden="true" title="" id="enableTescoSizeChart" data-id="'.$data->id.'" data-status=0 data-placement="left" data-original-title="Make Enabled"></i></a><a href="#"><i class="fa fa-pencil fa-button edit-tesco-size-chart filterbtn options" title="Edit" data-placement="left" data-id="'.$data->id.'"></i></a>';

                  $status = '<span class="label label-danger">Disabled</span>';
              }

        
              $td.='<tr><td>'.$data->No.'</td>'.
                 '<td>'.$data->department_title.'</td>'.
                 '<td>'.$data->uk_size.'</td>'.
                 '<td>'.$data->ce_size.'</td>'.
                 '<td>'.$data->Height.'</td>'.
                 '<td>'.$data->Weight.'</td>'.
                 '<td>'.$data->Chest.'</td>'.
                 '<td>'.$data->Bust.'</td>'.
                 '<td>'.$data->Waist.'</td>'.
                 '<td>'.$status.'</td>'.
                 '<td>'.$actions.'</td></tr>'
                 ;
             }
         

          return response()->json(['error'=>'false','message' =>'success', 
            // 'tesco_translationlist' => $tesco_translationlists,
            'table_data' => $td]);

           }
      catch (\Exception $e){
          return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
      }
    }

          //   $tesco_translationlists = TescoPrimarySize::with(['secSizeChart'=>function($q)
          //   {
          //     $q->leftJoin('tesco_secondary_size_category','tesco_secondary_size_category.id','tesco_secondary_size_chart.tesco_secondary_size_category_id');
          //   }])->get();


          //     $param_arr = DB::table('tesco_secondary_size_category')->pluck('category_name')->toArray();

          //     $tesco_translationlists->map(function ($tesco_translationlists,$key) use($param_arr)
          //     {
          //         $temp_arr=[];
          //         foreach ($tesco_translationlists->secSizeChart as $key => $value)
          //         {
          //           array_push($temp_arr, $value->category_name);
          //           $temp = $value->category_name;
          //           $tesco_translationlists->$temp= $value->secondary_size;
          //         }

          //         foreach (array_diff($param_arr,$temp_arr) as $key => $value) {
          //           $tesco_translationlists->$value= NULL;
          //         }
          //     });

          
          // $tr='';
          // $td='';
          // foreach ($tesco_translationlists as $key => $value) {
          //    # code...
          //   // dd($value->department_title);
          //   $sl_no = $key+1;
          //   $tesco_metadata = '';
          //   $actions = '';
          //   $status = '';

          //   if ($value->is_flag) 
          //   {
          //       $actions = '<a href="#"><i class="fa fa-ban filterbtn options fa-button status" aria-hidden="true" title="" id="disableTescoSizeChart" data-placement="left" data-original-title="Make Disabled"></i></a><a href="#"><i class="fa fa-pencil fa-button edit-tesco-size-chart filterbtn options" title="Edit" data-placement="left" data-id="'.$value->id.'"></i></a>';

          //       $status = '<span class="label label-success">Enabled</span>';
          //   }
          //   else 
          //   {
          //       $actions = '<a href="#"><i class="fa fa-check filterbtn options fa-button status" aria-hidden="true" title="" id="enableTescoSizeChart" data-placement="left" data-original-title="Make Enabled"></i></a><a href="#"><i class="fa fa-pencil fa-button edit-tesco-size-chart filterbtn options" title="Edit" data-placement="left" data-id="'.$value->id.'"></i></a>';

          //       $status = '<span class="label label-danger">Disabled</span>';
          //   }
          //   $h = $value->Height!=null ? $value->Height : "-";
          //   $w = $value->Weight!=null ? $value->Weight : "-";
          //   $c = $value->Chest!=null ? $value->Chest : "-";
          //   $b = $value->Bust!=null ? $value->Bust : "-";
          //   $w = $value->Waist!=null ? $value->Waist : "-";

          //   $td.='<tr><td>'.$sl_no.'</td>'.
          //        '<td>'.$value->id.'</td>'.
          //        '<td>'.$value->department_title.'</td>'.
          //        '<td>'.$value->uk_size.'</td>'.
          //        '<td>'.$value->ce_size.'</td>'.
          //        '<td>'.$h.'</td>'.
          //        '<td>'.$w.'</td>'.
          //        '<td>'.$c.'</td>'.
          //        '<td>'.$b.'</td>'.
          //        '<td>'.$w.'</td>'.
          //        '<td>'.$status.'</td>'.
          //        '<td>'.$actions.'</td></tr>'
          //        ;

          //        $tr.='<tr>'.$td.'</tr>'; 
          //  } 

           // dd($td);

          // return response()->json(['error'=>'false','message' =>'success', 
          //   'tesco_translationlist' => $tesco_translationlists,
          //   'table_data' => $td]);
     

     /**
     * for LabelSize add form modal data (categories and departments)
     * @return \Illuminate\Http\Response
     */
    public function getLabelSizeCategories(){
      try {
        $result = array();
        $label_categories = DB::table('tesco_secondary_size_category')->select('id', 'category_name')->get();
        $size_departments = DB::table('tesco_size_department')->select('id', 'department_name')->get();
        $result['label_categories'] = $label_categories;
        $result['size_departments'] = $size_departments;
        return response()->json(['error'=>'false','message' =>'success', 'result' => $result]);
      }
      catch (\Exception $e){
          return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
      }
    }

     /**
     * add tesco label size chart 
     * @return \Illuminate\Http\Response
     */
    public function addTescoLabelSizeChart(Request $request){
      $data = $request->all();
      $primary_data = $data['customData']['primary'];
      $sec_data = $data['customData']['secondary'];

      try {
          $primary_inserted = TescoPrimarySize::create([
                    'tesco_size_department_id'   => $primary_data['size_department'],
                    'department_title'    => $primary_data['labelsize_dept_title'],
                    'uk_size'     => $primary_data['labelsize_uksize'],
                    'ce_size'    => $primary_data['labelsize_cesize']
          ]);
          
          foreach ($sec_data as $key => $value) {
              $cat_id = explode('_', $key);
              $secondary_inserted = TescoSecondarySizeChart::create([
                'tesco_primary_size_id'   => $primary_inserted->id,
                'tesco_secondary_size_category_id'    => $cat_id[1],
                'secondary_size'     => $value
              ]);
          }

          $result = $primary_inserted != '' ? response()->json(['error'=> false,'message' =>'success'],200):response()->json(['error'=> true,'message' =>'Error'],400);
      } catch (\Exception $e) {
          $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
      }
      return $result;
    }

    public function updateStatusFlag(Request $request)
    {
      // dd($request->all());
      $row = TescoPrimarySize::where('id',$request->id)->first();
      if($request->flag)
      {
        $row->is_flag=false;
      }
      else
      {
        $row->is_flag=true;
      }
      $row->save();

      return response(1);
    }

    public function updateTescoLabelSizeChart(Request $request){
      $data = $request->all();
      // dd($data);
      // if (array_key_exists('secondary', $data)) 
      // {
      //   $sec_data = $data['customData']['secondary'];
      // }
      // else
      // {
      //   $sec_data = [];
      // }

      $primary_data = $data['customData']['primary'];
      $sec_data = $data['customData']['secondary'];

      // dd(array_key_exists('secondary', $data));
      // dd(sizeof($sec_data));
      // if(!empty($sec_data))
      //     {dd("not empty");}
      //   else
      //     {dd("empty");}

      // dd($data);
       // dd($primary_data['edit_labelsize_dept_title']);
      // 
      // dd($sec_data);

      // dd($data['customData']['primary']['edit_labelsize_dept_title']);

      try {
          TescoPrimarySize::where('id',$data['customData']['primary']['edit_tesco_primary_id'])->first()->delete();
          $primary_inserted = TescoPrimarySize::create([
                    'tesco_size_department_id'   => $primary_data['edit_size_department'],
                    'department_title'    => $primary_data['edit_labelsize_dept_title'],
                    'uk_size'     => $primary_data['edit_labelsize_uksize'],
                    'ce_size'    => $primary_data['edit_labelsize_cesize']
          ]);

          // if(!empty($sec_data))
          // {
            foreach ($sec_data as $key => $value) {
                $cat_id = explode('_', $key);
                $secondary_inserted = TescoSecondarySizeChart::create([
                  'tesco_primary_size_id'   => $primary_inserted->id,
                  'tesco_secondary_size_category_id'    => $cat_id[1],
                  'secondary_size'     => $value
                ]);
            }
          // }

          $result = $primary_inserted != '' ? response()->json(['error'=> false,'message' =>'success'],200):response()->json(['error'=> true,'message' =>'Error'],400);
      } catch (\Exception $e) {
          $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
      }
      return $result;
    }
      /**
     * Make primary Size chart enabled
     * @param enableTescoPrimarySize chart id
     * @return boolean true or false
     */
      public function enableTescoPrimarySize(Request $request){
        try {
              $id = $request['id'];
              TescoPrimarySize::where('id',$id)->update([
                      'is_flag'  => 1
              ]);
              $result = response()->json(['error'=> false,'message' =>'Success'],200);
        } catch (\Exception $e) {
                  $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
    }

    /**
     * Make tesco_primary_size size chart
     * @return boolean true or false
     */
    public function disableTescoPrimarySizeChart(Request $request){
      try {
          $id = $request['id'];
          TescoPrimarySize::where('id',$id)->update([
            'is_flag'  => 0
          ]);
          $result = response()->json(['error'=> false,'message' =>'Success'],200);
      } catch (\Exception $e) {
          $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
      }
          return $result;
    }

     /**
     * Get tesco_primary_size details by ID for editing.
     * @param tesco_primary_size id
     * @return \Illuminate\Http\Response
     */
    public function getTescoPrimarySizeById(Request $request){
      try {
            $id = $request['id'];
            // $id = 2;
            
            $tesco_primary_size = TescoPrimarySize::where('id',$id)->with(['secSizeChart' => function ($query) {
              $query->leftJoin('tesco_secondary_size_category', 'tesco_secondary_size_category.id', '=', 'tesco_secondary_size_chart.tesco_secondary_size_category_id');
            }])->orderBy('is_flag','desc')->get();

            $categories_values = [];
            $categories = [];
            $cat_id = [];

            foreach ($tesco_primary_size as $key => $value) {
              foreach ($value->secSizeChart as $k => $inner_loop) {
                array_push($categories, $inner_loop->category_name);  
                array_push($cat_id, $inner_loop->tesco_secondary_size_category_id);  
                array_push($categories_values, $inner_loop->secondary_size);  
                // $categories_values[$inner_loop->category_name] = $inner_loop->secondary_size;
              }
            }

            // 

            return response()->json(['error'=>'false','message' =>'success', 'tesco_primary_size' => $tesco_primary_size,'selected_fields'=>$categories_values,'categories'=>$categories,'category_id'=>$cat_id]);
      }
      catch (\Exception $e){
            return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
      }
    }

}


