<?php

namespace App\Models\PurchaseDetails;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class inv_mrd_item extends Model
{
    protected $table = 'inv_mrd_item';
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
        return $this->select('inv_mrd_item.id as id','inv_miq_item.expiry_control','inv_miq_item.expiry_date','inventory_rawmaterial.item_code','inv_item_type.type_name','inv_unit.unit_name','inv_lot_allocation.lot_number','inv_miq_item.value_inr','currency_exchange_rate.currency_code','inv_mrd_item.rejected_quantity','inv_mrd_item.remarks')
                    ->leftjoin('inv_mrd_item_rel','inv_mrd_item_rel.item','=','inv_mrd_item.id')
                    ->leftjoin('inv_miq_item','inv_miq_item.id','=','inv_mrd_item.miq_item_id')
                    ->leftjoin('inv_supplier_invoice_item','inv_supplier_invoice_item.id','=','inv_miq_item.invoice_item_id')
                    ->leftjoin('inv_purchase_req_item','inv_purchase_req_item.requisition_item_id','=','inv_supplier_invoice_item.item_id')
                    ->leftjoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_purchase_req_item.item_code')
                    ->leftjoin('inv_item_type','inv_item_type.id','=','inventory_rawmaterial.item_type_id')
                    ->leftjoin('inv_lot_allocation','inv_lot_allocation.si_invoice_item_id','=','inv_supplier_invoice_item.id')
                    ->leftjoin('inv_unit', 'inv_unit.id','=', 'inventory_rawmaterial.issue_unit_id')
                    ->leftjoin('currency_exchange_rate', 'currency_exchange_rate.currency_id','=', 'inv_miq_item.currency')
                    ->where($condition)
                    ->orderBy('inv_mrd_item.id','DESC')
                    ->get();
    }

    function get_item($condition){
        return $this->select('inv_mrd_item.id as id','inv_miq_item.expiry_control','inv_miq_item.expiry_date','inventory_rawmaterial.item_code','inv_item_type.type_name','inv_supplier_invoice_item.order_qty',
                    'inv_unit.unit_name','inv_lot_allocation.lot_number','inv_miq_item.value_inr','inv_supplier_invoice_item.rate','inv_supplier_invoice_item.discount','inv_mrd_item.rejected_quantity','inv_mrd_item.remarks',
                    'inv_mrd_item.currency','inv_mrd_item.value_inr','inv_mrd_item.conversion_rate')
                    ->leftjoin('inv_mrd_item_rel','inv_mrd_item_rel.item','=','inv_mrd_item.id')
                    ->leftjoin('inv_miq_item','inv_miq_item.id','=','inv_mrd_item.miq_item_id')
                    ->leftjoin('inv_supplier_invoice_item','inv_supplier_invoice_item.id','=','inv_miq_item.invoice_item_id')
                    ->leftjoin('inv_purchase_req_item','inv_purchase_req_item.requisition_item_id','=','inv_supplier_invoice_item.item_id')
                    ->leftjoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_purchase_req_item.item_code')
                    ->leftjoin('inv_item_type','inv_item_type.id','=','inventory_rawmaterial.item_type_id')
                    ->leftjoin('inv_lot_allocation','inv_lot_allocation.si_invoice_item_id','=','inv_supplier_invoice_item.id')
                    ->leftjoin('inv_unit', 'inv_unit.id','=', 'inventory_rawmaterial.issue_unit_id')
                    ->where($condition)
                    ->first();
    }
}
