<?php

namespace App\Models\PurchaseDetails;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class inv_stock_from_production extends Model
{
    protected $table = 'inv_stock_from_production';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;
    
    function insert_data($data){
        return $this->insertGetId($data);
    }
    function update_data($condition,$data){
        return $this->where($condition)->update($data);
    }

    function deleteData($condition)
    {
        return  $this->where($condition)->delete();
    }


    function get_all_data($condition)
    {
        return $this->select(['inv_stock_from_production.id','inv_stock_from_production.sir_number','inv_stock_from_production.quantity','inv_supplier.vendor_name',
            'inv_stock_to_production.created_at','inv_lot_allocation.lot_number','inventory_rawmaterial.item_code','inv_item_type.type_name','inv_unit.unit_name'])
        ->leftjoin('inv_stock_to_production','inv_stock_to_production.id','=','inv_stock_from_production.sip_id')
        ->leftjoin('inv_lot_allocation','inv_lot_allocation.id','=','inv_stock_from_production.lot_id')
        ->leftjoin('inv_purchase_req_item','inv_purchase_req_item.requisition_item_id','=','inv_stock_to_production.pr_item_id')
        ->leftjoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_purchase_req_item.Item_code')
        ->leftjoin('inv_item_type', 'inv_item_type.id', '=','inventory_rawmaterial.item_type_id' )
        ->leftjoin('inv_mac_item', 'inv_mac_item.id', '=','inv_stock_to_production.mac_item_id' )
        ->leftjoin('inv_miq_item', 'inv_miq_item.id', '=','inv_mac_item.miq_item_id' )
        ->leftjoin('inv_supplier_invoice_item', 'inv_supplier_invoice_item.id', '=','inv_miq_item.invoice_item_id' )
        ->leftjoin('inv_final_purchase_order_master', 'inv_final_purchase_order_master.id','=','inv_supplier_invoice_item.po_master_id')
        ->leftjoin('inv_supplier', 'inv_supplier.id', '=','inv_final_purchase_order_master.supplier_id' )
        ->leftjoin('inv_unit', 'inv_unit.id','=', 'inventory_rawmaterial.issue_unit_id')
        ->where($condition)
        ->where('inv_stock_from_production.status','=',1)
        ->orderby('inv_stock_from_production.id','asc')
        ->paginate(15);
    }

    function getSIR_Not_In_StockTransferOrder($condition)
    {
        return $this->select(['inv_stock_from_production.id','inv_stock_from_production.sir_number', 'inv_stock_from_production.quantity','inv_supplier.vendor_id','inv_supplier.vendor_name','inventory_rawmaterial.item_code',
        'inv_supplier_invoice_master.invoice_number','inv_supplier_invoice_master.invoice_date','inv_item_type.type_name','inv_lot_allocation.lot_number',
        'inv_supplier_invoice_item.id as si_invoice_item_id','inv_unit.unit_name'])
                ->leftjoin('inv_purchase_req_item', 'inv_purchase_req_item.requisition_item_id','=','inv_stock_from_production.pr_item_id')
                ->leftjoin('inventory_rawmaterial', 'inventory_rawmaterial.id','=','inv_purchase_req_item.Item_code')
                ->leftjoin('inv_stock_to_production', 'inv_stock_to_production.id','=','inv_stock_from_production.sip_id')
                ->leftjoin('inv_mac_item', 'inv_mac_item.id','=','inv_stock_to_production.mac_item_id')
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
                ->where('inv_stock_from_production.status','=',1)
                ->groupBy('inv_mac_item.id')
                ->whereNotIn('inv_stock_from_production.id',function($query) {

                    $query->select('inv_stock_transfer_order.sir_id')->from('inv_stock_transfer_order');
                
                })->orderby('inv_stock_from_production.id','asc')
                ->paginate(10);
    }

    
}
