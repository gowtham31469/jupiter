<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TescoWashcareOrders extends Model
{

    protected $table = 'tesco_washcare_orders';

    protected $fillable = [
        'template_id',
        'order_id',
        'zone',
        'template_type',   
        'po_number',
        'uk_style_ref',
        'ce_style_ref',
        'season_code',
        'delivery_date',
        'eqos_site_code',
        'tesco_supplier_id',
        'tesco_metadata_supplier_id',
        'ocn_number',
        'fabric_batch_code',
        'leather_suede_code',
        'country_of_origin_id',
        'kids_warning_id',
        'primary_size_id',
        'tog_id',
        'wash_symbol_id',
        'bleach_symbol_id',
        'dry_symbol_id',
        'ironing_symbol_id',
        'dryclean_symbol_id',
        'include_btn_sew_space',
        'exclusive_trims',
        'contains_non_textile_part',
        'part_of_set',
        'bs_en_declaration',
        'baby_sleeping_bag_symbol',
        'order_status',
        'order_flag',
        'is_order_count_empty',
        'po_file',
        'purchase_order_no',
        'po_status',
        'pi_file',
        'pi_id',
        'pi_rev_no',
        'pi_status',
        'total_qty',
        'edit_permission',
        'pen_pro_flag',
        'new_ord_flag',
        'app_ord_flag',
        'app_pipo_flag',
        'pen_ord_flag',
        'cancel_ord_flag',
        'order_placed_by',
        'order_created_at',
        'order_approved_at',
        'order_cancelled_at',
        'po_uploaded_at',
        'pi_approved_at',
        'exclusive_trims_text',
        'contains_non_textile_part_text',
        'part_of_set_text',
        'kids_warning_text',
        'country_of_origin_text',
        'additional_care_id_1',  
        'additional_care_text_1',
        'additional_care_id_2',  
        'additional_care_text_2',
        'additional_care_id_3',  
        'additional_care_text_3'
    ]; 

    protected $casts = [
        'country_of_origin_text'         => 'array',
        'kids_warning_text'              => 'array',
        'exclusive_trims_text'           => 'array',
        'contains_non_textile_part_text' => 'array',
        'part_of_set_text'               => 'array',
        'additional_care_text_1'         => 'array',
        'additional_care_text_2'         => 'array',
        'additional_care_text_3'         => 'array',
        'include_btn_sew_space'          => 'boolean',
        'exclusive_trims'                => 'boolean',
        'contains_non_textile_part'      => 'boolean',
        'part_of_set'                    => 'boolean',
        'bs_en_declaration'              => 'boolean',
        'baby_sleeping_bag_symbol'       => 'boolean',
    ];

    /**
     * Get the customer for the order.
     */
    public function customer()
    {
        return $this->hasOne('App\Customer', 'tesco_supplier_id');
    }

    /**
     * Get the template details for the order.
     */
    public function template()
    {
        return $this->hasOne('App\TescoItemMasterMdl', 'template_id');
    }

    /**
     * Get the Proforma Invoice details for the order.
     */
    public function pi()
    {
        return $this->hasOne('App\ProfomaInvoice', 'pi_id');
    }

    /**
     * Get the size breakup and quantities for the order.
     */
    public function quantity()
    {
        return $this->hasMany('App\TescoWashcareOrdersQuantity');
    }

}
