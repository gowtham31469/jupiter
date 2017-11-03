<?php
namespace App\Http\Controllers\CustomerAuth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

//Class needed for login and Logout logic
use Illuminate\Foundation\Auth\AuthenticatesUsers;

//Auth facade
use Auth;

class LoginController extends Controller
{
    //Where to redirect seller after login.
    protected $redirectTo = '/customer';

    //Trait
    use AuthenticatesUsers{
        logout as performLogout;
    }

    //Custom guard for seller
    protected function guard()
    {
      return Auth::guard('web_customer');
    }

    //Shows seller login form
   public function showLoginForm()
   {
       return view('customer.auth.login');
   }
   public function logout(Request $request)
   {
       $this->performLogout($request);
       return redirect("/customer");
   }
}
