<?php

namespace App\Models\PurchaseDetails;

use Illuminate\Database\Eloquent\Model;

class inventory_rawmaterial extends Model
{
    protected $table = 'inventory_rawmaterial';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;


    function get_inv_raw_data($condition){

        return $this->select(['inventory_rawmaterial.id','inventory_rawmaterial.item_code as text','inventory_rawmaterial.short_description','type_name','hsn_code','unit_name','min_stock',
        'max_stock','opening_quantity','availble_quantity'])
        ->leftjoin('inv_unit','inv_unit.id','=','inventory_rawmaterial.receipt_unit_id')
        ->leftjoin('inv_item_type','inv_item_type.id','=','inventory_rawmaterial.item_type_id')
        ->where($condition)->get()->toArray();


    }





}
