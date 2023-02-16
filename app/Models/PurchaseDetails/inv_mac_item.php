<?php

namespace App\Models\PurchaseDetails;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class inv_mac_item extends Model
{
    protected $table = 'inv_mac_item';
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
        return $this->select('inv_mac_item.id as id','inventory_rawmaterial.item_code','inventory_rawmaterial.discription','inv_item_type.type_name','inv_unit.unit_name','inv_lot_allocation.lot_number','inv_mac_item.accepted_quantity','inv_miq_item.expiry_control','inv_miq_item.expiry_date')
                    ->leftjoin('inv_mac_item_rel','inv_mac_item_rel.item','=','inv_mac_item.id')
                    ->leftjoin('inv_supplier_invoice_item','inv_supplier_invoice_item.id','=','inv_mac_item.invoice_item_id')
                    ->leftjoin('inv_miq_item','inv_miq_item.invoice_item_id','=','inv_supplier_invoice_item.id')
                    ->leftjoin('inv_purchase_req_item','inv_purchase_req_item.requisition_item_id','=','inv_supplier_invoice_item.item_id')
                    ->leftjoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_purchase_req_item.item_code')
                    ->leftjoin('inv_item_type','inv_item_type.id','=','inventory_rawmaterial.item_type_id')
                    ->leftjoin('inv_lot_allocation','inv_lot_allocation.si_invoice_item_id','=','inv_supplier_invoice_item.id')
                    ->leftjoin('inv_unit', 'inv_unit.id','=', 'inventory_rawmaterial.issue_unit_id')
                    //->leftjoin('currency_exchange_rate', 'currency_exchange_rate.currency_id','=', 'inv_miq_item.currency')
                    ->where($condition)
                    ->orderBy('inv_mac_item.id','DESC')
                    ->get();
    }
    function get_items_for_mrr($condition){
        return $this->select('inv_mac_item.id as id','inv_mac_item.pr_item_id as pr_item_id','inv_miq_item.expiry_control','inv_miq_item.expiry_date','inventory_rawmaterial.item_code','inv_item_type.type_name','inv_unit.unit_name','inv_lot_allocation.lot_number','inv_miq_item.value_inr','currency_exchange_rate.currency_code','inv_mac_item.accepted_quantity')
                    ->leftjoin('inv_mac_item_rel','inv_mac_item_rel.item','=','inv_mac_item.id')
                     ->leftjoin('inv_supplier_invoice_item','inv_supplier_invoice_item.id','=','inv_mac_item.invoice_item_id')
                    ->leftjoin('inv_miq_item','inv_miq_item.invoice_item_id','=','inv_supplier_invoice_item.id')
                    ->leftjoin('inv_purchase_req_item','inv_purchase_req_item.requisition_item_id','=','inv_supplier_invoice_item.item_id')
                    ->leftjoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_purchase_req_item.item_code')
                    ->leftjoin('inv_item_type','inv_item_type.id','=','inventory_rawmaterial.item_type_id')
                    ->leftjoin('inv_lot_allocation','inv_lot_allocation.si_invoice_item_id','=','inv_supplier_invoice_item.id')
                    ->leftjoin('inv_unit', 'inv_unit.id','=', 'inventory_rawmaterial.issue_unit_id')
                    ->leftjoin('currency_exchange_rate', 'currency_exchange_rate.currency_id','=', 'inv_miq_item.currency')
                    ->where($condition)
                    ->where('inv_mac_item.accepted_quantity','!=',NULL)
                    ->orderBy('inv_mac_item.id','DESC')
                    ->get();
    }

    function get_item($condition){
        return $this->select('inv_mac_item.id as id','inv_miq_item.expiry_control','inv_miq_item.expiry_date','inventory_rawmaterial.item_code','inventory_rawmaterial.discription','inv_item_type.type_name','inv_supplier_invoice_item.order_qty',
                    'inv_unit.unit_name','inv_lot_allocation.lot_number','inv_miq_item.value_inr','inv_supplier_invoice_item.rate','inv_supplier_invoice_item.discount','inv_mac_item.accepted_quantity',
                    'inv_purchase_req_item.requisition_item_id','inv_lot_allocation.id as lot_id','inv_supplier_invoice_item.id as invoice_item_id')
                    ->leftjoin('inv_mac_item_rel','inv_mac_item_rel.item','=','inv_mac_item.id')
                    ->leftjoin('inv_supplier_invoice_item','inv_supplier_invoice_item.id','=','inv_mac_item.invoice_item_id')
                    ->leftjoin('inv_miq_item','inv_miq_item.invoice_item_id','=','inv_supplier_invoice_item.id')
                    ->leftjoin('inv_purchase_req_item','inv_purchase_req_item.requisition_item_id','=','inv_supplier_invoice_item.item_id')
                    ->leftjoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_purchase_req_item.item_code')
                    ->leftjoin('inv_item_type','inv_item_type.id','=','inventory_rawmaterial.item_type_id')
                    ->leftjoin('inv_lot_allocation','inv_lot_allocation.si_invoice_item_id','=','inv_supplier_invoice_item.id')
                    ->leftjoin('inv_unit', 'inv_unit.id','=', 'inventory_rawmaterial.issue_unit_id')
                    ->where($condition)
                    ->first();
    }

    function getMAC_items_Not_In_StockToProduction($condition)
    {
        return $this->select(['inv_mac_item.*', 'inv_supplier.vendor_id','inv_supplier.vendor_name','inv_final_purchase_order_master.po_number','inventory_rawmaterial.item_code',
        'inv_supplier_invoice_master.invoice_number','inv_supplier_invoice_master.invoice_date','inv_item_type.type_name','inv_lot_allocation.id as lot_id','inv_lot_allocation.lot_number',
        'inv_supplier_invoice_item.id as si_invoice_item_id','inv_unit.unit_name'])
                ->leftjoin('inv_purchase_req_item', 'inv_purchase_req_item.requisition_item_id','=','inv_mac_item.pr_item_id')
                ->leftjoin('inventory_rawmaterial', 'inventory_rawmaterial.id','=','inv_purchase_req_item.Item_code')
                ->leftjoin('inv_miq_item','inv_miq_item.id','=','inv_mac_item.miq_item_id')
                ->leftjoin('inv_supplier_invoice_item','inv_supplier_invoice_item.id','=','inv_miq_item.invoice_item_id')
                ->leftjoin('inv_lot_allocation','inv_lot_allocation.si_invoice_item_id','=','inv_supplier_invoice_item.id')
                ->leftjoin('inv_item_type','inv_item_type.id','=','inventory_rawmaterial.item_type_id')
                ->leftjoin('inv_unit', 'inv_unit.id','=', 'inventory_rawmaterial.issue_unit_id')
                ->leftjoin('inv_supplier_invoice_rel', 'inv_supplier_invoice_rel.item','=','inv_miq_item.invoice_item_id')
                ->leftjoin('inv_supplier_invoice_master', 'inv_supplier_invoice_master.id','=','inv_supplier_invoice_rel.master')
                ->leftjoin('inv_final_purchase_order_master', 'inv_final_purchase_order_master.id','=','inv_lot_allocation.po_id')
                ->leftjoin('inv_supplier', 'inv_supplier.id','=','inv_supplier_invoice_master.supplier_id')
                ->where($condition)
                ->groupBy('inv_mac_item.id')
                ->whereNotIn('inv_mac_item.id',function($query) {

                    $query->select('inv_stock_to_production.mac_item_id')->from('inv_stock_to_production');
                
                })->orderby('inv_mac_item.id','asc')
                ->paginate(10);
    }
}
