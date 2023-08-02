<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class product_input_material extends Model
{
    protected $table = 'product_input_material';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;

    function insert_data($data)
    {
        return $this->insertGetId($data);  
    }
    function update_data($condition,$data){
        return $this->where($condition)->update($data);
    }
    function getAllData($condition)
    {
        return $this->select(['product_input_material.id','product_input_material.quantity1','inventory_rawmaterial.item_code',
        'inventory_rawmaterial.short_description','inv_unit.unit_name','product_input_material.item_id2','product_input_material.item_id3'])
        ->leftjoin('product_product','product_product.id','=','product_input_material.product_id')
        //->leftjoin('inventory_rawmaterial','inventory_rawmaterial.id','=','product_input_material.item_id1')
        ->leftJoin('inventory_rawmaterial','inventory_rawmaterial.id','=','product_input_material.item_id1')
        ->leftJoin('inventory_rawmaterial as alternative2','alternative2.id','=','product_input_material.item_id2')
        ->leftJoin('inventory_rawmaterial as alternative3','alternative3.id','=','product_input_material.item_id3')
        ->leftjoin('inv_unit','inv_unit.id','=','inventory_rawmaterial.receipt_unit_id')
        ->where($condition)
        ->where('product_input_material.status','=',1)
        ->orderBy('product_input_material.id', 'desc')
        ->get();
    }
    function getSingleData()
    {
        return $this->select(['*'])
        ->where('id','=',1)
        ->where('product_input_material.status','=',1)
        ->first();
    }
    function get_batchcard_material_product($condition)
    {
        return $this->select(['inventory_rawmaterial.item_code', ])
        ->leftjoin('batchcard_materials','batchcard_materials.product_inputmaterial_id','=','product_input_material.id')
        //->leftjoin('inventory_rawmaterial','inventory_rawmaterial.id','=','product_input_material.item_id1')
        ->leftJoin('inventory_rawmaterial','inventory_rawmaterial.id','=','product_input_material.item_id1')
        // ->leftJoin('inventory_rawmaterial as alternative2','alternative2.id','=','product_input_material.item_id2')
        // ->leftJoin('inventory_rawmaterial as alternative3','alternative3.id','=','product_input_material.item_id3')
        // ->leftjoin('inv_unit','inv_unit.id','=','inventory_rawmaterial.receipt_unit_id')
        ->where($condition)
        ->where('product_input_material.status','=',1)
        // ->orderBy('product_input_material.id', 'desc')
        ->get();
    }
}
