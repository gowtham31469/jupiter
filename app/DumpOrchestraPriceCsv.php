<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DumpOrchestraPriceCsv extends Model
{
    protected $table = 'dump_orchestra_price_csv';

    public function po_data() {
        return $this->hasOne('App\DumpOrchestraPOCsv', 'SKU CODE', 'SKU CODE');
    }
}
