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
use App\CustomerMetadata;
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
    * Show the customers list view on ajax.
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
            $already_linked_customers = CustomerMetadata::pluck('customer_metadata_id')->all();
            $tesco_customers_dropdown = DB::table('tesco_suppliers')
                               ->where('is_enabled','=',1)
                               ->whereNotIn('tesco_suppliers.id',$already_linked_customers)
                               ->pluck('supplier_name','id')
                               ->all();
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
                  $value->bill_pincode   = $is_billing_address->pincode;
                }
                else
                {
                  $value->bill_address   = "-";
                  $value->bill_country   = "-";
                  $value->bill_email     = "-";
                  $value->bill_telephone = "-";
                  $value->bill_fax       = "-";
                  $value->bill_pincode   = "-";
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
                  $value->ship_pincode   = $is_shipping_address->pincode;
                }
                else
                {
                  $value->ship_address   = "-";
                  $value->ship_country   = "-";
                  $value->ship_email     = "-";
                  $value->ship_telephone = "-";
                  $value->ship_fax       = "-";
                  $value->ship_pincode   = "-";
                }
                
              }            
            return response()->json(['error'=>'false','message' =>'success', 'customers' => $customers,'tesco_customers_dropdown' => $tesco_customers_dropdown]);
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
                $customers            = $request['customers'];
                $sap_code             = $request['sap_code'];

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
                  $customers        = explode(',',$customers);

                  $customer = Customer::create([
                                'factory_code'           => $factory_code,
                                'company_name'           => $company_name,
                                'courier_choice'         => $courier_choice,
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
                                'short_name'             => $short_name,
                                'sap_code'               => $sap_code
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
                    foreach($customers as $customer)
                    {
                        CustomerMetadata::create([
                            'customer_id'           => $insert_Id,
                            'customer_metadata_id'  => $customer
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
              
              /** Check if this company linked with customer **/
              $custumer_id = DB::table('customer_profile')->select('id')->where('company_id','=',$id)->where('status','=',1)->first();
              if($custumer_id)
              {
                $result = response()->json(['error'=> false,'message' =>'Some customers linked to this company.'],208);
              }
              else
              {
                Customer::where('id',$id)
                 ->update([
                   'is_active'  => 0
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
        public function getCustomerById(Request $request)
        {
          try {
                $id = $request['id'];
                $customer = DB::table('customers')
                         ->select('customers.id','customers.company_name','customers.factory_code','customers.courier_choice','customers.tin_number','customers.pan_number','customers.email','customers.gstin_number','customers.pi_required','customers.payment_terms','customers.place_of_supply','customers.po_required','customers.delivery_date_required','nominated_forwarder','freight_type','delivery_day_count','short_name','customers.sap_code')
                         ->where('customers.id','=',$id)
                         ->get();
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
                $customer_metadata = DB::table('customer_metadata')
                         ->where('customer_metadata.customer_id','=',$id)
                         ->pluck('customer_metadata.customer_metadata_id')
                         ->all();
                /** [ Fetch already linked customers where id's not in customer_metadata arrays.] */
                $already_linked_customers = CustomerMetadata::whereNotIn('customer_metadata_id',$customer_metadata)->pluck('customer_metadata_id')->all();

                /**  [Fetch all tesco_suppliers from metadata where id's not in already_linked_customers array] */
                $tesco_customers = DB::table('tesco_suppliers')
                                   ->where('is_enabled','=',1)
                                   ->whereNotIn('tesco_suppliers.id',$already_linked_customers)
                                   ->pluck('supplier_name','id')
                                   ->all();
                
                return response()->json(['error'=>'false','message' =>'success','customers' => $customer,'contacts' => $contacts,'address' => $address,'buyers' => $buyers, 'customer_metadata' => $customer_metadata,'tesco_customers' => $tesco_customers]);
              } catch (\Exception $e)
              {
                return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
              }
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
                    $input['place_of_supply']        = Input::get('place_of_supply');
                    $input['payment_term']           = Input::get('payment_term');
                    $input['po_required']            = Input::get('po_required');
                    $input['delivery_date_required'] = Input::get('delivery_date_req');
                    $input['forwarder']              = Input::get('forwarder');
                    $input['freight']                = Input::get('freight');
                    $input['delivery_day_count']     = Input::get('day_count');
                    $input['short_name']             = Input::get('short_name');
                    $input['sap_code']               = Input::get('sap_code');

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
                    $customers        = Input::get('customers');
                    

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
                      $customers        = explode(',',$customers);

                      Customer::where('id',$id)
                            ->update([
                            'factory_code'           => $input['factory_code'],
                            'company_name'           => $input['company_name'],
                            'courier_choice'         => $input['courier_choice'],
                            'tin_number'             => $input['tin_number'],
                            'gstin_number'           => $input['gstin_number'],
                            'pan_number'             => $input['pan_number'],
                            'email'                  => $input['email'],
                            'pi_required'            => $input['pi_required'],
                            'place_of_supply'        => $input['place_of_supply'],
                            'payment_terms'          => $input['payment_term'],
                            'po_required'            => $input['po_required'],
                            'delivery_date_required' => $input['delivery_date_required'],
                            'nominated_forwarder'    => $input['forwarder'],
                            'freight_type'           => $input['freight'],
                            'delivery_day_count'     => $input['delivery_day_count'],
                            'short_name'             => $input['short_name'],
                            'sap_code'               => $input['sap_code']
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
                        /** Delete existing data before updating **/
                        CustomerMetadata::where('customer_id', '=', $id)
                            ->delete();
                        foreach($customers as $customer)
                        {
                            CustomerMetadata::create([
                                'customer_id'          => $id,
                                'customer_metadata_id' => $customer
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
    * Show the customers list view on pageload.
    * @author <Praveen>
    * @return \Illuminate\Http\Response
    */
    public function customers()
    {
          try {
                 $module_var      = new Modules();
                 $is_allowed      = $module_var->permittedModules();
                 $buyers          = DB::table('buyers')->where('is_active','=',1)->pluck('buyer_name','id')->all();
                 $payment_terms   = DB::table('payment_terms')->pluck('payment_terms','id')->all();
                 $place_of_supply = DB::table('place_of_supply')->pluck('place_of_supply','id')->all();
                 $forwarder       = DB::table('nominated_forwarders')->pluck('forwarder','id')->all();
                 $freight_terms   = DB::table('freight_terms')->pluck('freight_terms','id')->all();
                 $already_linked_customers = CustomerMetadata::pluck('customer_metadata_id')->all();
                 $tesco_customers = DB::table('tesco_suppliers')
                                    ->where('is_enabled','=',1)
                                    ->whereNotIn('tesco_suppliers.id',$already_linked_customers)
                                    ->pluck('supplier_name','id')
                                    ->all();
                 
                 $customers       = DB::table('customers')
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
                        $value->bill_pincode   = $is_billing_address->pincode;
                      }
                      else
                      {
                        $value->bill_address   = "-";
                        $value->bill_pincode   = "-";
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
                        $value->ship_pincode   = $is_shipping_address->pincode;
                      }
                      else
                      {
                        $value->ship_address   = "-";
                        $value->ship_country   = "-";
                        $value->ship_email     = "-";
                        $value->ship_telephone = "-";
                        $value->ship_fax       = "-";
                        $value->ship_pincode   = "-";
                      }
                      
                    }  
                 if($is_allowed)
                 {
                     $get_main_module = $module_var->getMainModules();
                     $get_sub_module = $module_var->getSubModules(4);
                     View::composer('layouts.Admin.navbar', function($view) use ($get_main_module,$get_sub_module) {
                         return $view->with(['modules' => $get_main_module,'sub_modules' => $get_sub_module]);
                     });
                     return view('Admin/Customer Management/customers-list')->with(['customers' => $customers, 'buyers' => $buyers, 'payment_terms' => $payment_terms, 'place_of_supply' => $place_of_supply, 'freight' => $freight_terms, 'forwarder' => $forwarder, 'tesco_customers' => $tesco_customers]);
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
    * Show the company buyers list.
    * @author <Gowtham>
    * @return \Illuminate\Http\Response
    */
	public function customerCompanyBuyer(Request $request)
	{
		 try {
                $customer_list = DB::table('customer_buyers')
					            ->join('buyers', 'buyers.id', 'customer_buyers.buyer_id')
                      ->where('customer_buyers.customer_id',$request->customer_add_company)
                      ->select('customer_buyers.*','buyers.buyer_name')
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
	
	/**
    * Show the customers list view on pageload.
    * @author <Praveen>
    * @return \Illuminate\Http\Response
    */
    public function customerProfile()
    {
          try {
                 $module_var      = new Modules();
                 $is_allowed      = $module_var->permittedModules();
                 $buyers          = DB::table('buyers')->where('is_active','=',1)->pluck('buyer_name','id')->all();
			  
			           $roles = DB::table('roles')->pluck('role_name','id')->all();
                
                 $company = DB::table('customers')
                                    ->where('is_active','=',1)
                                    ->pluck('company_name','id')
                                    ->all();
                 
                 $customers       =  DB::table('customer_profile')
					 				 ->join('roles', 'roles.id', 'customer_profile.role_id')
					 				 ->join('customers', 'customers.id', 'customer_profile.company_id')
                                     ->select('customer_profile.*','roles.role_name','customers.company_name')
					 				->orderBy('customer_profile.status', 'asc')
                                     ->get();  
                 if($is_allowed)
                 {
                     $get_main_module = $module_var->getMainModules();
                     $get_sub_module = $module_var->getSubModules(4);
                     View::composer('layouts.Admin.navbar', function($view) use ($get_main_module,$get_sub_module) {
                         return $view->with(['modules' => $get_main_module,'sub_modules' => $get_sub_module]);
                     });
                     return view('Admin/Customer Management/company')->with(['company' => $company, 'buyers' => $buyers,'customers' => $customers,'roles' => $roles]);
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
            $buyer_count = count($buyers);
            return view('Admin/Customer Management/buyer-selection',['buyers' => $buyers,'buyer_count' => $buyer_count]);
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
            'price'       => 'bail|required'
        ]);

        if ($validator->fails()) {
             return response()->json(['error' => true,'error' => $validator], 409);
        }
        parse_str($request->price, $pricearray);        
        $isExists = DB::table('price_taxation')->where([
            'customer_id' => $request->customer_id,
            'buyer_id'    => $request->buyer_id,
            'template_id' => $request->template_id])->pluck('id');
        $price_taxation_id = '';
        if($isExists->count()) {
          $price_taxation_id = $isExists[0];
          DB::table('price_taxation')
              ->where('id', $price_taxation_id)
              ->update([
                  'custom_name' => $request->custom_name,
                  'moq' => $request->moq
                ]) ;
        } else {
            $price_taxation_id = DB::table('price_taxation')->insertGetId([
            'customer_id' => $request->customer_id,
            'buyer_id'    => $request->buyer_id,
            'template_id' => $request->template_id,
            'custom_name' => $request->custom_name,
            'moq'         => $request->moq
          ]);
        }

        DB::table('price_range')->where('price_taxation_id', '=', $price_taxation_id)->delete();
        $count = count($pricearray);
        for ($i=1; $i <= ($count/3); $i++) { 
          DB::table('price_range')->insert([
            'price_taxation_id' => $price_taxation_id,
            'volume_from'    => $pricearray['volume_from_'.$i],
            'volume_to' => $pricearray['volume_to_'.$i],
            'price' => $pricearray['price_'.$i]
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

        $price_list = DB::table('price_taxation')
                      ->join('price_range', 'price_range.price_taxation_id','price_taxation.id')
                      ->where([
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
                     ->select('customers.id','customers.company_name','customers.factory_code','customers.courier_choice','customers.tin_number','customers.pan_number','customers.email','customers.gstin_number','customers.pi_required','customers.po_required','customers.delivery_date_required','nominated_forwarders.forwarder','freight_terms.freight_terms','delivery_day_count','short_name','payment_terms.payment_terms','place_of_supply.place_of_supply')
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
