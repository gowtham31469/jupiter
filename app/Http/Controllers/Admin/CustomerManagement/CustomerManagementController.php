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
use App\CustomerBuyers;
use App\PaymentTerms;
use App\PlaceOfSupply;
use App\FreightTerms;
use App\NominatedForwarder;
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
                         ->select('customers.id','customers.company_name','customers.factory_code','customers.courier_choice','customers.tin_number','customers.pan_number','customers.email','customers.gstin_number','customers.is_active')
                         ->orderBy('is_active','desc')
                         ->get();
             foreach ($customers as $value) 
             {
                /** [Checking value null or not] */
                if($value->courier_choice == "")
                {
                  $value->courier_choice   = "-";
                }
                if($value->tin_number == "")
                {
                  $value->tin_number   = "-";
                }
                if($value->pan_number == "")
                {
                  $value->pan_number   = "-";
                }
                if($value->gstin_number == "")
                {
                  $value->gstin_number   = "-";
                }
                $customer_id = $value->id;
                $default_contact = DB::table('customer_contact_persons')
                         ->select('customer_contact_persons.contact_person_name as contact_person','customer_contact_persons.contact_person_email as contact_email','customer_contact_persons.contact_person_phone as phone_no')
                         ->where('customer_contact_persons.customer_id','=',$customer_id)
                         ->where('customer_contact_persons.default_contact_person','=',1)
                         ->first();
                if($default_contact)
                {
                  $value->contact_person = $default_contact->contact_person;
                  $value->contact_email  = $default_contact->contact_email;
                  $value->phone_no       = $default_contact->phone_no;
                }
                else
                {
                  $value->contact_person = "-";
                  $value->contact_email  = "-";
                  $value->phone_no       = "-";
                }
                

                $is_billing_address = DB::table('customer_bill_address')
                         ->select('customer_bill_address.address as bill_address','customer_bill_address.country as bill_country','customer_bill_address.email as bill_email','customer_bill_address.telephone as bill_telephone','customer_bill_address.fax as bill_fax','customer_bill_address.pincode')
                         ->where('customer_bill_address.customer_id','=',$customer_id)
                         ->where('customer_bill_address.is_billing_address','=',1)
                         ->first();
                if($is_billing_address)
                {
                  $value->bill_address   = $is_billing_address->bill_address;
                  $value->bill_country   = $is_billing_address->bill_country;
                  $value->bill_email     = $is_billing_address->bill_email;
                  $value->bill_telephone = $is_billing_address->bill_telephone;
                  $value->bill_fax       = $is_billing_address->bill_fax;
                  $value->pincode        = $is_billing_address->pincode;
                }
                else
                {
                  $value->bill_address   = "-";
                  $value->bill_country   = "-";
                  $value->bill_email     = "-";
                  $value->bill_telephone = "-";
                  $value->bill_fax       = "-";
                  $value->pincode        = "-";
                }
                $is_shipping_address = DB::table('customer_bill_address')
                         ->select('customer_bill_address.address as ship_address','customer_bill_address.country as ship_country','customer_bill_address.email as ship_email','customer_bill_address.telephone as ship_telephone','customer_bill_address.fax as ship_fax','customer_bill_address.pincode')
                         ->where('customer_bill_address.customer_id','=',$customer_id)
                         ->where('customer_bill_address.is_shipping_address','=',1)
                         ->first();
                if($is_shipping_address)
                {
                  $value->ship_address   = $is_shipping_address->ship_address;
                  $value->ship_country   = $is_shipping_address->ship_country;
                  $value->ship_email     = $is_shipping_address->ship_email;
                  $value->ship_telephone = $is_shipping_address->ship_telephone;
                  $value->ship_fax       = $is_shipping_address->ship_fax;
                  $value->pincode       = $is_shipping_address->pincode;
                }
                else
                {
                  $value->ship_address   = "-";
                  $value->ship_country   = "-";
                  $value->ship_email     = "-";
                  $value->ship_telephone = "-";
                  $value->ship_fax       = "-";
                  $value->pincode        = "-";
                }
                
              }            
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
                $factory_code         = $request['factory_code'];
                $company_name         = $request['company_name'];
                $courier_choice       = $request['courier_choice'];
                $password             = Hash::make($request['p']);
                $tin_number           = $request['tin_number'];
                $gstin_number         = $request['gstin_number'];
                $pan_number           = $request['pan_number'];
                $login_email          = $request['login_email'];
                $contact_person       = $request['contact_person'];
                $contact_phone        = $request['contact_phone'];
                $contact_email        = $request['contact_email'];
                $default_contact      = $request['default_contact'];
                $address              = $request['address'];
                $country              = $request['country'];
                $telephone            = $request['telephone'];
                $fax                  = $request['fax'];
                $address_email        = $request['address_email'];
                $billing_address      = $request['billing_address'];
                $shipping_address     = $request['shipping_address'];
                $buyer                = $request['buyer'];
                $pincode              = $request['pincode'];
                $pi_required          = $request['pi_required'];
                $place_of_supply      = $request['place_of_supply'];
                $payment_term         = $request['payment_term'];
                $po_required          = $request['po_required'];
                $delivery_date_req    = $request['delivery_date_req'];
                $forwarder            = $request['forwarder'];
                $freight              = $request['freight'];
                $delivery_day_count   = $request['day_count'];
                $short_name           = $request['short_name'];

                $email_exists = Customer::where('email',$login_email)->select('id')->get();
                if(count($email_exists) > 0)
                {
                  $result = response()->json(['error'=> true,'message' =>'Email Already Exists'],409);
                }
                else
                {
                  $contact_person   = explode(',',$contact_person);
                  $contact_phone    = explode(',',$contact_phone);
                  $contact_email    = explode(',',$contact_email);
                  $default_contact  = explode(',',$default_contact);

                  $address          = explode('|',$address);
                  $country          = explode(',',$country);
                  $telephone        = explode(',',$telephone);
                  $fax              = explode(',',$fax);
                  $address_email    = explode(',',$address_email);
                  $billing_address  = explode(',',$billing_address);
                  $shipping_address = explode(',',$shipping_address);
                  $buyers           = explode(',',$buyer);
                  $pincode          = explode(',',$pincode);

                  $customer = Customer::create([
                                'factory_code'           => $factory_code,
                                'company_name'           => $company_name,
                                'courier_choice'         => $courier_choice,
                                'password'               => $password,
                                'tin_number'             => $tin_number,
                                'gstin_number'           => $gstin_number,
                                'pan_number'             => $pan_number,
                                'email'                  => $login_email,
                                'pi_required'            => $pi_required,
                                'place_of_supply'        => $place_of_supply,
                                'payment_terms'          => $payment_term,
                                'po_required'            => $po_required,
                                'delivery_date_required' => $delivery_date_req,
                                'nominated_forwarder'    => $forwarder,
                                'freight_type'           => $freight,
                                'delivery_day_count'     => $delivery_day_count,
                                'short_name'             => $short_name
                              ]);
                  $insert_Id = $customer->id; 
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
                            'customer_id'            => $insert_Id,
                            'address'                => $value,
                            'country'                => $country[$j],
                            'email'                  => $address_email[$j],
                            'telephone'              => $telephone[$j],
                            'fax'                    => $fax[$j],
                            'is_billing_address'     => $billing_address[$j],
                            'is_shipping_address'    => $shipping_address[$j],
                            'pincode'                => $pincode[$j]
                        ]);
                        $j++;
                    }
                    foreach($buyers as $buyer)
                    {
                        CustomerBuyers::create([
                            'customer_id'     => $insert_Id,
                            'buyer_id'        => $buyer
                        ]);
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
                         ->select('customers.id','customers.company_name','customers.factory_code','customers.courier_choice','customers.tin_number','customers.pan_number','customers.email','customers.gstin_number','customers.password','customers.pi_required','customers.payment_terms','customers.place_of_supply','customers.po_required','customers.delivery_date_required','nominated_forwarder','freight_type','delivery_day_count','short_name')
                         ->where('customers.id','=',$id)
                         ->get();
                /** Checking password already exists or not **/
                if ($customer{0}->password != "" || $customer{0}->password != Null || $customer{0}->password != null || $customer{0}->password != NULL) 
                {
                  $customer{0}->password = 1;
                }
                else
                {
                  $customer{0}->password = 0;
                }
                $buyers = DB::table('customer_buyers')
                         ->select('customer_buyers.buyer_id')
                         ->where('customer_buyers.customer_id','=',$id)
                         ->get();
                $contacts = DB::table('customer_contact_persons')
                         ->select('customer_contact_persons.*')
                         ->where('customer_contact_persons.customer_id','=',$id)
                         ->get();
                $address = DB::table('customer_bill_address')
                         ->select('customer_bill_address.*')
                         ->where('customer_bill_address.customer_id','=',$id)
                         ->get();
                return response()->json(['error'=>'false','message' =>'success','customers' => $customer,'contacts' => $contacts,'address' => $address,'buyers' => $buyers]);
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
                    $id                              = $request['id'];
                    $input['factory_code']           = Input::get('factory_code');
                    $input['company_name']           = Input::get('company_name');
                    $input['courier_choice']         = Input::get('courier_choice');
                    $input['tin_number']             = Input::get('tin_number');
                    $input['gstin_number']           = Input::get('gstin_number');
                    $input['pan_number']             = Input::get('pan_number');
                    $input['email']                  = Input::get('login_email');
                    $input['pi_required']            = Input::get('pi_required');
                    $input['password']               = Hash::make(Input::get('p'));
                    $input['place_of_supply']        = Input::get('place_of_supply');
                    $input['payment_term']           = Input::get('payment_term');
                    $input['po_required']            = Input::get('po_required');
                    $input['delivery_date_required'] = Input::get('delivery_date_req');
                    $input['forwarder']              = Input::get('forwarder');
                    $input['freight']                = Input::get('freight');
                    $input['delivery_day_count']     = Input::get('day_count');
                    $input['short_name']             = Input::get('short_name');

                    $contact_person   = Input::get('contact_person');
                    $contact_phone    = Input::get('contact_phone');
                    $contact_email    = Input::get('contact_email');
                    $default_contact  = Input::get('default_contact');
                    $address          = Input::get('address');
                    $country          = Input::get('country');
                    $telephone        = Input::get('telephone');
                    $fax              = Input::get('fax');
                    $address_email    = Input::get('address_email');
                    $billing_address  = Input::get('billing_address');
                    $shipping_address = Input::get('shipping_address');
                    $buyer            = Input::get('buyer');
                    $pincode          = Input::get('pincode');
                    

                    $email_rules     = array('email' => 'unique:customers,email,'.$id);
                    $emailValidator  = Validator::make($input,$email_rules);

                    if($emailValidator->fails())
                    {
                        return response()->json(['error'=> true,'message' =>'Email Already Exists'],409);
                    }
                    else
                    {
                      $contact_person   = explode(',',$contact_person);
                      $contact_phone    = explode(',',$contact_phone);
                      $contact_email    = explode(',',$contact_email);
                      $default_contact  = explode(',',$default_contact);

                      $address          = explode('|',$address);
                      $country          = explode(',',$country);
                      $telephone        = explode(',',$telephone);
                      $fax              = explode(',',$fax);
                      $address_email    = explode(',',$address_email);
                      $billing_address  = explode(',',$billing_address);
                      $shipping_address = explode(',',$shipping_address);
                      $buyers           = explode(',',$buyer);
                      $pincode          = explode(',',$pincode);

                      Customer::where('id',$id)
                          ->update([
                          'factory_code'           => $input['factory_code'],
                          'company_name'           => $input['company_name'],
                          'courier_choice'         => $input['courier_choice'],
                          'tin_number'             => $input['tin_number'],
                          'gstin_number'           => $input['gstin_number'],
                          'pan_number'             => $input['pan_number'],
                          'email'                  => $input['email'],
                          'password'               => $input['password'],
                          'pi_required'            => $input['pi_required'],
                          'place_of_supply'        => $input['place_of_supply'],
                          'payment_terms'          => $input['payment_term'],
                          'po_required'            => $input['po_required'],
                          'delivery_date_required' => $input['delivery_date_required'],
                          'nominated_forwarder'    => $input['forwarder'],
                          'freight_type'           => $input['freight'],
                          'delivery_day_count'     => $input['delivery_day_count'],
                          'short_name'             => $input['short_name']
                        ]);
                      /** Delete existing data before updating **/
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
                        /** Delete existing data before updating **/
                        CustomerBillAddress::where('customer_id', '=', $id)
                            ->delete();
                        $j = 0;
                        foreach($address as $value)
                        {
                            CustomerBillAddress::create([
                                'customer_id'         => $id,
                                'address'             => $value,
                                'country'             => $country[$j],
                                'email'               => $address_email[$j],
                                'telephone'           => $telephone[$j],
                                'fax'                 => $fax[$j],
                                'is_billing_address'  => $billing_address[$j],
                                'is_shipping_address' => $shipping_address[$j],
                                'pincode'             => $pincode[$j]
                            ]);
                            $j++;
                        }
                        /** Delete existing data before updating **/
                        CustomerBuyers::where('customer_id', '=', $id)
                            ->delete();
                        foreach($buyers as $buyer)
                        {
                            CustomerBuyers::create([
                                'customer_id'     => $id,
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
    * Show the orchestra customers list view on pageload.
    * @author <Praveen>
    * @return \Illuminate\Http\Response
    */
    public function customers()
    {
          try {
           $module_var = new Modules();
           $is_allowed = $module_var->permittedModules();
           $buyers = DB::table('buyers')->where('is_active','=',1)->pluck('buyer_name','id')->all();
           $payment_terms = DB::table('payment_terms')->pluck('payment_terms','id')->all();
           $place_of_supply = DB::table('place_of_supply')->pluck('place_of_supply','id')->all();
           $forwarder = DB::table('nominated_forwarders')->pluck('forwarder','id')->all();
           $freight_terms = DB::table('freight_terms')->pluck('freight_terms','id')->all();
           
           $customers = DB::table('customers')
                         ->select('customers.id','customers.company_name','customers.factory_code','customers.courier_choice','customers.tin_number','customers.pan_number','customers.email','customers.gstin_number','customers.is_active')
                         ->orderBy('is_active','desc')
                         ->get();
             foreach ($customers as $value) 
             {
                /** [Checking value null or not] */
                if($value->courier_choice == "")
                {
                  $value->courier_choice   = "-";
                }
                if($value->tin_number == "")
                {
                  $value->tin_number   = "-";
                }
                if($value->pan_number == "")
                {
                  $value->pan_number   = "-";
                }
                if($value->gstin_number == "")
                {
                  $value->gstin_number   = "-";
                }
                $customer_id = $value->id;
                $default_contact = DB::table('customer_contact_persons')
                         ->select('customer_contact_persons.contact_person_name as contact_person','customer_contact_persons.contact_person_email as contact_email','customer_contact_persons.contact_person_phone as phone_no')
                         ->where('customer_contact_persons.customer_id','=',$customer_id)
                         ->where('customer_contact_persons.default_contact_person','=',1)
                         ->first();
                if($default_contact)
                {
                  $value->contact_person = $default_contact->contact_person;
                  $value->contact_email  = $default_contact->contact_email;
                  $value->phone_no       = $default_contact->phone_no;
                }
                else
                {
                  $value->contact_person = "-";
                  $value->contact_email  = "-";
                  $value->phone_no       = "-";
                }
                

                $is_billing_address = DB::table('customer_bill_address')
                         ->select('customer_bill_address.address as bill_address','customer_bill_address.country as bill_country','customer_bill_address.email as bill_email','customer_bill_address.telephone as bill_telephone','customer_bill_address.fax as bill_fax','customer_bill_address.pincode')
                         ->where('customer_bill_address.customer_id','=',$customer_id)
                         ->where('customer_bill_address.is_billing_address','=',1)
                         ->first();
                if($is_billing_address)
                {
                  $value->bill_address   = $is_billing_address->bill_address;
                  $value->bill_country   = $is_billing_address->bill_country;
                  $value->bill_email     = $is_billing_address->bill_email;
                  $value->bill_telephone = $is_billing_address->bill_telephone;
                  $value->bill_fax       = $is_billing_address->bill_fax;
                  $value->pincode        = $is_billing_address->pincode;
                }
                else
                {
                  $value->bill_address   = "-";
                  $value->pincode        = "-";
                  $value->bill_country   = "-";
                  $value->bill_email     = "-";
                  $value->bill_telephone = "-";
                  $value->bill_fax       = "-";
                }
                $is_shipping_address = DB::table('customer_bill_address')
                         ->select('customer_bill_address.address as ship_address','customer_bill_address.country as ship_country','customer_bill_address.email as ship_email','customer_bill_address.telephone as ship_telephone','customer_bill_address.fax as ship_fax','customer_bill_address.pincode')
                         ->where('customer_bill_address.customer_id','=',$customer_id)
                         ->where('customer_bill_address.is_shipping_address','=',1)
                         ->first();
                if($is_shipping_address)
                {
                  $value->ship_address   = $is_shipping_address->ship_address;
                  $value->ship_country   = $is_shipping_address->ship_country;
                  $value->ship_email     = $is_shipping_address->ship_email;
                  $value->ship_telephone = $is_shipping_address->ship_telephone;
                  $value->ship_fax       = $is_shipping_address->ship_fax;
                  $value->pincode        = $is_shipping_address->pincode;
                }
                else
                {
                  $value->ship_address   = "-";
                  $value->ship_country   = "-";
                  $value->ship_email     = "-";
                  $value->ship_telephone = "-";
                  $value->ship_fax       = "-";
                  $value->pincode        = "-";
                }
                
              }  
           if($is_allowed)
           {
               $get_main_module = $module_var->getMainModules();
               $get_sub_module = $module_var->getSubModules(4);
               View::composer('layouts.Admin.navbar', function($view) use ($get_main_module,$get_sub_module) {
                   return $view->with(['modules' => $get_main_module,'sub_modules' => $get_sub_module]);
               });
               return view('Admin/Customer Management/customers-list')->with(['customers' => $customers,'buyers' => $buyers,'payment_terms' => $payment_terms,'place_of_supply' => $place_of_supply,'freight' => $freight_terms,'forwarder' => $forwarder]);
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
     * Method to load the buyer templates in custumer module
     * @return [type] [description]
     */
    public function custumerPriceList() {
        $module_var = new Modules();
        $is_allowed = $module_var->permittedModules();
        if($is_allowed) {
            $get_main_module = $module_var->getMainModules();
            $get_sub_module = $module_var->getSubModules(4);
            View::composer('layouts.Admin.navbar', function($view) use ($get_main_module,$get_sub_module) {
                return $view->with(['modules' => $get_main_module,'sub_modules' => $get_sub_module]);
            });
            
            $buyers = DB::table('buyers')->select('*')->where('is_active','=',1)->get();
            return view('Admin/Customer Management/buyer-selection',['buyers' => $buyers]);
        } else {
             return redirect('/admin/dashboard');
        }
    }

    /**
     * Method to get all the customers for a specific buyer
     * @param  Request $request buyer id 
     * @return json list of all customers
     */
    public function getBuyersCustomers(Request $request) {
      $customer_list = DB::table('customers')
                      ->join('customer_buyers', 'customer_buyers.customer_id', 'customers.id' )
                      ->where('customer_buyers.buyer_id',$request->buyer_id)
                      ->select('customers.company_name', 'customers.id')
                      ->get();
      return response()->json(['customer_list' => $customer_list]);

    }
    /**
     * Method to save price and tax details of a customer per template
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function savePriceTax(Request $request) {

         $validator = Validator::make($request->all(), [
            'customer_id' => 'bail|required',
            'template_id' => 'bail|required',
            'custom_name' => 'bail|required',
            'price'       => 'bail|required|numeric',
            'tax'         => 'bail|required|numeric'
        ]);

        if ($validator->fails()) {
             return response()->json(['error' => true,'error' => $validator], 409);
        }
        $isExists = DB::table('price_taxation')->where([
            'customer_id' => $request->customer_id,
            'buyer_id'    => $request->buyer_id,
            'template_id' => $request->template_id])->pluck('id');
        if($isExists->count()) {
          DB::table('price_taxation')
              ->where('id',$isExists[0])
              ->update([
                  'custom_name' => $request->custom_name,
                  'price'       => $request->price,
                  'tax'         => $request->tax
                ]) ;
        } else {
            DB::table('price_taxation')->insert([
            'customer_id' => $request->customer_id,
            'buyer_id'    => $request->buyer_id,
            'template_id' => $request->template_id,
            'custom_name' => $request->custom_name,
            'price'       => $request->price,
            'tax'         => $request->tax
          ]);
        }

        return response()->json(['message' => 'success'], 200);

    }

    /**
     * Method to get price and tax details of a customer per template
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getPriceTax(Request $request) {

        $price_list = DB::table('price_taxation')->where([
                          'customer_id' => $request->customer_id,
                          'buyer_id'    => $request->buyer_id,
                          'template_id' => $request->template_id
                        ])->get();
        if($price_list->count()) {
          return response()->json(['price_list' => $price_list], 200);
        } else {
          return response()->json(['price_list' => 'empty'], 204);
        }
        

    }

    public function templateView(Request $request) {
          $treeviewData= DB::table("tesco_template")
                                ->select('tesco_template.*','tesco_item_master.ref_no','company.name as company_name')
                                ->join('tesco_item_master','tesco_item_master.id','=','tesco_template.item_id')
                                ->join('company','company.id','=','tesco_item_master.company_id')
                                ->where('item_id',$request['template_id'])
                                ->get();
         
         return json_encode($treeviewData);
    }
    /**
     * Get the customer details by identifier(View more functionality).
     * @param customer id
     * @return The customer by identifier.
     * @author <Praveen>
     */
    public function getCustomerDetailsById(Request $request)
    {
      try {
            $id = $request['id'];
            $customer = DB::table('customers')
                     ->select('customers.id','customers.company_name','customers.factory_code','customers.courier_choice','customers.tin_number','customers.pan_number','customers.email','customers.gstin_number','customers.password','customers.pi_required','customers.po_required','customers.delivery_date_required','nominated_forwarders.forwarder','freight_terms.freight_terms','delivery_day_count','short_name','payment_terms.payment_terms','place_of_supply.place_of_supply')
                     ->leftjoin('payment_terms','customers.payment_terms','payment_terms.id')
                     ->leftjoin('place_of_supply','customers.place_of_supply','place_of_supply.id')
                     ->leftjoin('freight_terms','customers.freight_type','freight_terms.id')
                     ->leftjoin('nominated_forwarders','customers.nominated_forwarder','nominated_forwarders.id')
                     ->where('customers.id','=',$id)
                     ->get();
            
              $buyers = DB::table('customer_buyers')
                     ->select('buyers.buyer_name')
                     ->leftjoin('customers','customer_buyers.customer_id','customers.id')
                     ->leftjoin('buyers','buyers.id','customer_buyers.buyer_id')
                     ->where('customer_buyers.customer_id','=',$id)
                     ->get();
            
            return response()->json(['error'=>'false','message' =>'success','customers' => $customer,'buyers' => $buyers]);
          } catch (\Exception $e)
          {
            return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
          }
    }

}
