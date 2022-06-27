<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobcardMainTable extends Model
{
    protected $table = 'jobcard_main_table';

    protected $fillable = [
        'work_order_id',
        'company_id',
        'customer_id',
        'buyer_id',
        'created_date',
        'material',
        'article',
        'reference',
        'order_type',
        'dispatch_mode',
        'order_date',            
        'length',
        'repeat_gap',
        'machine',
        'customer_del_date',
        'width',
        'roll_width',
        'film_no',
        'invoice_date',
        'length_side',
        'excess_percentage',
        'teeth_no',
        'invoice_num',
        'finish_type',
        'print_color',
        'compiled_by',
        'barcode_read',
        'operator',
        'checker',
        'authorized_by',
        'axicon_verifier',
        'approved_by',
        'packer',
        'approval_date',
        'roll_issued_by',
        'cutter',
        'cartons',
        'template_quantity_json',
        'created_at',
        'updated_at',
        'aw_approve_date',
        'act_del_date',
        'no_of_repeats',
        'is_non_artwork_template',
        'tint_color',
        'file_path',
        'style_number',
        'no_of_stickers',
        'customer_po_num'
    ];
}
