<?php
namespace App\Classes;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Response;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use DB;
use Auth;
use App\RolePermissionMdl;

class Modules{
/**
     * Function to get sub modules
     *
     * @param parent_id
     * @return sub modules
     */
    private $employee_role_id;

    public function getSubModules($parent_id)
	{
        $this->user_role_id = Auth::guard("web")->user()->user_role_id;
		return RolePermissionMdl::where('role_permission.user_role_id',$this->user_role_id)
                    ->join('module','module.id','=','role_permission.module_id')
                    ->where('module.parent_id',$parent_id)
                    ->where('permission',1)->select('module.*')->get();
//        return $sub_modules;
	}
/**
     * Function to get main modules
     *
     *
     * @return Main modules
     */
    public function getMainModules()
    {
        $this->user_role_id = Auth::guard('web')->user()->user_role_id;
        $modules = RolePermissionMdl::where('role_permission.user_role_id',$this->user_role_id)
                    ->join('module','module.id','=','role_permission.module_id')
                    ->where('permission',1)->select('role_permission.module_id','module.*')
                    ->orderBy('module.id')
                    ->get();
        return $modules;
    }
    /**
     * Function to get default sub modules
     *
     *
     * @return Main modules
     */
    public function subModules($parent_id)
	{
		return DB::table('module')->where('module.parent_id',$parent_id)
                            ->select('module.*')->get();
//        return $sub_modules;
	}
    /**
     * Function to get default main modules
     *
     *
     * @return Main modules
     */
    public function mainModules()
    {
        return DB::table('module')->where('module.parent_id',0)
                    ->select('module.*')->get();
//        return $modules;
    }
    /**
     * Function to get role permissions for modules
     *
     *
     * @return Main modules
     */
    public function permittedModules()
    {
        $modules_array = array();
        $this->user_role_id = Auth::guard('web')->user()->user_role_id;
        $modules = RolePermissionMdl::where('role_permission.user_role_id',$this->user_role_id)
                    ->join('module','module.id','=','role_permission.module_id')
                    ->where('permission',1)->select('module.url_path')->get();
        foreach($modules as $modules_per)
        {
            array_push($modules_array,$modules_per->url_path);
        }
        $url = $_SERVER['REQUEST_URI'];
        if(in_array($url,$modules_array)){
            return true;
        }else{
            return false;
        }
    }
    /**
     * Function to get top module
     *
     * @return Top Module
     */
    public function getTopParent($url)
	{
        $loop='';
		$first_level= DB::table("module")->where("url_path", $url)->first();
        if ($first_level->parent_id == 0){
            return $first_level->module_name;
        }else{
            $first_level1= DB::table("module")->where("id", $first_level->parent_id)->first();
            $loop = $this->getTopParent($first_level1->url_path);
        }
        //return module name
        if($loop != '')
        {
            return $loop;
        }
	}
    /**
     * Function to get role permissions for modules
     * @return Main modules
     */
    public function editModules($id)
    {
        $modules_array = array();
        return RolePermissionMdl::where('role_permission.user_role_id',$id)
                    ->join('module','module.id','=','role_permission.module_id')
                    ->where('permission',1)->select('role_permission.module_id')->get();
//        return $modules;
    }

}
