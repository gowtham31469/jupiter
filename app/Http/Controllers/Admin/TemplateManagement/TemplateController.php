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
              
                    TemplateNotes::create([
                        'template_id' => $template_id,
                        'notes'       => $note
                    ]);
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
