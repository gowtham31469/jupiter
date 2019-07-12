<?php
namespace App\Classes;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Response;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use App\Customer;
use App\CustomerBillAddress;
use DB;
use Auth;
use View;
use App\RolePermissionMdl;
use Carbon\Carbon;
use App\Classes\PiTCPDF;

class ProformaInvoice
{
	/**
	 * method to get the Proforma information for a particular order
	 * @param  [type] $request [description]
	 * @return PI details
	 */
	public function getOrchestraPIDetails($request)
	{
		    $id         =   $request->id;
        $table_name =   $request->table_name;
        $uvc        =   $request->uvc;
        $buyer_id   =   $request->buyer_id;
        $pi_id      =   DB::table('profoma_invoices')->max('id');
        $today      =   Carbon::now('Asia/Kolkata');

        if ($table_name === 'orchestra_hang_tag_orders') 
        {
          $temp_id_field = "template_front_id";
        }
        else   
        {
          $temp_id_field = "template_id";
        }

        $data['company_details'] = DB::table('orchestra_item_master')
                                      ->join($table_name,$table_name.'.'.$temp_id_field,'=','orchestra_item_master.id')
                                      ->join('company','company.id','=','orchestra_item_master.company_id')
                                      ->where($table_name.'.id',$id)
                                      ->select('company.*',$table_name.'.pi_rev_no as rev_number')
                                      ->first();
                                         
        if ($data['company_details']->id == 1) 
        {
          $data['company_details']->pi_number = "JGA00".$pi_id;
        }
        else 
        {
          $data['company_details']->pi_number = "SP00".$pi_id;
        }

        $data['company_details']->pi_date = $today->toDateString();
        
        $customer_details       = DB::table($table_name)
                                  ->join('customers',$table_name.'.customer_id','=','customers.id')
                                  ->leftjoin('orchestra_template','orchestra_template.id','=',$table_name.'.'.$temp_id_field)
                                  ->join('orchestra_item_master','orchestra_item_master.id','orchestra_template.item_id')
                                  ->leftjoin('payment_terms','customers.payment_terms','=','payment_terms.id')
                                  ->leftjoin('freight_terms','customers.freight_type','=','freight_terms.id')
                                  ->where($table_name.'.id',$id)
                                  ->select('customers.*',$table_name.'.'.$temp_id_field.' as template_id',$table_name.'.po',$table_name.'.uvc','payment_terms.payment_terms','freight_terms.freight_terms','orchestra_item_master.item_name as ref_no')
                                  ->first();
        if ($customer_details)
        {
          if ($customer_details->nominated_forwarder != null && $customer_details->nominated_forwarder != 0)
          {
           $customer_details->freight_terms =  DB::table('nominated_forwarders')->where('id', '=', $customer_details->nominated_forwarder)->pluck('forwarder')->first();
          }
        }

        $data['bank_details']   = $customer_details;
        $data['bill_to']        = DB::table('customer_bill_address')
                                  ->where('customer_bill_address.customer_id',$customer_details->id)
                                  ->where('customer_bill_address.is_billing_address',1)
                                  ->select('address','country')
                                  ->first();                 
        $data['ship_to']        = DB::table('customer_bill_address')
                                  ->where('customer_bill_address.customer_id',$customer_details->id)
                                  ->where('customer_bill_address.is_shipping_address',1)
                                  ->select('address','country')
                                  ->first();
        // get the first letter of each word for a given string
        $result_str  =  str_replace("_"," ",str_replace("_orders","",$table_name));
        $qnty_id = '';
        foreach (explode(' ', $result_str) as $word)
        {
            $qnty_id .= strtolower($word[0]);
        }

        $item_details    = DB::table($table_name)
                          ->join($table_name.'_qty',$table_name.'_qty.'.$qnty_id.'_id','=',$table_name.'.id')
                          ->leftjoin('orchestra_template','orchestra_template.id','=',$table_name.'.'.$temp_id_field)
                          ->join('orchestra_item_master','orchestra_item_master.id','orchestra_template.item_id')
                          ->leftjoin('price_taxation','price_taxation.template_id','=','orchestra_item_master.id')
                          ->where('price_taxation.customer_id',$customer_details->id)
                          ->where('price_taxation.buyer_id',$buyer_id)
                          ->where('orchestra_template.id',$customer_details->template_id)
                          ->where($table_name.'_qty.'.$qnty_id.'_id',$id)
                          ->select('size',$table_name.'_qty.quantity','price_taxation.custom_name as material_name','po as po_no','orchestra_item_master.item_name as ref_no','orchestra_template.item_id','pi_rev_no','order_flag','orchestra_template.template_name','pi_status','pi_rev_no','uvc', DB::raw($customer_details->template_id.' as item_id'))
                          ->get()->toArray();

        $data['tables'][$table_name]= $table_name.'_qty';
        $data['template_id'] = $customer_details->template_id;
        if (sizeof($item_details) > 0)
        {
          $data['item_details'] = $item_details;
          $item_id_array = array();
          $price_array = array();

          array_push($item_id_array, $customer_details->template_id);

          if ($item_id_array) 
          {
            foreach (array_unique($item_id_array) as $item_id) 
            {
              $price_list      = DB::table('price_taxation')
                                      ->join('price_range','price_range.price_taxation_id','price_taxation.id')
                                      ->join('orchestra_template','orchestra_template.item_id','price_taxation.template_id')
                                      ->where('orchestra_template.id','=',$item_id)
                                      ->where('price_taxation.customer_id',$customer_details->id)
                                      ->where('price_taxation.buyer_id',$buyer_id)
                                      ->orderBy('price_range.volume_to')
                                      ->select('price_range.volume_from', 'price_range.volume_to','price_range.price',DB::raw($item_id.' as item_id'),'orchestra_template.tax')
                                      ->get()->toArray();

                $price_array = array_merge($price_array,$price_list);
            } 
            $data['price_list'] = $price_array;
          }
        }
        else
        {
          $data['price_list'] = [];
          $data['item_details'] = [];
        }

        return $data;
	}

