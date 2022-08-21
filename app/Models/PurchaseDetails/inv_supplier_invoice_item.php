<?php

namespace App\Models\PurchaseDetails;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use DB;


class inv_supplier_invoice_item extends Model
{
    protected $table = 'inv_supplier_invoice_item';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;
   
    protected static function booted()
    {
        static::addGlobalScope('inv_supplier_invoice_item.status', function (Builder $builder) {
            $builder->where('inv_supplier_invoice_item.status', '!=', 2);
        });
    }
    function get_supplier_invoice_item($condition){
        return $this->select(['inv_supplier_invoice_item.id','inv_supplier_invoice_item.order_qty','inv_supplier_invoice_item.rate','inv_supplier_invoice_item.discount',
        'inv_purchase_req_master.pr_no','inventory_rawmaterial.item_code','inventory_rawmaterial.hsn_code'])
                    ->join('inv_supplier_invoice_rel','inv_supplier_invoice_rel.item','=','inv_supplier_invoice_item.id')
                    ->leftjoin('inv_purchase_req_item','inv_purchase_req_item.requisition_item_id','=','inv_supplier_invoice_item.item_id')
                    ->leftjoin('inv_purchase_req_master_item_rel','inv_purchase_req_master_item_rel.item','=','inv_supplier_invoice_item.item_id')
                    ->leftjoin('inv_purchase_req_master','inv_purchase_req_master.master_id','=','inv_purchase_req_master_item_rel.master')
                    ->leftjoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_purchase_req_item.Item_code')
                    ->where($condition)
                    ->groupBy('inv_supplier_invoice_item.id')
                    ->get();
    }
    function updatedata($condition, $data)
    {
        return $this->where($condition)->update($data);
    }
    function get_si_item($condition){
        return $this->select(['inv_purchase_req_master.pr_no','inventory_rawmaterial.item_code','inventory_rawmaterial.hsn_code','inv_supplier.vendor_id','inv_supplier.vendor_name',
        'inv_final_purchase_order_master.po_number','inv_final_purchase_order_master.po_date','inv_supplier_invoice_master.invoice_number','inv_supplier_invoice_master.invoice_date',
        'inv_supplier_invoice_item.order_qty','inv_supplier_invoice_item.rate','inv_supplier_invoice_item.discount','inv_supplier_invoice_item.specification'])   
                    ->join('inv_supplier_invoice_rel','inv_supplier_invoice_rel.item','=','inv_supplier_invoice_item.id') 
                    ->leftjoin('inv_purchase_req_item','inv_purchase_req_item.requisition_item_id','=','inv_supplier_invoice_item.item_id')                                 
                    ->leftjoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_purchase_req_item.Item_code')                                 
                    ->leftjoin('inv_supplier_invoice_master','inv_supplier_invoice_master.id','=','inv_supplier_invoice_rel.master')                                 
                    ->leftjoin('inv_supplier','inv_supplier.id','=','inv_supplier_invoice_master.supplier_id')                                 
                    ->leftjoin('inv_final_purchase_order_master','inv_final_purchase_order_master.id','=','inv_supplier_invoice_master.po_master_id') 
                    ->leftjoin('inv_purchase_req_master_item_rel','inv_purchase_req_master_item_rel.item','=','inv_purchase_req_item.requisition_item_id') 
                    ->leftjoin('inv_purchase_req_master','inv_purchase_req_master.master_id','=','inv_purchase_req_master_item_rel.master') 
                    ->where($condition)
                    ->first();
    }


}
