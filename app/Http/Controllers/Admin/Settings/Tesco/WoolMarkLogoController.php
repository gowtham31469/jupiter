<?php

namespace App\Http\Controllers\Admin\Settings\Tesco;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\TescoWoolmarkLogo;

class WoolMarkLogoController extends Controller
{
   public function tescoWoolmarkLogo(){
      try {
          $tesco_woolmark_logos = TescoWoolmarkLogo::select('id', 'woolmark_logo','woolmark_logo_text','is_enabled')->orderBy('is_enabled','desc')->get();
          return response()->json(['error'=>'false','message' =>'success', 'tesco_woolmark_logos' => $tesco_woolmark_logos]);
      }
      catch (\Exception $e){
          return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
      }
    }

    /**
     * Add tesco Components.
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function addTescoWoolmarkLogo(Request $request){

      try {
        $destination_path='images/tesco/TescoWoolmarkLogo/';

        $woolmark_logo= $request->file('woolmark_logo');

        $woolmark_logo_orname = $woolmark_logo->getClientOriginalName();

        $woolmark_logo_extension = $woolmark_logo->getClientOriginalExtension();

        if($woolmark_logo_extension === 'svg')
        {
          $woolmark_logos_filename= str_random(6).'_'.preg_replace('/#[^\s]+/','',preg_replace('/\s+/', '-', $woolmark_logo_orname));
        
            $woolmark_logo->move($destination_path,$woolmark_logos_filename);
    
  
            $path_insert=$destination_path.$woolmark_logos_filename;

            $inserted = TescoWoolmarkLogo::insert([
                'woolmark_logo'     =>  "/".$path_insert,
                'woolmark_logo_text'     =>  $request->woolmark_logo_text,
            ]);
        }
        else   
        {
          $inserted = '';
        }

        $result = $inserted != ''?response()->json(['error'=> false,'message' =>'success'],200):response()->json(['error'=> true,'message' =>'Unprocessable Format'],422);

      } catch (\Exception $e)
      {
          $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
      }
      return $result;
    }

    /**
     * Get tesco Components details by ID for editing.
     * @param tesco Components id
     * @return \Illuminate\Http\Response
     */
    public function getTescoWoolmarkLogo(Request $request){
      try {
            $id = $request['id'];
            $tesco_woolmark_log = TescoWoolmarkLogo::where('id',$id)->select('*')->get();
            return response()->json(['error'=>'false','message' =>'success', 'tesco_woolmark_log' => $tesco_woolmark_log]);
      }
      catch (\Exception $e){
            return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
      }
    }

    /**
    * Update tesco Components by id.
    * @param tesco Components id
    * @return \Illuminate\Http\Response
    */
    public function updateTescoWoolmarkLogo(Request $request){
      
      try {
        $destination_path='images/tesco/TescoWoolmarkLogo/';

        $woolmark_logo= $request->file('woolmark_logo');

        $woolmark_logo_orname = $woolmark_logo->getClientOriginalName();

        $woolmark_logo_extension = $woolmark_logo->getClientOriginalExtension();

        if($woolmark_logo_extension === 'svg')
        {
          $woolmark_logos_filename= str_random(6).'_'.preg_replace('/#[^\s]+/','',preg_replace('/\s+/', '-', $woolmark_logo_orname));
        
            $woolmark_logo->move($destination_path,$woolmark_logos_filename);
    
  
            $path_insert=$destination_path.$woolmark_logos_filename;

            $inserted = TescoWoolmarkLogo::where('id',$request->id)->update([
                'woolmark_logo'     =>  "/".$path_insert,
                'woolmark_logo_text'     =>  $request->woolmark_logo_text,
            ]);
        }
        else   
        {
          $inserted = '';
        }

        $result = $inserted != ''?response()->json(['error'=> false,'message' =>'success'],200):response()->json(['error'=> true,'message' =>'Unprocessable Format'],422);

      } catch (\Exception $e)
      {
          $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
      }
      return $result;
    }

    /**
       * Make Components enabled
       * @param Components id
       * @return boolean true or false
       */
      public function enableTescoWoolmarkLogo(Request $request){
          try {
                $id = $request['id'];
                TescoWoolmarkLogo::where('id',$id)->update([
                        'is_enabled'  => 1
                ]);
                $result = response()->json(['error'=> false,'message' =>'Success'],200);
          } catch (\Exception $e) {
                    $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
          }
          return $result;
      }

      /**
       * Make Components disabled
       * @return boolean true or false
       */
      public function disableTescoWoolmarkLogo(Request $request){
          try {
              $id = $request['id'];
              TescoWoolmarkLogo::where('id',$id)->update([
                'is_enabled'  => 0
              ]);
              $result = response()->json(['error'=> false,'message' =>'Success'],200);
          } catch (\Exception $e) {
              $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
          }
              return $result;
      }
}
