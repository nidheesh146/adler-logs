<?php

namespace App\Models\PurchaseDetails;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class inv_stock_to_production extends Model
{
    protected $table = 'inv_stock_to_production';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;
    
    function insert_data($data){
        return $this->insertGetId($data);
    }
    function update_data($condition,$data){
        return $this->where($condition)->update($data);
    }

    function get_all_data($condition)
    {
        return $this->select(['inv_stock_to_production.id','inv_stock_to_production.sip_number','inv_stock_to_production.quantity','inv_supplier.vendor_name',
            'inv_stock_to_production.created_at','inv_lot_allocation.lot_number','inventory_rawmaterial.item_code','inv_item_type.type_name'])
        ->leftjoin('inv_lot_allocation','inv_lot_allocation.id','=','inv_stock_to_production.lot_id')
        ->leftjoin('inv_purchase_req_item','inv_purchase_req_item.requisition_item_id','=','inv_stock_to_production.pr_item_id')
        ->leftjoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_purchase_req_item.Item_code')
        ->leftjoin('inv_item_type', 'inv_item_type.id', '=','inventory_rawmaterial.item_type_id' )
        ->leftjoin('inv_supplier', 'inv_supplier.id', '=','inv_lot_allocation.supplier_id' )
        ->where($condition)
        ->orderby('inv_stock_to_production.id','desc')
        ->paginate(15);
    }

    function deleteData($condition)
    {
        return  $this->where($condition)->delete();
    }

    
}
