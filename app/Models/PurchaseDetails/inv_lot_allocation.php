<?php

namespace App\Models\PurchaseDetails;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class inv_lot_allocation extends Model
{
    protected $table = 'inv_lot_allocation';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;

    function insertdata($data){
        return $this->insertGetId($data);
    }
    function updatedata($condition,$data){
        return $this->where($condition)->update($data);
    }

    function getdata($condition){
        return $this->select(['inv_lot_allocation.*', 'inv_supplier.vendor_id','inv_supplier.vendor_name','inv_final_purchase_order_master.po_number','inventory_rawmaterial.item_code',
        'inv_supplier_invoice_master.invoice_number','inv_supplier_invoice_master.invoice_date','inv_supplier_invoice_item.order_qty as inv_odr_qty','inv_item_type.type_name'])
                ->leftjoin('inv_final_purchase_order_master', 'inv_final_purchase_order_master.id','=','inv_lot_allocation.po_id')
                ->leftjoin('inv_supplier', 'inv_supplier.id','=','inv_lot_allocation.supplier_id')
                ->leftjoin('inv_purchase_req_item', 'inv_purchase_req_item.requisition_item_id','=','inv_lot_allocation.pr_item_id')
                ->leftjoin('inventory_rawmaterial', 'inventory_rawmaterial.id','=','inv_purchase_req_item.Item_code')
                ->leftjoin('inv_supplier_invoice_item', 'inv_supplier_invoice_item.id','=','inv_lot_allocation.si_invoice_item_id')
                ->leftjoin('inv_supplier_invoice_rel', 'inv_supplier_invoice_rel.item','=','inv_lot_allocation.si_invoice_item_id')
                ->leftjoin('inv_supplier_invoice_master', 'inv_supplier_invoice_master.id','=','inv_supplier_invoice_rel.master')
                ->leftjoin('inv_item_type', 'inv_item_type.id', '=','inventory_rawmaterial.item_type_id' )
                ->where($condition)
                ->groupBy('inv_lot_allocation.id')
                ->orderby('inv_lot_allocation.id','desc')
                ->paginate(10);
    }
    function getLot_Not_In_StockToProduction($condition)
    {
        return $this->select(['inv_lot_allocation.*', 'inv_supplier.vendor_id','inv_supplier.vendor_name','inv_final_purchase_order_master.po_number','inventory_rawmaterial.item_code',
        'inv_supplier_invoice_master.invoice_number','inv_supplier_invoice_master.invoice_date','inv_supplier_invoice_item.order_qty as inv_odr_qty','inv_item_type.type_name'])
                ->leftjoin('inv_final_purchase_order_master', 'inv_final_purchase_order_master.id','=','inv_lot_allocation.po_id','inv_lot_allocation.pr_item_id')
                ->leftjoin('inv_supplier', 'inv_supplier.id','=','inv_lot_allocation.supplier_id')
                ->leftjoin('inv_purchase_req_item', 'inv_purchase_req_item.requisition_item_id','=','inv_lot_allocation.pr_item_id')
                ->leftjoin('inventory_rawmaterial', 'inventory_rawmaterial.id','=','inv_purchase_req_item.Item_code')
                ->leftjoin('inv_supplier_invoice_item', 'inv_supplier_invoice_item.id','=','inv_lot_allocation.si_invoice_item_id')
                ->leftjoin('inv_supplier_invoice_rel', 'inv_supplier_invoice_rel.item','=','inv_lot_allocation.si_invoice_item_id')
                ->leftjoin('inv_supplier_invoice_master', 'inv_supplier_invoice_master.id','=','inv_supplier_invoice_rel.master')
                ->leftjoin('inv_item_type','inv_item_type.id','=','inventory_rawmaterial.item_type_id')
                // ->leftjoin('inv_miq', 'inv_miq.invoice_master_id','=','inv_supplier_invoice_master.id')
                // ->leftjoin('inv_mac', 'inv_mac.miq_id','=','inv_miq.id')
                // ->leftjoin('inv_mac', 'inv_mac.miq_id','=','inv_miq.id')
                ->where($condition)
                ->groupBy('inv_lot_allocation.id')
                ->whereNotIn('inv_lot_allocation.id',function($query) {

                    $query->select('inv_stock_to_production.lot_id')->from('inv_stock_to_production');
                
                })->orderby('inv_lot_allocation.id','asc')
                ->paginate(10);
    }

