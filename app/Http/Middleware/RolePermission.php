<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\ErpUserRolesMapping;
class RolePermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
		if(Auth::guard('erpuser')->user()->is_admin)
			return $next($request);
		else
			return ($this->checkPermissions($next, $role)) ? $next($request) : redirect('/erp');
    }
	
	
	public function checkPermissions($next,$role){
		
		
		$u = ErpUserRolesMapping::where('erp_user', Auth::guard('erpuser')->user()->id)
				->join('erp_roles', 'erpusers_roles_mapping.role', 'erp_roles.id')
				->select('erp_roles.name')
				->get()->toArray();
		
		if(in_array( $role, array_pluck($u, 'name')))
			return true;
		else
			return false;
		
	}
}
