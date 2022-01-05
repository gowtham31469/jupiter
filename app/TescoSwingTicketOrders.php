<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TescoSwingTicketOrders extends Model
{
    protected $table = 'tesco_swing_ticket_orders';

    protected $fillable = [
            'template_id',
            'order_id',
            'zone',
            'template_type',   
            'po_number',
            'uk_style_ref',
            'ce_style_ref',
            'season_code_id',
            'delivery_date',
            'eqos_site_code',
            'tesco_supplier_id',
            'tesco_metadata_supplier_id',
            'ocn_number',
            'country_of_origin_id',
            'country_of_origin_text',
            'primary_size_id',
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
            '24_char_desc',
            'cz_selling_price',
            'sk_selling_price',
            'hu_selling_price',
            'wee_symbol_id',
            'woolmark_logo_id',
            'product_desc_id',
            'product_desc_text',
            'product_benefit_id',
            'product_benefit_text',
            'mm_product_benefit_id',
            'mm_product_benefit_text',
            'is_ce_mark_required',
            'is_machine_washable',
    ];

    protected $casts = [
        'country_of_origin_text'         => 'array',
        'product_desc_text'              => 'array',
        'product_benefit_text'           => 'array',
        'mm_product_benefit_text'        => 'array',
    ];
    /**
     * Get the customer for the order.
     */
    public function customer()
    {
        return $this->belongsTo('App\Customer', 'tesco_supplier_id');
    }

    public function item_master() {

        return $this->hasOne('App\TescoTemplate', 'item_id', 'template_id');
    }

    /**
     * Get the template details for the order.
     */
    public function template()
    {
        return $this->belongsTo('App\TescoItemMasterMdl', 'template_id');
    }

    /**
     * Get the Proforma Invoice details for the order.
     */
    public function pi()
    {
        return $this->belongsTo('App\ProfomaInvoice', 'pi_id');
    }

    /**
     * Get the size breakup and quantities for the order.
     */
    public function quantities()
    {
        return $this->hasMany('App\TescoSwingTicketOrdersQuantity', 'swing_ticket_order_id')
            ->join('tesco_primary_size', 'tesco_swing_ticket_orders_quanty.size_id', 'tesco_primary_size.id')
            ->select([
                'tesco_swing_ticket_orders_quanty.id',
                'swing_ticket_order_id',
                'ean_barcode',
                'quantity as count',
                'uk_size as size',
                'ce_size',
                'department_title',
                'tesco_primary_size.id as size_id'
            ])->orderBy('tesco_swing_ticket_orders_quanty.id', 'asc');
    }
}