    function get_single_lot($condition)
    {
        return $this->select(['inv_lot_allocation.*','inv_supplier_invoice_item.id as invoice_item_id','inv_supplier_invoice_item.order_qty as invoice_qty','inv_supplier_invoice_item.rate','inv_supplier_invoice_item.discount',
        'inv_purchase_req_master.pr_no','inventory_rawmaterial.item_code','inventory_rawmaterial.discription','inventory_rawmaterial.hsn_code','inv_final_purchase_order_master.po_number','inv_supplier_invoice_master.invoice_number as invoiceNumber',
        'inv_supplier_invoice_master.invoice_date','inv_supplier.vendor_id', 'inv_supplier.vendor_name', 'inv_supplier.id as supplier_id', 'inv_final_purchase_order_master.id as po_id', 'inv_unit.unit_name', 'inv_unit.id as unit_id','inv_lot_allocation.pr_item_id'])
                    ->leftjoin('inv_supplier_invoice_item', 'inv_supplier_invoice_item.id','=','inv_lot_allocation.invoice_number')
                    ->join('inv_supplier_invoice_rel','inv_supplier_invoice_rel.item','=','inv_supplier_invoice_item.id')
                    ->leftjoin('inv_purchase_req_item','inv_purchase_req_item.requisition_item_id','=','inv_supplier_invoice_item.item_id')
                    ->leftjoin('inv_purchase_req_master_item_rel','inv_purchase_req_master_item_rel.item','=','inv_supplier_invoice_item.item_id')
                    ->leftjoin('inv_purchase_req_master','inv_purchase_req_master.master_id','=','inv_purchase_req_master_item_rel.master')
                    ->leftjoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_purchase_req_item.Item_code')
                     ->leftjoin('inv_unit','inv_unit.id','=','inventory_rawmaterial.issue_unit_id')
                    ->leftjoin('inv_supplier_invoice_master','inv_supplier_invoice_master.id', '=', 'inv_supplier_invoice_rel.master')
                    ->leftjoin('inv_final_purchase_order_master','inv_final_purchase_order_master.id', '=','inv_supplier_invoice_master.po_master_id')
                    ->leftjoin('inv_supplier', 'inv_supplier.id','=','inv_final_purchase_order_master.supplier_id')
                    ->where($condition)
                    ->whereNotIn('id')
                    ->first();
    }
    function get_single_lot1($condition)
    {
        return $this->select(['inv_lot_allocation.*','inv_supplier_invoice_item.id as invoice_item_id','inv_supplier_invoice_item.order_qty as invoice_qty','inv_supplier_invoice_item.rate','inv_supplier_invoice_item.discount',
             'inventory_rawmaterial.item_code','inventory_rawmaterial.discription','inventory_rawmaterial.hsn_code','inv_final_purchase_order_master.po_number','inv_supplier_invoice_master.invoice_number as invoiceNumber',
        'inv_supplier_invoice_master.invoice_date','inv_supplier.vendor_id', 'inv_supplier.vendor_name', 'inv_supplier.id as supplier_id', 'inv_final_purchase_order_master.id as po_id', 'inv_unit.unit_name', 'inv_unit.id as unit_id' ,
        'inv_supplier_invoice_item.specification','inv_lot_allocation.pr_item_id','inv_supplier_invoice_master.created_at as invoice_created','inv_lot_allocation.si_invoice_item_id'])
                    ->leftjoin('inv_final_purchase_order_master', 'inv_final_purchase_order_master.id','=','inv_lot_allocation.po_id')
                    ->leftjoin('inv_supplier', 'inv_supplier.id','=','inv_lot_allocation.supplier_id')
                    ->leftjoin('inv_purchase_req_item', 'inv_purchase_req_item.requisition_item_id','=','inv_lot_allocation.pr_item_id')
                    ->leftjoin('inventory_rawmaterial', 'inventory_rawmaterial.id','=','inv_purchase_req_item.Item_code')
                    ->leftjoin('inv_unit','inv_unit.id','=','inventory_rawmaterial.issue_unit_id')
                    ->leftjoin('inv_supplier_invoice_item', 'inv_supplier_invoice_item.id','=','inv_lot_allocation.si_invoice_item_id')
                    ->leftjoin('inv_supplier_invoice_rel', 'inv_supplier_invoice_rel.item','=','inv_lot_allocation.si_invoice_item_id')
                    ->leftjoin('inv_supplier_invoice_master', 'inv_supplier_invoice_master.id','=','inv_supplier_invoice_rel.master')
                    ->groupBy('inv_lot_allocation.id')
                    ->where($condition)
                    ->first();
    }

    function all_lot_invoice_number(){
        return $this->pluck('si_invoice_item_id')->all();
    }

    function get_lots(){
        return $this->select('id','lot_number')->get();
    }

    function getLots_sip($condition)
    {
        return $this->select(['inv_lot_allocation.*', 'inventory_rawmaterial.item_code'])
                ->leftjoin('inv_purchase_req_item', 'inv_purchase_req_item.requisition_item_id','=','inv_lot_allocation.pr_item_id')
                ->leftjoin('inventory_rawmaterial', 'inventory_rawmaterial.id','=','inv_purchase_req_item.Item_code')
                ->where($condition)
                ->where('inv_lot_allocation.available_qty','!=',0)
                ->groupBy('inv_lot_allocation.id')
                ->orderby('inv_lot_allocation.id','asc')
                ->get();
    }

}
