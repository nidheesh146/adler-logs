<?php

namespace App\Models\PurchaseDetails;

use Illuminate\Database\Eloquent\Model;

class inventory_rawmaterial extends Model
{
    protected $table = 'inventory_rawmaterial';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;


    function insertdata($data){
        return $this->insertGetId($data);
    }
    function updatedata($condition,$data){
        return $this->where($condition)->update($data);
    }

    function get_inv_raw_data($condition){

        return $this->select(['inventory_rawmaterial.id','inventory_rawmaterial.item_code as text','inventory_rawmaterial.short_description','inventory_rawmaterial.discription','inv_item_type.type_name','hsn_code','unit_name','min_stock',
        'max_stock','opening_quantity','availble_quantity','inv_item_type_2.type_name as type2_name'])
        ->leftjoin('inv_unit','inv_unit.id','=','inventory_rawmaterial.receipt_unit_id')
        ->leftjoin('inv_item_type','inv_item_type.id','=','inventory_rawmaterial.item_type_id')
        ->leftjoin('inv_item_type_2','inv_item_type_2.id','=','inventory_rawmaterial.item_type_id_2')
        ->where($condition)
        ->where('inventory_rawmaterial.is_product','!=',0)
        ->get()->toArray();


    }

    function get_items()
    {
        return $this->select('id','item_code')->where('inventory_rawmaterial.is_product','!=',0)->get();
    }
    function getItems($condition)
    {
        return $this->select('id','item_code as text')->where($condition)->where('inventory_rawmaterial.is_product','!=',0)->get();
    }
    function getFilterDescription($condition,$length,$start){
        return $this->select(['inventory_rawmaterial.item_code','inventory_rawmaterial.discription','inventory_rawmaterial.id'])
               ->where($condition)->skip($start)->take($length)->get()->toArray();
    }
    function getFilterDescription1($condition){
        return $this->select(['inventory_rawmaterial.discription','inventory_rawmaterial.id'])
                ->where('inventory_rawmaterial.is_product','!=',0)
               ->where($condition)->get()->count();
    }
    function getSingleDescription($condition){
        return $this->select(['inventory_rawmaterial.id','inventory_rawmaterial.item_code as text','inventory_rawmaterial.discription','inv_item_type.type_name'])
        ->join('inv_item_type','inv_item_type.id','=','inventory_rawmaterial.item_type_id')->where($condition)->first();
    }
    function get_single_data($condition){
        return $this->select(['inventory_rawmaterial.*'])
           ->where($condition)->first();
    } 
    function getData($condition){
        return $this->select(['inventory_rawmaterial.*','inv_item_type.type_name as type1','unit_name','inv_item_type_2.type_name as type2'])
        ->leftjoin('inv_unit','inv_unit.id','=','inventory_rawmaterial.receipt_unit_id')
        ->leftjoin('inv_item_type','inv_item_type.id','=','inventory_rawmaterial.item_type_id')
        ->leftjoin('inv_item_type_2','inv_item_type_2.id','=','inventory_rawmaterial.item_type_id_2')
        ->where('inventory_rawmaterial.is_active','=',1)
        ->where('inventory_rawmaterial.is_product','!=',0)
        ->where($condition)
        ->orderBy('inventory_rawmaterial.id','desc')
        ->paginate(15);
    }



}
