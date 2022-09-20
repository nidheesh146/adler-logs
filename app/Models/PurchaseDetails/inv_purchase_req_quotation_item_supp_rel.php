<?php

namespace App\Models\PurchaseDetails;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use DB;


class inv_purchase_req_quotation_item_supp_rel extends Model
{
    protected $table = 'inv_purchase_req_quotation_item_supp_rel';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;

    protected static function booted()
    {
        static::addGlobalScope('inv_purchase_req_quotation_item_supp_rel.status', function (Builder $builder) {
            $builder->where('inv_purchase_req_quotation_item_supp_rel.status', '!=', 2);
        });
    }
    function insert_data($data,$prm_id){
        $item_id =  $this->insertGetId($data);
    }

    function updatedata($condition,$data){
        return $this->where($condition)->update($data);
    }
    function get_Item($condition){
       return $this->select(['inv_purchase_req_master.pr_no','inventory_rawmaterial.item_code','inventory_rawmaterial.hsn_code','inv_purchase_req_quotation.delivery_schedule',
       'inv_purchase_req_item.actual_order_qty','inv_purchase_req_quotation_item_supp_rel.quantity','inv_purchase_req_quotation_item_supp_rel.rate',
       'inv_purchase_req_quotation_item_supp_rel.discount','inv_purchase_req_quotation_item_supp_rel.item_id as inv_item_id'])
       ->join('inv_purchase_req_item','inv_purchase_req_item.requisition_item_id','=','inv_purchase_req_quotation_item_supp_rel.item_id')
       ->leftjoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_purchase_req_item.Item_code')
       ->leftjoin('inv_purchase_req_quotation','inv_purchase_req_quotation.quotation_id','=','inv_purchase_req_quotation_item_supp_rel.quotation_id')
       ->leftjoin('inv_purchase_req_master_item_rel','inv_purchase_req_master_item_rel.item','=','inv_purchase_req_quotation_item_supp_rel.item_id')
       ->leftjoin('inv_purchase_req_master','inv_purchase_req_master.master_id','=','inv_purchase_req_master_item_rel.master')
       ->where($condition)
       ->groupBy('inv_purchase_req_quotation_item_supp_rel.id')
       ->get();
    }

    function get_item_single($condition){
        return $this->select(['inv_purchase_req_master.pr_no','inventory_rawmaterial.item_code','inventory_rawmaterial.hsn_code','inv_purchase_req_quotation.delivery_schedule',
        'inv_purchase_req_item.actual_order_qty','inv_purchase_req_quotation_item_supp_rel.quantity as supp_quantity','inv_purchase_req_quotation_item_supp_rel.rate as supp_rate',
        'inv_purchase_req_quotation_item_supp_rel.discount as supplier_discount','inv_purchase_req_quotation_item_supp_rel.item_id as inv_item_id','inv_supplier.vendor_id','inv_supplier.vendor_name',
        'inv_purchase_req_quotation_item_supp_rel.specification as supp_specification','inv_purchase_req_quotation_item_supp_rel.remarks','inventory_rawmaterial.item_name','inv_unit.unit_name','inventory_gst.gst','currency_exchange_rate.currency_code',
        'inv_purchase_req_item.basic_value','inv_purchase_req_item.rate','inv_purchase_req_item.discount_percent','inv_purchase_req_item.net_value','inv_purchase_req_item.discount_percent',
        'inventory_rawmaterial.short_description','inv_purchase_req_item_approve.approved_qty'
      
      
        ])
        ->join('inv_purchase_req_item','inv_purchase_req_item.requisition_item_id','=','inv_purchase_req_quotation_item_supp_rel.item_id')
        ->join('inv_purchase_req_item_approve','inv_purchase_req_item_approve.pr_item_id','=','inv_purchase_req_item.requisition_item_id')
        ->leftjoin('inventory_gst','inventory_gst.id','=','inv_purchase_req_item.gst')
        ->leftjoin('currency_exchange_rate','currency_exchange_rate.currency_id','=','inv_purchase_req_item.currency')
        ->leftjoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_purchase_req_item.Item_code')
        ->leftjoin('inv_unit','inv_unit.id','=','inventory_rawmaterial.receipt_unit_id')
        ->leftjoin('inv_purchase_req_quotation','inv_purchase_req_quotation.quotation_id','=','inv_purchase_req_quotation_item_supp_rel.quotation_id')
        ->leftjoin('inv_purchase_req_master_item_rel','inv_purchase_req_master_item_rel.item','=','inv_purchase_req_quotation_item_supp_rel.item_id')
        ->leftjoin('inv_purchase_req_master','inv_purchase_req_master.master_id','=','inv_purchase_req_master_item_rel.master')
        ->leftjoin('inv_supplier','inv_supplier.id','=','inv_purchase_req_quotation_item_supp_rel.supplier_id')
        ->where($condition)
        ->first();
     }

