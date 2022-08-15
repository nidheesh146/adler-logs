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
      
        return $this->select(['inv_purchase_req_master.pr_no','inventory_rawmaterial.item_code','inventory_rawmaterial.hsn_code','inv_final_purchase_order_item.delivery_schedule',
        'inv_final_purchase_order_item.order_qty','inv_final_purchase_order_item.rate','inv_final_purchase_order_item.discount','inv_final_purchase_order_item.Specification',
        'inv_final_purchase_order_item.id'])
                    ->leftjoin('inv_final_purchase_order_rel','inv_final_purchase_order_rel.item','=','inv_final_purchase_order_item.id')
                    ->leftjoin('inv_purchase_req_item','inv_purchase_req_item.requisition_item_id','=','inv_final_purchase_order_item.item_id')
                    ->leftjoin('inv_purchase_req_master_item_rel','inv_purchase_req_master_item_rel.item','=','inv_purchase_req_item.requisition_item_id')
                    ->leftjoin('inv_purchase_req_master','inv_purchase_req_master.master_id','=','inv_purchase_req_master_item_rel.master')
                    ->leftjoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_purchase_req_item.Item_code')
                    ->where($condition)
                    ->get();

    }


}
