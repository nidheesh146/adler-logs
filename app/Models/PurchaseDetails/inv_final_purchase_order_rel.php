<?php

namespace App\Models\PurchaseDetails;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class inv_final_purchase_order_rel   extends Model
{
    use HasFactory;
    protected $table = 'inv_final_purchase_order_rel';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;

function getData($condition){
  return  $this->select(['inv_purchase_req_item.requisition_item_id', 'inv_purchase_req_item.actual_order_qty', 'inv_unit.unit_name',
  'inv_purchase_req_item_approve.created_user', 'inventory_rawmaterial.item_code', 'inv_purchase_req_item_approve.approved_qty',
  'inv_purchase_req_master.pr_no', 'inv_purchase_req_master.PR_SR', 'inv_item_type.type_name', 'inventory_rawmaterial.item_type_id',
   'inventory_rawmaterial.short_description'])
                ->leftjoin('inv_final_purchase_order_master','inv_final_purchase_order_master.id','=','inv_final_purchase_order_rel.master')
                ->leftjoin('inv_final_purchase_order_item','inv_final_purchase_order_item.id','=','inv_final_purchase_order_rel.item')
                ->leftjoin('inv_purchase_req_quotation','inv_purchase_req_quotation.quotation_id','=','inv_final_purchase_order_master.rq_master_id')
                ->leftjoin('inv_purchase_req_item','inv_purchase_req_item.requisition_item_id','=','inv_final_purchase_order_item.item_id')
                ->leftjoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_purchase_req_item.Item_code')
                ->leftjoin('inv_unit','inv_unit.id','=','inventory_rawmaterial.receipt_unit_id')
                ->leftjoin('inv_item_type', 'inv_item_type.id', '=', 'inventory_rawmaterial.item_type_id')
                ->leftjoin('inv_purchase_req_master_item_rel','inv_purchase_req_master_item_rel.item','=','inv_purchase_req_item.requisition_item_id')
                ->leftjoin('inv_purchase_req_master','inv_purchase_req_master.master_id','=','inv_purchase_req_master_item_rel.master')
                ->leftjoin('inv_purchase_req_item_approve', 'inv_purchase_req_item_approve.pr_item_id', '=', 'inv_purchase_req_item.requisition_item_id')
                ->where($condition)
                ->get();   
}
function singleGetData($condition){
    return  $this->select(['inv_final_purchase_order_master.po_number','inv_purchase_req_quotation.rq_no'])
                  ->leftjoin('inv_final_purchase_order_master','inv_final_purchase_order_master.id','=','inv_final_purchase_order_rel.master')
                  ->leftjoin('inv_final_purchase_order_item','inv_final_purchase_order_item.id','=','inv_final_purchase_order_rel.item')
                  ->leftjoin('inv_purchase_req_quotation','inv_purchase_req_quotation.quotation_id','=','inv_final_purchase_order_master.rq_master_id')
                  ->leftjoin('inv_purchase_req_item','inv_purchase_req_item.requisition_item_id','=','inv_final_purchase_order_item.item_id')
                  ->leftjoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_purchase_req_item.Item_code')
                  ->leftjoin('inv_unit','inv_unit.id','=','inventory_rawmaterial.receipt_unit_id')
                  ->leftjoin('inv_item_type', 'inv_item_type.id', '=', 'inventory_rawmaterial.item_type_id')
                  ->leftjoin('inv_purchase_req_master_item_rel','inv_purchase_req_master_item_rel.item','=','inv_purchase_req_item.requisition_item_id')
                  ->leftjoin('inv_purchase_req_master','inv_purchase_req_master.master_id','=','inv_purchase_req_master_item_rel.master')
                  ->leftjoin('inv_purchase_req_item_approve', 'inv_purchase_req_item_approve.pr_item_id', '=', 'inv_purchase_req_item.requisition_item_id')
                  ->where($condition)
                  ->first();   
  }
  


    
}
