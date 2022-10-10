<?php

namespace App\Models\PurchaseDetails;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class inv_miq_item extends Model
{
    protected $table = 'inv_miq_item';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;
    
    function insert_data($data){
        return $this->insertGetId($data);
    }
    function update_data($condition,$data){
        return $this->where($condition)->update($data);
    }
    function get_items($condition){
        return $this->select('inv_miq_item.id as item_id','inv_miq_item.expiry_control','inv_miq_item.expiry_date','inv_supplier_invoice_item.order_qty','inv_supplier_invoice_item.rate','inv_supplier_invoice_item.discount',
                    'inventory_rawmaterial.item_code','inv_item_type.type_name','inv_unit.unit_name','inv_lot_allocation.lot_number')
                    ->leftjoin('inv_miq_item_rel','inv_miq_item_rel.item','=','inv_miq_item.id')
                    ->leftjoin('inv_supplier_invoice_item','inv_supplier_invoice_item.id','=','inv_miq_item.invoice_item_id')
                    ->leftjoin('inv_purchase_req_item','inv_purchase_req_item.requisition_item_id','=','inv_supplier_invoice_item.item_id')
                    ->leftjoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_purchase_req_item.item_code')
                    ->leftjoin('inv_item_type','inv_item_type.id','=','inventory_rawmaterial.item_type_id')
                    ->leftjoin('inv_lot_allocation','inv_lot_allocation.si_invoice_item_id','=','inv_supplier_invoice_item.id')
                    ->leftjoin('inv_unit', 'inv_unit.id','=', 'inventory_rawmaterial.issue_unit_id')
                    ->where($condition)
                    ->orderBy('inv_miq_item.id','DESC')
                    ->get();
    }
    function get_item($condition){
        return $this->select('inv_miq_item.id as item_id','inv_miq_item.currency','inv_miq_item.conversion_rate','inv_miq_item.value_inr','inv_miq_item.expiry_control','inv_miq_item.expiry_date','inv_supplier_invoice_item.order_qty','inv_supplier_invoice_item.rate',
                    'inventory_rawmaterial.item_code','inv_item_type.type_name','inv_unit.unit_name','inv_lot_allocation.lot_number','inv_supplier_invoice_item.discount')
                    ->leftjoin('inv_miq_item_rel','inv_miq_item_rel.item','=','inv_miq_item.id')
                    ->leftjoin('inv_supplier_invoice_item','inv_supplier_invoice_item.id','=','inv_miq_item.invoice_item_id')
                    ->leftjoin('inv_purchase_req_item','inv_purchase_req_item.requisition_item_id','=','inv_supplier_invoice_item.item_id')
                    ->leftjoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_purchase_req_item.item_code')
                    ->leftjoin('inv_item_type','inv_item_type.id','=','inventory_rawmaterial.item_type_id')
                    ->leftjoin('inv_lot_allocation','inv_lot_allocation.si_invoice_item_id','=','inv_supplier_invoice_item.id')
                    ->leftjoin('inv_unit', 'inv_unit.id','=', 'inventory_rawmaterial.issue_unit_id')
                    ->where($condition)
                    ->orderBy('inv_miq_item.id','DESC')
                    ->first();
    }
}
