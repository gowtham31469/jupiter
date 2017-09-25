<?php

namespace App\Http\Controllers\MerchandiserAuth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Merchandiser;	
use Auth;

class RegisterController extends Controller
{
    protected $redirectPath = 'merchandiser';

    public function showRegistrationForm()
	{
	      return view('merchandiser.auth.register');
	}
    public function register(Request $request)
    {
       //Validates data
        $this->validator($request->all())->validate();

        $merchandiser = $this->create($request->all());

        //Authenticates seller
        $this->guard()->login($merchandiser);

       //Redirects sellers
        return redirect($this->redirectPath);
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email' => 'required|email|max:255|unique:merchandisers',
            'password' => 'required|min:6|confirmed',
        ]);
    }
    protected function create(array $data)
    {
        return Merchandiser::create([
           'email' => $data['email'],
           'password' => bcrypt($data['password']),
        ]);
    }

    protected function guard()
	{
	    return Auth::guard('web_merchandiser');
	}
}
