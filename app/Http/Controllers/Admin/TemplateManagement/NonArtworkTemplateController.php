<?php
 
namespace App\Http\Controllers\Admin\TemplateManagement;
 
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classes\Modules;
use View;
use DB;
use App\Classes\ClearSpectrraTemplateMetadata;
 
class NonArtworkTemplateController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('Admin/login');
    }


    /**
     * Method to fetch buyers
     *
     * @return \Illuminate\Http\Response
     */
    public function templateList()
    {
        $module_var = new Modules();
        $is_allowed = $module_var->permittedModules();
        if($is_allowed)
        {
            $get_main_module = $module_var->getMainModules();
            $get_sub_module = $module_var->getSubModules(21);
            View::composer('layouts.Admin.navbar', function($view) use ($get_main_module,$get_sub_module) {
                return $view->with(['modules' => $get_main_module,'sub_modules' => $get_sub_module]);
            });
            
            $buyers = DB::table('buyers')->select('*')->where('is_active','=',1)->get();
            $buyer_count = count($buyers);
            $company = DB::table("company")->pluck('name', 'id');
            $tax = DB::table("tax_code")->where('is_enabled','=',true)->pluck('tax', 'tax');
            $material = DB::table("raw_material_master")->where('is_enabled','=',true)->pluck('material_name_employee', 'id');
            $name_in_reports = DB::table("reports_template_level_name")->pluck('name', 'id');
            
            return view('Admin/Template Management/non-artwork-template-list',['buyers' => $buyers, 'buyer_count' => $buyer_count, 'company' => $company, 'tax' => $tax, 'material'=> $material, 'name_in_reports' => $name_in_reports ]);
        }
        else
        {
             return redirect('/admin/dashboard');
        }
    }
    

    /**
     * Method to add new templates
     *
     * @return \Illuminate\Http\Response
     */
    public function addTemplate(Request $request) {
        $buyer 		 = strtolower(str_replace(" ", "_", $request['buyer']));
        $taxation    = $request['tax'];
        $ref         = $request['ref'];
        $company     = $request['company'];
        $hsncode     = $request['hsncode'];
        $glcode      = $request['glcode'];
        $color       = $request['color'];
        $material_id = $request['material_id'];
        $height      = $request['height'];
        $width       = $request['width'];
        $gltext      = $request['gltext'];
        $parent_id   = $request['template_id'];
        $template_name   = $request['template_name'];
        
        
        $auto_inc_id = DB::table($buyer.'_item_master')->max('id');
        $item_id = DB::table($buyer.'_item_master')->insertGetId([
                            'id' => $auto_inc_id + 1,
                            'company_id' => $company,
                            'parent_id' => $parent_id,
                            'item_name'    => $template_name,
                            'tag_name'    => $ref,
                            'ref_no' => $ref,
                            'is_qrcode_required' => 0,
                            'is_non_artwork_template' => 1
                    ]);

        $temp_auto_inc_id = DB::table($buyer.'_template')->max('id');           
        DB::table($buyer.'_template')->insertGetId([
                            'id' => $temp_auto_inc_id + 1,
                            'item_id'          => $item_id,
                            'tax'              => $taxation,
                            'template_name'    => $ref,
                            'hsn_code'         => $hsncode,
                            'gl_code'          => $glcode,
                            'gl_text'          => $gltext,
                            'color'            => $color,
                            'dimension_height' => $height,
                            'dimension_width'  => $width,
                            'material_id'      => $material_id,
                            'hierarchy_id'     => $parent_id,
                            'name_in_reports'  => $request['name_in_reports'],
                            'flag'             => 0
                        ]);
        return response()->json(['error'=> false,'message' =>'success'],200);
    }

    /**
	*To Show Template When Click Tree Node
	*/
    public function templateView(Request $request)
    {
         $template_id = $request['template_id'];
		 $buyer = strtolower(str_replace(" ", "_", $request['buyer']));
         $treeviewData= DB::table($buyer."_template")
                                ->select($buyer.'_template.*',$buyer.'_item_master.ref_no',$buyer.'_item_master.company_id','company.name as company_name')
                                ->join($buyer.'_item_master',$buyer.'_item_master.id','=',$buyer.'_template.item_id')
                                ->join('company','company.id','=',$buyer.'_item_master.company_id')
                                ->where('item_id',$template_id)->orderBy($buyer.'_template.id','asc')
                                ->get();
         return json_encode($treeviewData);
    }
    
    
    /**
     * Method to get the company, tax, material and report names in select box
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getSelectList(Request $request)
    {
        $items = array();
        $items['company'] = DB::table("company")
                            ->select('*')
                            ->get();
        $items['tax'] = DB::table("tax_code")
                            ->select('id', 'tax')
                            ->where('is_enabled','=',true)
                            ->get();
        $items['material'] = DB::table("raw_material_master")
                            ->select('id','material_name_employee')
                            ->where('is_enabled','=',true)
                            ->get();
        $items['name_in_reports'] = DB::table("reports_template_level_name")->pluck('name', 'id');
         
         return json_encode($items);
    }

    /**
     * Method to update the non artwork template
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function updateTemplate(Request $request) {
        try {
			$buyer 		 = strtolower(str_replace(" ", "_", $request['buyer']));
            $template_id = $request['template_id'];
            $taxation    = $request['tax'];
            $ref         = $request['ref'];
            $company     = $request['company'];
            $hsncode     = $request['hsncode'];
            $glcode      = $request['glcode'];
            $color       = $request['color'];
            $material_id = $request['material_id'];
            $height      = $request['height'];
            $width       = $request['width'];
            $gltext      = $request['gltext'];
            DB::table($buyer.'_template')
                        ->where('id',$template_id)
                        ->update([
                          'tax'              => $taxation,
                          'template_name'    => $ref,
                          'hsn_code'         => $hsncode,
                          'gl_code'          => $glcode,
                          'gl_text'          => $gltext,
                          'color'            => $color,
                          'dimension_height' => $height,
                          'dimension_width'  => $width,
                          'material_id'      => $material_id,
                          'name_in_reports'  => $request['name_in_reports']
                          ]);
			
			$items = DB::table($buyer."_template")
                                ->select('*')
                                ->where('id',$template_id)
                                ->get();
			$item_id = $items[0]->item_id;
			DB::table($buyer.'_item_master')
                        ->where('id',$item_id)
                        ->update([
            						   'company_id' => $company,
            						   'ref_no' => $ref
                        ]);
            
            $clear_spectrra_data = new ClearSpectrraTemplateMetadata();
            $clear_spectrra_data->clearSpectrraData($buyer, $template_id, $company);

            $result = response()->json(['error'=> false,'message' =>'success'],200);
        }
        catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
         
    }

    /**
     * Method to list the template hierarchy
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function templateHierarchy(Request $request) {
	    $buyer = strtolower(str_replace(" ", "_", $request->buyer));
        $treeviewData = array();
        $treeviewData= DB::table($buyer."_item_master")
                    ->select('id','parent_id','item_name as text')
                    ->where('is_non_artwork_template', 1)
                    ->orderBy('id', 'asc')
                    ->get()->toArray();
        
         
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

       return  $treeviewData;

    } 

    /**
     * Method to rename the template
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function renameTemplate(Request $request) {
        try {
			$buyer = strtolower(str_replace(" ", "_", $request['buyer']));
            $template_id = $request['template_id'];
            $template_name = $request['new_name'];
            
            DB::table($buyer.'_item_master')
                        ->where('id',$template_id)
                        ->update(['item_name' => $template_name]);
			
            $result = response()->json(['error'=> false,'message' =>'success'],200);
        }
        catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
         
    }
}