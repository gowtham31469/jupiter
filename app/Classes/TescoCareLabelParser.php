<?php
namespace App\Classes;
use Illuminate\Http\Request;
use Response;
use Config;
/**
 * Parse Tesco Care Label Purchase Order
 * Zone can  be UK or CE
 */
class TescoCareLabelParser
{
    /**
     * Method to create tesco order for UK zone from purchase order using Tabula-Java
     * @return \Illuminate\Http\Response
     */
    public function createOrderForUK($request) {
        try {
          //Check the file has been uploaded or not
          if($request->hasFile('purchase_order')) {
            $file = $request->file('purchase_order');
            $name = preg_replace("/[^A-Za-z0-9\_\-\.]/", '', $file->getClientOriginalName());
            $path = $file->move('storage/app/TESCO/purchaseOrder/UK', time().'_'.$name);
            //Read the Purchase Order Series
            $po_number = $this->getPOSeries($path);
            $po_series = explode('-', $po_number);
            if(count($po_series) < 2) {
              return response()->json(['error' => 'Unknown Purchase Order for UK'], 400);
            }
            $po_series = $po_series[0];
            //Check the type of PO
            $po_type = $this->checkPOType($path);
            $arr = explode(' ',trim($po_type));
            $po_type = strtolower($arr[0]);
            
            switch ($po_type) {
              case 'single':
                $data = $this->cropPDF($path, 'single', 'UK');
                break;
              case 'ratio':
                //Check for Ratio Pack B
                $packB = $this->checkPackB($path , 'UK');
                $data  = $this->cropPDF($path, 'ratio', 'UK');
                if($packB) {
                  $data['ratioPackB'] = $packB;
                }
                break;
              default:
                return response()->json(['error' => 'Unknown PO Type'], 400);
                break;
            }
            $data['supplier'] = $this->getSupplierInfo($path);  
            return response()->json(['details' =>$data, 'type' => $po_type, 'po_number' => $po_number], 200);
          } else {
            return response()->json(['error' => 'File not found'], 400);
          }
        } catch (\Exception $e) {
          return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
      }


    /**
     * Method to create tesco order for CE zone from purchase order using Tabula-Java
     * @return \Illuminate\Http\Response
     */
    public function createOrderForCE($request) {
        try {
  
            $output = array();
            $orms_data = array();
            $retek_data = array();
            $template_type = 'TCL';
            //Check the files has been uploaded or not
            if($request->hasFile('ce_orms_purchase_order') && $request->hasFile('ce_retek_purchase_order')) {
              //Move the orms file
              $orms = $request->file('ce_orms_purchase_order');
              $orms_filename = preg_replace("/[^A-Za-z0-9\_\-\.]/", '', $orms->getClientOriginalName());
              $orms_path = $orms->move('storage/app/TESCO/purchaseOrder/CE/ORMS', time().'_'.$orms_filename);
  
              //Move the retek file
              $retek = $request->file('ce_retek_purchase_order');
              $retek_filename = preg_replace("/[^A-Za-z0-9\_\-\.]/", '', $retek->getClientOriginalName());
              $retek_path = $retek->move('storage/app/TESCO/purchaseOrder/CE/RETEK', time().'_'.$retek_filename);
  
              //Parse ORMS File
              if(!$this->parseORMS($orms_path)) {
                return response()->json(['error' => 'Unrecognized ORMS file', 'path' => $orms_path], 400);
              }
              list($orms_no, $retek_no)  = $this->parseORMS($orms_path);
              $orms_data['order_number'] = trim($orms_no);
              $orms_data['retek_number'] = trim($retek_no);
              //Check the RETEK purchase order validation
              //Check the purchase order type whether it is Single or Ratio.
              $retek_data['retek_po_number'] = trim($this->getPOSeries($retek_path));
              //Compare RETEK PO number from both the sheets
              if($retek_data['retek_po_number'] !== $orms_data['retek_number']) {
                return response()->json(['error' => 'RETEK Purchase Order Number Mismatch'], 400);
              }
              $po_type   = $this->checkPOType($retek_path);
              $arr = explode(' ',trim($po_type));
              $retek_data['retek_po_type'] = strtolower($arr[0]);
  
              //Check for Packing Method to identify whether it is TCL or THL
              //If Packing Method is 'HANGING' then it is THL
              //If Packing Method is 'BOX' or something like that then it is TCL
              $isTHL = $this->checkPackingMethod($retek_path);
              if(gettype($isTHL) !== 'boolean') {
                return response()->json(['error' => $isTHL], 400);
              } else if($isTHL){
                $template_type = 'THL';
              }
  
              switch ($retek_data['retek_po_type']) {
                case 'single':
                  $orms_table = $this->parseORMSTable($orms_path, 'single');
                  $orms_data['order_table']  = $orms_table;
                  $retek_single_data = $this->cropPDF($retek_path, 'single', 'CE');
                  $retek_data['info'] = $retek_single_data['info'];
                  $retek_data['table'] = $retek_single_data['table'];
                  break;
                case 'ratio':
                  $orms_table = $this->parseORMSTable($orms_path, 'ratio');
                  $orms_data['order_table']  = $orms_table;
                  //$packB = $this->checkPackB($retek_path, 'CE');//Check for Ratio Pack B
                  $ratio_table          = $this->extractCERatioTable($retek_path, 'CE');//Check for Ratio Pack B
                  // $retek_ratio_data     = $this->cropPDF($retek_path, 'ratio', 'CE');
                  $retek_ratio_data     = $this->cropVerticalPDF($retek_path, 'ratio', 'CE');
                  $retek_data['info']   = json_encode($retek_ratio_data['info']);
                  //$retek_data['table']  = $retek_ratio_data['table'];
                  $retek_data['ratioPackA']  = json_encode($ratio_table[0]);
                  //Parse Ratio Pack B
                  //Not Required for CE
                  // if(count($ratio_table) > 1) {
                  //   $retek_data['ratioPackB'] = json_encode($ratio_table[1]);
                  // }
                  break;
                default:
                  return response()->json(['error' => 'Unknown PO Type'], 400);
                  break;
              }
  
              $retek_data['supplier'] = $this->getSupplierInfo($retek_path);
              $output['orms'] = $orms_data;
              $output['retek'] = $retek_data;
              return response()->json(['details' =>$output], 200);
            
            } else {
              return response()->json(['error' => 'File not found'], 400);
            }
          } catch (\Exception $e) {
            return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
          }
    }
  
      /**
       * Method to parse the CE type purchase order
       * @param  [string] $file PDF file path.
       * @return [array]  ORMS PO parsed data
       */
      public function parseORMS($file) {
        //Read the tabula configuration from config/tabula.php.
          $tabula       = Config::get('tabula.tabula_jar');
          $orms_number  = Config::get('tabula.orms_number');
          $ce_orms_no   = json_decode(shell_exec('java -jar '.$tabula.$orms_number.$file), true);
          if (strpos($ce_orms_no[0]['data'][0][0]['text'], 'ORMS ORDER NUMBER:') === false) {
              return false;
          }
          $orms_po_number   = $ce_orms_no[0]['data'][0][0]['text'];
          $retek_po_number  = $ce_orms_no[0]['data'][1][0]['text'];
          $ce_orms_no       = explode(':', $orms_po_number);
          $ce_retek_no      = explode(':', $retek_po_number);
          return array($ce_orms_no[1], $ce_retek_no[1]);
      }
  
      /**
       * Method to parse the CE type purchase order
       * @param  [string] $file PDF file path.
       * @return [array]  ORMS PO parsed data
       */
      public function parseORMSTable($file, $type) {
        //Read the tabula configuration from config/tabula.php.
          $tabula       = Config::get('tabula.tabula_jar');
          $orms_table   = Config::get('tabula.orms_table_'.$type);
          $ce_orms_table    = json_decode(shell_exec('java -jar '.$tabula.$orms_table.$file), true);
          return $ce_orms_table[0]['data'];
      }
  
      /**
       * Method to parse the packing method to determine is it THL or TCL
       * @param  [string] $file PDF file path.
       * @return [array]  ORMS PO parsed data
       */
      public function checkPackingMethod($file) {
        //Read the tabula configuration from config/tabula.php.
          $tabula           = Config::get('tabula.tabula_jar');
          $packing_method   = Config::get('tabula.packing_method');
          $info_col_3      = json_decode(shell_exec('java -jar '.$tabula.$packing_method.$file), true);
          foreach ($info_col_3[0]['data'] as $key => $value) {
            foreach ($value as $i => $val) {
              if(strpos($val['text'], 'Packaging Method :') !== false) {
                $method = explode(':', $value[1]['text']);
                $method = (count($method) === 1) ? $value[$i+1]['text'] : trim($method[1]);
                if($method === 'HANGING') {
                  return true;
                } else {
                  return false;
                }
              }
            }
  
          }
          return 'Unable to process the packing method';
      }
  
  
      /**
       * Method to find the type of P.O uploaded.
       * @param  [string] $file PDF file path.
       * @return [string]       Type of the PDF
       */
      public function checkPOType($file) {
        //Read the tabula configuration from config/tabula.php.
          $tabula  = Config::get('tabula.tabula_jar');
          $po_type = Config::get('tabula.po_type');
          $type    = shell_exec('java -jar '.$tabula.$po_type.$file);
          $type    = json_decode($type, true);
          $type    = $type[0]['data'][0][0]['text'];
          return $type;
      }
  
      /**
       * Method to find the series of P.O uploaded.
       * @param  [string] $file PDF file path.
       * @return [string]       Type of the PDF
       */
      public function getPOSeries($file) {
        //Read the tabula configuration from config/tabula.php.
          $tabula    = Config::get('tabula.tabula_jar');
          $po_series = Config::get('tabula.po_series');
          $series    = shell_exec('java -jar '.$tabula.$po_series.$file);
          $series    = json_decode($series, true);
          $series    = $series[0]['data'][0][0]['text'];
          return $series;
      }
  
      /**
       * Method to get the appropriate dimensions from the PDF
       * @param  [path] $file File to be cropped
       * @param  [str]  $type Purchase Order type
       * @param  [str]  $zone Template Zone CE/UK
       * @return [str]       JSON extracted string
       */
      public function cropPDF($file, $type, $zone) {
        //Read the tabula configuration from config/tabula.php.
        $tabula      = Config::get('tabula.tabula_jar');
        $quantity    = Config::get('tabula.'.$zone.'_'.$type.'_quantity');
        $information = Config::get('tabula.'.$zone.'_'.$type.'_info');
        //Execute tabula method to extract the quantity table.
        $info    = shell_exec('java -jar '.$tabula.$information.$file);
        $table   = shell_exec('java -jar '.$tabula.$quantity.$file);
        return ['info' => $info, 'table' => $table];
      }
  
      /**
       * Method to get the info section from the PDF
       * @param  [path] $file File to be cropped
       * @param  [str]  $type Purchase Order type
       * @param  [str]  $zone Template Zone CE/UK
       * @return [str]  JSON extracted string
       */
      public function cropVerticalPDF($file, $type, $zone) {
        //Read the tabula configuration from config/tabula.php.
        $tabula      = Config::get('tabula.tabula_jar');
        $column_1    = Config::get('tabula.'.$zone.'_'.$type.'_info_col_1');
        $column_2    = Config::get('tabula.'.$zone.'_'.$type.'_info_col_2');
        //Execute tabula method to extract the quantity table.
        $info1    = shell_exec('java -jar '.$tabula.$column_1.$file);
        $info2    = shell_exec('java -jar '.$tabula.$column_2.$file);
        $info1    = json_decode( preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $info1), true);
        $info2    = json_decode( preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $info2), true);
        foreach ($info2[0]['data'] as $key => $value) {
          if($value[0]['text'] === 'Season Code :') {
              array_push($info1[0]['data'], $value);
          }
        }
        return ['info' => $info1];
      }
  
      /**
       * Method to check Ratio Pack B exist or not
       * @param  [path] $file File to be cropped
       * @return [str]       JSON extracted string
       */
      public function checkPackB($file, $zone) {
        //Read the tabula configuration from config/tabula.php.
        $tabula      = Config::get('tabula.tabula_jar');
        $check_packB = Config::get('tabula.'.$zone.'_check_packB');
        //Execute tabula method to extract the quantity table.
        $pack_b = shell_exec('java -jar '.$tabula.$check_packB.$file);
        $pack_b = json_decode( preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $pack_b), true);
        foreach ($pack_b as $key => $value) {
          if(count($value['data']) < 2) {
            unset($pack_b[$key]);
          }
        }
        $pack_b = array_values($pack_b); // 'reindex' array
        if(count($pack_b) > 1) {
          //return the ratio pack B row
          return json_encode($pack_b[1]['data'][5]);
        } else {
          return false;
        }
      }
  
      /**
       * Method to extract Ratio table from Page 2 for CE
       * @param  [path] $file File to be cropped
       * @return [str]       JSON extracted string
       */
      public function extractCERatioTable($file, $zone) {
        //Read the tabula configuration from config/tabula.php.
        $tabula      = Config::get('tabula.tabula_jar');
        $check_packB = Config::get('tabula.'.$zone.'_check_packB');
        //Execute tabula method to extract the quantity table.
        $pack_b = shell_exec('java -jar '.$tabula.$check_packB.$file);
        $pack_b = json_decode( preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $pack_b), true);
        $ratio_arr = array();
        foreach ($pack_b as $key => $data_arr) {
          foreach ($data_arr['data'] as $i => $val) {
            if($val[0]['text'] === 'Ratio Pack Description') {
              array_push($ratio_arr, $data_arr['data'][$i+1]);
            }
          }
        }
        if(count($ratio_arr) > 0) {
          return $ratio_arr;
        } else {
          return false;
        }
      }
  
      /**
       * Method to get the supplier number and name.
       * @param  [path] $file File to be cropped
       * @return [str]       JSON extracted string
       */
      public function getSupplierInfo($file) {
        //Read the tabula configuration from config/tabula.php.
        $tabula      = Config::get('tabula.tabula_jar');
        $sup_info    = Config::get('tabula.supplier_info');
        //Execute tabula method to extract the seller section.
        $sup_info = shell_exec('java -jar '.$tabula.$sup_info.$file);
        return $sup_info;
      }
  

}