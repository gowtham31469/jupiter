<?php

namespace App\Http\Controllers\Customer\Orchestra;

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
        $this->middleware('customer_auth');
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
		$carton_moq = DB::table('price_taxation')->select('*')->where('customer_id',$customer_id)->where('buyer_id',1)->where('template_id',$template_id)->get();

		if(count($carton_moq) > 0)
		{
			$data['moq']=$carton_moq{0}->moq;
		}
		else
		{
			$data['moq']=1;
		}
         return json_encode($treeviewData);
    } 
	public function saveTax(Request $request) {
        try {
            $template_id = $request['template_id'];
            $taxation    = $request['tax'];
            $ref         = $request['ref'];
            $company     = $request['company'];
            $hsncode     = $request['hsncode'];
            $sapcode     = $request['sapcode'];
            $glcode      = $request['glcode'];
            $color       = $request['color'];
            $material_id = $request['material_id'];
            $height      = $request['height'];
            $width       = $request['width'];
            $gltext      = $request['gltext'];
            DB::table('orchestra_template')
                        ->where('id',$template_id)
                        ->update([
                          'tax'              => $taxation,
                          'template_name'    => $ref,
                          'hsn_code'         => $hsncode,
                          'gl_code'          => $glcode,
                          'gl_text'          => $gltext,
                          'sap_ref'          => $sapcode,
                          'color'            => $color,
                          'dimension_height' => $height,
                          'dimension_width'  => $width,
                          'material_id'      => $material_id
                          ]);
			
			$items = DB::table("orchestra_template")
                                ->select('*')
                                ->where('id',$template_id)
                                ->get();
			$item_id = $items{0}->item_id;
			DB::table('orchestra_item_master')
                        ->where('id',$item_id)
                        ->update([
            						   'company_id' => $company,
            						   'ref_no' => $ref
                        ]);
            $result = response()->json(['error'=> false,'message' =>'success'],200);
        }
        catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
         
    }
}

