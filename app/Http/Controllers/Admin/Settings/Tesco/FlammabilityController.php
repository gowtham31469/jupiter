<?php

namespace App\Http\Controllers\Admin\Settings\Tesco;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\TescoFlammability;

class FlammabilityController extends Controller
{
    public function tescoFlammability(){
      try {
          $tesco_components = TescoFlammability::select('id', 'english','polish', 'czech', 'slovak', 'hungarian','is_enabled')->orderBy('is_enabled','desc')->get();
          return response()->json(['error'=>'false','message' =>'success', 'tesco_components' => $tesco_components]);
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
    public function addTescoFlammability(Request $request){

        try {
            if(TescoFlammability::where('english','ilike',$request['english'])->exists()){
                $result = response()->json(['error'=> true,'message' =>'Description[English] Already Exists'],208);
            }else{
                $inserted = TescoFlammability::create([
                        'english'   => $request['english'],
                        'polish'    => $request['polish'],
                        'czech'     => $request['czech'],
                        'slovak'    => $request['slovak'],
                        'hungarian' => $request['hungarian'],
                        'template_id' => $request['template_id']
                ]);
                $result = $inserted != '' ? response()->json(['error'=> false,'message' =>'success'],200):response()->json(['error'=> true,'message' =>'Error'],400);
            }

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
    public function getTescoFlammability(Request $request){
      try {
            $id = $request['id'];
            $flammability = TescoFlammability::where('id',$id)->select('*')->get();
            return response()->json(['error'=>'false','message' =>'success', 'flammability' => $flammability]);
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
    public function updateTescoFlammability(Request $request){
      try {
          $id = $request['id'];
              $updated = TescoFlammability::where('id',$id)->update([
                                        'english'   => $request['english'],
                                        'polish'    => $request['polish'],
                                        'czech'     => $request['czech'],
                                        'slovak'    => $request['slovak'],
                                        'hungarian' => $request['hungarian'],
                                        'template_id' => $request['template_id']
                        ]);
            $result = $updated != ''?response()->json(['error'=> false,'message' =>'success'],200):response()->json(['error'=> true,'message' =>'Error'],400);
      } catch (\Exception $e) {
                $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
      }
      return $result;
    }

    /**
       * Make Components enabled
       * @param Components id
       * @return boolean true or false
       */
      public function enableFlammability(Request $request){
          try {
                $id = $request['id'];
                TescoFlammability::where('id',$id)->update([
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
      public function disableFlammability(Request $request){
          try {
              $id = $request['id'];
              TescoFlammability::where('id',$id)->update([
                'is_enabled'  => 0
              ]);
              $result = response()->json(['error'=> false,'message' =>'Success'],200);
          } catch (\Exception $e) {
              $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
          }
              return $result;
      }
}
