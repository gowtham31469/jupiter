<?php

namespace App\Http\Controllers\Admin\Settings\Tesco;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\TescoWeeSymbol;
use DB;

class WeeSymbolsController extends Controller
{
    public function tescoWeeSymbols(){
      try {
          $tesco_wee_symbols = TescoWeeSymbol::select('id', 'wee_symbol','wee_symbol_text','is_enabled')->orderBy('is_enabled','desc')->get();
          return response()->json(['error'=>'false','message' =>'success', 'tesco_wee_symbols' => $tesco_wee_symbols]);
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
    public function addTescoWeeSymbols(Request $request){

      try {
        $destination_path='images/tesco/TescoWeeSymbols/';

        $wee_symbol= $request->file('wee_symbol_file');

        $wee_symbols_orname = $wee_symbol->getClientOriginalName();

        $wee_extension = $wee_symbol->getClientOriginalExtension();

        if($wee_extension === 'svg')
        {
          $wee_symbols_filename= str_random(6).'_'.preg_replace('/#[^\s]+/','',preg_replace('/\s+/', '-', $wee_symbols_orname));
        
            $wee_symbol->move($destination_path,$wee_symbols_filename);
    
  
            $path_insert=$destination_path.$wee_symbols_filename;

            $inserted = DB::table('tesco_wee_symbols')->insert([
                'wee_symbol'     =>  "/".$path_insert,
                'wee_symbol_text'     =>  $request->wee_symbol_text,
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
    public function getTescoWeeSymbol(Request $request){
      try {
            $id = $request['id'];
            $tesco_wee_symbol = TescoWeeSymbol::where('id',$id)->select('*')->get();
            return response()->json(['error'=>'false','message' =>'success', 'tesco_wee_symbol' => $tesco_wee_symbol]);
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
    public function updateTescoWeeSymbol(Request $request){
      
      try {
        $destination_path='images/tesco/TescoWeeSymbols/';

        $wee_symbol= $request->file('wee_symbol_file');

        $wee_symbols_orname = $wee_symbol->getClientOriginalName();

        $wee_extension = $wee_symbol->getClientOriginalExtension();

        if($wee_extension === 'svg')
        {
          $wee_symbols_filename= str_random(6).'_'.preg_replace('/#[^\s]+/','',preg_replace('/\s+/', '-', $wee_symbols_orname));
        
            $wee_symbol->move($destination_path,$wee_symbols_filename);
    
  
            $path_insert=$destination_path.$wee_symbols_filename;

            $updated = TescoWeeSymbol::where('id',$request->id)->update([
                'wee_symbol'     =>  "/".$path_insert,
                'wee_symbol_text'     =>  $request->wee_symbol_text,
            ]);
        }
        else   
        {
          $updated = '';
        }

        $result = $updated != ''?response()->json(['error'=> false,'message' =>'success'],200):response()->json(['error'=> true,'message' =>'Unprocessable Format'],422);

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
      public function enableWeeSymbol(Request $request){
          try {
                $id = $request['id'];
                TescoWeeSymbol::where('id',$id)->update([
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
      public function disableWeeSymbol(Request $request){
          try {
              $id = $request['id'];
              TescoWeeSymbol::where('id',$id)->update([
                'is_enabled'  => 0
              ]);
              $result = response()->json(['error'=> false,'message' =>'Success'],200);
          } catch (\Exception $e) {
              $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
          }
              return $result;
      }
}