    /**
     * method for getting all the details for tesco PI generation
     * @param  [type] $request [id]
     * @return [type]          [table_name]
     */
    public function getTescoPIDetails($request)
    {
        $id         =   $request['id'];
        $table_name =   $request['tableName'];
        $buyer_id   =   $request->buyer_id;
        $pi_id      =   DB::table('profoma_invoices')->max('id');
        $today      =   Carbon::now('Asia/Kolkata');
        
        $data['company_details'] =  DB::table('tesco_item_master')
                                          ->join($table_name,$table_name.'.template_id','=','tesco_item_master.id')
                                          ->join('company','company.id','=','tesco_item_master.company_id')
                                          ->where($table_name.'.id',$id)
                                          ->select('company.*',$table_name.'.pi_rev_no as rev_number')
                                          ->first();
                                         
        if ($data['company_details']->id == 1) {
          $data['company_details']->pi_number = "JGA00".$pi_id;
        }
        else {
          $data['company_details']->pi_number = "SP00".$pi_id;
        }
        // $data['company_details']->rev_number = 0;
        $data['company_details']->pi_date = $today->toDateString();
        
        $customer_details       = DB::table($table_name)
                                  ->join('customers',$table_name.'.tesco_supplier_id','=','customers.id')
                                  ->join('tesco_item_master','tesco_item_master.id',$table_name.'.template_id')
                                  ->where($table_name.'.is_order_count_empty',0)
                                  ->leftjoin('payment_terms','customers.payment_terms','=','payment_terms.id')
                                  ->leftjoin('freight_terms','customers.freight_type','=','freight_terms.id')
                                  ->where($table_name.'.id',$id)
                                  ->select('customers.*',$table_name.'.template_id',$table_name.'.po_number',$table_name.'.uk_style_ref',$table_name.'.ce_style_ref',$table_name.'.zone','payment_terms.payment_terms','freight_terms.freight_terms',$table_name.'.template_type','tesco_item_master.item_name as ref_no')
                                  ->first();
        if ($customer_details)
        {
          if ($customer_details->nominated_forwarder != null && $customer_details->nominated_forwarder != 0)
          {
           $customer_details->freight_terms =  DB::table('nominated_forwarders')->where('id', '=', $customer_details->nominated_forwarder)->pluck('forwarder')->first();
          }
        }
        $data['bank_details']   = $customer_details;
        $data['bill_to']        = DB::table('customer_bill_address')
                                  ->where('customer_bill_address.customer_id',$customer_details->id)
                                  ->where('customer_bill_address.is_billing_address',1)
                                  ->select('address','country')
                                  ->first();                 
        $data['ship_to']        = DB::table('customer_bill_address')
                                  ->where('customer_bill_address.customer_id',$customer_details->id)
                                  ->where('customer_bill_address.is_shipping_address',1)
                                  ->select('address','country')
                                  ->first();
        $qnty_id =  str_replace("_orders","_order_id",str_replace("tesco_","",$table_name));
        

        $item_details    = DB::table($table_name)
                          ->join($table_name.'_quanty',$table_name.'_quanty.'.$qnty_id,'=',$table_name.'.id')
                          ->join('tesco_item_master','tesco_item_master.id',$table_name.'.template_id')
                          ->leftjoin('price_taxation','price_taxation.template_id','=',$table_name.'.template_id')
                          ->leftjoin('tesco_template','tesco_template.item_id','=','price_taxation.template_id')
                          ->where('price_taxation.customer_id',$customer_details->id)
                          ->where('price_taxation.buyer_id',$buyer_id)
                          ->where('tesco_template.item_id',$request->template_id)
                          ->where($table_name.'_quanty.'.$qnty_id,$id)
                          ->select('size',$table_name.'_quanty.count as quantity','uk_style_ref','ce_style_ref','price_taxation.custom_name as material_name','po_number as po_no','zone','tesco_item_master.item_name as ref_no','tesco_template.item_id',$table_name.'.pi_rev_no','order_flag','tesco_template.template_name','pi_status','pi_rev_no')
                          ->get()->toArray();
        if ($item_details){
          foreach ($item_details as $key => $value) {
           if ($value->zone == "CE") {
              $value->style = $value->ce_style_ref;
           }
           else{
              $value->style = $value->uk_style_ref;
           }
          }
          $data['tables'][$table_name]= $table_name.'_quanty';
          $item_id_array = array();
          $price_array = array();
          $templates = array();
          array_push($item_id_array, $request->template_id);
          if ($item_details[0]->order_flag!="reOrder" && $item_details[0]->order_flag!="repeatOrder"){
              if ($table_name == "tesco_tcl1_orders" || $table_name == "tesco_tcl2_orders" || $table_name == "tesco_tsl1_orders") {
                $table_array = array('tesco_tcl1_orders','tesco_tcl2_orders','tesco_tsl1_orders');
                $qnty_id_array = array('tesco_tcl1_orders_quanty','tesco_tcl2_orders_quanty','tesco_tsl1_orders_quanty');
                $template_array = array_combine($table_array, $qnty_id_array);
                $count = 3;

              }
              if ($table_name == "tesco_tcl4_orders" || $table_name == "tesco_tsl3_orders") {
                $table_array = array('tesco_tcl4_orders','tesco_tsl3_orders');
                $qnty_id_array = array('tesco_tcl4_orders_quanty','tesco_tsl3_orders_quanty');
                $template_array = array_combine($table_array, $qnty_id_array);
                $count = 2;
              }
              if (!empty($template_array)) {
                  unset($template_array[$table_name]);
                  $data['tables']  = array_merge($data['tables'],$template_array);

                  foreach ($template_array as $key => $qunty_tbl) {
                  $qnty_id =  str_replace("_orders","_order_id",str_replace("tesco_","",$key));
                  if ($item_details[0]->zone =="CE") {
                    $item_details2    = DB::table($key)
                                  ->join($qunty_tbl,$qunty_tbl.'.'.$qnty_id,'=',$key.'.id')
                                  ->join('tesco_item_master','tesco_item_master.id',$key.'.template_id')
                                  ->leftjoin('price_taxation','price_taxation.template_id','=',$key.'.template_id')
                                  ->leftjoin('tesco_template','tesco_template.item_id','=','price_taxation.template_id')
                                  ->where($key.'.is_order_count_empty',0)
                                  ->where('price_taxation.customer_id',$customer_details->id)
                                  ->where('price_taxation.buyer_id',$buyer_id)
                                  ->where($key.'.po_number',$item_details[0]->po_no)
                                  ->where('ce_style_ref',$item_details[0]->style)
                                  ->where($key.'.pi_status',$item_details[0]->pi_status)
                                  ->where($key.'.order_flag',"newOrder")
                                  ->select($key.'.id','size',$qunty_tbl.'.count as quantity','uk_style_ref','ce_style_ref','price_taxation.custom_name as material_name','po_number as po_no','zone','tesco_item_master.item_name as ref_no','tesco_template.item_id',$key.'.pi_rev_no','tesco_template.template_name','pi_rev_no')->get()->toArray();

                  }
                  else{
                    
                    $item_details2    = DB::table($key)
                                  ->join($qunty_tbl,$qunty_tbl.'.'.$qnty_id,'=',$key.'.id')
                                  ->join('tesco_item_master','tesco_item_master.id',$key.'.template_id')
                                  ->leftjoin('price_taxation','price_taxation.template_id','=',$key.'.template_id')
                                  ->leftjoin('tesco_template','tesco_template.item_id','=','price_taxation.template_id')
                                  ->where($key.'.is_order_count_empty',0)
                                  ->where('price_taxation.customer_id',$customer_details->id)
                                  ->where('price_taxation.buyer_id',$buyer_id)
                                  ->where($key.'.po_number',$item_details[0]->po_no)
                                  ->where($key.'.pi_status',$item_details[0]->pi_status)
                                  ->where('uk_style_ref',$item_details[0]->style)
                                  ->where($key.'.order_flag',"newOrder")
                                  ->select($key.'.id','size',$qunty_tbl.'.count as quantity','uk_style_ref','ce_style_ref','price_taxation.custom_name as material_name','po_number as po_no','zone','tesco_item_master.item_name as ref_no','tesco_template.item_id',$key.'.pi_rev_no','tesco_template.template_name','pi_rev_no')->get()->toArray();
                  }
                  if ($item_details2) {
                    $item_details = array_merge($item_details,$item_details2);
                  }
                }
                foreach ($item_details as $key => $value) {
                   if ($value->zone == "CE") {
                      $value->style = $value->ce_style_ref;
                   }
                   else{
                      $value->style = $value->uk_style_ref;
                   }
                   array_push($item_id_array, $value->item_id);
                }
                // if ($count == sizeof($item_details)) {
                //   $data['item_details'] = $item_details;
                // }
                // else{
                //   $data['item_details'] = [];
                // }
                $data['item_details'] = $item_details;
              }
          }  

          if ($item_id_array) {
            foreach (array_unique($item_id_array) as $item_id) {
             $price_list      = DB::table('price_taxation')
                                    ->join('price_range','price_range.price_taxation_id','price_taxation.id')
                                    ->join('tesco_template','tesco_template.item_id','price_taxation.template_id')
                                    // ->join('tax_code','tesco_template.tax_code_id','tax_code.id')
                                    ->where('price_taxation.template_id','=',$item_id)
                                    ->where('price_taxation.customer_id',$customer_details->id)
                                    ->where('price_taxation.buyer_id',$buyer_id)
                                    ->orderBy('price_range.volume_to')
                                    ->select('price_range.volume_from', 'price_range.volume_to','price_range.price',DB::raw($item_id.' as item_id'),'tesco_template.tax')
                                    ->get()->toArray();
              $price_array = array_merge($price_array,$price_list);
            } 
          $data['price_list'] = $price_array;
          }
          if (empty($template_array) && sizeof($item_details)!=0) {
            $data['item_details'] = $item_details;
          }
        }
        else{
          $data['price_list'] = [];
          $data['item_details'] = [];
        }
        
        

        return $data;
       
    }  

