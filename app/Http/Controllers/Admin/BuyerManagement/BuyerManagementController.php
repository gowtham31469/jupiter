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
    public function __construct()
    {
       $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
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
            $get_sub_module = $module_var->getSubModules(3);
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
                $address   = $request['address'];

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
                  $filename= time().'_'.preg_replace('/\s+/', '-', $Orname);
                  $image->move($path,$filename);
                  $destination_path_insert=$destination_path.$filename;
                }
                
                $email_exists = Buyer::where('buyer_email',$email)->select('id')->get();
                if(count($email_exists) > 0)
                {
                  $result = response()->json(['error'=> true,'message' =>'Email Already Exists'],409);
                }
                else
                {
                  $inserted = Buyer::create([
                                'buyer_name' => $name,
                                'buyer_email'      => $email,
                                'buyer_phone'      => $phone,
                                'buyer_address'    => $address,
                                'is_active'        => 0,
                                'logo'             => $destination_path_insert
                              ]);
                    if($inserted != '')
                    {
                      $result = response()->json(['error'=> false,'message' =>'success'],200);
                    }
                    else
                    {
                      $result = response()->json(['error'=> true,'message' =>'Error'],400);
                    }
                }

            } catch (\Exception $e)
            {
                   $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
            }
            return $result;
      }
      /**
       * Get buyer details by ID for editing.
       * @param buyer id
       * @author <Praveen>
       * @return \Illuminate\Http\Response
       */
      public function getBuyerById(Request $request)
      {
        try {
              $id = $request['id'];
              $buyer = DB::table('buyers')->select('id','buyer_name','buyer_address','buyer_email','buyer_phone','is_active','logo')->where('buyers.id',$id)->get();
              return response()->json(['error'=>'false','message' =>'success', 'buyer' => $buyer]);
            } catch (\Exception $e)
            {
              return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
            }
      }
      /**
       * Update buyer by id.
       * @param buyer id
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
                   $files = $buyer{0}->logo;
                   $files = substr($files,1);
                   File::delete($files);

                   $image = $request['edit_image'];
                   $destination_path='/storage/app/public/buyer-logo/';
                   $path='storage/app/public/buyer-logo/';
                   $Orname = $image->getClientOriginalName();
                   $filename= time().'_'.preg_replace('/\s+/', '-', $Orname);
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
                  Buyer::where('id',$id)
                   ->update([
                     'buyer_name'          => $input['name'],
                     'buyer_email'         => $input['email'],
                     'buyer_phone'         => $input['phone'],
                     'buyer_address'       => $input['address'],
                     'logo'                => $destination_path_insert
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
                $address               = $request['address'];
                $password              = Hash::make($request['p']);
                $merchandiser_buyer    = $request['merchandiser_buyer'];
                $email_exists = Merchandiser::where('email',$email)->select('id')->get();
                if(count($email_exists) > 0)
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
                    if($inserted != '')
                    {
                      $result = response()->json(['error'=> false,'message' =>'success'],200);
                    }
                    else
                    {
                      $result = response()->json(['error'=> true,'message' =>'Error'],400);
                    }
                }

            } catch (\Exception $e)
            {
                   $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
            }
            return $result;
      }
      /**
       * Get buyer details by ID for editing.
       * @param Merchandiser id
       * @author <Praveen>
       * @return \Illuminate\Http\Response
       */
      public function getMerchandiserById(Request $request)
      {
        try {
              $id = $request['id'];
              $merchandiser = DB::table('merchandisers')->select('id','merchandiser_name','email','merchandiser_address','merchandiser_phone','is_active','buyer_id')->where('merchandisers.id',$id)->get();
              return response()->json(['error'=>'false','message' =>'success', 'merchandiser' => $merchandiser]);
            } catch (\Exception $e)
            {
              return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
            }
      }
      /**
       * Update merchandiser by id.
       * @param Merchandiser id
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
                  Merchandiser::where('id',$id)
                   ->update([
                     'merchandiser_name'      => $input['name'],
                     'email'                  => $input['email'],
                     'merchandiser_address'   => $input['address'],
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
            $module_var = new Modules();
            $is_allowed = $module_var->permittedModules();
            if($is_allowed)
            {
                $get_main_module = $module_var->getMainModules();
                $get_sub_module = $module_var->getSubModules(3);
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
            $module_var = new Modules();
            $is_allowed = $module_var->permittedModules();
            if($is_allowed)
            {
                $get_main_module = $module_var->getMainModules();
                $get_sub_module = $module_var->getSubModules(3);
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
         * @param buyer id
         * @author <Praveen>
         * @return boolean true or false
         */
        public function makeBuyerActive(Request $request)
        {
          try {
                $id      = $request['id'];
                
                Buyer::where('id',$id)
                 ->update([
                   'is_active'  => 1
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
         * Make Buyer in-active
         * @author <Praveen>
         * @param buyer id
         * @return boolean true or false
         */
        public function makeBuyerInactive(Request $request)
        {
          try {
                $id      = $request['id'];
                
                Buyer::where('id',$id)
                 ->update([
                   'is_active'  => 0
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
       * Make Merchandiser active
       * @param Merchandiser id
       * @author <Praveen>
       * @return boolean true or false
       */
      public function makeMerchandiserActive(Request $request)
      {
        try {
              $id      = $request['id'];
              
              Merchandiser::where('id',$id)
               ->update([
                 'is_active'  => 1
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
       * Make Merchandiser in-active
       * @author <Praveen>
       * @param Merchandiser id
       * @return boolean true or false
       */
      public function makeMerchandiserInactive(Request $request)
      {
        try {
              $id      = $request['id'];
              
              Merchandiser::where('id',$id)
               ->update([
                 'is_active'  => 0
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
