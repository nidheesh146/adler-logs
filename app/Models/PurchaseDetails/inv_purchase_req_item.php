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
        // static::addGlobalScope('inv_purchase_req_item', function (Builder $builder) {
        //     $builder->join('inv_purchase_req_item_approve', function ($join) {
        //         $join->on('inv_purchase_req_item_approve.pr_item_id', '=', 'inv_purchase_req_item.requisition_item_id');
        //         $join->on('user.user_id','=','inv_purchase_req_item_approve.created_user');
        //     })->where('inv_purchase_req_item_approve.status', '!=', 2);
        // });
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
        return $this->leftjoin('inv_purchase_req_item_approve','inv_purchase_req_item_approve.pr_item_id', '=', 'inv_purchase_req_item.requisition_item_id')
        ->where($condition)->update($data);
    }

    function getItem($condition){
        return  $this->select(['inv_purchase_req_item.requisition_item_id','inv_purchase_req_item.actual_order_qty',
                               'inventory_rawmaterial.item_code','inventory_rawmaterial.discription','inventory_rawmaterial.hsn_code', 'inventory_rawmaterial.availble_quantity', 'inventory_rawmaterial.opening_quantity',
                                'inventory_rawmaterial.max_stock', 'inventory_rawmaterial.min_stock', 'inv_item_type.type_name','inv_item_type.id as item_type_id', 'inv_purchase_req_item.remarks','inv_unit.unit_name', 
                                'inv_unit.id as unit_id','inv_purchase_req_item.Item_code as Item_code'])
                     ->leftjoin('inv_purchase_req_master_item_rel','inv_purchase_req_master_item_rel.item','=','inv_purchase_req_item.requisition_item_id')
                     ->leftjoin('inv_purchase_req_item_approve','inv_purchase_req_item_approve.pr_item_id', '=', 'inv_purchase_req_item.requisition_item_id')
                     ->leftjoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_purchase_req_item.Item_code')
                     ->leftjoin('inv_unit', 'inv_unit.id','=', 'inventory_rawmaterial.issue_unit_id')
                     ->leftjoin('inv_item_type', 'inv_item_type.id', '=','inventory_rawmaterial.item_type_id' )
                     ->where($condition)
                     ->first();
        
    }

    function getdata($condition){
        return  $this->select(['inv_purchase_req_item.requisition_item_id','inv_purchase_req_item.actual_order_qty','inv_unit.unit_name', 'user.f_name','user.l_name',
                              'inv_purchase_req_item_approve.created_user','inventory_rawmaterial.item_code','inventory_rawmaterial.hsn_code','inv_purchase_req_item_approve.approved_qty',
                              'inv_purchase_req_master.pr_no','inv_purchase_req_master.PR_SR','inv_item_type.type_name','inventory_rawmaterial.item_type_id','inventory_rawmaterial.short_description'])
                     ->leftjoin('inv_purchase_req_master_item_rel','inv_purchase_req_master_item_rel.item','=','inv_purchase_req_item.requisition_item_id')
                     ->leftjoin('inv_purchase_req_item_approve','inv_purchase_req_item_approve.pr_item_id', '=', 'inv_purchase_req_item.requisition_item_id')
 
                     ->leftjoin('inv_purchase_req_master','inv_purchase_req_master.master_id','=','inv_purchase_req_master_item_rel.master')
                     ->leftjoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_purchase_req_item.Item_code')
                     ->leftjoin('inv_item_type','inv_item_type.id', '=', 'inventory_rawmaterial.item_type_id')
                     ->leftjoin('inv_unit', 'inv_unit.id','=', 'inventory_rawmaterial.issue_unit_id')
                     ->leftjoin('user','inv_purchase_req_master.requestor_id','=','user.user_id')
                     ->whereNotIn('inv_purchase_req_item.requisition_item_id',function($query) {
 
                         $query->select('inv_purchase_req_quotation_item_supp_rel.item_id')->from('inv_purchase_req_quotation_item_supp_rel');
                     
                     })
                     ->where($condition)
                     ->where('inv_purchase_req_item_approve.status','=',1)
                     ->groupBy('inv_purchase_req_item.requisition_item_id')
                     ->orderby('inv_purchase_req_item.requisition_item_id','desc')
                     //->paginate(10);
                     ->get();
     }
    function getdataFixedItems($condition){
        return  $this->select(['inv_purchase_req_item.requisition_item_id','inv_purchase_req_item.actual_order_qty','inv_unit.unit_name','user.f_name','user.l_name',
                              'inv_purchase_req_item_approve.created_user','inventory_rawmaterial.item_code','inventory_rawmaterial.hsn_code','inv_purchase_req_item_approve.approved_qty',
                              'inv_purchase_req_master.pr_no','inv_purchase_req_master.PR_SR','inv_item_type.type_name','inventory_rawmaterial.item_type_id',
                              'inventory_rawmaterial.short_description','inv_supplier.vendor_name'])
                     ->leftjoin('inv_purchase_req_master_item_rel','inv_purchase_req_master_item_rel.item','=','inv_purchase_req_item.requisition_item_id')
                     ->leftjoin('inv_purchase_req_item_approve','inv_purchase_req_item_approve.pr_item_id', '=', 'inv_purchase_req_item.requisition_item_id')
 
                     ->leftjoin('inv_purchase_req_master','inv_purchase_req_master.master_id','=','inv_purchase_req_master_item_rel.master')
                     ->leftjoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_purchase_req_item.Item_code')
                     ->leftjoin('inv_item_type','inv_item_type.id', '=', 'inventory_rawmaterial.item_type_id')
                     ->leftjoin('inv_unit', 'inv_unit.id','=', 'inventory_rawmaterial.issue_unit_id')
                     ->leftjoin('inv_supplier_itemrate','inv_supplier_itemrate.item_id','=','inv_purchase_req_item.Item_code')
                     ->leftjoin('inv_supplier','inv_supplier.id','=','inv_supplier_itemrate.supplier_id')
                     ->leftjoin('user','inv_purchase_req_master.requestor_id','=','user.user_id')
                     ->whereNotIn('inv_purchase_req_item.requisition_item_id',function($query) {
 
                         $query->select('inv_purchase_req_quotation_item_supp_rel.item_id')->from('inv_purchase_req_quotation_item_supp_rel');
                     
                     })
                     ->whereIn('inv_purchase_req_item.Item_code',function($query){
                        $query->select('inv_supplier_itemrate.item_id')->from('inv_supplier_itemrate');
                     })
                     ->where($condition)
                     ->where('inv_purchase_req_item_approve.status','=',1)
                     ->groupBy('inv_purchase_req_item.requisition_item_id')
                     ->orderby('inv_purchase_req_item.requisition_item_id','desc')
                     ->paginate(10);
     }

    function getItemdata($condition){
       return  $this->select(['inv_purchase_req_item.requisition_item_id','inv_purchase_req_item.actual_order_qty','inv_item_type.type_name',
                             'inv_purchase_req_item_approve.created_user','inventory_rawmaterial.item_code','inventory_rawmaterial.hsn_code','inventory_rawmaterial.short_description',
                             'inv_purchase_req_item_approve.approved_qty','inv_purchase_req_master.pr_no','inv_purchase_req_master.PR_SR','inv_unit.unit_name'])
                    ->leftjoin('inv_purchase_req_master_item_rel','inv_purchase_req_master_item_rel.item','=','inv_purchase_req_item.requisition_item_id')
                    ->leftjoin('inv_purchase_req_master','inv_purchase_req_master.master_id','=','inv_purchase_req_master_item_rel.master')
                    ->leftjoin('inv_purchase_req_item_approve','inv_purchase_req_item_approve.pr_item_id', '=', 'inv_purchase_req_item.requisition_item_id')
                    ->leftjoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_purchase_req_item.Item_code')
                    ->leftjoin('inv_item_type','inv_item_type.id', '=', 'inventory_rawmaterial.item_type_id')
                    ->leftjoin('inv_unit', 'inv_unit.id','=', 'inventory_rawmaterial.issue_unit_id')
                    ->where($condition)
                    //->where('inv_purchase_req_item_approve.status','=',1)
                    ->groupBy('inv_purchase_req_item.requisition_item_id')
                    ->orderby('inv_purchase_req_item.requisition_item_id','desc')
                    ->paginate(10);
    }
    
    function getdata_approved($condition, $wherein = null){
         $query = $this->select(['inv_purchase_req_item_approve.pr_item_id','inv_purchase_req_item.requisition_item_id','inv_purchase_req_item.actual_order_qty','inventory_rawmaterial.item_code',
                               'inventory_rawmaterial.short_description','inv_purchase_req_master.pr_no','inv_purchase_req_item_approve.status','inv_purchase_req_master.PR_SR',
                                'user.f_name','user.l_name','inv_purchase_req_item_approve.updated_at','inv_unit.unit_name'])
                     ->leftjoin('inv_purchase_req_item_approve','inv_purchase_req_item_approve.pr_item_id', '=', 'inv_purchase_req_item.requisition_item_id')
                     ->leftjoin('inv_purchase_req_master_item_rel','inv_purchase_req_master_item_rel.item','=','inv_purchase_req_item.requisition_item_id')
                     ->leftjoin('inv_purchase_req_master','inv_purchase_req_master.master_id','=','inv_purchase_req_master_item_rel.master')
                     ->leftjoin('inventory_rawmaterial','inventory_rawmaterial.id','=','inv_purchase_req_item.Item_code')
                    // ->leftjoin('user','user.user_id','=', 'inv_purchase_req_item_approve.created_user')
                     ->leftjoin('user','user.user_id','=','inv_purchase_req_master.requestor_id')
                     ->leftjoin('inv_unit', 'inv_unit.id','=', 'inventory_rawmaterial.issue_unit_id');
                     if($wherein ){
                        $query = $query->whereIn('inv_purchase_req_item_approve.status',$wherein);
                     }
                    return  $query->where($condition)
                                ->groupBy('inv_purchase_req_item.requisition_item_id')
                                ->where('inv_purchase_req_item_approve.status','!=',2)
                                ->orderby('inv_purchase_req_item.requisition_item_id','desc')
                                ->paginate(10);
     }

     

}
