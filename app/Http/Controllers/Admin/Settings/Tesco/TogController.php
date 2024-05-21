<?php

namespace App\Http\Controllers\Admin\Settings\Tesco;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\TescoTog;

class TogController extends Controller
{
    public function tescoTog(){
      try {
          $tesco_togs = TescoTog::select('id', 'tog_value','is_enabled')->orderBy('is_enabled','desc')->get();
          return response()->json(['error'=>'false','message' =>'success', 'tesco_togs' => $tesco_togs]);
      }
      catch (\Exception $e){
          return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
      }
    }

    /**
     * Add tesco tog.
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function addTescoTog(Request $request){

        try {
            if(TescoTog::where('tog_value','ilike',$request['tog_value'])->exists()){
                $result = response()->json(['error'=> true,'message' =>'Tog value Already Exists'],208);
            }else{
                $inserted = TescoTog::create([
                        'tog_value'   => $request['tog_value'],
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
     * Get tesco tog details by ID for editing.
     * @param tesco tog id
     * @return \Illuminate\Http\Response
     */
    public function getTescoTog(Request $request){
      try {
            $id = $request['id'];
            $tesco_tog = TescoTog::where('id',$id)->select('*')->get();
            return response()->json(['error'=>'false','message' =>'success', 'tog' => $tesco_tog]);
      }
      catch (\Exception $e){
            return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
      }
    }

    /**
    * Update tesco tog by id.
    * @param tesco tog id
    * @return \Illuminate\Http\Response
    */
    public function updateTescoTog(Request $request){
      try {
          $id = $request['id'];
              $updated = TescoTog::where('id',$id)->update([
                                        'tog_value'   => $request['tog_value'],
                        ]);
            $result = $updated != ''?response()->json(['error'=> false,'message' =>'success'],200):response()->json(['error'=> true,'message' =>'Error'],400);
      } catch (\Exception $e) {
                $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
      }
      return $result;
    }

    /**
       * Make tog enabled
       * @param tog id
       * @return boolean true or false
       */
      public function enableTog(Request $request){
          try {
                $id = $request['id'];
                TescoTog::where('id',$id)->update([
                        'is_enabled'  => 1
                ]);
                $result = response()->json(['error'=> false,'message' =>'Success'],200);
          } catch (\Exception $e) {
                    $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
          }
          return $result;
      }

      /**
       * Make tog disabled
       * @return boolean true or false
       */
      public function disableTog(Request $request){
          try {
              $id = $request['id'];
              TescoTog::where('id',$id)->update([
                'is_enabled'  => 0
              ]);
              $result = response()->json(['error'=> false,'message' =>'Success'],200);
          } catch (\Exception $e) {
              $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
          }
              return $result;
      }
}
