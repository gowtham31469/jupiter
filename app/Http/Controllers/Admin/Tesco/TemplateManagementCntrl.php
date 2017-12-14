<?php

namespace App\Http\Controllers\Admin\Tesco;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;   
use App\Classes\Modules;
use Response;
use View;
use DB;

class TemplateManagementCntrl extends Controller
{
    private $modules_permission;
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $module_var = new Modules();
        $is_allowed = $module_var->permittedModules();
        if($is_allowed)
        {
            $top_mod = "Template Management";
            $get_main_module = $module_var->getMainModules();
            View::composer('layouts.Admin.navbar', function($view) use ($get_main_module) {
                return $view->with(['modules' => $get_main_module]);
            });
            $get_sub_module = $module_var->getSubModules(22);
            return view('Admin/Orchestra/template-management');
        }
        else
        {
             return redirect('/admin/dashboard');
        }
        
    }
     public function templateList()
    {
        $treeviewData= DB::table("tesco_item_master")->select('id','item_name as text','parent_id')->get()->toArray();
         
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
    public function templateView(Request $request)
    {
         $template_id = $request['template_id'];
         $exp_temp = explode(',',$template_id);
         $view=array();
         foreach($exp_temp as $exp)
         {
            $treeviewData= DB::table("tesco_template")
                                ->select('tesco_template.*','tesco_item_master.ref_no','company.name as company_name')
                                ->join('tesco_item_master','tesco_item_master.id','=','tesco_template.item_id')
                                ->join('company','company.id','=','tesco_item_master.company_id')
                                ->where('item_id',$exp)
                                ->get();
             array_push($view,$treeviewData);
         }
         
         return json_encode($view);
    }
}

