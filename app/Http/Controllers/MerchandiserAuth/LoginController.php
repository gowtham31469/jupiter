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
   public function logout(Request $request)
   {
       $this->performLogout($request);
       return redirect("/merchandiser");
   } 
   public function dashboard(Request $request)
   {
         $orders = DB::table('orchestra_wash_care_label_orders')->join('orchestra_item_details','orchestra_item_details.UVC','=','orchestra_wash_care_label_orders.uvc')->select('orchestra_wash_care_label_orders.uvc as UVC',DB::raw('"WashCareLabel" as Artwork'),'orchestra_item_details.SupplierName as SupplierName','orchestra_item_details.Collection as Season','orchestra_wash_care_label_orders.created_at as OrderDate','orchestra_wash_care_label_orders.delivery_date as DeliveryDate','orchestra_wash_care_label_orders.status as Status','orchestra_item_details.Collection as Season')->groupBy('orchestra_item_details.UVC')->groupBy('orchestra_wash_care_label_orders.id')->get();
         return view('merchandiser.home',['orders' => $orders]);
   }
}
