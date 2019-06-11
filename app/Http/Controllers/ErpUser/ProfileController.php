<?php
namespace App\Http\Controllers\ErpUser;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Response;
use View;
use DB;
use Auth;
use App\Erpuser;
use App\ErpuserBuyerMapping;
use App\ErpuserCompanyMapping;
use App\Buyer;

class ProfileController extends Controller
{
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('erpuser');
	}
/**
     * Get the loggedin user profile.
     *
     * @param  NULL
     * @return \Illuminate\Http\Response
     */
    public function getProfile(Request $request)
    {
		$base = Auth::guard('erpuser')->user()->id;
		$app = app();
    	$profile = $app->make('stdClass');
		if(! Auth::guard('erpuser')->user()->is_admin){
		
		$profile->company = \App\ErpuserCompanyMapping::where('erp_user', $base)
								->join('company', 'erpuser_company_mapping.company', 'company.id')
								->pluck('company.name')->all();
			
		$profile->buyer = \App\ErpuserBuyerMapping::where('erp_user', $base)
							->join('buyers', 'erpuser_buyer.buyer', 'buyers.id')
							->pluck('buyers.buyer_name')
							->all();

		$profile->roles = \App\ErpUserRolesMapping::where('erp_user', $base)
							->join('erp_roles', 'erpusers_roles_mapping.role', 'erp_roles.id')
							->pluck('erp_roles.name')
							->all();

		$template 		= \App\ErpusersTemplateMapping::where('erp_user', $base)
							->select('erpusers_template_mapping.templates', 'erpusers_template_mapping.buyer')
							->get();
		
		$temp = array();
		
		foreach($template as $t){
			$im_table 		= \App\Buyer::where('id', $t->buyer)
								->first();
					
			$template_name	= DB::table($im_table->item_master_table)
								->where('id', $t->templates)
								->value('item_name');
			
			if(!in_array($im_table->buyer_name."-".$template_name, $temp)){
				array_push($temp, $im_table->buyer_name."-".$template_name);
			}
			
		}
		
		$profile->template	= $temp;
			
		}else{
			$profile->company	= array('Admin can access all Companies');
			$profile->buyer		= array('Admin can access all Buyes');
			$profile->roles		= array('You are Admin ! You have access to all modules');
			$profile->template	= array('Admin can access all Templates');
		}
		if($request->ajax()){
			return response()->json(['profile' => $profile], 200);
		}
		return $profile;
    }
	/**
     * Update the loggedin user profile.
     *
     * @param  $name, $email
     * @return \Illuminate\Http\Response
     */
    public function putProfile(Request $request)
    {
		$id		= Auth::guard('erpuser')->user()->id;
		
		if($request->has(['name', 'email'])){
			$name	= $request->name;
			$email	= $request->email;
			$input['email']	= $request->email;
			
			$messages = [
				'email.email'    => 'Enter Valid Email',
				'email.unique'    => 'Email Already Exists'
			];
			
			$email_rules    = array('email' => 'email|unique:erpusers,email,'.$id);
			$emailValidator = Validator::make($input, $email_rules, $messages);

			if($emailValidator->fails())
			{
				return response()->json(['message' => $emailValidator->errors()], 409);
			}
			else
			{
				/*   Update ErpUser   */
				\App\Erpuser::where('id', $id)->update([
					'name'	=>	$request->name,
					'email'	=>	$request->email,
				]);
				
				return response()->json(['message' =>'Updated Successfully'], 200);
			}	
		}else{
			return response()->json(['message' =>'Parameter Missing'], 400);
		}
    }
	/**
     * Update the password of loggedin user.
     *
     * @param  $name, $email
     * @return \Illuminate\Http\Response
     */
    public function putPassword(Request $request)
    {
		$user	= Auth::guard('erpuser')->user();
		
		$input['old_pwd']	= $request->old_pwd;
		$input['new_pwd']	= $request->new_pwd;
		$input['new_pwd_confirmation']	= $request->new_pwd_confirmation;
		$value	=	$input['old_pwd'];

		$messages = [
			'old_pwd.required'    => 'Old Password is required',
			'new_pwd.required'    => 'New Password is required',
			'new_pwd.confirmed'   => 'New Password and confirm password doesnot match',
		];

		$validator = Validator::make($input, [
			'new_pwd' => 'required|confirmed',
			'old_pwd' => ['required', function ($attribute, $value, $fail) use ($user) {
				if (!\Hash::check($value, $user->password)) {
					return $fail(__('The Old password is incorrect.'));
				}
			}],
		], $messages);

		if ($validator->fails()) {
			return response()->json(['message' =>$validator->errors()], 400);
		} else {
			if($request->old_pwd === $request->new_pwd){
				$err_array = array(array('Current Password and New Password Can\'t be same'));
				return response()->json(['message' => $err_array], 400);
			} else {
				/*   Update Password   */
				\App\Erpuser::where('id', $user->id)->update([
					'password'	=>	\Hash::make($request->new_pwd)
				]);

				return response()->json(['message' =>'Updated Successfully'], 200);
			}
		}	
    }
}