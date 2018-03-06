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
use App\RolePermissionMdl;
use Carbon\Carbon;

class ProfomaInvoice
{
	/**
	 * method to the Profoma information for a particular order
	 * @param  [type] $request [description]
	 * @return PI details
	 */
	public function getPIDetails($request)
	{
		    $id         =   $request->id;
        $table_name =   $request->table_name;
        $uvc        =   $request->uvc;
        $buyer_id   =   $request->buyer_id;

        $data['company_details'] =  DB::table('orchestra_item_master')
                                          ->join('orchestra_hang_tag_orders','orchestra_hang_tag_orders.template_front_id','=','orchestra_item_master.id')
                                          ->join('company','company.id','=','orchestra_item_master.company_id')
                                          ->where('orchestra_hang_tag_orders.id',$id)
                                          ->select('company.*')
                                          ->first();

        $customer_details = DB::table('orchestra_item_details')
                          ->join('customers','orchestra_item_details.FactoryCode','=','customers.factory_code')
                          ->leftjoin('payment_terms','customers.payment_terms','=','payment_terms.id')
                          ->leftjoin('freight_terms','customers.freight_type','=','freight_terms.id')
                          ->where('orchestra_item_details.UVC',$uvc)
                          ->select('customers.*','payment_terms.payment_terms','freight_terms.freight_terms')
                          ->first();
        $data['bank_details'] =  $customer_details;
        $data['bill_to'] = DB::table('customer_bill_address')
                          ->where('customer_bill_address.customer_id',$customer_details->id)
                          ->where('customer_bill_address.is_billing_address',1)
                          ->select('address','country')
                          ->first();                 
        $data['ship_to'] = DB::table('customer_bill_address')
                          ->where('customer_bill_address.customer_id',$customer_details->id)
                          ->where('customer_bill_address.is_shipping_address',1)
                          ->select('address','country')
                          ->first();
        switch ($table_name)
        {
            case 'orchestra_hang_tag_orders':
               
               $data['item_details']    = DB::table('orchestra_hang_tag_orders')
                                            ->join('orchestra_hang_tag_orders_qty','orchestra_hang_tag_orders_qty.oht_id','=','orchestra_hang_tag_orders.id')
                                            ->leftjoin('price_taxation','price_taxation.template_id','=','orchestra_hang_tag_orders.template_front_id')
                                            ->where('price_taxation.customer_id',$customer_details->id)
                                            ->where('price_taxation.buyer_id',$buyer_id)
                                            ->where('orchestra_hang_tag_orders_qty.oht_id',$id)
                                            ->select('size','quantity','orchestra_hang_tag_orders.uvc as style','price_taxation.custom_name as material_name','price_taxation.price as unit_price','price_taxation.tax')
                                            ->get();
               
                break;
            case 'orchestra_main_label_orders':
                $data['item_details']    = DB::table('orchestra_main_label_orders')
                                            ->join('orchestra_main_label_orders_qty','orchestra_main_label_orders_qty.oml_id','=','orchestra_main_label_orders.id')
                                            ->leftjoin('price_taxation','price_taxation.template_id','=','orchestra_main_label_orders.template_id')
                                            ->where('price_taxation.customer_id',$customer_details->id)
                                            ->where('price_taxation.buyer_id',$buyer_id)
                                            ->where('orchestra_main_label_orders_qty.oml_id',$id)
                                            ->select('size','quantity','orchestra_main_label_orders.uvc as style','price_taxation.custom_name as material_name','price_taxation.price as unit_price','price_taxation.tax')
                                            ->get();        
                break;
            case 'orchestra_solid_carton_sticker_orders':
                $data['item_details']    = DB::table('orchestra_solid_carton_sticker_orders')
                                            ->join('orchestra_solid_carton_sticker_orders_qty','orchestra_solid_carton_sticker_orders_qty.oscs_id','=','orchestra_solid_carton_sticker_orders.id')
                                            ->leftjoin('price_taxation','price_taxation.template_id','=','orchestra_solid_carton_sticker_orders.template_id')
                                            ->where('price_taxation.customer_id',$customer_details->id)
                                            ->where('price_taxation.buyer_id',$buyer_id)
                                            ->where('orchestra_solid_carton_sticker_orders_qty.oscs_id',$id)
                                            ->select('size','quantity','orchestra_solid_carton_sticker_orders.uvc as style','price_taxation.custom_name as material_name','price_taxation.price as unit_price','price_taxation.tax')
                                            ->get(); 
                break;
            case 'orchestra_solid_poly_bag_sticker_orders':
                $data['item_details']    = DB::table('orchestra_solid_poly_bag_sticker_orders')
                                            ->join('orchestra_solid_poly_bag_sticker_order_qty','orchestra_solid_poly_bag_sticker_order_qty.ospbs_id','=','orchestra_solid_poly_bag_sticker_orders.id')
                                            ->leftjoin('price_taxation','price_taxation.template_id','=','orchestra_solid_poly_bag_sticker_orders.template_id')
                                            ->where('price_taxation.customer_id',$customer_details->id)
                                            ->where('price_taxation.buyer_id',$buyer_id)
                                            ->where('orchestra_solid_poly_bag_sticker_order_qty.ospbs_id',$id)
                                            ->select('size','quantity','orchestra_solid_poly_bag_sticker_orders.uvc as style','price_taxation.custom_name as material_name','price_taxation.price as unit_price','price_taxation.tax')
                                            ->get(); 
                break;
            case 'orchestra_standard_carton_sticker_orders':
                $data['item_details']    = DB::table('orchestra_standard_carton_sticker_orders')
                                            ->leftjoin('price_taxation','price_taxation.template_id','=','orchestra_standard_carton_sticker_orders.template_id')
                                            ->where('price_taxation.customer_id',$customer_details->id)
                                            ->where('price_taxation.buyer_id',$buyer_id)
                                            ->where('orchestra_standard_carton_sticker_orders.id',$id)
                                            ->select(DB::raw('"-" as size'),'final_order_qty as quantity','orchestra_standard_carton_sticker_orders.uvc as style','price_taxation.custom_name as material_name','price_taxation.price as unit_price','price_taxation.tax')
                                            ->get();
                break;
            case 'orchestra_standard_polybag_sticker_orders':
                $data['item_details']    = DB::table('orchestra_standard_polybag_sticker_orders')
                                            ->leftjoin('price_taxation','price_taxation.template_id','=','orchestra_standard_polybag_sticker_orders.template_id')
                                            ->where('price_taxation.customer_id',$customer_details->id)
                                            ->where('price_taxation.buyer_id',$buyer_id)
                                            ->where('orchestra_standard_polybag_sticker_orders.id',$id)
                                            ->select(DB::raw('"-" as size'),'final_order_qty as quantity','orchestra_standard_polybag_sticker_orders.uvc as style','price_taxation.custom_name as material_name','price_taxation.price as unit_price','price_taxation.tax')
                                            ->get();
                break;
            case 'orchestra_wash_care_label_orders':
                $data['item_details']    = DB::table('orchestra_wash_care_label_orders')
                                            ->join('orchestra_wash_care_label_orders_qty','orchestra_wash_care_label_orders_qty.owcl_id','=','orchestra_wash_care_label_orders.id')
                                            ->leftjoin('price_taxation','price_taxation.template_id','=','orchestra_wash_care_label_orders.template_id')
                                            ->where('price_taxation.customer_id',$customer_details->id)
                                            ->where('price_taxation.buyer_id',$buyer_id)
                                            ->where('orchestra_wash_care_label_orders_qty.owcl_id',$id)
                                            ->select('size','quantity','orchestra_wash_care_label_orders.uvc as style','price_taxation.custom_name as material_name','price_taxation.price as unit_price','price_taxation.tax')
                                            ->get(); 
                break;
            case 'orchestra_special_tag_orders':
                $data['item_details']    = DB::table('orchestra_special_tag_orders')
                                            ->join('orchestra_special_tag_orders_qty','orchestra_special_tag_orders_qty.ost_id','=','orchestra_special_tag_orders.id')
                                            ->leftjoin('price_taxation','price_taxation.template_id','=','orchestra_special_tag_orders.template_id')
                                            ->where('price_taxation.customer_id',$customer_details->id)
                                            ->where('price_taxation.buyer_id',$buyer_id)
                                            ->where('orchestra_special_tag_orders_qty.ost_id',$id)
                                            ->select('size','quantity','orchestra_special_tag_orders.uvc as style','price_taxation.custom_name as material_name','price_taxation.price as unit_price','price_taxation.tax')
                                            ->get();
                break;    
            
            default:
               $data['item_details'] = [];
                break;
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
        $id         =   $request->id;
        $table_name =   $request->table_name;
        $buyer_id   =   $request->buyer_id;
        $pi_id      =   DB::table('profoma_invoices')->max('id');
        $today      =   Carbon::now('Asia/Kolkata');
        
        $data['company_details'] =  DB::table('tesco_item_master')
                                          ->join($table_name,$table_name.'.template_id','=','tesco_item_master.id')
                                          ->join('company','company.id','=','tesco_item_master.company_id')
                                          ->where($table_name.'.id',$id)
                                          ->select('company.*')
                                          ->first();
        if ($data['company_details']->id == 1)
        {
          $data['company_details']->pi_number = "JGA00".$pi_id;
        }
        else{
         $data['company_details']->pi_number = "SP00".$pi_id;
        }
        $data['company_details']->rev_number = 0;
        $data['company_details']->pi_date = $today->toDateString();
        $customer_details       = DB::table($table_name)
                                  ->join('customers',$table_name.'.tesco_supplier_id','=','customers.id')
                                  ->leftjoin('payment_terms','customers.payment_terms','=','payment_terms.id')
                                  ->leftjoin('freight_terms','customers.freight_type','=','freight_terms.id')
                                  ->where($table_name.'.id',$id)
                                  ->select('customers.*',$table_name.'.template_id',$table_name.'.po_number',$table_name.'.uk_style_ref',$table_name.'.ce_style_ref',$table_name.'.zone','payment_terms.payment_terms','freight_terms.freight_terms',$table_name.'.template_type')
                                  ->first();
        if ($customer_details)
        {
          if ($customer_details->nominated_forwarder!=null)
          {
           $customer_details->freight_terms =  DB::table('nominated_forwarders')->pluck('forwarder')->first();
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
        $data['item_details']    = DB::table($table_name)
                                            ->join($table_name.'_quanty',$table_name.'_quanty.'.$qnty_id,'=',$table_name.'.id')
                                            ->leftjoin('price_taxation','price_taxation.template_id','=',$table_name.'.template_id')
                                            ->where('price_taxation.customer_id',$customer_details->id)
                                            ->where('price_taxation.buyer_id',$buyer_id)
                                            ->where($table_name.'_quanty.'.$qnty_id,$id)
                                            ->select('size',$table_name.'_quanty.count as quantity',$table_name.'.uk_style_ref as style','price_taxation.custom_name as material_name','price_taxation.price as unit_price','price_taxation.tax')
                                            ->get();
        return $data;
       
    }  

    public function getApprovedTescoPI($request)
    {
        $pi_status      = $request->pi_status;
        $buyer_id       = $request->buyer_id;
        $pi_status      = $request->pi_status;
        $style_no       = $request->style_no;
        $template_id    = $request->template_id;
        $buyer_po       = $request->buyer_po;
        $template_id    = $request->template_id;

        $pi_details = DB::table('profoma_invoices')
        ->where('buyer_id',$buyer_id)
        ->where('uvc_style_no',$style_no)
        ->where('template_id',$template_id)
        ->where('buyer_po_no',$buyer_po)
        ->select('profoma_invoices.*',DB::raw("DATE_FORMAT(profoma_invoices.created_at,'%Y-%m-%d') as pi_date"))
        ->first();
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
           $customer_details->template_type = $pi_details->template_type;
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
        $data['item_details']    = DB::table('profoma_invoice_item_details')
                                    ->where('profoma_invoice_item_details.pi_id',$pi_details->id)
                                    ->select('size','quantity','style','material as material_name','unit_cost as unit_price',DB::raw($pi_details->tax.' as tax'))
                                    ->get();
        return $data;

    }
}
