<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DumpOrchestraPOCsv extends Model
{
    protected $table = 'dump_orchestra_po_csv';

    public function price_data() {
        return $this->hasMany('App\DumpOrchestraPriceCsv', 'SKU CODE', 'SKU CODE');
    }
}
