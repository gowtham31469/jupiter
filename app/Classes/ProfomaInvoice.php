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
        $data['company_details'] =  DB::table('orchestra_item_master')
                                          ->join('orchestra_hang_tag_orders','orchestra_hang_tag_orders.template_front_id','=','orchestra_item_master.id')
                                          ->join('company','company.id','=','orchestra_item_master.company_id')
                                          ->where('orchestra_hang_tag_orders.id',$id)
                                          ->select('company.id','company.name','company.address','company.logo','company.phone')
                                          ->first();
        $customer_details = DB::table('orchestra_item_details')
                          ->join('customers','orchestra_item_details.FactoryCode','=','customers.factory_code')
                          ->where('orchestra_item_details.UVC',$uvc)
                          ->select('customers.*')
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
                                            ->where('price_taxation.buyer_id',1)
                                            ->where('orchestra_hang_tag_orders_qty.oht_id',$id)
                                            ->select('size','quantity','orchestra_hang_tag_orders.uvc as style','price_taxation.custom_name as material_name','price_taxation.price as unit_price','price_taxation.tax')
                                            ->get();
               
                break;
            case 'orchestra_main_label_orders':
                $data['item_details']    = DB::table('orchestra_main_label_orders')
                                            ->join('orchestra_main_label_orders_qty','orchestra_main_label_orders_qty.oml_id','=','orchestra_main_label_orders.id')
                                            ->leftjoin('price_taxation','price_taxation.template_id','=','orchestra_main_label_orders.template_id')
                                            ->where('price_taxation.customer_id',$customer_details->id)
                                            ->where('price_taxation.buyer_id',1)
                                            ->where('orchestra_main_label_orders_qty.oml_id',$id)
                                            ->select('size','quantity','orchestra_main_label_orders.uvc as style','price_taxation.custom_name as material_name','price_taxation.price as unit_price','price_taxation.tax')
                                            ->get();        
                break;
            case 'orchestra_solid_carton_sticker_orders':
                $data['item_details']    = DB::table('orchestra_solid_carton_sticker_orders')
                                            ->join('orchestra_solid_carton_sticker_orders_qty','orchestra_solid_carton_sticker_orders_qty.oscs_id','=','orchestra_solid_carton_sticker_orders.id')
                                            ->leftjoin('price_taxation','price_taxation.template_id','=','orchestra_solid_carton_sticker_orders.template_id')
                                            ->where('price_taxation.customer_id',$customer_details->id)
                                            ->where('price_taxation.buyer_id',1)
                                            ->where('orchestra_solid_carton_sticker_orders_qty.oscs_id',$id)
                                            ->select('size','quantity','orchestra_solid_carton_sticker_orders.uvc as style','price_taxation.custom_name as material_name','price_taxation.price as unit_price','price_taxation.tax')
                                            ->get(); 
                break;
            case 'orchestra_solid_poly_bag_sticker_orders':
                $data['item_details']    = DB::table('orchestra_solid_poly_bag_sticker_orders')
                                            ->join('orchestra_solid_poly_bag_sticker_order_qty','orchestra_solid_poly_bag_sticker_order_qty.ospbs_id','=','orchestra_solid_poly_bag_sticker_orders.id')
                                            ->leftjoin('price_taxation','price_taxation.template_id','=','orchestra_solid_poly_bag_sticker_orders.template_id')
                                            ->where('price_taxation.customer_id',$customer_details->id)
                                            ->where('price_taxation.buyer_id',1)
                                            ->where('orchestra_solid_poly_bag_sticker_order_qty.ospbs_id',$id)
                                            ->select('size','quantity','orchestra_solid_poly_bag_sticker_orders.uvc as style','price_taxation.custom_name as material_name','price_taxation.price as unit_price','price_taxation.tax')
                                            ->get(); 
                break;
            case 'orchestra_standard_carton_sticker_orders':
                $data['item_details']    = DB::table('orchestra_standard_carton_sticker_orders')
                                            ->leftjoin('price_taxation','price_taxation.template_id','=','orchestra_standard_carton_sticker_orders.template_id')
                                            ->where('price_taxation.customer_id',$customer_details->id)
                                            ->where('price_taxation.buyer_id',1)
                                            ->where('orchestra_standard_carton_sticker_orders.id',$id)
                                            ->select(DB::raw('"-" as size'),'final_order_qty as quantity','orchestra_standard_carton_sticker_orders.uvc as style','price_taxation.custom_name as material_name','price_taxation.price as unit_price','price_taxation.tax')
                                            ->get();
                break;
            case 'orchestra_standard_polybag_sticker_orders':
                $data['item_details']    = DB::table('orchestra_standard_polybag_sticker_orders')
                                            ->leftjoin('price_taxation','price_taxation.template_id','=','orchestra_standard_polybag_sticker_orders.template_id')
                                            ->where('price_taxation.customer_id',$customer_details->id)
                                            ->where('price_taxation.buyer_id',1)
                                            ->where('orchestra_standard_polybag_sticker_orders.id',$id)
                                            ->select(DB::raw('"-" as size'),'final_order_qty as quantity','orchestra_standard_polybag_sticker_orders.uvc as style','price_taxation.custom_name as material_name','price_taxation.price as unit_price','price_taxation.tax')
                                            ->get();
                break;
            case 'orchestra_wash_care_label_orders':
                $data['item_details']    = DB::table('orchestra_wash_care_label_orders')
                                            ->join('orchestra_wash_care_label_orders_qty','orchestra_wash_care_label_orders_qty.owcl_id','=','orchestra_wash_care_label_orders.id')
                                            ->leftjoin('price_taxation','price_taxation.template_id','=','orchestra_wash_care_label_orders.template_id')
                                            ->where('price_taxation.customer_id',$customer_details->id)
                                            ->where('price_taxation.buyer_id',1)
                                            ->where('orchestra_wash_care_label_orders_qty.owcl_id',$id)
                                            ->select('size','quantity','orchestra_wash_care_label_orders.uvc as style','price_taxation.custom_name as material_name','price_taxation.price as unit_price','price_taxation.tax')
                                            ->get(); 
                break;
            case 'orchestra_special_tag_orders':
                $data['item_details']    = DB::table('orchestra_special_tag_orders')
                                            ->join('orchestra_special_tag_orders_qty','orchestra_special_tag_orders_qty.ost_id','=','orchestra_special_tag_orders.id')
                                            ->leftjoin('price_taxation','price_taxation.template_id','=','orchestra_special_tag_orders.template_id')
                                            ->where('price_taxation.customer_id',$customer_details->id)
                                            ->where('price_taxation.buyer_id',1)
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
}
