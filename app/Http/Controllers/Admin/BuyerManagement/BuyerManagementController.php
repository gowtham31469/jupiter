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
            $buyers = DB::table('buyers')->select('id','buyer_name','buyer_address','buyer_email','buyer_phone')->get();
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
              $buyer = DB::table('buyers')->select('id','buyer_name','buyer_address','buyer_email','buyer_phone')->where('buyers.id',$id)->get();
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
                     'buyer_address'       => $input['address']
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
     * function to delete buyer by id.
     * @param buyer id
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function deleteBuyerById(Request $request)
    {
      try {
                $id        = $request['id'];
                $deleted = Buyer::where('id', '=', $id)
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
     * get all buyers and customers list for dropdown.
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function getBuyers()
    {
      try {
                $buyers = DB::table('buyers')->select('buyer_name','id')->get();
                $customers = DB::table('customers')->select('company_name','id')->get();
                return response()->json(['error'=> false,'message' =>'Success','buyers' => $buyers,'customers' => $customers],200);
          } catch (\Exception $e)
          {
                 return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
          }
    }
    /**
     * Show Merchandisers list page on ajax.
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function merchandiserList()
    {
      try {
             $merchandisers = DB::table('merchandisers')->select('merchandisers.id','merchandiser_name','merchandisers.email','merchandiser_address','merchandiser_phone','buyers.buyer_name','customers.company_name')
             ->join('buyers','buyers.id','=','merchandisers.buyer_id')
             ->join('customers','customers.id','=','merchandisers.customer_id')->get();

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
                $merchandiser_supplier = $request['merchandiser_supplier'];
                $email_exists = Merchandiser::where('email',$email)->select('id')->get();
                if(count($email_exists) > 0)
                {
                  $result = response()->json(['error'=> true,'message' =>'Email Already Exists'],409);
                }
                else
                {
                  $inserted = Merchandiser::create([
                                'buyer_id'               => $merchandiser_buyer,
                                'customer_id'            => $merchandiser_supplier,
                                'merchandiser_name'      => $name,
                                'email'                  => $email,
                                'password'               => $password,
                                'merchandiser_address'   => $address,
                                'merchandiser_phone'     => $phone
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
              $merchandiser = DB::table('merchandisers')->select('id','buyer_id','customer_id','merchandiser_name','email','merchandiser_address','merchandiser_phone')->where('merchandisers.id',$id)->get();
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
              $input['supplier']  = Input::get('supplier');
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
                     'buyer_id'               => $input['buyer'],
                     'customer_id'            => $input['supplier'],
                     'merchandiser_name'      => $input['name'],
                     'email'                  => $input['email'],
                     'merchandiser_address'   => $input['address'],
                     'merchandiser_phone'     => $input['phone']
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
         * function to delete Merchandiser by id.
         * @param Merchandiser id
         * @author <Praveen>
         * @return \Illuminate\Http\Response
         */
        public function deleteMerchandiserById(Request $request)
        {
          try {
                    $id        = $request['id'];
                    $deleted = Merchandiser::where('id', '=', $id)
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
                $buyers = DB::table('buyers')->select('id','buyer_name','buyer_address','buyer_email','buyer_phone')->get();
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
                $merchandisers = DB::table('merchandisers')->select('merchandisers.id','merchandiser_name','merchandisers.email','merchandiser_address','merchandiser_phone','buyers.buyer_name','customers.company_name')
             ->join('buyers','buyers.id','=','merchandisers.buyer_id')
             ->join('customers','customers.id','=','merchandisers.customer_id')->get();
             $buyers = DB::table('buyers')->pluck('buyer_name','id')->all();
             $customers = DB::table('customers')->pluck('company_name','id')->all();
                View::composer('layouts.Admin.navbar', function($view) use ($get_main_module,$get_sub_module) {
                    return $view->with(['modules' => $get_main_module,'sub_modules' => $get_sub_module]);
                });
                return view('Admin/Buyer Management/merchandisers')->with(['merchandisers' => $merchandisers,'buyers' => $buyers,'customers' => $customers]);
            }
            else
            {
                 return redirect('/admin/dashboard');
            }
        }
}
