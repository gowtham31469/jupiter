<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Response;
use View;
use DB;
use Auth;
use Hash;

class CustomerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('customer_auth');
    }

    /**
     * Show the application Customers.
     * @author [Gowtham] <[gowtham@wizroots.com]>
     * @return \Illuminate\Http\Response
     */
    public function customers()
    {
          try {
                 $customer_id = Auth::guard('web_customer')->user()->id;
			   $company_id = Auth::guard('web_customer')->user()->company_id;
                 $buyers          = DB::table('customer_profile_buyer')
								  ->join('buyers', 'buyers.id', 'customer_profile_buyer.buyer_id')
								  ->where('customer_profile_buyer.customer_profile_id',$customer_id)
								  ->pluck('buyers.buyer_name','buyers.id')->all();
			  
			     $roles          = DB::table('roles')->where('id','=',2)->pluck('role_name','id')->all();
                 
                 $company = DB::table('customers')
                                    ->where('is_active','=',1)
                                    ->pluck('company_name','id')
                                    ->all();
                 // $tesco_customers = DB::table('tesco_suppliers')->where('is_enabled','=',1)->pluck('supplier_name','id')->all();
                 $customers       =  DB::table('customer_profile')
					 				 ->join('roles', 'roles.id', 'customer_profile.role_id')
					 				 ->join('customers', 'customers.id', 'customer_profile.company_id')
					 				 ->where('company_id',Auth::guard('web_customer')->user()->company_id)
					 				->where('role_id','!=','1')
                                     ->select('customer_profile.*','roles.role_name','customers.company_name')
                                     ->get();  
                
                   
                     return view('customer/company')->with(['company' => $company, 'buyers' => $buyers,'customers' => $customers,'roles' => $roles,'customer_id'=>$customer_id,'company_id'=>$company_id]);
                 
         } catch (\Exception $e)
         {
                return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
         }
        
    }
	
	/**
       * Make customer active
       * @param customer id
       * @author <Praveen>
       * @return boolean true or false
       */
      public function makeCustomerProfileActive(Request $request)
      {
        try {
              $id      = $request['id'];
              
              DB::table('customer_profile')->where('id',$id)
               ->update([
                 'status'  => 1
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
       * Make customer in-active
       * @author <Praveen>
       * @return boolean true or false
       */
      public function makeCustomerProfileInactive(Request $request)
      {
        try {
              $id      = $request['id'];
              
              DB::table('customer_profile')->where('id',$id)
               ->update([
                 'status'  => 0
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
         * Gets the customer by identifier.
         * @param customer id
         * @return The customer by identifier.
         * @author <Praveen>
         */
        public function getCustomerProfileById(Request $request)
        {
          try {
                $id = $request['id'];
                $customer = DB::table('customer_profile')
                            ->select('*')
                            ->where('customer_profile.id','=',$id)
                            ->get();
        			   $buyers = DB::table('customer_profile_buyer')
                                 ->select('customer_profile_buyer.buyer_id')
                                 ->where('customer_profile_buyer.customer_profile_id','=',$id)
                                 ->get();
        			 
			  
			     $company_buyers = DB::table('customer_buyers')
        								    ->join('buyers', 'buyers.id', 'customer_buyers.buyer_id')
        								    ->select('customer_buyers.*','buyers.buyer_name')
        								    ->where('customer_buyers.customer_id','=',$customer{0}->company_id)
        								    ->get();
			  
                
                return response()->json(['error'=>'false','message' =>'success','customers' => $customer,'buyers' => $buyers,'company_buyers' => $company_buyers]);
              } catch (\Exception $e)
              {
                return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
              }
        }
		/**
         * Update Customer by id.
         * @param \Illuminate\Http\Request
         * @author <Praveen>
         * @return boolean true or false
         */
        public function updateCustomerProfile(Request $request)
          {
            try {
                    $id               = $request['id'];
                    $buyer            = Input::get('buyer');
                    $input['customer_name']= Input::get('customer_name');
					$input['customer_email']= Input::get('customer_email');
				    $input['customer_role']		= Input::get('customer_role');
					$input['customer_company']= Input::get('customer_company');
					$input['customer_title']= Input::get('customer_title');	
                    $email_rules     = array('customer_email' => 'unique:customer_profile,email,'.$id);
                    $emailValidator  = Validator::make($input,$email_rules);

                    if($emailValidator->fails())
                    {
                        return response()->json(['error'=> true,'message' =>'Email Already Exists'],409);
                    }
                    else
                    {
                       $buyers           = explode(',',$buyer);
                       DB::table('customer_profile')->where('id',$id)
                            ->update([
                            'name'            => $input['customer_name'],
                            'email'           => $input['customer_email'],
                            'role_id'         => $input['customer_role'],
							'title'      	  => $input['customer_title'],
                            'company_id'      => $input['customer_company']
                          ]);
                        
                        /** Delete existing data before updating **/
                        DB::table('customer_profile_buyer')->where('customer_profile_id', '=', $id)
                            ->delete();
                        foreach($buyers as $buyer)
                        {
                             DB::table('customer_profile_buyer')->insert([
                                'customer_profile_id'     => $id,
                                'buyer_id'        => $buyer
                            ]);
                        }
                       
                        $result = response()->json(['error'=> false,'message' =>'success'],200);
                    }

                } catch (\Exception $e)
                {
                       $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
                }
                return $result;
          }
	/**
     * Add Customers to jga.
     * @param \Illuminate\Http\Request
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function addCustomerProfile(Request $request)
      {
        try {
               
                $password             = Hash::make($request['password']);
                $customer_email       = $request['customer_email'];
                $buyer                = $request['buyer'];
                $customer_name        = $request['customer_name'];
                $customer_role        = $request['customer_role'];
                $customer_company     = $request['customer_company'];
			    $customer_title       = $request['customer_title'];

                $email_exists =  DB::table('customer_profile')->where('email',$customer_email)->select('id')->get();
                if(count($email_exists) > 0)
                {
                  $result = response()->json(['error'=> true,'message' =>'Email Already Exists'],409);
                }
                else
                {
                  $buyers           = explode(',',$buyer);

                  $customer = DB::table('customer_profile')->insertGetId([
                                'password'               => $password,
                                'email'                  => $customer_email,
                                'name'             => $customer_name,
                                'role_id'          => $customer_role,
                                'title'      		 => $customer_title,
                                'company_id'       => $customer_company
                              ]);
                  $insert_Id = $customer; 
                    foreach($buyers as $buyer)
                    {
                        DB::table('customer_profile_buyer')->insert([
                            'customer_profile_id'     => $insert_Id,
                            'buyer_id'        => $buyer
                        ]);
                    }
                    $result = response()->json(['error'=> false,'message' =>'success'],200);
                  }

            } catch (\Exception $e)
            {
                   $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
            }
            return $result;
      }
		/**
		* Show the company buyers list.
		* @author <Gowtham>
		* @return \Illuminate\Http\Response
		*/
		public function customerCompanyBuyer(Request $request)
		{
			 try {
					$customer_list = DB::table('customer_profile_buyer')
						  ->join('buyers', 'buyers.id', 'customer_profile_buyer.buyer_id')
						  ->where('customer_profile_buyer.customer_profile_id',$request->customer_add_company)
						  ->select('customer_profile_buyer.*','buyers.buyer_name')
						  ->get();
				 	$role = DB::table('customer_profile')
						  ->where('customer_profile.company_id',$request->customer_add_company)
					 	  ->where('customer_profile.role_id','1')
						  ->select('customer_profile.*')
						  ->get();
					return response()->json(['customer_list' => $customer_list,'role' => $role]);

			 } catch (\Exception $e)
			 {
					return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
			 }
		}

}