<?php
namespace App\Http\Controllers\ErpUser\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Response;
use View;
use DB;
use Auth;

class OrderController extends Controller
{
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
		$role = "ADMIN";
        $this->middleware('erpuser');
        $this->middleware('role_permission:'.$role);
   
    }

    /**
     * Show the application dashboard.
     * @author [Gba] <[guberakannan@wizroots.com]>
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		//
	}
	
	/**
     * Show the users list.
     * @author [Gba] <[guberakannan@wizroots.com]>
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
		$orders 	= \App\ErpOrders::orders();
		return view('erp.Admin Orders.index', compact('orders'));
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
					
					$im_table 	= \App\Buyer::where('buyer_name', $t[1])
								->value('item_master_table');
					
					$company_id	= DB::table($im_table)->where('id', intval($t[0]))
										->value('company_id');
					
					\App\ErpusersTemplateMapping::create([
						'erp_user'	=>	$data->id,
						'buyer'		=>	$buyer,
						'templates'	=>	intval($t[0]),
						'company'	=>	$company_id
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
				
				$im_table 	= \App\Buyer::where('buyer_name', $t[1])
								->value('item_master_table');
					
				$company_id	= DB::table($im_table)->where('id', intval($t[0]))
										->value('company_id');

				\App\ErpusersTemplateMapping::create([
					'erp_user'	=>	$id,
					'buyer'		=>	$buyer,
					'templates'	=>	intval($t[0]),
					'company'	=>	$company_id
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
        \App\ErpOrders::where('id', $request->id)->delete();
		return redirect('erp/order-management')->with('message', 'Order Cancelled successfully');
    }
	
}