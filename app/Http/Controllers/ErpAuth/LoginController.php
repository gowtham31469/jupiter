<?php

namespace App\Http\Controllers\ErpAuth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    protected $redirectTo = '/erp/dashboard';
    //Trait
     use AuthenticatesUsers{
        logout as performLogout;
    }

    //Custom guard for seller
    protected function guard()
    {
      return Auth::guard('web_erp_user');
    }

    //Shows seller login form
   public function showLoginForm()
   {
       return view('erp.auth.login');
   }
     public function logout(Request $request)
   {
       $this->performLogout($request);
       return redirect("/erp");
   }
}
