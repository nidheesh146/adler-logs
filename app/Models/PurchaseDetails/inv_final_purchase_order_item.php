<?php

namespace App\Models\PurchaseDetails;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use DB;


class inv_final_purchase_order_item extends Model
{
    protected $table = 'inv_final_purchase_order_item';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;
   
    protected static function booted()
    {
        static::addGlobalScope('inv_final_purchase_order_item.status', function (Builder $builder) {
            $builder->where('inv_final_purchase_order_item.status', '!=', 2);
        });
    }
    function get_purchase_order_item($condition){
      
        return $this->select(['inv_purchase_req_master.pr_no','inv_final_purchase_order_master.po_date','inventory_rawmaterial.item_code','inventory_rawmaterial.hsn_code','inv_final_purchase_order_item.delivery_schedule',
        'inv_final_purchase_order_item.order_qty','inv_final_purchase_order_item.rate','inv_final_purchase_order_item.discount','inv_final_purchase_order_item.Specification',
        'inv_final_purchase_order_item.id'])
                    ->leftjoin('inv_final_purchase_order_rel','inv_final_purchase_order_rel.item','=','inv_final_purchase_order_item.id')
                    ->leftjoin('inv_final_purchase_order_master','inv_final_purchase_order_master.id','=','inv_final_purchase_order_rel.master')
                    ->leftjoin('inv_purchase_req_item','inv_purchase_req_item.requisition_item_id','=','inv_final_purchase_order_item.item_id')
                    ->leftjoin('inv_purchase_req_master_item_rel','inv_purchase_req_master_item_rel.item','=','inv_purchase_req_item.requisition_item_id')
                    ->leftjoin('inv_purchase_req_master','inv_purchase_req_master.master_id','=','inv_purchase_req_master_item_rel.master')
                    ->leftjoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_purchase_req_item.Item_code')
                    ->where($condition)
                    ->get();

    }

    function get_purchase_order_single_item($condition){
      
        return $this->select(['inv_purchase_req_master.pr_no','inventory_rawmaterial.item_code','inventory_rawmaterial.discription','inventory_rawmaterial.hsn_code','inv_final_purchase_order_item.delivery_schedule',
        'inv_purchase_req_item.actual_order_qty', 'inv_final_purchase_order_item.order_qty','inv_unit.unit_name','inv_final_purchase_order_item.rate','inv_final_purchase_order_item.discount','inv_final_purchase_order_item.Specification',
        'inv_final_purchase_order_item.id', 'inv_purchase_req_master.pr_no', 'department.dept_name', 'inv_purchase_req_master.date as requisition_date', 'inv_purchase_req_master.PR_SR',
        'inv_purchase_req_quotation.rq_no', 'inv_purchase_req_quotation_supplier.quotation_date','inv_purchase_req_quotation_supplier.commited_delivery_date', 'inv_supplier.vendor_id', 'inv_supplier.vendor_name',
        'inv_final_purchase_order_master.type','inv_final_purchase_order_master.id as fpo_master_id','inv_purchase_req_quotation_supplier.freight_charge'])
                    ->leftjoin('inv_final_purchase_order_rel','inv_final_purchase_order_rel.item','=','inv_final_purchase_order_item.id')
                    ->leftjoin('inv_purchase_req_item','inv_purchase_req_item.requisition_item_id','=','inv_final_purchase_order_item.item_id')
                    ->leftjoin('inv_purchase_req_master_item_rel','inv_purchase_req_master_item_rel.item','=','inv_purchase_req_item.requisition_item_id')
                    ->leftjoin('inv_final_purchase_order_master', 'inv_final_purchase_order_master.id','=','inv_final_purchase_order_rel.master')
                    ->leftjoin('inv_purchase_req_quotation','inv_purchase_req_quotation.quotation_id', '=','inv_final_purchase_order_master.rq_master_id')
                    ->leftjoin('inv_purchase_req_master','inv_purchase_req_master.master_id','=','inv_purchase_req_master_item_rel.master')
                    ->leftjoin('department', 'department.id', '=', 'inv_purchase_req_master.department')
                    ->leftjoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_purchase_req_item.Item_code')
                    ->leftjoin('inv_unit', 'inv_unit.id','=', 'inventory_rawmaterial.issue_unit_id')
                    ->leftjoin('inv_purchase_req_quotation_supplier','inv_purchase_req_quotation_supplier.quotation_id','=','inv_purchase_req_quotation.quotation_id')
                    ->leftjoin('inv_supplier', 'inv_supplier.id','=', 'inv_purchase_req_quotation_supplier.supplier_id')
                   // ->where('inv_purchase_req_quotation_supplier.selected_supplier', '=', 1)
                    ->where($condition)
                    ->first();
    }

