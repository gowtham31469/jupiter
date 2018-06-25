<?php

//LoginController.php

namespace App\Http\Controllers\MerchandiserAuth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

//Class needed for login and Logout logic
use Illuminate\Foundation\Auth\AuthenticatesUsers;

//Auth facade
use Auth;
use View;
use DB;

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
   /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }
    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);
        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the user making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }
        // Customization: Validate if user status is active (1)
        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }
        // Customization: Validate if user status is active (1)
        $email = $request->get($this->username());
        // Customization: It's assumed that email field should be an unique field 
        $user = CustomerProfile::where($this->username(), $email)->first();
        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);
        // Customization: If user status is inactive (0) return failed_status error.
        if($user)
        {
            if ($user->status === 0) 
            {
                return $this->sendFailedLoginResponse($request, 'Your account has not been activated yet.');
            }
        }
        else
        {
            return $this->sendFailedLoginResponse($request, 'These credentials do not match our records.');
        }
        return $this->sendFailedLoginResponse($request);
    }
    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        $credentials = $request->only($this->username(), 'password');
        // Customization: validate if user status is active (1)
        $credentials['status'] = 1;
        return $credentials;
    }
    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $field
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendFailedLoginResponse(Request $request, $trans = 'auth.failed')
    {
        $errors = [$this->username() => trans($trans)];
        if ($request->expectsJson()) {
            return response()->json($errors, 422);
        }
        return redirect()->back()
            ->withInput($request->only($this->username(), 'remember'))
            ->withErrors($errors);
    }
   public function logout(Request $request)
   {
       $this->performLogout($request);
       return redirect("/merchandiser");
   } 
}
