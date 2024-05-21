<?php

namespace App\Http\Controllers\Admin\Settings\Tesco;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\TescoGarmentStatement;

class GarmentStatementController extends Controller
{
    public function tescoGarmentStatement(){
      try {
          $tesco_garment_statement = TescoGarmentStatement::select('id', 'english','polish', 'czech', 'slovak', 'hungarian','is_enabled')->orderBy('is_enabled','desc')->get();
          return response()->json(['error'=>'false','message' =>'success', 'tesco_garment_statement' => $tesco_garment_statement]);
      }
      catch (\Exception $e){
          return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
      }
    }

    /**
     * Add tesco garment statement.
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function addTescoGarmentStatement(Request $request){

        try {
            if(TescoGarmentStatement::where('english','ilike',$request['english'])->exists()){
                $result = response()->json(['error'=> true,'message' =>'Description[English] Already Exists'],208);
            }else{
                $inserted = TescoGarmentStatement::create([
                        'english'   => $request['english'],
                        'polish'    => $request['polish'],
                        'czech'     => $request['czech'],
                        'slovak'    => $request['slovak'],
                        'hungarian' => $request['hungarian'],
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
     * Get tesco garment statement details by ID for editing.
     * @param tesco garment statement id
     * @return \Illuminate\Http\Response
     */
    public function getTescoGarmentStatement(Request $request){
      try {
            $id = $request['id'];
            $garment_statement = TescoGarmentStatement::where('id',$id)->select('*')->get();
            return response()->json(['error'=>'false','message' =>'success', 'garment_statement' => $garment_statement]);
      }
      catch (\Exception $e){
            return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
      }
    }

    /**
    * Update tesco garment statement by id.
    * @param tesco garment statement id
    * @return \Illuminate\Http\Response
    */
    public function updateTescoGarmentStatement(Request $request){
      try {
          $id = $request['id'];
              $updated = TescoGarmentStatement::where('id',$id)->update([
                                        'english'   => $request['english'],
                                        'polish'    => $request['polish'],
                                        'czech'     => $request['czech'],
                                        'slovak'    => $request['slovak'],
                                        'hungarian' => $request['hungarian'],
                        ]);
            $result = $updated != ''?response()->json(['error'=> false,'message' =>'success'],200):response()->json(['error'=> true,'message' =>'Error'],400);
      } catch (\Exception $e) {
                $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
      }
      return $result;
    }

    /**
       * Make garment statement enabled
       * @param garment statement id
       * @return boolean true or false
       */
      public function enableGarmentStatement(Request $request){
          try {
                $id = $request['id'];
                TescoGarmentStatement::where('id',$id)->update([
                        'is_enabled'  => 1
                ]);
                $result = response()->json(['error'=> false,'message' =>'Success'],200);
          } catch (\Exception $e) {
                    $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
          }
          return $result;
      }

      /**
       * Make garment statement disabled
       * @return boolean true or false
       */
      public function disableGarmentStatement(Request $request){
          try {
              $id = $request['id'];
              TescoGarmentStatement::where('id',$id)->update([
                'is_enabled'  => 0
              ]);
              $result = response()->json(['error'=> false,'message' =>'Success'],200);
          } catch (\Exception $e) {
              $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
          }
              return $result;
      }
}
