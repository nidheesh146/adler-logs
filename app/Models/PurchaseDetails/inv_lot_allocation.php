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

    function getdata(){
        return $this->select(['inv_lot_allocation.*', 'inv_supplier.vendor_id','inv_supplier.vendor_name','inv_final_purchase_order_master.po_number'])
                ->leftjoin('inv_final_purchase_order_master', 'inv_final_purchase_order_master.id','=','inv_lot_allocation.po_id')
                ->leftjoin('inv_supplier', 'inv_supplier.id','=','inv_lot_allocation.supplier_id')
                ->paginate(10);
    }

    function get_single_lot($condition)
    {
        return $this->select(['inv_lot_allocation.*','inv_supplier_invoice_item.id as invoice_item_id','inv_supplier_invoice_item.order_qty as invoice_qty','inv_supplier_invoice_item.rate','inv_supplier_invoice_item.discount',
        'inv_purchase_req_master.pr_no','inventory_rawmaterial.item_code','inventory_rawmaterial.discription','inventory_rawmaterial.hsn_code','inv_final_purchase_order_master.po_number','inv_supplier_invoice_master.invoice_number as invoiceNumber',
        'inv_supplier_invoice_master.invoice_date','inv_supplier.vendor_id', 'inv_supplier.vendor_name', 'inv_supplier.id as supplier_id', 'inv_final_purchase_order_master.id as po_id', 'inv_unit.unit_name', 'inv_unit.id as unit_id'])
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
                    ->first();
    }

}
