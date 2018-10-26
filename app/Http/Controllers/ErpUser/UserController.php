<?php
namespace App\Http\Controllers\ErpUser;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Response;
use View;
use DB;
use App\Erpuser;
use App\ErpuserBuyerMapping;
use App\ErpuserCompanyMapping;
use App\Buyer;

class UserController extends Controller
{
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
		$roles = "ADMIN";
        $this->middleware('erpuser');
        $this->middleware('role_permission:'.$roles)->except('index');
   
    }

    /**
     * Show the application dashboard.
     * @author [Gba] <[guberakannan@wizroots.com]>
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		return view('erp.home');
	}
	
	/**
     * Show the users list.
     * @author [Gba] <[guberakannan@wizroots.com]>
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
		$users 		= \App\Erpuser::users();
		$roles 		= \App\ErpRoles::pluck('name', 'id')->all();
		$company 	= DB::table('company')->pluck('name', 'id')->all();
		$buyers 	= \App\Buyer::pluck('buyer_name as name', 'id')->all();
		return view('erp.users.user-list', ['users' => $users, 'company' => $company, 'buyers' => $buyers, 'roles' => $roles]);
	}
	/**
     * Store the users.
     * @author [Gba] <[guberakannan@wizroots.com]>
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		$input['email']	= $request->email;
		$email_rules    = array('email' => 'unique:erpusers,email');
		$emailValidator = Validator::make($input,$email_rules);

		if($emailValidator->fails())
		{
			return response()->json(['error'=> true,'message' =>'Email Already Exists'], 409);
		}
		else
		{
			$data = new Erpuser;
			
			$data->name = $request->name;
			$data->email = $request->email;
			$data->password = bcrypt(random_bytes(8));
			/*   Insert and get id of ErpUser   */
			if($data->save()) {
				/*   ErpUser - Buyer Mapping  */
				foreach($request->buyer as $buyer){
					ErpuserBuyerMapping::create([
						'erp_user'	=>	$data->id,
						'buyer'	=>	$buyer
					]);
				}
				/*   ErpUser - Company Mapping  */
				foreach($request->company as $company){
					ErpuserCompanyMapping::create([
						'erp_user'	=>	$data->id,
						'company'	=>	$company
					]);
				}
				/*   ErpUser - Template Mapping  */
				foreach($request->templates as $template){
					$t		= explode("_", $template);
					$buyer 	= \App\Buyer::where('buyer_name', $t[1])
								->value('id');
					
					\App\ErpusersTemplateMapping::create([
						'erp_user'	=>	$data->id,
						'buyer'		=>	$buyer,
						'templates'	=>	intval($t[0])
					]);
				}
				/*   ErpUser - Roles Mapping  */
				foreach($request->roles as $role){
					
					\App\ErpUserRolesMapping::create([
						'erp_user'	=>	$data->id,
						'role'		=>	$role
					]);
				}
				return Response::json(array('error' => false, 'message' => 'User Details Added'), 200);
			}
			
			return Response::json(array('error'=> true, 'message' =>'Something Went wrong'), 500);
		}
	}
	/**
     * Update the user.
     * @author [Gba] <[guberakannan@wizroots.com]>
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
		$input['email']	= $request->email;
		$id				= $request->id;
		$email_rules    = array('email' => 'unique:erpusers,email,'.$id);
		$emailValidator = Validator::make($input,$email_rules);

		if($emailValidator->fails())
		{
			return response()->json(['error'=> true, 'message' =>'Email Already Exists'], 409);
		}
		else
		{
			/*   Update ErpUser   */
			\App\Erpuser::where('id', $id)->update([
				'name'	=>	$request->name,
				'email'	=>	$request->email,
			]);
			/*   ErpUser - Buyer Mapping  */
			\App\ErpuserBuyerMapping::where('erp_user', $id)->delete();
			foreach($request->buyer as $buyer){
				\App\ErpuserBuyerMapping::create([
					'erp_user'	=>	$id,
					'buyer'	=>	$buyer
				]);
			}
			/*   ErpUser - Company Mapping  */
			\App\ErpuserCompanyMapping::where('erp_user', $id)->delete();
			foreach($request->company as $company){
				\App\ErpuserCompanyMapping::create([
					'erp_user'	=>	$id,
					'company'	=>	$company
				]);
			}
			/*   ErpUser - Template Mapping  */
			\App\ErpusersTemplateMapping::where('erp_user', $id)->delete();
			foreach($request->templates as $template){
				$t		= explode("_", $template);
				$buyer 	= \App\Buyer::where('buyer_name', $t[1])
							->value('id');

				\App\ErpusersTemplateMapping::create([
					'erp_user'	=>	$id,
					'buyer'		=>	$buyer,
					'templates'	=>	intval($t[0])
				]);
			}
			\App\ErpUserRolesMapping::where('erp_user', $id)->delete();
			/*   ErpUser - Roles Mapping  */
			foreach($request->roles as $role){

				\App\ErpUserRolesMapping::create([
					'erp_user'	=>	$id,
					'role'		=>	$role
				]);
			}
			return Response::json(array('error' => false, 'message' => 'User Details Updated'), 200);
		}
	}
	/**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        \App\Erpuser::where('id', $request->id)->delete();
		return redirect('erp/users')->with('message', 'User Deleted successfully');
    }
	/**
     * Get the specified company template hierarchy.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getTemplate(Request $request)
    {
		return $this->getTemplateDetail($request->buyer); 
    }
	/**
     * Common Function to fetch template hierarchy.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getTemplateDetail($buyer)
    {
		switch ($buyer){
			case "Tesco":
				$treeviewData= DB::table("tesco_item_master")->select('id','item_name as text','parent_id')->whereIn('parent_id', [0,1])->get()->toArray();
				break;
				
			case "Orchestra":
				$treeviewData= DB::table("orchestra_item_master")->select('id','item_name as text','parent_id')->whereIn('parent_id', [0,1])->get()->toArray();
				break;
				
			default:
				$treeviewData= DB::table("tesco_item_master")->select('id','item_name as text','parent_id')->whereIn('parent_id', [0,1])->get()->toArray();
				break;
		}
        
        if($treeviewData){
			$childs = array();
			foreach($treeviewData as &$item) 
			{
			$childs[$item->parent_id][] = &$item;
			unset($item);
			}
			foreach($treeviewData as &$item) 
			{
				if (isset($childs[$item->id]))
				{
					$item->children = $childs[$item->id];
					unset($item);
				}
			}
			$tree = $childs[0];
			$treefinalData=json_encode($tree);
			return $treefinalData;
       } 
    }
	/**
     * Get the specified user details.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getUser(Request $request)
    {
		$user = \App\Erpuser::user($request->id);
		$templates 	= array();
		$t_name		= array();
		foreach($user[0]->buyer as $buyer){
			
			$b = \App\Buyer::where('id', $buyer->id)->first();
			if($b){
				array_push($templates, $this->getTemplateDetail($b->buyer_name));
				array_push($t_name, $b->buyer_name);
			}
			
			$user[0]->templates = array_combine($t_name, $templates);
		}
		
		return $user;
    }

}