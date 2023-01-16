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
    function getAllData($condition)
    {
        return $this->select(['product_input_material.id','inventory_rawmaterial.item_code','inventory_rawmaterial.short_description'])
        ->leftjoin('product_product','product_product.id','=','product_input_material.product_id')
        ->leftjoin('inventory_rawmaterial','inventory_rawmaterial.id','=','product_input_material.item_id')
        ->where($condition)
        ->orderBy('product_input_material.id', 'desc')
        ->get();
    }
}
