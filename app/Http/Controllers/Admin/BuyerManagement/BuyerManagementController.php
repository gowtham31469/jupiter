<?php

namespace App\Http\Controllers\Admin\BuyerManagement;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classes\Modules;
use Response;
use View;
use App\Buyer;
use App\Merchandiser;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use DB;
use Hash;
use File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use ItemMasterSeeder;
/**
 * Buyer management controller
 * @author <Praveen>
 */
class BuyerManagementController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Modules $module)
    {
       $this->middleware('auth');
       $this->module_var = $module;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     * @author <Praveen>
     */
    public function index()
    {
        $is_allowed = $this->module_var->permittedModules();
        if($is_allowed)
        {
            $get_main_module = $this->module_var->getMainModules();
            $get_sub_module = $this->module_var->getSubModules(3);
            View::composer('layouts.Admin.navbar', function($view) use ($get_main_module,$get_sub_module) {
                return $view->with(['modules' => $get_main_module,'sub_modules' => $get_sub_module]);
            });
            return view('Admin/Buyer Management/home');
        }
        else
        {
             return redirect('/admin/dashboard');
        }
    }
    /**
     * Buyers list page show using ajax.
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function buyerList()
    {
      try {
            $buyers = DB::table('buyers')->select('id','buyer_name','buyer_address','buyer_email','buyer_phone','is_active')->orderBy('is_active','desc')->get();
            return response()->json(['error'=>'false','message' =>'success', 'buyers' => $buyers]);
          } catch (\Exception $e)
          {
                 return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
          }
    }

    /**
     * Method to create dynamic item master
     * @return \Illuminate\Http\Response
     */
    public function createItemMaster($buyer_name)
    {
        // set dynamic table name 
        $table_name = strtolower($buyer_name).'_item_master';

        Schema::create($table_name, function (Blueprint $table) {
          $table->increments('id');
          $table->integer('company_id')->unsigned();
          $table->integer('parent_id')->unsigned();
          $table->string('item_name');
          $table->string('ref_no');
          $table->string('tag_name');
          $table->integer('is_qrcode_required')->unsigned();
          $table->boolean('is_non_artwork_template')->deafult(false);
          $table->timestamps();

          $table->foreign('company_id')->references('id')
          ->on('company')->onDelete('cascade');
          /* Indexes	*/
          $table->index(['company_id']);
        });

        // Run seeder for item master
        $item_master_seeder = new ItemMasterSeeder;
        $item_master_seeder->run($table_name);

        return response()->json(['message' => 'Given table has been successfully created!'], 200);
    }


    /**
     * Method to create dynamic template table while creating buyers
     * @return \Illuminate\Http\Response
     */
    public function createTemplateTable($buyer_name)
    {
        // set dynamic table name 
        $table_name = strtolower($buyer_name).'_template';
        $item_master_table = strtolower($buyer_name).'_item_master';

        Schema::create($table_name, function (Blueprint $table) use ($item_master_table) {
          $table->increments('id');
          $table->integer('item_id')->unsigned();
          $table->integer('material_id')->unsigned();
          $table->integer('hierarchy_id');
          $table->string('template_name');
          $table->string('template_description')->nullable();
          $table->string('template_file')->nullable();
          $table->smallInteger('disney')->nullable();
          $table->integer('tax');
          $table->string('gl_code');
          $table->string('hsn_code');
          $table->string('color');
          $table->integer('dimension_height');
          $table->integer('dimension_width');
          $table->string('gl_text');
          $table->smallInteger('flag');
          $table->integer('name_in_reports')->nullable();
          $table->string('type')->nullable();
          $table->timestamps();
          
          $table->foreign('item_id')->references('id')
          ->on($item_master_table)->onDelete('cascade');
    
          $table->foreign('material_id')->references('id')
          ->on('raw_material_master')->onDelete('cascade');
          /* Indexes	*/
          $table->index(['item_id','material_id']);
        });

        return response()->json(['message' => 'Given table has been successfully created!'], 200);
    }

    /**
     * Method to rename dynamic item master
     * @return \Illuminate\Http\Response
     */
    public function renameItemMaster($old_buyer_name, $new_buyer_name)
    {
        // set dynamic table name 
        $old_table_name = strtolower($old_buyer_name).'_item_master';
        $new_table_name = strtolower($new_buyer_name).'_item_master';

        Schema::table($old_table_name, function (Blueprint $table) {
          $table->dropForeign(['company_id']);
        });

        Schema::table($old_table_name, function (Blueprint $table) {
          $table->dropIndex(['company_id']);
        });
      
        Schema::rename($old_table_name, $new_table_name);
      
        Schema::table($new_table_name, function (Blueprint $table) {
          $table->foreign('company_id')->references('id')->on('company')->onDelete('cascade');
          $table->index(['company_id']);
        });
    }

    /**
     * Method to rename buyer template table
     * @return \Illuminate\Http\Response
     */
    public function renameTemplateTable($old_buyer_name, $new_buyer_name)
    {
        // set dynamic table name 
        $old_table_name = strtolower($old_buyer_name).'_template';
        $new_table_name = strtolower($new_buyer_name).'_template';
        $item_master_table = strtolower($new_buyer_name).'_item_master';

        Schema::table($old_table_name, function (Blueprint $table) {
          $table->dropForeign(['item_id']);
          $table->dropForeign(['material_id']);
          $table->dropIndex(['item_id', 'material_id']);
        });

        Schema::rename($old_table_name, $new_table_name);
      
        Schema::table($new_table_name, function (Blueprint $table) {
          $table->foreign('item_id')->references('id')->on($item_master_table)->onDelete('cascade');
          $table->foreign('material_id')->references('id')->on('raw_material_master')->onDelete('cascade');
          $table->index(['company_id', 'material_id']);
        });
    }

    /**
     * Add buyers for JGA.
     * @param \Illuminate\Http\Request
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function addJgaBuyer(Request $request)
      {
        try {
                $name      = $request['name'];
                $email     = $request['email'];
                $phone     = $request['phone'];
                $address   = preg_replace('/\s*,\s*/', ', ', $request['address']);

                $image = $request->file('image');
                $image_rules = array(
                    'image' => 'required|image|file|max:2048',
                );
                $imageValidator = Validator::make($request->all() ,$image_rules);

                if($imageValidator->fails())
                {
                    return response()->json(['error'=> true,'message' =>'Invalid file'],422);
                }
                else
                {
                  $image = $request['image'];
                  $destination_path='/storage/app/public/buyer-logo/';
                  $path='storage/app/public/buyer-logo/';
                  $Orname = $image->getClientOriginalName();
                  $filename= time().'_'.preg_replace('/#[^\s]+/','',preg_replace('/\s+/', '-', $Orname));
                  $image->move($path,$filename);
                  $destination_path_insert=$destination_path.$filename;
                }
                
                if(Buyer::where('buyer_email',$email)->exists())
                {
                  $result = response()->json(['error'=> true,'message' =>'Email already registered.'],409);
                }
                elseif(Buyer::where('buyer_name',$name)->exists()) {
                  $result = response()->json(['error'=> true,'message' =>'Buyer already registered'],409);
                }
                else
                {
                  $buyer = strtolower($name);
                  $inserted = Buyer::create([
                                'buyer_name' => $name,
                                'buyer_email'      => $email,
                                'buyer_phone'      => $phone,
                                'buyer_address'    => $address,
                                'is_active'        => 0,
                                'logo'             => $destination_path_insert,
                                'template_table'   => $buyer.'_template',
                                'item_master_table' => $buyer.'_item_master',
                                'material_master_table' => $buyer.'_material_masters'
                              ]);
                  $item_master_creation = $this->createItemMaster($name);
                  $template_creation = $this->createTemplateTable($name);
                  $template_creation = $this->createTemplateTable($name);
                    
                  $result = $inserted != ''?response()->json(['error'=> false,'message' =>'success'],200):response()->json(['error'=> true,'message' =>'Error'],400);
                }

            } catch (\Exception $e)
            {
                   $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
            }
            return $result;
      }
      /**
       * Get buyer details by ID for editing.
       * @param Request $request
       * @author <Praveen>
       * @return \Illuminate\Http\Response
       */
      public function getBuyerById(Request $request)
      {
        try {
              $buyer = DB::table('buyers')->select('id','buyer_name','buyer_address','buyer_email','buyer_phone','is_active','logo')->where('buyers.id',$request['id'])->get();
              return response()->json(['error'=>'false','message' =>'success', 'buyer' => $buyer]);
            } catch (\Exception $e)
            {
              return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
            }
      }
      /**
       * Update buyer by id.
       * @param Request $request
       * @author <Praveen>
       * @return \Illuminate\Http\Response
       */
      public function updateBuyerById(Request $request)
      {
        try {
              $id                 = $request['id'];
              $input['id']        = Input::get('id');
              $input['email']     = Input::get('email');
              $input['name']      = Input::get('name');
              $input['address']   = Input::get('address');
              $input['phone']     = Input::get('phone');

              if($request->hasFile('edit_image'))
              {
                  $image = $request->file('edit_image');
                  $image_rules = array(
                      'edit_image' => 'required|image|file|max:2048',
                  );
                  $imageValidator = Validator::make($request->all() ,$image_rules);

                if($imageValidator->fails())
                {
                   return response()->json(['error'=> true,'message' =>'Invalid file'],422);
                }
                else
                {
                   //delete old file
                   $buyer = Buyer::select('id','logo')->where('id','=',$id)->get();
                   $files = $buyer[0]->logo;
                   $files = substr($files,1);
                   File::delete($files);

                   $image = $request['edit_image'];
                   $destination_path='/storage/app/public/buyer-logo/';
                   $path='storage/app/public/buyer-logo/';
                   $Orname = $image->getClientOriginalName();
                   $filename= time().'_'.preg_replace('/#[^\s]+/','',preg_replace('/\s+/', '-', $Orname));
                   $image->move($path,$filename);
                   $destination_path_insert=$destination_path.$filename;
                }
               }
               else
               {
                   $destination_path_insert = $request->imgName;
               }
               
               

              $email_rules = array('email' => 'unique:buyers,buyer_email,'.$id);
              $emailValidator = Validator::make($input ,$email_rules);

              if($emailValidator->fails())
              {
                  return response()->json(['error'=> true,'message' =>'Email Already Exists'],409);
              }
              else
              {

                $b_name = DB::table('buyers')->where('id','=',$id)->value('buyer_name');
                Buyer::find($id)
                  ->update([
                    'buyer_name'          => $input['name'],
                    'buyer_email'         => $input['email'],
                    'buyer_phone'         => $input['phone'],
                    'buyer_address'       => preg_replace('/\s*,\s*/', ', ', $input['address']),
                    'logo'                => $destination_path_insert
                ]);
                if($b_name !== $input['name']) {
                  // Update Corresponding item master name
                  $updated_item_master = $this->renameItemMaster($b_name, $input['name']);
                  // Update corresponding template name
                  $updated_template_name = $this->renameTemplateTable($b_name, $input['name']);
                }
                 
                $result = response()->json(['error'=> false,'message' =>'Success'],200);
              }
            } catch (\Exception $e)
            {
                   $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
            }
            return $result;
        }
    /**
     * Show Merchandisers list page on ajax.
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function merchandiserList()
    {
      try {
             $merchandisers = DB::table('merchandisers')->select('merchandisers.id','merchandiser_name','merchandisers.email','merchandiser_address','merchandiser_phone','merchandisers.is_active','buyers.buyer_name')
             ->orderBy('is_active','desc')
             ->join('buyers','buyers.id','=','merchandisers.buyer_id')
             ->get();

            return response()->json(['error'=>'false','message' =>'success', 'merchandisers' => $merchandisers]);
          } catch (\Exception $e)
          {
                 return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
          }
            echo json_encode($merchandisers);
    }
    /**
     * Add Merchandisers to jga. 
     * @param \Illuminate\Http\Request
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function addMerchandiser(Request $request)
      {
        try {
                $name                  = $request['name'];
                $email                 = $request['email'];
                $phone                 = $request['phone'];
                $address               = preg_replace('/\s*,\s*/', ', ', $request['address']);
                $password              = Hash::make($request['p']);
                $merchandiser_buyer    = $request['merchandiser_buyer'];

                if(Merchandiser::where('email',$email)->exists())
                {
                  $result = response()->json(['error'=> true,'message' =>'Email Already Exists'],409);
                }
                else
                {
                  $inserted = Merchandiser::create([
                                'merchandiser_name'      => $name,
                                'email'                  => $email,
                                'password'               => $password,
                                'merchandiser_address'   => $address,
                                'merchandiser_phone'     => $phone,
                                'is_active'              => 0,
                                'buyer_id'               => $merchandiser_buyer,
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
       * Get buyer details by ID for editing.
       * @param Request $request
       * @author <Praveen>
       * @return \Illuminate\Http\Response
       */
      public function getMerchandiserById(Request $request)
      {
        try {
              $merchandiser = DB::table('merchandisers')->select('id','merchandiser_name','email','merchandiser_address','merchandiser_phone','is_active','buyer_id')->where('merchandisers.id', $request['id'])->get();
              return response()->json(['error'=>'false','message' =>'success', 'merchandiser' => $merchandiser]);
            } catch (\Exception $e)
            {
              return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
            }
      }
      /**
       * Update merchandiser by id.
       * @param Request $request
       * @author <Praveen>
       * @return \Illuminate\Http\Response
       */
      public function updateMerchandiserById(Request $request)
      {
        try {
              $id      = $request['id'];
              $input['id']        = Input::get('id');
              $input['email']     = Input::get('email');
              $input['name']      = Input::get('name');
              $input['address']   = Input::get('address');
              $input['phone']     = Input::get('phone');
              $input['buyer']     = Input::get('buyer');
              $email_rules = array('email' => 'unique:merchandisers,email,'.$id);
              $emailValidator = Validator::make($input ,$email_rules);

              if($emailValidator->fails())
              {
                  return response()->json(['error'=> true,'message' =>'Email Already Exists'],409);
              }
              else
              {
                  Merchandiser::find($id)
                   ->update([
                     'merchandiser_name'      => $input['name'],
                     'email'                  => $input['email'],
                     'merchandiser_address'   => preg_replace('/\s*,\s*/', ', ', $input['address']),
                     'merchandiser_phone'     => $input['phone'],
                     'buyer_id'               => $input['buyer'],
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
         * Show the buyers list on pageload.
         *
         * @return \Illuminate\Http\Response
         */
        public function buyers()
        {
            $is_allowed = $this->module_var->permittedModules();
            if($is_allowed)
            {
                $get_main_module = $this->module_var->getMainModules();
                $get_sub_module = $this->module_var->getSubModules(3);
                $buyers = DB::table('buyers')->select('id','buyer_name','buyer_address','buyer_email','buyer_phone','is_active')->orderBy('is_active','desc')->get();
                View::composer('layouts.Admin.navbar', function($view) use ($get_main_module,$get_sub_module) {
                    return $view->with(['modules' => $get_main_module,'sub_modules' => $get_sub_module]);
                });
                return view('Admin/Buyer Management/buyers')->with(['buyers' => $buyers]);
            }
            else
            {
                 return redirect('/admin/dashboard');
            }
        }
        /**
         * Show the merchandisers list on pageload.
         *
         * @return \Illuminate\Http\Response
         */
        public function merchandisers()
        {
            $is_allowed = $this->module_var->permittedModules();
            if($is_allowed)
            {
                $get_main_module = $this->module_var->getMainModules();
                $get_sub_module = $this->module_var->getSubModules(3);
                $merchandisers = DB::table('merchandisers')->select('merchandisers.id','merchandiser_name','merchandisers.email','merchandiser_address','merchandiser_phone','merchandisers.is_active','buyers.buyer_name')
                ->orderBy('is_active','desc')
                ->join('buyers','buyers.id','=','merchandisers.buyer_id')
                ->get();
                $buyers = DB::table('buyers')->where('is_active','=',1)->pluck('buyer_name','id')->all();
                
                View::composer('layouts.Admin.navbar', function($view) use ($get_main_module,$get_sub_module) {
                    return $view->with(['modules' => $get_main_module,'sub_modules' => $get_sub_module]);
                });
                return view('Admin/Buyer Management/merchandisers')->with(['merchandisers' => $merchandisers,'buyers' => $buyers]);
            }
            else
            {
                 return redirect('/admin/dashboard');
            }
        }
        /**
         * Make buyer active
         * @param Request $request
         * @author <Praveen>
         * @return boolean true or false
         */
        public function makeBuyerActive(Request $request)
        {
          try {
                Buyer::find($request['id'])->update(['is_active' => 1]);

                $result = response()->json(['error'=> false,'message' =>'Success'],200);
              } 
              catch (\Exception $e)
              {
                $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
              }
              return $result;
        }
        /**
         * Make Buyer in-active
         * @author <Praveen>
         * @param Request $request
         * @return boolean true or false
         */
        public function makeBuyerInactive(Request $request)
        {
          try {
                Buyer::find($request['id'])->update(['is_active' => 0]);

                $result = response()->json(['error'=> false,'message' =>'Success'],200);
              } 
              catch (\Exception $e)
              {
                $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
              }
              return $result;
        }
      /**
       * Make Merchandiser active
       * @param Request $request
       * @author <Praveen>
       * @return boolean true or false
       */
      public function makeMerchandiserActive(Request $request)
      {
        try {
              Merchandiser::find($request['id'])->update(['is_active' => 1]);

              $result = response()->json(['error'=> false,'message' =>'Success'],200);
            } 
            catch (\Exception $e)
            {
              $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
            }
            return $result;
      }
      /**
       * Make Merchandiser in-active
       * @author <Praveen>
       * @param Request $request
       * @return boolean true or false
       */
      public function makeMerchandiserInactive(Request $request)
      {
        try {
              Merchandiser::find($request['id'])->update(['is_active' => 0]);

              $result = response()->json(['error'=> false,'message' =>'Success'],200);
            } 
            catch (\Exception $e)
            {
              $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
            }
            return $result;
      }
}
