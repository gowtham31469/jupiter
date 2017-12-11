<?php

namespace App\Http\Controllers\Admin\CustomerManagement;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classes\Modules;
use Response;
use View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use DB;
use Hash;
use App\Customer;
use App\CustomerContactPersons;
use App\CustomerBillAddress;
/**
 * Customer management controller
 * @author <Praveen>
 */
class CustomerManagementController extends Controller
{
  /**
   * Create a new controller instance.
   * @author <Praveen>
   * @return void
   */
   public function __construct()
   {
      $this->middleware('auth');
   }
   /**
    * Show the Customer-Management home page.
    * @author <Praveen>
    * @return \Illuminate\Http\Response
    */
   public function index()
   {
     try {
           $module_var = new Modules();
           $is_allowed = $module_var->permittedModules();
           if($is_allowed)
           {
               $get_main_module = $module_var->getMainModules();
               $get_sub_module = $module_var->getSubModules(4);
               View::composer('layouts.Admin.navbar', function($view) use ($get_main_module,$get_sub_module) {
                   return $view->with(['modules' => $get_main_module,'sub_modules' => $get_sub_module]);
               });
               return view('Admin/Customer Management/home');
           }
           else
           {
                return redirect('/admin/dashboard');
           }
         } catch (\Exception $e)
         {
                return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
         }
   }
   /**
    * Show the orchestra customers list view on ajax.
    * @author <Praveen>
    * @return \Illuminate\Http\Response
    */
    public function customersList()
    {
      try{
            $customers = DB::table('customers')
                         ->select('customers.id','customers.company_name','customers.factory_code','customers.courier_choice','customers.account_no','customers.tin_number','customers.pan_number','customers.email','customer_contact_persons.contact_person_name as contact_person','customer_contact_persons.contact_person_email as contact_email','customer_contact_persons.contact_person_phone as phone_no','customers.gstin_number','customer_bill_address.address','customer_bill_address.country','customer_bill_address.email','customer_bill_address.telephone','customer_bill_address.fax','customers.is_active')
                         ->join('customer_contact_persons','customer_contact_persons.customer_id','customers.id')
                         ->join('customer_bill_address','customer_bill_address.customer_id','customers.id')
                         ->where('customer_bill_address.default_address','=',1)
                         ->where('customer_contact_persons.default_contact_person','=',1)
                         ->get();

            return response()->json(['error'=>'false','message' =>'success', 'customers' => $customers]);
          } catch (\Exception $e)
          {
            return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
          }
    }
    /**
     * Add Customers to jga.
     * @param \Illuminate\Http\Request
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function addCustomer(Request $request)
      {
        try {
                $factory_code    = $request['factory_code'];
                $company_name    = $request['company_name'];
                $courier_choice  = $request['courier_choice'];
                $account_no      = $request['account_no'];
                $password        = Hash::make($request['p']);
                $tin_number      = $request['tin_number'];
                $gstin_number    = $request['gstin_number'];
                $pan_number      = $request['pan_number'];
                $login_email     = $request['login_email'];
                
                $contact_person  = $request['contact_person'];
                $contact_phone   = $request['contact_phone'];
                $contact_email   = $request['contact_email'];
                $default_contact = $request['default_contact'];
                $address         = $request['address'];
                $country         = $request['country'];
                $telephone       = $request['telephone'];
                $fax             = $request['fax'];
                $address_email   = $request['address_email'];
                $default_address = $request['default_address'];

                $email_exists = Customer::where('email',$login_email)->select('id')->get();
                if(count($email_exists) > 0)
                {
                  $result = response()->json(['error'=> true,'message' =>'Email Already Exists'],409);
                }
                else
                {
                  $contact_person  = explode(',',$contact_person);
                  $contact_phone   = explode(',',$contact_phone);
                  $contact_email   = explode(',',$contact_email);
                  $default_contact = explode(',',$default_contact);

                  $address         = explode('|',$address);
                  $country         = explode(',',$country);
                  $telephone       = explode(',',$telephone);
                  $fax             = explode(',',$fax);
                  $address_email   = explode(',',$address_email);
                  $default_address = explode(',',$default_address);

                  $insert_Id = Customer::insertGetId([
                                'factory_code'   => $factory_code,
                                'company_name'   => $company_name,
                                'courier_choice' => $courier_choice,
                                'account_no'     => $account_no,
                                'password'       => $password,
                                'tin_number'     => $tin_number,
                                'gstin_number'   => $gstin_number,
                                'pan_number'     => $pan_number,
                                'email'          => $login_email
                              ]);
                  
                  if($insert_Id != '')
                  {
                    $i = 0;
                    foreach($contact_person as $contact)
                    {
                        CustomerContactPersons::create([
                            'customer_id'            => $insert_Id,
                            'contact_person_name'    => $contact,
                            'contact_person_email'   => $contact_email[$i],
                            'contact_person_phone'   => $contact_phone[$i],
                            'default_contact_person' => $default_contact[$i]
                        ]);
                        $i++;
                    }
                    $j = 0;
                    foreach($address as $value)
                    {
                        CustomerBillAddress::create([
                            'customer_id'     => $insert_Id,
                            'address'         => $value,
                            'country'         => $country[$j],
                            'email'           => $address_email[$j],
                            'telephone'       => $telephone[$j],
                            'fax'             => $fax[$j],
                            'default_address' => $default_address[$j]
                        ]);
                        $j++;
                    }
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
       * Make customer active
       * @param customer id
       * @author <Praveen>
       * @return boolean true or false
       */
      public function makeCustomerActive(Request $request)
      {
        try {
              $id      = $request['id'];
              
              Customer::where('id',$id)
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
       * Make customer in-active
       * @author <Praveen>
       * @return boolean true or false
       */
      public function makeCustomerInactive(Request $request)
      {
        try {
              $id      = $request['id'];
              
              Customer::where('id',$id)
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
         * Gets the customer by identifier.
         * @param customer id
         * @return The customer by identifier.
         * @author <Praveen>
         */
        public function getCustomerById(Request $request)
        {
          try {
                $id = $request['id'];
                $customer = DB::table('customers')
                         ->select('customers.id','customers.company_name','customers.factory_code','customers.courier_choice','customers.account_no','customers.tin_number','customers.pan_number','customers.email','customers.gstin_number')
                         ->where('customers.id','=',$id)
                         ->get();
                $contacts = DB::table('customer_contact_persons')
                         ->select('customer_contact_persons.*')
                         ->where('customer_contact_persons.customer_id','=',$id)
                         ->get();
                $address = DB::table('customer_bill_address')
                         ->select('customer_bill_address.*')
                         ->where('customer_bill_address.customer_id','=',$id)
                         ->get();
                return response()->json(['error'=>'false','message' =>'success','customers' => $customer,'contacts' => $contacts,'address' => $address]);
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
        public function updateCustomer(Request $request)
          {
            try {
                    $id                       = $request['id'];
                    $input['factory_code']    = Input::get('factory_code');
                    $input['company_name']    = Input::get('company_name');
                    $input['courier_choice']  = Input::get('courier_choice');
                    $input['account_no']      = Input::get('account_no');
                    $input['tin_number']      = Input::get('tin_number');
                    $input['gstin_number']    = Input::get('gstin_number');
                    $input['pan_number']      = Input::get('pan_number');
                    $input['email']           = Input::get('login_email');
                    
                    $contact_person  = Input::get('contact_person');
                    $contact_phone   = Input::get('contact_phone');
                    $contact_email   = Input::get('contact_email');
                    $default_contact = Input::get('default_contact');
                    $address         = Input::get('address');
                    $country         = Input::get('country');
                    $telephone       = Input::get('telephone');
                    $fax             = Input::get('fax');
                    $address_email   = Input::get('address_email');
                    $default_address = Input::get('default_address');

                    $email_rules     = array('email' => 'unique:customers,email,'.$id);
                    $emailValidator  = Validator::make($input,$email_rules);

                    if($emailValidator->fails())
                    {
                        return response()->json(['error'=> true,'message' =>'Email Already Exists'],409);
                    }
                    else
                    {
                      $contact_person  = explode(',',$contact_person);
                      $contact_phone   = explode(',',$contact_phone);
                      $contact_email   = explode(',',$contact_email);
                      $default_contact = explode(',',$default_contact);

                      $address         = explode('|',$address);
                      $country         = explode(',',$country);
                      $telephone       = explode(',',$telephone);
                      $fax             = explode(',',$fax);
                      $address_email   = explode(',',$address_email);
                      $default_address = explode(',',$default_address);

                      Customer::where('id',$id)
                          ->update([
                          'factory_code'   => $input['factory_code'],
                          'company_name'   => $input['company_name'],
                          'courier_choice' => $input['courier_choice'],
                          'account_no'     => $input['account_no'],
                          'tin_number'     => $input['tin_number'],
                          'gstin_number'   => $input['gstin_number'],
                          'pan_number'     => $input['pan_number'],
                          'email'          => $input['email']
                        ]);
                      CustomerContactPersons::where('customer_id', '=', $id)
                            ->delete();
                        $i = 0;
                        foreach($contact_person as $contact)
                        {
                            CustomerContactPersons::create([
                                'customer_id'            => $id,
                                'contact_person_name'    => $contact,
                                'contact_person_email'   => $contact_email[$i],
                                'contact_person_phone'   => $contact_phone[$i],
                                'default_contact_person' => $default_contact[$i]
                            ]);
                            $i++;
                        }
                        CustomerBillAddress::where('customer_id', '=', $id)
                            ->delete();
                        $j = 0;
                        foreach($address as $value)
                        {
                            CustomerBillAddress::create([
                                'customer_id'     => $id,
                                'address'         => $value,
                                'country'         => $country[$j],
                                'email'           => $address_email[$j],
                                'telephone'       => $telephone[$j],
                                'fax'             => $fax[$j],
                                'default_address' => $default_address[$j]
                            ]);
                            $j++;
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
         * function to delete Customer by id.
         * @param customer id
         * @author <Praveen>
         * @return boolean true or false
         */
        public function deleteCustomerById(Request $request)
        {
          try {
                    $id        = $request['id'];
                    $deleted = Customer::where('id', '=', $id)
                                ->delete();
                    CustomerBillAddress::where('customer_id', '=', $id)
                            ->delete();
                    CustomerContactPersons::where('customer_id', '=', $id)
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
    * Show the orchestra customers list view on pageload.
    * @author <Praveen>
    * @return \Illuminate\Http\Response
    */
    public function customers()
    {
          try {
           $module_var = new Modules();
           $is_allowed = $module_var->permittedModules();
           $customers = DB::table('customers')
                         ->select('customers.id','customers.company_name','customers.factory_code','customers.courier_choice','customers.account_no','customers.tin_number','customers.pan_number','customers.email','customer_contact_persons.contact_person_name as contact_person','customer_contact_persons.contact_person_email as contact_email','customer_contact_persons.contact_person_phone as phone_no','customers.gstin_number','customer_bill_address.address','customer_bill_address.country','customer_bill_address.email','customer_bill_address.telephone','customer_bill_address.fax','customers.is_active')
                         ->join('customer_contact_persons','customer_contact_persons.customer_id','customers.id')
                         ->join('customer_bill_address','customer_bill_address.customer_id','customers.id')
                         ->where('customer_bill_address.default_address','=',1)
                         ->where('customer_contact_persons.default_contact_person','=',1)
                         ->get();
           if($is_allowed)
           {
               $get_main_module = $module_var->getMainModules();
               $get_sub_module = $module_var->getSubModules(4);
               View::composer('layouts.Admin.navbar', function($view) use ($get_main_module,$get_sub_module) {
                   return $view->with(['modules' => $get_main_module,'sub_modules' => $get_sub_module]);
               });
               return view('Admin/Customer Management/customers-list')->with(['customers' => $customers]);
           }
           else
           {
                return redirect('/admin/dashboard');
           }
         } catch (\Exception $e)
         {
                return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
         }
    }
}