    /**
     * Get approved tesco PI Data
     *
     * @param [type] $request
     * @return void
     */
    public function getApprovedTescoPI($request)
    {
        
        $table_id       = $request->id;
        $template_name  = $request->template_name;
        $order_flag     = $request->order_flag;
        $pi_status      = $request->pi_status;
        $buyer_id       = $request->buyer_id;
        $pi_status      = $request->pi_status;
        $style_no       = $request->style_no;
        $template_id    = $request->template_id;
        $buyer_po       = $request->buyer_po;
        if ($order_flag == "reOrder") {
           $rep_order_id   =  str_replace(' ', '', $template_name)."_".$table_id;
           $pi_details = DB::table('profoma_invoices')
            ->where('buyer_id',$buyer_id)
            ->where('uvc_style_no',$style_no)
            ->where('buyer_po_no',$buyer_po)
            ->where('rep_order_id',$rep_order_id)
            ->select('profoma_invoices.*',DB::raw("to_char(profoma_invoices.created_at,'DD-MM-YYYY') as pi_date"))
            ->first();
        }
        else{
          $pi_details = DB::table('profoma_invoices')
            ->where('buyer_id',$buyer_id)
            ->where('uvc_style_no',$style_no)
            ->where('buyer_po_no',$buyer_po)
            ->select('profoma_invoices.*',DB::raw("to_char(profoma_invoices.created_at,'DD-MM-YYYY') as pi_date"))
            ->first();
        }
               
        $data['company_details'] =  DB::table('tesco_item_master')
                                       ->join('company','company.id','=','tesco_item_master.company_id')
                                       ->where('tesco_item_master.id',$pi_details->company_id)
                                       ->select('company.*')
                                       ->first();

        if ($pi_details->company_id == 1)
        {
          $data['company_details']->pi_number = "JGA00".$pi_details->id;
        }
        else{
         $data['company_details']->pi_number = "SP00".$pi_details->id;
        }
        $data['company_details']->rev_number = $pi_details->revision_no;
        
        $data['company_details']->pi_date    = $pi_details->pi_date;
        $customer_details       = DB::table('customers')
                                  ->join('payment_terms','customers.payment_terms','=','payment_terms.id')
                                  ->join('freight_terms','customers.freight_type','=','freight_terms.id')
                                  ->where('customers.id',$pi_details->customer_id)
                                  ->select('customers.*','payment_terms.payment_terms','freight_terms.freight_terms')
                                  ->first();

        if ($customer_details)
        {
          if ($customer_details->nominated_forwarder!=null)
          {
           $customer_details->freight_terms =  DB::table('nominated_forwarders')->pluck('forwarder')->first();
           // $customer_details['template_type'] = $pi_details->template_type;
          }
        }                        
        $data['bank_details']   = $customer_details;
        $data['bank_details']->template_type = $pi_details->template_type;
        $data['bill_to']        = DB::table('customer_bill_address')
                                  ->where('customer_bill_address.customer_id',$customer_details->id)
                                  ->where('customer_bill_address.is_billing_address',1)
                                  ->select('address','country')
                                  ->first();                 
        $data['ship_to']        = DB::table('customer_bill_address')
                                  ->where('customer_bill_address.customer_id',$customer_details->id)
                                  ->where('customer_bill_address.is_shipping_address',1)
                                  ->select('address','country')
                                  ->first();
        $data['item_details']    = DB::table('profoma_invoice_item_details')
                                    ->where('profoma_invoice_item_details.pi_id',$pi_details->id)
                                    ->select('size','quantity','style','material as material_name','unit_cost as unit_price','profoma_invoice_item_details.tax','item_name as as ref_no','profoma_invoice_item_details.template_id as item_id')
                                    ->get();
        foreach ($data['item_details'] as $key => $value) {
          $value->style = $pi_details->uvc_style_no;
          $value->po_no = $pi_details->buyer_po_no;
        }
        $data['price_list'] = array();
        foreach ($data['item_details'] as $key => $value) {
          array_push($data['price_list'], ['volume_to' => $value->quantity, 'price' => $value->unit_price,'tax' => $value->tax ,'item_id'=>$value->item_id]);
          
        }
        return $data;

    }