    function get_purchase_order_single_item_receipt($condition){
      
        return $this->select(['inv_purchase_req_master.pr_no','inv_final_purchase_order_master.id as po_id','inv_final_purchase_order_master.po_date','inv_final_purchase_order_master.updated_at','inv_final_purchase_order_master.po_number','inventory_rawmaterial.item_code','inventory_rawmaterial.discription','inventory_rawmaterial.hsn_code','inv_final_purchase_order_item.delivery_schedule',
        'inv_purchase_req_item.actual_order_qty', 'inv_final_purchase_order_item.order_qty','inv_unit.unit_name','inv_final_purchase_order_item.rate','inv_final_purchase_order_item.discount','inv_final_purchase_order_item.Specification',
        'inv_final_purchase_order_item.id', 'inv_purchase_req_master.pr_no', 'department.dept_name', 'inv_purchase_req_master.date as requisition_date', 'inv_purchase_req_master.PR_SR','currency_exchange_rate.currency_code',
        'inv_purchase_req_quotation.rq_no', 'inv_purchase_req_quotation_supplier.quotation_date','inv_purchase_req_quotation_supplier.id as spid','inv_purchase_req_quotation_supplier.commited_delivery_date', 'inv_supplier.vendor_id', 'inv_supplier.vendor_name',
        'inv_supplier.address', 'inv_supplier.contact_person','inv_supplier.contact_number','inv_supplier.email','inv_supplier.supplier_type','inv_final_purchase_order_master.rq_master_id','inv_final_purchase_order_master.supplier_id as supplierId'])
                    ->leftjoin('inv_final_purchase_order_rel','inv_final_purchase_order_rel.item','=','inv_final_purchase_order_item.id')
                    ->leftjoin('inv_purchase_req_item','inv_purchase_req_item.requisition_item_id','=','inv_final_purchase_order_item.item_id')
                    ->leftjoin('inv_purchase_req_master_item_rel','inv_purchase_req_master_item_rel.item','=','inv_purchase_req_item.requisition_item_id')
                    ->leftjoin('inv_final_purchase_order_master', 'inv_final_purchase_order_master.id','=','inv_final_purchase_order_rel.master')
                    ->leftjoin('inv_purchase_req_quotation','inv_purchase_req_quotation.quotation_id', '=','inv_final_purchase_order_master.rq_master_id')
                    ->leftjoin('inv_purchase_req_master','inv_purchase_req_master.master_id','=','inv_purchase_req_master_item_rel.master')
                    ->leftjoin('department', 'department.id', '=', 'inv_purchase_req_master.department')
                    ->leftjoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_purchase_req_item.Item_code')
                    ->leftjoin('inv_unit', 'inv_unit.id','=', 'inventory_rawmaterial.issue_unit_id')
                    ->leftjoin('currency_exchange_rate','currency_exchange_rate.currency_id', '=', 'inv_purchase_req_item.currency')
                    ->leftjoin('inv_purchase_req_quotation_supplier','inv_purchase_req_quotation_supplier.quotation_id','=','inv_purchase_req_quotation.quotation_id')
                    ->leftjoin('inv_supplier', 'inv_supplier.id','=', 'inv_final_purchase_order_master.supplier_id')
                   // ->where('inv_purchase_req_quotation_supplier.selected_supplier', '=', 1)
                    ->where($condition)
                    ->first();
    }

    function get_purchase_items($condition)
    {
        return $this->select(['inventory_rawmaterial.hsn_code','inventory_rawmaterial.item_code','inventory_rawmaterial.discription','inventory_rawmaterial.short_description',
            'inv_final_purchase_order_item.order_qty', 'inv_final_purchase_order_item.rate', 'inv_final_purchase_order_item.discount', 'inv_unit.unit_name','inventory_gst.igst','inventory_gst.sgst','inventory_gst.cgst','inv_purchase_req_item.gst'])
                    ->leftjoin('inv_final_purchase_order_rel','inv_final_purchase_order_rel.item','=','inv_final_purchase_order_item.id','inv_supplier.supplier_type')
                    ->leftjoin('inv_final_purchase_order_master', 'inv_final_purchase_order_master.id','=','inv_final_purchase_order_rel.master')
                    ->leftjoin('inv_supplier', 'inv_supplier.id','=', 'inv_final_purchase_order_master.supplier_id')
                    ->leftjoin('inv_purchase_req_item','inv_purchase_req_item.requisition_item_id','=','inv_final_purchase_order_item.item_id')
                    ->leftjoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_purchase_req_item.Item_code')
                    ->leftjoin('inventory_gst','inventory_gst.id','=','inv_final_purchase_order_item.gst' )
                    ->leftjoin('inv_unit', 'inv_unit.id','=', 'inventory_rawmaterial.issue_unit_id')            
                    ->where($condition)
                    ->get();
    }


    function updatedata($condition, $data)
    {
        return $this->where($condition)->update($data);
    }


}
