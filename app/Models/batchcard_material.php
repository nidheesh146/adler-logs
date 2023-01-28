<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class batchcard_material extends Model
{
    protected $table = 'batchcard_materials';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;

    function insert_data($data)
    {
        return $this->insertGetId($data);  
    }
    function get_batchcard_material($condition)
    {
        return $this->select(['batchcard_materials.id','batchcard_materials.product_inputmaterial_id','batchcard_materials.quantity','inventory_rawmaterial.item_code',
        'inventory_rawmaterial.discription','inv_unit.unit_name'])
                ->leftjoin('inventory_rawmaterial','inventory_rawmaterial.id','=','batchcard_materials.item_id')
                ->leftjoin('inv_unit','inv_unit.id','=','inventory_rawmaterial.issue_unit_id')
                ->where($condition)
                ->orderBy('batchcard_materials.id', 'desc')
                ->get();
    }
}