    /**
     * Generate Tesco PI PDF using TCPDF
     *
     * @param [type] $data
     * @param [type] $request
     * @return void
     */
    public function getTescoAttachment($data,$request){
      // pi name based on company
      $pi_max_id = DB::table('profoma_invoices')->max('id')+1;
      if($data['company_details']->name === 'Jupiter Garments Accessories')
      {
        $pi_name = 'JGA-PI-'.$pi_max_id;
      }
      else 
      {
        $pi_name = 'SP-PI-'.$pi_max_id;
      }
      $dynView = View::make('Admin.Order Management.pi-pdf-template')
                ->with(['company_details' => $data['company_details'], 'bank_details' => $data['bank_details'], 'ship_to'=>$data['ship_to'],'bill_to'=>$data['bill_to'],'item_details'=>$data['item_details'],'price_list'=> $data['price_list'], 'amtText'=>$request->amntText ]);
      ob_start();
      $pdf = new PiTCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
      $pdf->setCompanyData($data['company_details']);
      //margins
      $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
      $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
      $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
      // set header and footer fonts
      $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
      $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
      // set auto page breaks
      $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_TOP);

      // set image scale factor
      $pdf->setImageScale(0.8);

      // set font
      $pdf->SetFont('helvetica', '', 8);

      // add a page
      $pdf->AddPage('P', 'A4');
      $pdf->SetXY(14, 35);
      $pdf->writeHTML($dynView->render());

