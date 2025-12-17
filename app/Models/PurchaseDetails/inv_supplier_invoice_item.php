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
        return $this->select(['inv_supplier_invoice_item.id','inv_supplier_invoice_item.order_qty','inv_supplier_invoice_item.rate','inv_supplier_invoice_item.discount','inv_lot_allocation.lot_number',
        'inv_purchase_req_master.pr_no','inventory_rawmaterial.item_code','inventory_rawmaterial.hsn_code','inv_final_purchase_order_master.po_number','inv_supplier_invoice_master.invoice_number'
        ,'inv_supplier_invoice_master.invoice_date','inv_supplier.vendor_id', 'inv_supplier.vendor_name','inventory_gst.igst','inventory_gst.sgst','inventory_gst.cgst','inv_unit.unit_name','inv_item_type.type_name',
        'inv_mac_item.accepted_quantity','inv_mrd_item.rejected_quantity','inv_supplier_invoice_item.item_id as pr_item_id'])
                    ->join('inv_supplier_invoice_rel','inv_supplier_invoice_rel.item','=','inv_supplier_invoice_item.id')
                    ->leftjoin('inv_purchase_req_item','inv_purchase_req_item.requisition_item_id','=','inv_supplier_invoice_item.item_id')
                    ->leftjoin('inv_purchase_req_master_item_rel','inv_purchase_req_master_item_rel.item','=','inv_supplier_invoice_item.item_id')
                    ->leftjoin('inv_purchase_req_master','inv_purchase_req_master.master_id','=','inv_purchase_req_master_item_rel.master')
                    ->leftjoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_purchase_req_item.Item_code')
                    ->leftjoin('inv_supplier_invoice_master','inv_supplier_invoice_master.id', '=', 'inv_supplier_invoice_rel.master')
                    ->leftjoin('inv_final_purchase_order_master','inv_final_purchase_order_master.id', '=','inv_supplier_invoice_master.po_master_id')
                    ->leftjoin('inv_supplier', 'inv_supplier.id','=','inv_final_purchase_order_master.supplier_id')
                    ->leftjoin('inventory_gst','inventory_gst.id','=','inv_supplier_invoice_item.gst')
                    ->leftjoin('inv_unit','inv_unit.id','=','inventory_rawmaterial.issue_unit_id')
                    ->leftjoin('inv_lot_allocation','inv_lot_allocation.si_invoice_item_id', '=', 'inv_supplier_invoice_item.id')
                    ->leftjoin('inv_mac_item','inv_mac_item.invoice_item_id','=','inv_supplier_invoice_item.id')
                    ->leftjoin('inv_mrd_item','inv_mrd_item.invoice_item_id','=','inv_supplier_invoice_item.id')
                    ->leftjoin('inv_item_type', 'inv_item_type.id', '=','inventory_rawmaterial.item_type_id' )
                    ->where($condition)
                    ->where('inv_supplier_invoice_item.is_merged','=',0)
                    ->groupBy('inv_supplier_invoice_item.id')
                    ->distinct('inv_supplier_invoice_item.id')
                    ->orderBy('inv_supplier_invoice_item.id','desc')
                    ->get();
    }
    
    function get_supplier_invoice_item_mac($condition){
        return $this->select(['inv_supplier_invoice_item.id','inv_supplier_invoice_item.order_qty','inv_supplier_invoice_item.rate','inv_supplier_invoice_item.discount','inv_lot_allocation.lot_number',
        'inv_purchase_req_master.pr_no','inventory_rawmaterial.item_code','inventory_rawmaterial.hsn_code','inv_final_purchase_order_master.po_number','inv_supplier_invoice_master.invoice_number',
        'inv_supplier_invoice_master.invoice_date','inv_supplier.vendor_id', 'inv_supplier.vendor_name','inventory_gst.igst','inventory_gst.sgst','inventory_gst.cgst','inv_unit.unit_name','inv_mac_item.accepted_quantity'])
                    ->join('inv_supplier_invoice_rel','inv_supplier_invoice_rel.item','=','inv_supplier_invoice_item.id')
                    ->leftjoin('inv_purchase_req_item','inv_purchase_req_item.requisition_item_id','=','inv_supplier_invoice_item.item_id')
                    ->leftjoin('inv_purchase_req_master_item_rel','inv_purchase_req_master_item_rel.item','=','inv_supplier_invoice_item.item_id')
                    ->leftjoin('inv_purchase_req_master','inv_purchase_req_master.master_id','=','inv_purchase_req_master_item_rel.master')
                    ->leftjoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_purchase_req_item.Item_code')
                    ->leftjoin('inv_supplier_invoice_master','inv_supplier_invoice_master.id', '=', 'inv_supplier_invoice_rel.master')
                    ->leftjoin('inv_mac_item','inv_mac_item.invoice_item_id', '=', 'inv_supplier_invoice_item.id')
                    ->leftjoin('inv_final_purchase_order_master','inv_final_purchase_order_master.id', '=','inv_supplier_invoice_master.po_master_id')
                    ->leftjoin('inv_supplier', 'inv_supplier.id','=','inv_final_purchase_order_master.supplier_id')
                    ->leftjoin('inventory_gst','inventory_gst.id','=','inv_supplier_invoice_item.gst')
                    ->leftjoin('inv_unit','inv_unit.id','=','inventory_rawmaterial.issue_unit_id')
                    ->leftjoin('inv_lot_allocation','inv_lot_allocation.si_invoice_item_id', '=', 'inv_supplier_invoice_item.id')
                    ->where($condition)
                    ->groupBy('inv_supplier_invoice_item.id')
                    ->orderBy('inv_supplier_invoice_item.id','desc')
                    ->where('inv_supplier_invoice_item.is_merged','=',0)
                    ->get();
    }

    function get_non_lot_alloted_supplier_invoice_items($condition,$all_lot_invoice_number){
        return $this->select(['inv_supplier_invoice_item.id','inv_supplier_invoice_item.order_qty','inv_supplier_invoice_item.rate','inv_supplier_invoice_item.discount',
        'inv_purchase_req_master.pr_no','inventory_rawmaterial.item_code','inventory_rawmaterial.hsn_code','inv_final_purchase_order_master.po_number','inv_supplier_invoice_master.invoice_number','inv_supplier_invoice_master.invoice_date','inv_supplier.vendor_id', 'inv_supplier.vendor_name'])
                    ->join('inv_supplier_invoice_rel','inv_supplier_invoice_rel.item','=','inv_supplier_invoice_item.id')
                    ->leftjoin('inv_purchase_req_item','inv_purchase_req_item.requisition_item_id','=','inv_supplier_invoice_item.item_id')
                    ->leftjoin('inv_purchase_req_master_item_rel','inv_purchase_req_master_item_rel.item','=','inv_supplier_invoice_item.item_id')
                    ->leftjoin('inv_purchase_req_master','inv_purchase_req_master.master_id','=','inv_purchase_req_master_item_rel.master')
                    ->leftjoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_purchase_req_item.Item_code')
                    ->leftjoin('inv_supplier_invoice_master','inv_supplier_invoice_master.id', '=', 'inv_supplier_invoice_rel.master')
                    ->leftjoin('inv_final_purchase_order_master','inv_final_purchase_order_master.id', '=','inv_supplier_invoice_master.po_master_id')
                    ->leftjoin('inv_supplier', 'inv_supplier.id','=','inv_final_purchase_order_master.supplier_id')
                    ->where($condition)
                    ->whereNotIn('inv_supplier_invoice_item.id',$all_lot_invoice_number)
                    ->groupBy('inv_supplier_invoice_item.id')
                    ->orderBy('inv_supplier_invoice_item.id','desc')
                    ->where('inv_supplier_invoice_item.is_merged','=',0)
                    ->get();
    }

    function get_single_supplier_invoice_item($condition){
        return $this->select(['inv_supplier_invoice_item.id as invoice_item_id','inv_supplier_invoice_item.order_qty as invoice_qty','inv_supplier_invoice_item.rate','inv_supplier_invoice_item.discount',
        'inv_purchase_req_master.pr_no','inventory_rawmaterial.item_code','inventory_rawmaterial.discription','inventory_rawmaterial.hsn_code','inv_final_purchase_order_master.po_number','inv_supplier_invoice_master.invoice_number',
        'inv_supplier_invoice_master.invoice_date','inv_supplier_invoice_master.created_at as transaction_date','inv_supplier.vendor_id', 'inv_supplier.vendor_name', 'inv_supplier.id as supplier_id', 'inv_final_purchase_order_master.id as po_id', 'inv_unit.unit_name', 'inv_unit.id as unit_id'
        ,'inv_supplier_invoice_item.specification','inv_supplier_invoice_item.rate','inv_supplier_invoice_item.discount'])
                    ->join('inv_supplier_invoice_rel','inv_supplier_invoice_rel.item','=','inv_supplier_invoice_item.id')
                    ->leftjoin('inv_purchase_req_item','inv_purchase_req_item.requisition_item_id','=','inv_supplier_invoice_item.item_id')
                    ->leftjoin('inv_purchase_req_master_item_rel','inv_purchase_req_master_item_rel.item','=','inv_supplier_invoice_item.item_id')
                    ->leftjoin('inv_purchase_req_master','inv_purchase_req_master.master_id','=','inv_purchase_req_master_item_rel.master')
                    ->leftjoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_purchase_req_item.Item_code')
                     ->leftjoin('inv_unit','inv_unit.id','=','inventory_rawmaterial.issue_unit_id')
                    ->leftjoin('inv_supplier_invoice_master','inv_supplier_invoice_master.id', '=', 'inv_supplier_invoice_rel.master')
                    ->leftjoin('inv_final_purchase_order_master','inv_final_purchase_order_master.id', '=','inv_supplier_invoice_item.po_master_id')
                    ->leftjoin('inv_supplier', 'inv_supplier.id','=','inv_supplier_invoice_master.supplier_id')
                    //->leftjoin('inv_lot_allocation','inv_lot_allocation.si_invoice_item_id', '=', 'inv_supplier_invoice_item.id');
                    ->where($condition)
                    ->first();
    }
    function get_single_supplier_invoice_item_id($condition){
        return $this->select(['inv_supplier_invoice_item.id as invoice_item_id','inv_supplier.id as supp_id','inv_purchase_req_item.requisition_item_id','inv_supplier_invoice_item.po_master_id as po_master_id'])
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
                    ->where('inv_supplier_invoice_item.is_merged','=',0)
                    ->where($condition)
                    ->first();
    }
    function get_supplier_invoice_item1($condition){
        return $this->select(['inv_supplier_invoice_item.id','inv_supplier_invoice_item.order_qty','inv_supplier_invoice_item.rate','inv_supplier_invoice_item.discount',
        'inv_purchase_req_master.pr_no','inventory_rawmaterial.item_code','inventory_rawmaterial.hsn_code','inv_supplier_invoice_master.invoice_number'
        ,'inv_supplier_invoice_master.invoice_date','inv_supplier.vendor_id', 'inv_supplier.vendor_name','inv_item_type.type_name','inv_unit.unit_name'])
                    ->leftjoin('inv_supplier_invoice_rel','inv_supplier_invoice_rel.item','=','inv_supplier_invoice_item.id')
                    ->leftjoin('inv_purchase_req_item','inv_purchase_req_item.requisition_item_id','=','inv_supplier_invoice_item.item_id')
                    ->leftjoin('inv_purchase_req_master_item_rel','inv_purchase_req_master_item_rel.item','=','inv_supplier_invoice_item.item_id')
                    ->leftjoin('inv_purchase_req_master','inv_purchase_req_master.master_id','=','inv_purchase_req_master_item_rel.master')
                    ->leftjoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_purchase_req_item.Item_code')
                    ->leftjoin('inv_supplier_invoice_master','inv_supplier_invoice_master.id', '=', 'inv_supplier_invoice_rel.master')
                    ->leftjoin('inv_final_purchase_order_master','inv_final_purchase_order_master.id', '=','inv_supplier_invoice_master.po_master_id')
                    ->leftjoin('inv_supplier', 'inv_supplier.id','=','inv_supplier_invoice_master.supplier_id')
                    ->leftjoin('inv_item_type', 'inv_item_type.id', '=','inventory_rawmaterial.item_type_id' )
                    ->leftjoin('inv_item_type_2', 'inv_item_type_2.id', '=','inventory_rawmaterial.item_type_id_2' )
                    ->leftjoin('inv_unit', 'inv_unit.id','=', 'inventory_rawmaterial.issue_unit_id')
                    // ->leftjoin('inv_lot_allocation', function($join)
                    // {
                    //     $join->on('inv_lot_allocation.si_invoice_item_id', '=', 'inv_supplier_invoice_item.id');
                    //     $join->where('inv_lot_allocation.status','=',1);
                    // })
                    ->whereNotIn('inv_supplier_invoice_item.id',function($query) {

                        $query->select('inv_lot_allocation.si_invoice_item_id')->from('inv_lot_allocation');
                    
                    })
                    ->where($condition)
                    ->where('inv_item_type.type_name','=','Direct Items')
                    ->where('inv_item_type_2.type_name','!=','Finished Goods')
                    ->where('inv_supplier_invoice_item.is_merged','=',0)
                    ->groupBy('inv_supplier_invoice_item.id')
                    ->orderBy('inv_supplier_invoice_item.id','desc')
                    //->orderBy('inv_lot_allocation.id','desc')
                    ->paginate(15);
                    //->get();
    }


}