     function get_quotation_items_details($condition)
     {
        return $this->select('inventory_rawmaterial.id as itemId','inventory_rawmaterial.item_code','inventory_rawmaterial.item_name', 'inventory_rawmaterial.hsn_code', 'inv_purchase_req_quotation_item_supp_rel.supplier_id',
                             'inv_purchase_req_quotation_item_supp_rel.quantity','inv_purchase_req_quotation_item_supp_rel.rate', 'inv_purchase_req_quotation_item_supp_rel.discount',
                             'inv_supplier.vendor_id','inv_supplier.vendor_name', 'inventory_rawmaterial.id as itemId')
                    ->leftjoin('inv_purchase_req_item','inv_purchase_req_item.requisition_item_id','=','inv_purchase_req_quotation_item_supp_rel.item_id')
                    ->leftjoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_purchase_req_item.Item_code')
                    ->leftjoin('inv_supplier','inv_supplier.id','=','inv_purchase_req_quotation_item_supp_rel.supplier_id')
                    ->where($condition)
                    ->groupBy('inventory_rawmaterial.item_code', 'inv_purchase_req_quotation_item_supp_rel.supplier_id')
                    ->orderBy('inventory_rawmaterial.id','DESC')
                    ->orderBy('inv_purchase_req_quotation_item_supp_rel.supplier_id','ASC')
                    ->get();
     }

     function get_quotation_items($condition)
     {
        return $this->select('inv_purchase_req_quotation_item_supp_rel.quotation_id','inventory_rawmaterial.id as itemId','inventory_rawmaterial.item_code','inventory_rawmaterial.item_name', 'inventory_rawmaterial.hsn_code', 
                             'inv_purchase_req_quotation_item_supp_rel.supplier_id','currency_exchange_rate.currency_code','inv_unit.unit_name')
                    ->leftjoin('inv_purchase_req_item','inv_purchase_req_item.requisition_item_id','=','inv_purchase_req_quotation_item_supp_rel.item_id')
                    ->leftjoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_purchase_req_item.Item_code')
                    ->leftjoin('currency_exchange_rate','inv_purchase_req_item.currency','=','currency_exchange_rate.currency_id')
                    ->leftjoin('inv_unit', 'inv_unit.id','=', 'inventory_rawmaterial.issue_unit_id')
                    ->orderBy('inventory_rawmaterial.id','DESC')
                    ->where($condition)
                    ->groupBy('inventory_rawmaterial.item_code')
                    ->get()->toArray();
        }
        function inv_purchase_req_quotation_item_data($condition){
            return $this->select(['inv_purchase_req_quotation_item_supp_rel.quantity','inv_purchase_req_quotation_item_supp_rel.rate','inv_purchase_req_quotation_item_supp_rel.discount',
            'inv_purchase_req_master.pr_no','inventory_rawmaterial.item_code','inventory_rawmaterial.hsn_code'])
                        ->join('inv_purchase_req_master_item_rel','inv_purchase_req_master_item_rel.item','=','inv_purchase_req_quotation_item_supp_rel.item_id')
                        ->join('inv_purchase_req_item','inv_purchase_req_item.requisition_item_id','=','inv_purchase_req_master_item_rel.item')
                        ->leftjoin('inv_purchase_req_master','inv_purchase_req_master.master_id','=','inv_purchase_req_master_item_rel.master')
                        ->leftjoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_purchase_req_item.Item_code')
                        ->orderBy('inv_purchase_req_quotation_item_supp_rel.id','DESC')
                        ->where($condition)
                        ->get();

            
        }


}
