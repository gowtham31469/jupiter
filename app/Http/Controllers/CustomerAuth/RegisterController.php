<?php

namespace App\Http\Controllers\CustomerAuth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Customer;	
use Auth;

class RegisterController extends Controller
{
    protected $redirectPath = 'customer';

    public function showRegistrationForm()
	{
	      return view('customer.auth.register');
	}
    public function register(Request $request)
    {
       //Validates data
        $this->validator($request->all())->validate();

        $customer = $this->create($request->all());

        //Authenticates seller
        $this->guard()->login($customer);

       //Redirects sellers
        return redirect($this->redirectPath);
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:customers',
            'password' => 'required|min:6|confirmed',
        ]);
    }
    protected function create(array $data)
    {
        return Customer::create([-
            'contact_person' => $data['name'],
            'company_name' => $data['name'],
            'factory_code' => $data['name'],
            'contact_emails' => $data['name'],
            'phone_no' => 1,
            'tin_number' => $data['name'],
            'account_no' => $data['name'],
            'courier_choice' => $data['name'],
            'pan_number' => $data['name'],
            'gstn_number' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    protected function guard()
	{
	    return Auth::guard('web_customer');
	}
}
