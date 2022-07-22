<?php

namespace App\Http\Controllers\Admin\Orchestra;

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
            $get_sub_module = $module_var->getSubModules(21);
            return view('Admin/Orchestra/template-management');
        }
        else
        {
             return redirect('/admin/dashboard');
        }
        
    } 
    /**
     * [getCompany to get company and orchestra taxcode ]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getCompanyList(Request $request)
    {
        $items = array();
         $items['company'] = DB::table("company")
                                ->select('*')
                                ->get();
         $items['tax'] = DB::table("tax_code")
                                ->select('tax')
                                ->distinct()
                                ->get();
         $items['material'] = DB::table("raw_material_master")
                                ->select('id','material_name_employee')
                                ->where('is_enabled','=',true)
                                ->get();
         $items['name_in_reports'] = DB::table("reports_template_level_name")->pluck('name', 'id');
         return json_encode($items);
    }  
	/**
		To Show Template List
	*/
    public function templateList()
    {
        $treeviewData= DB::table("orchestra_item_master")->select('id','item_name as text','parent_id')->get()->toArray();
          /*if($first_level)
          {
            foreach ($first_level as $level1)
            {
              $level1_parent = $level1->id;
              $second_level = DB::table("orchestra_item_master")->where("parent_id",$level1_parent)->select('id','item_name')->get();
              $level1->second = $second_level;
              foreach ($level1->second as $level2)
              {
                  $level2_parent = $level2->id;
                  $third_level = DB::table("orchestra_template")->where("item_id",$level2_parent)->select('id','template_name')->get();
                  $level2->third = $third_level;
              }
            }

          }*/
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
		To Show Template When Click Tree Node
	*/
    public function templateListView(Request $request)
    {
         $template_id = $request['template_id'];
         
			
         $treeviewData= DB::table("orchestra_template")
                                ->select('orchestra_template.*','orchestra_item_master.ref_no','company.name as company_name','orchestra_item_master.company_id')
                                ->join('orchestra_item_master','orchestra_item_master.id','=','orchestra_template.item_id')
                                ->join('company','company.id','=','orchestra_item_master.company_id')
                                ->where('orchestra_template.item_id',$template_id)->orderBy('orchestra_template.id','asc')
                                ->get();

        $template_file = explode(',',$treeviewData{0}->template_file);


        $data['treeviewData'] = [];

        foreach($template_file as $value)
        {
            array_push($data['treeviewData'], (object)[
                'id' => $treeviewData{0}->id,
                'item_id' => $treeviewData{0}->item_id,
                'template_name' => $treeviewData{0}->template_name,
                'template_description' => $treeviewData{0}->template_description,
                'template_file' => $value,
                'disney' => $treeviewData{0}->disney,
                'tax' => $treeviewData{0}->tax,
                'position' => $treeviewData{0}->position,
                'created_at' => $treeviewData{0}->created_at,
                'updated_at' => $treeviewData{0}->updated_at,
                'sap_ref' => $treeviewData{0}->sap_ref,
                'gl_code' => $treeviewData{0}->gl_code,
                'hsn_code' => $treeviewData{0}->hsn_code,
                'color' => $treeviewData{0}->color,
                'dimension_height' => $treeviewData{0}->dimension_height,
                'dimension_width' => $treeviewData{0}->dimension_width,
                'gl_text' => $treeviewData{0}->gl_text,
                'material_id' => $treeviewData{0}->material_id,
                'flag' => $treeviewData{0}->flag,
                'hierarchy_id' => $treeviewData{0}->hierarchy_id,
                'name_in_reports' => $treeviewData{0}->name_in_reports,
                'ref_no' => $treeviewData{0}->ref_no,
                'company_id' => $treeviewData{0}->company_id,
                'company_name' => $treeviewData{0}->company_name,
            ]);
        }
         
         return json_encode($data['treeviewData']);
    } 
	/**
		To Show Template When Click Tree Node
	*/
	public function templateView(Request $request)
    {
         $template_id = $request['template_id'];
         $customer_id = $request['customer_id'];
         $treeviewData= DB::table("orchestra_template")
                                ->select('orchestra_template.*','orchestra_item_master.ref_no','orchestra_item_master.company_id','company.name as company_name')
                                ->join('orchestra_item_master','orchestra_item_master.id','=','orchestra_template.item_id')
                                ->join('company','company.id','=','orchestra_item_master.company_id')
                                ->where('item_id',$template_id)->orderBy('orchestra_template.id','asc')
                                ->get();

        $template_file = explode(',',$treeviewData{0}->template_file);


        $data['treeviewData'] = [];

        foreach($template_file as $value)
        {
            array_push($data['treeviewData'], (object)[
                'id' => $treeviewData{0}->id,
                'item_id' => $treeviewData{0}->item_id,
                'template_name' => $treeviewData{0}->template_name,
                'template_description' => $treeviewData{0}->template_description,
                'template_file' => $value,
                'disney' => $treeviewData{0}->disney,
                'tax' => $treeviewData{0}->tax,
                'position' => $treeviewData{0}->position,
                'created_at' => $treeviewData{0}->created_at,
                'updated_at' => $treeviewData{0}->updated_at,
                'sap_ref' => $treeviewData{0}->sap_ref,
                'gl_code' => $treeviewData{0}->gl_code,
                'hsn_code' => $treeviewData{0}->hsn_code,
                'color' => $treeviewData{0}->color,
                'dimension_height' => $treeviewData{0}->dimension_height,
                'dimension_width' => $treeviewData{0}->dimension_width,
                'gl_text' => $treeviewData{0}->gl_text,
                'material_id' => $treeviewData{0}->material_id,
                'flag' => $treeviewData{0}->flag,
                'hierarchy_id' => $treeviewData{0}->hierarchy_id,
                'name_in_reports' => $treeviewData{0}->name_in_reports,
                'ref_no' => $treeviewData{0}->ref_no,
                'company_id' => $treeviewData{0}->company_id,
                'company_name' => $treeviewData{0}->company_name,
            ]);
        }
		
		$carton_moq = DB::table('price_taxation')->select('*')->where('customer_id',$customer_id)->where('buyer_id',1)->where('template_id',$template_id)->get();

		if(count($carton_moq) > 0)
		{
			$data['moq']=$carton_moq{0}->moq;
		}
		else
		{
			$data['moq']=1;
		}
		
         return json_encode($data);
    } 
}

