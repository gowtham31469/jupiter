<?php

namespace App\Http\Controllers\Admin\Settings\Orchestra;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classes\Modules;
use Response;
use View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use DB;
use App\OrchestraGarments;
use App\OrchestraAgeGroup;
use App\OrchestraCountries;
use App\OrchestraProductFamily;
use App\OrchestraSpecialInstructions;
use App\OrchestraCompositions;
use App\OrchestraParts;
use App\OrchestraSizeCharts;

/**
 * Orchestra Age Group controller
 * @author <Praveen>
 * @date(03/01/2018)
 */
class OrchestraSettings extends Controller
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
     * Show the orchestra age group table view.
     *
     * @return \Illuminate\Http\Response
     * @author <Praveen>
     */
    public function index()
    {
        $module_var = new Modules();
        $is_allowed = $module_var->permittedModules();
        if($is_allowed)
        {
            $get_main_module = $module_var->getMainModules();
            $get_sub_module = $module_var->getSubModules(40);
            View::composer('layouts.Admin.navbar', function($view) use ($get_main_module,$get_sub_module) {
                return $view->with(['modules' => $get_main_module,'sub_modules' => $get_sub_module]);
            });
            // For getting care category 
            $category = DB::table('orchestra_care_category')->pluck('category_name','id')->all();

            $buyers = DB::table('buyers')->select('*')->get();
            return view('Admin/Settings/home')->with(['buyers' => $buyers, 'category' => $category]);
        }
        else
        {
             return redirect('/admin/dashboard');
        }
    }
    /**
     * Orchestra metadata garments list show using ajax.
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function orchestraGarments()
    {
      try {
            $orchestra_garments = DB::table('orchestra_garments')->select('id','garment_name')->get();
            return response()->json(['error'=>'false','message' =>'success', 'orchestra_garments' => $orchestra_garments]);
          } catch (\Exception $e)
          {
                 return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
          }
    }
    /**
     * Add orchestra garments.
     * @param \Illuminate\Http\Request
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function addOrchestraGarments(Request $request)
    {
        try {
            $inserted = OrchestraGarments::create([
                        'garment_name' => $request['name']
                      ]);
            if($inserted != '')
            {
              $result = response()->json(['error'=> false,'message' =>'success'],200);
            }
            else
            {
              $result = response()->json(['error'=> true,'message' =>'Error'],400);
            }

        } catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
    }
    /**
     * Get orchestra garment details by ID for editing.
     * @param orchestra garment id
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function getOrchestraGarmentsById(Request $request)
    {
    try {
          $id = $request['id'];
          $orchestra_garments = DB::table('orchestra_garments')->select('id','garment_name')->where('orchestra_garments.id',$id)->get();
          return response()->json(['error'=>'false','message' =>'success', 'orchestra_garments' => $orchestra_garments]);
        } catch (\Exception $e)
        {
          return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
    }
    /**
    * Update garment by id.
    * @param garment id
    * @author <Praveen>
    * @return \Illuminate\Http\Response
    */
    public function updateOrchestraGarmentsById(Request $request)
    {
    try {
          $id        = $request['id'];
          $name      = $request['name'];
          
          OrchestraGarments::where('id',$id)
           ->update([
             'garment_name' => $name
          ]);

          $result = response()->json(['error'=> false,'message' =>'Success'],200);
        
        } catch (\Exception $e)
        {
               $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
    }
    /**
     * function to delete garment by id.
     * @param garment id
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function deleteOrchestraGarmentsById(Request $request)
    {
      try {
            $id        = $request['id'];
            $deleted = OrchestraGarments::where('id', '=', $id)
                        ->delete();
            if($deleted != '')
            {
              $result = response()->json(['error'=> false,'message' =>'success'],200);
            }
            else
            {
              $result = response()->json(['error'=> true,'message' =>'Error'],400);
            }
          } catch (\Exception $e)
          {
                $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
          }
          return $result;
    }
    /**
     * Orchestra metadata age group list show using ajax.
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function orchestraAgeGroup()
    {
      try {
            $orchestra_agegroups = DB::table('orchestra_age_group')->select('id','age_group_name','color')->get();
            return response()->json(['error'=>'false','message' =>'success', 'orchestra_agegroups' => $orchestra_agegroups]);
          } catch (\Exception $e)
          {
                 return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
          }
    }
    /**
     * Add orchestra age group.
     * @param \Illuminate\Http\Request
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function addOrchestraAgeGroup(Request $request)
    {
        try {
            $inserted = OrchestraAgeGroup::create([
                        'age_group_name' => $request['name'],
                        'color'          => $request['color']
                      ]);
            if($inserted != '')
            {
              $result = response()->json(['error'=> false,'message' =>'success'],200);
            }
            else
            {
              $result = response()->json(['error'=> true,'message' =>'Error'],400);
            }

        } catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
    }
    /**
     * Get orchestra age group details by ID for editing.
     * @param orchestra age-group id
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function getOrchestraAgeGroupById(Request $request)
    {
    try {
          $id = $request['id'];
          $orchestra_age_group = DB::table('orchestra_age_group')->select('id','age_group_name','color')->where('orchestra_age_group.id',$id)->get();
          return response()->json(['error'=>'false','message' =>'success', 'orchestra_age_group' => $orchestra_age_group]);
        } catch (\Exception $e)
        {
          return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
    }
    /**
    * Update garment by id.
    * @param garment id
    * @author <Praveen>
    * @return \Illuminate\Http\Response
    */
    public function updateOrchestraAgeGroupById(Request $request)
    {
    try {
          $id        = $request['id'];
          $name      = $request['name'];
          $color     = $request['color'];          
          OrchestraAgeGroup::where('id',$id)
           ->update([
             'age_group_name' => $name,
             'color'          => $color
          ]);

          $result = response()->json(['error'=> false,'message' =>'Success'],200);
        
        } catch (\Exception $e)
        {
               $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
    }
    /**
     * function to delete age group by id.
     * @param age group id
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function deleteOrchestraAgeGroupsById(Request $request)
    {
      try {
            $id        = $request['id'];
            $deleted = OrchestraAgeGroup::where('id', '=', $id)
                        ->delete();
            if($deleted != '')
            {
              $result = response()->json(['error'=> false,'message' =>'success'],200);
            }
            else
            {
              $result = response()->json(['error'=> true,'message' =>'Error'],400);
            }
          } catch (\Exception $e)
          {
                $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
          }
          return $result;
    }
    /**
     * Orchestra metadata countries list show using ajax.
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function orchestraCountries()
    {
      try {
            $orchestra_countries = DB::table('orchestra_countries')->select('id','french','spanish','portuguese','italian','dutch','german','russian','chinese','arabic','greek','turkish','english')->get();
            return response()->json(['error'=>'false','message' =>'success', 'orchestra_countries' => $orchestra_countries]);
          } catch (\Exception $e)
          {
                 return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
          }
    }
    /**
     * Add orchestra country.
     * @param \Illuminate\Http\Request
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function addOrchestraCountry(Request $request)
    {
        try {
            $inserted = OrchestraCountries::create([
                        'english'    => $request['english'],
                        'french'     => $request['french'],
                        'spanish'    => $request['spanish'],
                        'portuguese' => $request['portuguese'],
                        'italian'    => $request['italian'],
                        'dutch'      => $request['dutch'],
                        'german'     => $request['german'],
                        'russian'    => $request['russian'],
                        'chinese'    => $request['chinese'],
                        'arabic'     => $request['arabic'],
                        'greek'      => $request['greek'],
                        'turkish'    => $request['turkish']
                      ]);
            if($inserted != '')
            {
              $result = response()->json(['error'=> false,'message' =>'success'],200);
            }
            else
            {
              $result = response()->json(['error'=> true,'message' =>'Error'],400);
            }

        } catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
    }
    /**
     * Get orchestra country details by ID for editing.
     * @param orchestra country id
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function getOrchestraCountryById(Request $request)
    {
    try {
          $id = $request['id'];
          $orchestra_countries = DB::table('orchestra_countries')->select('*')->where('orchestra_countries.id',$id)->get();
          return response()->json(['error'=>'false','message' =>'success', 'orchestra_countries' => $orchestra_countries]);
        } catch (\Exception $e)
        {
          return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
    }
    /**
    * Update orchestra country by id.
    * @param orchestra country id
    * @author <Praveen>
    * @return \Illuminate\Http\Response
    */
    public function updateOrchestraCountryById(Request $request)
    {
    try {
          $id        = $request['id'];
                   
          OrchestraCountries::where('id',$id)
           ->update([
            'english'    => $request['english'],
            'french'     => $request['french'],
            'spanish'    => $request['spanish'],
            'portuguese' => $request['portuguese'],
            'italian'    => $request['italian'],
            'dutch'      => $request['dutch'],
            'german'     => $request['german'],
            'russian'    => $request['russian'],
            'chinese'    => $request['chinese'],
            'arabic'     => $request['arabic'],
            'greek'      => $request['greek'],
            'turkish'    => $request['turkish']
          ]);

          $result = response()->json(['error'=> false,'message' =>'Success'],200);
        
        } catch (\Exception $e)
        {
               $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
    }
    /**
     * function to delete orchestra country by id.
     * @param orchestra country id
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function deleteOrchestraCountryById(Request $request)
    {
      try {
            $id        = $request['id'];
            $deleted = OrchestraCountries::where('id', '=', $id)
                        ->delete();
            if($deleted != '')
            {
              $result = response()->json(['error'=> false,'message' =>'success'],200);
            }
            else
            {
              $result = response()->json(['error'=> true,'message' =>'Error'],400);
            }
          } catch (\Exception $e)
          {
                $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
          }
          return $result;
    }
    /**
     * Orchestra metadata Product Family list show using ajax.
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function orchestraProductFamily()
    {
      try {
            $orchestra_product_family = DB::table('orchestra_product_family')->select('id','french','arabic','english','code')->get();
            return response()->json(['error'=>'false','message' =>'success', 'orchestra_product_family' => $orchestra_product_family]);
          } catch (\Exception $e)
          {
                 return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
          }
    }
    /**
     * Add orchestra product family.
     * @param \Illuminate\Http\Request
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function addOrchestraProductFamily(Request $request)
    {
        try {
            $inserted = OrchestraProductFamily::create([
                        'code'       => $request['code'],
                        'english'    => $request['english'],
                        'french'     => $request['french'],
                        'arabic'     => $request['arabic']
                      ]);
            if($inserted != '')
            {
              $result = response()->json(['error'=> false,'message' =>'success'],200);
            }
            else
            {
              $result = response()->json(['error'=> true,'message' =>'Error'],400);
            }

        } catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
    }
    /**
     * Get orchestra age group details by ID for editing.
     * @param orchestra age-group id
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function getOrchestraProductFamilyById(Request $request)
    {
    try {
          $id = $request['id'];
          $orchestra_product_family = DB::table('orchestra_product_family')->select('id','code','english','french','arabic')->where('orchestra_product_family.id',$id)->get();
          return response()->json(['error'=>'false','message' =>'success', 'orchestra_product_family' => $orchestra_product_family]);
        } catch (\Exception $e)
        {
          return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
    }
    /**
    * Update orchestra productfamily by id.
    * @param orchestra productfamily id
    * @author <Praveen>
    * @return \Illuminate\Http\Response
    */
    public function updateOrchestraProductFamilyById(Request $request)
    {
    try {
          $id        = $request['id'];
          OrchestraProductFamily::where('id',$id)
           ->update([
              'code'       => $request['code'],
              'english'    => $request['english'],
              'french'     => $request['french'],
              'arabic'     => $request['arabic']
          ]);

          $result = response()->json(['error'=> false,'message' =>'Success'],200);
        
        } catch (\Exception $e)
        {
               $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
    }
    /**
     * function to delete productfamily by id.
     * @param productfamily id
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function deleteOrchestraProductFamilyById(Request $request)
    {
      try {
            $id        = $request['id'];
            $deleted = OrchestraProductFamily::where('id', '=', $id)
                        ->delete();
            if($deleted != '')
            {
              $result = response()->json(['error'=> false,'message' =>'success'],200);
            }
            else
            {
              $result = response()->json(['error'=> true,'message' =>'Error'],400);
            }
          } catch (\Exception $e)
          {
                $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
          }
          return $result;
    }
    /**
     * Orchestra metadata special instructions list show using ajax.
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function orchestraSpecialInstructions()
    {
      try {
            $orchestra_specialinstructions = DB::table('orchestra_care_instruction')->select('id','french','spanish','portuguese','italian','dutch','german','russian','chinese','arabic','greek','turkish','english')->get();
            return response()->json(['error'=>'false','message' =>'success', 'orchestra_specialinstructions' => $orchestra_specialinstructions]);
          } catch (\Exception $e)
          {
                 return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
          }
    }
    /**
     * Add orchestra special instructions.
     * @param \Illuminate\Http\Request
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function addOrchestraSpecialInstructions(Request $request)
    {
        try {
            $inserted = OrchestraSpecialInstructions::create([
                        'english'    => $request['english'],
                        'french'     => $request['french'],
                        'spanish'    => $request['spanish'],
                        'portuguese' => $request['portuguese'],
                        'italian'    => $request['italian'],
                        'dutch'      => $request['dutch'],
                        'german'     => $request['german'],
                        'russian'    => $request['russian'],
                        'chinese'    => $request['chinese'],
                        'arabic'     => $request['arabic'],
                        'greek'      => $request['greek'],
                        'turkish'    => $request['turkish']
                      ]);
            if($inserted != '')
            {
              $result = response()->json(['error'=> false,'message' =>'success'],200);
            }
            else
            {
              $result = response()->json(['error'=> true,'message' =>'Error'],400);
            }

        } catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
    }
    /**
     * Get orchestra special instructions details by ID for editing.
     * @param orchestra special instructions id
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function getOrchestraSpecialInstructionById(Request $request)
    {
    try {
          $id = $request['id'];
          $orchestra_special_instruction = DB::table('orchestra_care_instruction')->select('*')->where('orchestra_care_instruction.id',$id)->get();
          return response()->json(['error'=>'false','message' =>'success', 'orchestra_special_instruction' => $orchestra_special_instruction]);
        } catch (\Exception $e)
        {
          return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
    }
    /**
    * Update orchestra special instruction by id.
    * @param orchestra special instruction id
    * @author <Praveen>
    * @return \Illuminate\Http\Response
    */
    public function updateOrchestraSpecialInstructionById(Request $request)
    {
    try {
          $id        = $request['id'];
                   
          OrchestraSpecialInstructions::where('id',$id)
           ->update([
            'english'    => $request['english'],
            'french'     => $request['french'],
            'spanish'    => $request['spanish'],
            'portuguese' => $request['portuguese'],
            'italian'    => $request['italian'],
            'dutch'      => $request['dutch'],
            'german'     => $request['german'],
            'russian'    => $request['russian'],
            'chinese'    => $request['chinese'],
            'arabic'     => $request['arabic'],
            'greek'      => $request['greek'],
            'turkish'    => $request['turkish']
          ]);

          $result = response()->json(['error'=> false,'message' =>'Success'],200);
        
        } catch (\Exception $e)
        {
               $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
    }
    /**
     * function to delete special instruction by id.
     * @param special instruction id
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function deleteOrchestraSpecialInstructionById(Request $request)
    {
      try {
            $id        = $request['id'];
            $deleted = OrchestraSpecialInstructions::where('id', '=', $id)
                        ->delete();
            if($deleted != '')
            {
              $result = response()->json(['error'=> false,'message' =>'success'],200);
            }
            else
            {
              $result = response()->json(['error'=> true,'message' =>'Error'],400);
            }
          } catch (\Exception $e)
          {
                $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
          }
          return $result;
    }
    /**
     * Orchestra Compositions list show using ajax.
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function orchestraCompositions()
    {
      try {
            $orchestra_compositions = DB::table('orchestra_compositions')->select('id','french','spanish','portuguese','italian','dutch','german','russian','chinese','arabic','greek','turkish','english')->get();
            return response()->json(['error'=>'false','message' =>'success', 'orchestra_compositions' => $orchestra_compositions]);
          } catch (\Exception $e)
          {
                 return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
          }
    }
    /**
     * Add orchestra Compositions.
     * @param \Illuminate\Http\Request
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function addOrchestraCompositions(Request $request)
    {
        try {
            $inserted = OrchestraCompositions::create([
                        'english'    => $request['english'],
                        'french'     => $request['french'],
                        'spanish'    => $request['spanish'],
                        'portuguese' => $request['portuguese'],
                        'italian'    => $request['italian'],
                        'dutch'      => $request['dutch'],
                        'german'     => $request['german'],
                        'russian'    => $request['russian'],
                        'chinese'    => $request['chinese'],
                        'arabic'     => $request['arabic'],
                        'greek'      => $request['greek'],
                        'turkish'    => $request['turkish']
                      ]);
            if($inserted != '')
            {
              $result = response()->json(['error'=> false,'message' =>'success'],200);
            }
            else
            {
              $result = response()->json(['error'=> true,'message' =>'Error'],400);
            }

        } catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
    }
    /**
     * Get orchestra Compositions details by ID for editing.
     * @param orchestra Compositions id
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function getOrchestraCompositionById(Request $request)
    {
    try {
          $id = $request['id'];
          $orchestra_composition = DB::table('orchestra_compositions')->select('*')->where('orchestra_compositions.id',$id)->get();
          return response()->json(['error'=>'false','message' =>'success', 'orchestra_composition' => $orchestra_composition]);
        } catch (\Exception $e)
        {
          return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
    }
    /**
    * Update orchestra Composition by id.
    * @param orchestra Composition id
    * @author <Praveen>
    * @return \Illuminate\Http\Response
    */
    public function updateOrchestraCompositionById(Request $request)
    {
    try {
          $id        = $request['id'];
                   
          OrchestraCompositions::where('id',$id)
           ->update([
            'english'    => $request['english'],
            'french'     => $request['french'],
            'spanish'    => $request['spanish'],
            'portuguese' => $request['portuguese'],
            'italian'    => $request['italian'],
            'dutch'      => $request['dutch'],
            'german'     => $request['german'],
            'russian'    => $request['russian'],
            'chinese'    => $request['chinese'],
            'arabic'     => $request['arabic'],
            'greek'      => $request['greek'],
            'turkish'    => $request['turkish']
          ]);

          $result = response()->json(['error'=> false,'message' =>'Success'],200);
        
        } catch (\Exception $e)
        {
               $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
    }
    /**
     * function to delete Composition by id.
     * @param Composition id
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function deleteOrchestraCompositionById(Request $request)
    {
      try {
            $id        = $request['id'];
            $deleted = OrchestraCompositions::where('id', '=', $id)
                        ->delete();
            if($deleted != '')
            {
              $result = response()->json(['error'=> false,'message' =>'success'],200);
            }
            else
            {
              $result = response()->json(['error'=> true,'message' =>'Error'],400);
            }
          } catch (\Exception $e)
          {
                $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
          }
          return $result;
    }
    /**
     * Orchestra Parts list show using ajax.
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function orchestraParts()
    {
      try {
            $orchestra_parts = DB::table('orchestra_parts')->select('id','french','spanish','portuguese','italian','dutch','german','russian','chinese','arabic','greek','turkish','english')->get();
            return response()->json(['error'=>'false','message' =>'success', 'orchestra_parts' => $orchestra_parts]);
          } catch (\Exception $e)
          {
                 return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
          }
    }
    /**
     * Add orchestra Parts.
     * @param \Illuminate\Http\Request
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function addOrchestraParts(Request $request)
    {
        try {
            $inserted = OrchestraParts::create([
                        'english'    => $request['english'],
                        'french'     => $request['french'],
                        'spanish'    => $request['spanish'],
                        'portuguese' => $request['portuguese'],
                        'italian'    => $request['italian'],
                        'dutch'      => $request['dutch'],
                        'german'     => $request['german'],
                        'russian'    => $request['russian'],
                        'chinese'    => $request['chinese'],
                        'arabic'     => $request['arabic'],
                        'greek'      => $request['greek'],
                        'turkish'    => $request['turkish']
                      ]);
            if($inserted != '')
            {
              $result = response()->json(['error'=> false,'message' =>'success'],200);
            }
            else
            {
              $result = response()->json(['error'=> true,'message' =>'Error'],400);
            }

        } catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
    }
    /**
     * Get orchestra Parts details by ID for editing.
     * @param orchestra Parts id
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function getOrchestraPartById(Request $request)
    {
    try {
          $id = $request['id'];
          $orchestra_part = DB::table('orchestra_parts')->select('*')->where('orchestra_parts.id',$id)->get();
          return response()->json(['error'=>'false','message' =>'success', 'orchestra_part' => $orchestra_part]);
        } catch (\Exception $e)
        {
          return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
    }
    /**
    * Update orchestra Part by id.
    * @param orchestra Part id
    * @author <Praveen>
    * @return \Illuminate\Http\Response
    */
    public function updateOrchestraPartById(Request $request)
    {
    try {
          $id        = $request['id'];
                   
          OrchestraParts::where('id',$id)
           ->update([
            'english'    => $request['english'],
            'french'     => $request['french'],
            'spanish'    => $request['spanish'],
            'portuguese' => $request['portuguese'],
            'italian'    => $request['italian'],
            'dutch'      => $request['dutch'],
            'german'     => $request['german'],
            'russian'    => $request['russian'],
            'chinese'    => $request['chinese'],
            'arabic'     => $request['arabic'],
            'greek'      => $request['greek'],
            'turkish'    => $request['turkish']
          ]);

          $result = response()->json(['error'=> false,'message' =>'Success'],200);
        
        } catch (\Exception $e)
        {
               $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
    }
    /**
     * function to delete Part by id.
     * @param Part id
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function deleteOrchestraPartById(Request $request)
    {
      try {
            $id        = $request['id'];
            $deleted = OrchestraParts::where('id', '=', $id)
                        ->delete();
            if($deleted != '')
            {
              $result = response()->json(['error'=> false,'message' =>'success'],200);
            }
            else
            {
              $result = response()->json(['error'=> true,'message' =>'Error'],400);
            }
          } catch (\Exception $e)
          {
                $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
          }
          return $result;
    }
    /**
     * Orchestra Size Charts list show using ajax.
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function orchestraSizeCharts()
    {
      try {
            $orchestra_size_chart = DB::table('orchestra_size_chart')->select('id','age','cm','in')->get();
            return response()->json(['error'=>'false','message' =>'success', 'orchestra_size_charts' => $orchestra_size_chart]);
          } catch (\Exception $e)
          {
                 return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
          }
    }
    /**
     * Add orchestra Size Charts.
     * @param \Illuminate\Http\Request
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function addOrchestraSizeCharts(Request $request)
    {
        try {
            $inserted = OrchestraSizeCharts::create([
                        'age'    => $request['age'],
                        'cm'     => $request['cm'],
                        'in'     => $request['in']
                      ]);
            if($inserted != '')
            {
              $result = response()->json(['error'=> false,'message' =>'success'],200);
            }
            else
            {
              $result = response()->json(['error'=> true,'message' =>'Error'],400);
            }

        } catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
    }
    /**
     * Get orchestra Size Chart details by ID for editing.
     * @param orchestra Size Chart id
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function getOrchestraSizeChartById(Request $request)
    {
    try {
          $id = $request['id'];
          $orchestra_size_chart = DB::table('orchestra_size_chart')->select('*')->where('orchestra_size_chart.id',$id)->get();
          return response()->json(['error'=>'false','message' =>'success', 'orchestra_size_chart' => $orchestra_size_chart]);
        } catch (\Exception $e)
        {
          return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
    }
    /**
    * Update orchestra size chart by id.
    * @param orchestra size chart id
    * @author <Praveen>
    * @return \Illuminate\Http\Response
    */
    public function updateOrchestraSizeChartById(Request $request)
    {
    try {
          $id        = $request['id'];
                   
          OrchestraSizeCharts::where('id',$id)
           ->update([
            'age'    => $request['age'],
            'cm'     => $request['cm'],
            'in'     => $request['in']
          ]);

          $result = response()->json(['error'=> false,'message' =>'Success'],200);
        
        } catch (\Exception $e)
        {
               $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
    }
    /**
     * function to delete Orchestra Size Chart by id.
     * @param size chart id
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function deleteOrchestraSizeChartById(Request $request)
    {
      try {
            $id        = $request['id'];
            $deleted = OrchestraSizeCharts::where('id', '=', $id)
                        ->delete();
            if($deleted != '')
            {
              $result = response()->json(['error'=> false,'message' =>'success'],200);
            }
            else
            {
              $result = response()->json(['error'=> true,'message' =>'Error'],400);
            }
          } catch (\Exception $e)
          {
                $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
          }
          return $result;
    }
     /**
     * Orchestra metadata Symbols list show using ajax.
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function orchestraSymbols()
    {
      try {
            $orchestra_care_symbols = DB::table('orchestra_care_symbols')->select('*')->get();
       
            return response()->json(['error'=>'false','message' =>'success', 'orchestra_care_symbols' => $orchestra_care_symbols]);
          } catch (\Exception $e)
          {
                 return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
          }
    }
     /**
     * Add orchestra Symbols.
     * @param \Illuminate\Http\Request
     * @author <Sumanth>
     * @return \Illuminate\Http\Response
     */
    public function addOrchestraSymbols(Request $request)
    {
        try {
          $destination_path='images/WashCareSymbols/';
          $destination_path_db='/images/WashCareSymbols/';
          $cofreet_symbols= $request->file('cofreet_symbols');
          $astms_symbol= $request->file('astms_symbol');
          
          $cofreet_symbols_orname = $cofreet_symbols->getClientOriginalName();
          $astms_symbol_orname = $astms_symbol->getClientOriginalName();

          $cofreet_symbols_filename= str_random(6).'_'.$cofreet_symbols_orname;
          $astms_symbol_filename= str_random(6).'_'.$astms_symbol_orname;

            switch($request->category_id){
              case 1:
                $upload_path = 'Washing/';
                break;
              case 2:
                $upload_path = 'Whitening/';
                break;
              case 3:
                $upload_path = 'Dryer/';
                break;
              case 4:
                $upload_path = 'Ironing/';
                break;
              case 5:
                $upload_path = 'Cleaning/';
                break;
              default:
                $upload_path = 'Washing/';
                break;
            }

            $cofreet_symbols->move($destination_path.$upload_path,$cofreet_symbols_filename);
            $astms_symbol->move($destination_path.$upload_path,$astms_symbol_filename);
  
            $cofreet_path_insert=$destination_path_db.$upload_path.$cofreet_symbols_filename;
            $astms_path_insert=$destination_path_db.$upload_path.$astms_symbol_filename;

            $inserted = DB::table('orchestra_care_symbols')->insert([
                'category_id'     =>  $request->category_id,
                'cofreet_symbols' =>  $cofreet_path_insert,
                'astms_symbol'    =>  $astms_path_insert,
                'care_instruction'=>  $request->care_instruction
            ]);

            if($inserted != '')
            {
              $result = response()->json(['error'=> false,'message' =>'success'],200);
            }
            else
            {
              $result = response()->json(['error'=> true,'message' =>'Error'],400);
            }

        } catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
    }

    /**
     * Edit orchestra Symbols.
     * @param \Illuminate\Http\Request
     * @author <Sumanth>
     * @return \Illuminate\Http\Response
     */
    public function editOrchestraSymbols(Request $request)
    {
        try {

          $destination_path='images/WashCareSymbols/';
          $destination_path_db='/images/WashCareSymbols/';

          switch($request->category_id){
            case 1:
              $upload_path = 'Washing/';
              break;
            case 2:
              $upload_path = 'Whitening/';
              break;
            case 3:
              $upload_path = 'Dryer/';
              break;
            case 4:
              $upload_path = 'Ironing/';
              break;
            case 5:
              $upload_path = 'Cleaning/';
              break;
            default:
              $upload_path = 'Washing/';
              break;
          }


          if($request->file('cofreet_symbols') == ""){
            
          }else {
            $cofreet_symbols= $request->file('cofreet_symbols');
            $cofreet_symbols_orname = $cofreet_symbols->getClientOriginalName();
            $cofreet_symbols_filename= str_random(6).'_'.$cofreet_symbols_orname;
            $cofreet_symbols->move($destination_path.$upload_path,$cofreet_symbols_filename);
            $cofreet_path_insert=$destination_path_db.$upload_path.$cofreet_symbols_filename;
          }

          if($request->file('astms_symbol') == ""){
           
          }else {
            $astms_symbol= $request->file('astms_symbol');
            $astms_symbol_orname = $astms_symbol->getClientOriginalName();
            $astms_symbol_filename= str_random(6).'_'.$astms_symbol_orname;
            $astms_symbol->move($destination_path.$upload_path,$astms_symbol_filename);
            $astms_path_insert=$destination_path_db.$upload_path.$astms_symbol_filename;
          }

            if($request->file('astms_symbol') == ""){
              
             } else {

              $old_cof = DB::table('orchestra_care_symbols')->where("id",$request->id)->value('astms_symbol');
              unlink(ltrim($old_cof, '/'));
              DB::table('orchestra_care_symbols')->where("id",$request->id)->update([
                'astms_symbol'     =>  $astms_path_insert
              ]);
             }

             if($request->file('cofreet_symbols') == ""){
              
             } else {
              $old_cof = DB::table('orchestra_care_symbols')->where("id",$request->id)->value('cofreet_symbols');
              unlink(ltrim($old_cof, '/'));

              DB::table('orchestra_care_symbols')->where("id",$request->id)->update([
                'cofreet_symbols'     =>  $cofreet_path_insert
              ]);
             }

             $inserted = DB::table('orchestra_care_symbols')->where("id",$request->id )->update([
              'category_id'     =>  $request->category_id,
              'care_instruction'=>  $request->care_instruction
          ]);

            // if($inserted != '')
            // {
              $result = response()->json(['error'=> false,'message' =>'success'],200);
            // }
            // else
            // {
            //   $result = response()->json(['error'=> true,'message' =>'Error'],400);
            // }

        } catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
    }

     /**
     * function to delete Orchestra Symbol by id.
     * @param symbol id
     * @author <Sumanth>
     * @return \Illuminate\Http\Response
     */
    public function deleteOrchestraSymbolById(Request $request)
    {
      try {
            $id        = $request['id'];
            $old_cof = DB::table('orchestra_care_symbols')->where("id",$request->id)->value('astms_symbol');
            unlink(ltrim($old_cof, '/'));
            $old_cof = DB::table('orchestra_care_symbols')->where("id",$request->id)->value('cofreet_symbols');
            unlink(ltrim($old_cof, '/'));
            $deleted = DB::table('orchestra_care_symbols')->where('id', '=', $id)->delete();
           if($deleted != '')
            {
              $result = response()->json(['error'=> false,'message' =>'success'],200);
            }
            else
            {
              $result = response()->json(['error'=> true,'message' =>'Error'],400);
            }
          } catch (\Exception $e)
          {
                $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
          }
          return $result;
    }
}
