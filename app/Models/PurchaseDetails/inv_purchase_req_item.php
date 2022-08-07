<?php

namespace App\Models\PurchaseDetails;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use DB;


class inv_purchase_req_item extends Model
{
    protected $table = 'inv_purchase_req_item';
    protected $primary_key = 'requisition_item_id';
    protected $guarded = [];
    public $timestamps = false;
   
    protected static function booted()
    {
        static::addGlobalScope('inv_purchase_req_item', function (Builder $builder) {
            $builder->join('inv_purchase_req_item_approve', function ($join) {
                $join->on('inv_purchase_req_item_approve.pr_item_id', '=', 'inv_purchase_req_item.requisition_item_id');
            })->where('inv_purchase_req_item_approve.status', '!=', 2);
        });
    }
    function insert_data($data,$prm_id){
        $item_id =  $this->insertGetId($data);
        if($item_id){
            DB::table('inv_purchase_req_master_item_rel')->insert(['master'=>$prm_id,'item'=>$item_id]);
            DB::table('inv_purchase_req_item_approve')->insert(['pr_item_id'=>$item_id]);
        }
        return true;
    }

    function updatedata($condition,$data){
        return $this->where($condition)->update($data);
    }

    function getItem($condition){
        return  $this->select(['inv_purchase_req_item.requisition_item_id','inv_supplier.vendor_id','inv_supplier.vendor_name','inv_purchase_req_item.actual_order_qty','inv_purchase_req_item.rate',
                               'inv_purchase_req_item.discount_percent','inventory_gst.gst','currency_exchange_rate.currency_code','inv_purchase_req_item.net_value','inv_purchase_req_item.basic_value',
                               'inventory_rawmaterial.item_code','inventory_rawmaterial.discription','inventory_rawmaterial.hsn_code', 'inventory_rawmaterial.availble_quantity', 'inventory_rawmaterial.opening_quantity',
                                'inventory_rawmaterial.max_stock', 'inventory_rawmaterial.min_stock', 'inv_item_type.type_name','inv_item_type.id as item_type_id', 'inv_purchase_req_item.remarks','inv_unit.unit_name', 
                                'inv_unit.id as unit_id', 'inv_supplier.id as supplierId','inv_supplier.vendor_id as vendorId','inv_supplier.vendor_name as vendorName',
                                'inv_purchase_req_item.Item_code as Item_code'])
                     ->leftjoin('inv_purchase_req_master_item_rel','inv_purchase_req_master_item_rel.item','=','inv_purchase_req_item.requisition_item_id')
                     ->leftjoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_purchase_req_item.Item_code')
                     ->leftjoin('inv_unit', 'inv_unit.id','=', 'inventory_rawmaterial.issue_unit_id')
                     ->leftjoin('inv_supplier','inv_supplier.id','=','inv_purchase_req_item.supplier')
                     ->leftjoin('inv_item_type', 'inv_item_type.id', '=','inventory_rawmaterial.item_type_id' )
                     ->leftjoin('inventory_gst','inventory_gst.id','=','inv_purchase_req_item.gst')
                     ->leftjoin('currency_exchange_rate','currency_exchange_rate.currency_id','=','inv_purchase_req_item.currency')
                     ->where($condition)
                     ->first();
        
    }

    function getdata($condition){
       return  $this->select(['inv_purchase_req_item.requisition_item_id','inv_supplier.vendor_id','inv_supplier.vendor_name','inv_purchase_req_item.actual_order_qty','inv_purchase_req_item.rate',
                             'inv_purchase_req_item.discount_percent','inventory_gst.gst','currency_exchange_rate.currency_code','inv_purchase_req_item.net_value','inventory_rawmaterial.item_code',
                             'inv_purchase_req_item_approve.approved_qty','inv_purchase_req_master.pr_no'])
                    ->leftjoin('inv_purchase_req_master_item_rel','inv_purchase_req_master_item_rel.item','=','inv_purchase_req_item.requisition_item_id')
                    ->leftjoin('inv_purchase_req_master','inv_purchase_req_master.master_id','=','inv_purchase_req_master_item_rel.master')
                    ->leftjoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_purchase_req_item.Item_code')
                    ->leftjoin('inv_supplier','inv_supplier.id','=','inv_purchase_req_item.supplier')
                    ->leftjoin('inventory_gst','inventory_gst.id','=','inv_purchase_req_item.gst')
                    ->leftjoin('currency_exchange_rate','currency_exchange_rate.currency_id','=','inv_purchase_req_item.currency')
                    ->where($condition)
                    ->groupBy('inv_purchase_req_item.requisition_item_id')
                    ->orderby('inv_purchase_req_item.requisition_item_id','desc')
                    ->paginate(10);
    }
    
    function getdata_approved($condition){
        return  $this->select(['inv_purchase_req_item.requisition_item_id','inv_supplier.vendor_id','inv_supplier.vendor_name','inv_purchase_req_item.actual_order_qty','inv_purchase_req_item.rate',
                               'inv_purchase_req_item.discount_percent','inventory_gst.gst','currency_exchange_rate.currency_code','inv_purchase_req_item.net_value','inventory_rawmaterial.item_code',
                               'inv_purchase_req_master.pr_no','inv_purchase_req_item_approve.status'])
                     ->leftjoin('inv_purchase_req_master_item_rel','inv_purchase_req_master_item_rel.item','=','inv_purchase_req_item.requisition_item_id')
                     ->leftjoin('inv_purchase_req_master','inv_purchase_req_master.master_id','=','inv_purchase_req_master_item_rel.master')
                     ->leftjoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_purchase_req_item.Item_code')
                     ->leftjoin('inv_supplier','inv_supplier.id','=','inv_purchase_req_item.supplier')
                     ->leftjoin('inventory_gst','inventory_gst.id','=','inv_purchase_req_item.gst')
                     ->leftjoin('currency_exchange_rate','currency_exchange_rate.currency_id','=','inv_purchase_req_item.currency')
                     ->whereIn('inv_purchase_req_item_approve.status',[4,5])
                     ->where($condition)
                     ->groupBy('inv_purchase_req_item.requisition_item_id')
                     ->orderby('inv_purchase_req_item.requisition_item_id','desc')
                     ->paginate(10);
     }

}
