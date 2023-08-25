<?php

namespace App\Models\PurchaseDetails;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class inv_mrr_item extends Model
{
    protected $table = 'inv_mrr_item';
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
        return $this->select('inv_mrr_item.id as id','inv_miq_item.expiry_control','inv_miq_item.expiry_date','inventory_rawmaterial.item_code','inventory_rawmaterial.discription as item_description','inv_item_type.type_name','inv_unit.unit_name','inv_final_purchase_order_item.cancelled_qty',
        'inv_lot_allocation.lot_number','inv_miq_item.value_inr','currency_exchange_rate.currency_code','inv_mac_item.accepted_quantity','inv_purchase_req_item.actual_order_qty as actualqty','inv_final_purchase_order_item.order_qty as actual_order_qty','inv_supplier_invoice_item.rate','inv_miq_item.conversion_rate',
        'inv_supplier_invoice_item.order_qty as received_qty','inv_mrd_item.rejected_quantity','inv_mrd_item.remarks as rejection_reason','inv_final_purchase_order_master.po_number','inv_final_purchase_order_master.po_date','inv_supplier_invoice_item.id as supplier_invoice_item_id')
                    ->leftjoin('inv_mrr_item_rel','inv_mrr_item_rel.item','=','inv_mrr_item.id')
                    ->leftjoin('inv_mac_item','inv_mac_item.invoice_item_id','=','inv_mrr_item.invoice_item_id')
                    ->leftjoin('inv_supplier_invoice_item','inv_supplier_invoice_item.id','=','inv_mac_item.invoice_item_id')
                    ->leftjoin('inv_miq_item','inv_miq_item.invoice_item_id','=','inv_supplier_invoice_item.id')
                    ->leftjoin('inv_mrd_item','inv_mrd_item.invoice_item_id','=','inv_supplier_invoice_item.id')
                    ->leftjoin('inv_final_purchase_order_master','inv_final_purchase_order_master.id','=','inv_supplier_invoice_item.po_master_id')
                    ->leftjoin('inv_final_purchase_order_item','inv_final_purchase_order_item.id','=','inv_supplier_invoice_item.po_item_id')
                    ->leftjoin('inv_purchase_req_item','inv_purchase_req_item.requisition_item_id','=','inv_supplier_invoice_item.item_id')
                    ->leftjoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_purchase_req_item.item_code')
                    ->leftjoin('inv_item_type','inv_item_type.id','=','inventory_rawmaterial.item_type_id')
                    ->leftjoin('inv_lot_allocation','inv_lot_allocation.si_invoice_item_id','=','inv_supplier_invoice_item.id')
                    ->leftjoin('inv_unit', 'inv_unit.id','=', 'inventory_rawmaterial.issue_unit_id')
                    ->leftjoin('currency_exchange_rate', 'currency_exchange_rate.currency_id','=', 'inv_miq_item.currency')
                    ->where($condition)
                    ->orderBy('inv_mrr_item.id','DESC')
                    ->groupBY('inv_mrr_item.id')
                    ->distinct('inv_mrr_item_rel.id')
                    // ->distinct('inv_supplier_invoice_item.id')
                    ->get();
    }
}
