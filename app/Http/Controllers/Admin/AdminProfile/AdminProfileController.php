<?php

namespace App\Http\Controllers\Admin\AdminProfile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classes\Modules;
use Response;
use View;
use App\User;
use App\UserRole;
use App\RolePermissionMdl;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use DB;
use Hash;

/**
 * Admin Profile controller for admin profile related functionality.
 * @author <Praveen>
 */
class AdminProfileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       $this->middleware('auth');
    }
    /**
     * Get admin details by ID for editing.
     * @param admin id
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function getAdminDetailsById(Request $request)
    {
      try {
            $id = $request['id'];
            $admin = DB::table('users')->select('id','name','address','email','phone')->where('users.id',$id)->get();
            return response()->json(['error'=>'false','message' =>'success', 'admin' => $admin]);
          } catch (\Exception $e)
          {
            return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
          }
    }
}
