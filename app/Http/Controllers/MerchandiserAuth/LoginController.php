<?php

//LoginController.php

namespace App\Http\Controllers\MerchandiserAuth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

//Class needed for login and Logout logic
use Illuminate\Foundation\Auth\AuthenticatesUsers;

//Auth facade
use Auth;

class LoginController extends Controller
{
    protected $redirectTo = '/merchandiser';
    //Trait
    use AuthenticatesUsers{
        logout as performLogout;
    }

    //Custom guard for seller
    protected function guard()
    {
      return Auth::guard('web_merchandiser');
    }

    //Shows seller login form
   public function showLoginForm()
   {
       return view('merchandiser.auth.login');
   }
   public function logout(Request $request)
   {
       $this->performLogout($request);
       return redirect("/merchandiser");
   }
}
