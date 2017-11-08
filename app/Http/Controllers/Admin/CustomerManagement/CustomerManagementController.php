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
    public function customers()
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
     * Add Customers.
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
       * @author <Praveen>
       * @return \Illuminate\Http\Response
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
       * Make customer active
       * @author <Praveen>
       * @return \Illuminate\Http\Response
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
}
