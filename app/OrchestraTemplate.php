<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrchestraTemplate extends Model
{
    protected $table = 'orchestra_template';
    protected $fillable = ['id', 'item_id', 'template_name', 'template_description', 'template_file', 'disney', 'tax', 'flag', 'position', 'created_at', 'updated_a', 'sap_ref', 'gl_code', 'hsn_code', 'color', 'dimension_height', 'dimension_width', 'hierarchy_id', 'gl_text', 'material_id', 'name_in_reports'];
}