      $pdfdata =$pdf->output( public_path('storage/app/public/pi/'.$pi_name.'.pdf'), 'F');

      $attatchment =  public_path('storage/app/public/pi/'.$pi_name.'.pdf');
      return "/storage/app/public/pi/".$pi_name.".pdf";
    }

    /**
     * Generate Tesco PI PDF using TCPDF
     *
     * @param [type] $data
     * @param [type] $request
     * @return void
     */
    public function getOrchestraAttachment($data,$request){
      // pi name based on company
      $pi_max_id = DB::table('profoma_invoices')->max('id')+1;
      if($data['company_details']->name === 'Jupiter Garments Accessories')
      {
        $pi_name = 'JGA-PI-'.$pi_max_id;
      }
      else 
      {
        $pi_name = 'SP-PI-'.$pi_max_id;
      }
      $dynView = View::make('Admin.Order Management.pi-pdf-template-orchestra')
                ->with(['company_details' => $data['company_details'], 'bank_details' => $data['bank_details'], 'ship_to'=>$data['ship_to'],'bill_to'=>$data['bill_to'],'item_details'=>$data['item_details'],'price_list'=> $data['price_list'], 'amtText'=>$request->amntText ]);
      ob_start();
      $pdf = new PiTCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
      $pdf->setCompanyData($data['company_details']);
      //margins
      $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
      $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
      $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
      // set header and footer fonts
      $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
      $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
      // set auto page breaks
      $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_TOP);

      // set image scale factor
      $pdf->setImageScale(0.8);

      // set font
      $pdf->SetFont('helvetica', '', 8);

      // add a page
      $pdf->AddPage('P', 'A4');
      $pdf->SetXY(14, 35);
      $pdf->writeHTML($dynView->render());

      $pdfdata =$pdf->output( public_path('storage/app/public/pi/'.$pi_name.'.pdf'), 'F');

      $attatchment =  public_path('storage/app/public/pi/'.$pi_name.'.pdf');
      return "/storage/app/public/pi/".$pi_name.".pdf";
    }
}
