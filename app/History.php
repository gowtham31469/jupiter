<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    protected $table = 'history';

    protected $fillable = [ 'erp_order_details_id',
                            'buyer_id',
                            'template',
                            'season',
                            'country',
                            'customer_id',
                            'material',
                            'mat_sub',
                            'reference',
                            'style',
                            'po',
                            'buyer_po',
                            'ocn',
                            'disp_mode_id'];
} 