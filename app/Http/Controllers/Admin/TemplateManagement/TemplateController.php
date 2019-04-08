<?php

namespace App\Http\Controllers\Admin\TemplateManagement;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classes\Modules;
use Response;
use View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use DB;
use App\TemplateNotes;

class TemplateController extends Controller
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

    public function dashboard()
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
            return view('Admin/Template Management/dashboard',['buyers' => $buyers,'buyer_count' => $buyer_count]);
        }
        else
        {
             return redirect('/admin/dashboard');
        }

    }
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
            return view('Admin/Template Management/template-list',['buyers' => $buyers,'buyer_count' => $buyer_count]);
        }
        else
        {
             return redirect('/admin/dashboard');
        }

    }  
	
	public function templateHierarchy(Request $request)
    {
	    $buyer = $request->buyer;
        $treeviewData= DB::table($buyer."_item_master")->select('id','item_name as text','parent_id')->get()->toArray();
         
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
	
	public function saveTax(Request $request) {
        try {
			$buyer 		 = $request['buyer'];
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
                          'material_id'      => $material_id
                          ]);
			
			$items = DB::table($buyer."_template")
                                ->select('*')
                                ->where('id',$template_id)
                                ->get();
			$item_id = $items{0}->item_id;
			DB::table($buyer.'_item_master')
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
	public function addTemplate(Request $request) {
        try {
			$buyer 		 = $request['buyer'];
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
			
			
			
			$item_id = DB::table($buyer.'_item_master')
                        ->insertGetId([
							   'company_id' => $company,
							   'parent_id' => $parent_id,
							   'item_name'    => $ref,
							   'tag_name'    => $ref,
							   'ref_no' => $ref
                        ]);
			
		    $template_id = DB::table($buyer.'_template')->insertGetId([
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
						  ]);
			
			
            $result = response()->json(['error'=> false,'message' =>'success'],200);
        }
        catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
         
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
		To Show Template When Click Tree Node
	*/
    public function templateView(Request $request)
    {
         $template_id = $request['template_id'];
		 $buyer = $request['buyer'];
         $treeviewData= DB::table($buyer."_template")
                                ->select($buyer.'_template.*',$buyer.'_item_master.ref_no',$buyer.'_item_master.company_id','company.name as company_name')
                                ->join($buyer.'_item_master',$buyer.'_item_master.id','=',$buyer.'_template.item_id')
                                ->join('company','company.id','=',$buyer.'_item_master.company_id')
                                ->where('item_id',$template_id)
                                ->get();
         return json_encode($treeviewData);
    } 

    /**
     * method to get the templates notes
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getTemplateNotes(Request $request)
    {
        try
        {   
            $template_id = $request->template_id;
            $template_notes =   DB::table('template_notes')->select('*')->where('template_id',$template_id)->get();

            if ($template_notes) {
                 $result    = response()->json(['error'=> false,'message' =>'success','data'=>$template_notes],200);
            }
            else{
                $result = response()->json(['error'=> true,'message' =>'Error'],400);
            }
           
        }
        catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
    }

    public function setTemplateNotes(Request $request)
    {
        $notes       =  $request->edit_details;
        $template_id =  $request->template_id;

        if ($notes) {
            TemplateNotes::where('template_id',$template_id)->delete();
            foreach ($notes as $key => $note) {
              
                    if ($note!=null) {
                        TemplateNotes::create([
                            'template_id' => $template_id,
                            'notes'       => $note
                        ]);
                    }
            }
            return redirect('admin/template-management')->with('message', 'Notes added successfully');
        }
        
    }

//    public function base64ToJpeg(Request $request)
//    {
//            $output_file=  public_path('/storage/app/template/barcode.svg');
//            $ifp = fopen($output_file , 'wb' ); 
//
//            $data = explode( ',', $request->src );
//
//            fwrite( $ifp, base64_decode( $data[ 1 ] ) );
//
//            fclose( $ifp ); 
//            print_r(url('/storage/app/template/barcode.svg'));
//          
//    }
}
